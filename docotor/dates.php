<?php 
// 1. استدعاء الهيدر وتفعيل وضع الأخطاء للمتابعة الدقيقة
include ('include/header.php');

if (isset($pdo)) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

// خريطة تحويل أسماء الأيام العربية إلى الإنجليزية
$days_map = [
    'السبت'    => 'Saturday',
    'الأحد'    => 'Sunday',
    'الإثنين'  => 'Monday',
    'الثلاثاء' => 'Tuesday',
    'الأربعاء' => 'Wednesday'
];
$clinic_work_days = array_keys($days_map);

// ============================================================
// API ENDPOINTS (POST + ?api=...)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['api'])) {
    header('Content-Type: application/json');

    // 1. تثبيت حجز مريض
    if ($_GET['api'] === 'book') {
        $slot_id     = (int)$_POST['slot_id'];
        $patient_name = trim($_POST['patient_name']);
        $service      = trim($_POST['service']);

        $stmt = $pdo->prepare("
        UPDATE clinic_slots SET is_booked = 1, patient_name = ?, service_name = ? WHERE id = ? AND is_booked = 0");
        if ($stmt->execute([$patient_name, $service, $slot_id])) {
            echo json_encode(['success' => true, 'patient' => $patient_name, 'service' => $service]);
        } else {
            echo json_encode(['success' => false, 'error' => 'تعذر حفظ الحجز']);
        }
        exit();
    }

    // 2. حذف موعد غير محجوز
    if ($_GET['api'] === 'delete_slot') {
        $slot_id = (int)$_POST['slot_id'];
        $stmt = $pdo->prepare("DELETE FROM clinic_slots WHERE id = ? AND is_booked = 0");
        if ($stmt->execute([$slot_id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'لا يمكن حذف موعد محجوز بالفعل']);
        }
        exit();
    }

    // 3. تعديل وقت موعد غير محجوز
    if ($_GET['api'] === 'update_slot') {
        $slot_id  = (int)$_POST['slot_id'];
        $new_time = trim($_POST['new_time_range']);
        $stmt = $pdo->prepare("UPDATE clinic_slots SET time_range = ? WHERE id = ? AND is_booked = 0");
        if ($stmt->execute([$new_time, $slot_id])) {
            echo json_encode(['success' => true, 'new_time' => $new_time]);
        } else {
            echo json_encode(['success' => false, 'error' => 'تعذر تعديل الوقت']);
        }
        exit();
    }
}

// ============================================================
// توليد المواعيد (POST + action=generate)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
    $days      = $_POST['days'] ?? [];
    $work_from = $_POST['work_from'];
    $work_to   = $_POST['work_to'];
    $duration  = (int)$_POST['slot_duration'];
    $week_type = $_POST['week_type'] ?? 'current';

    foreach ($days as $day) {
        if (!isset($days_map[$day])) continue;
        $english_day = $days_map[$day];

        // ---- حساب التاريخ المستهدف لكل يوم ----
        $today_day = date('l');

        if ($week_type === 'next') {
            // الأسبوع المستقبلي: ابدأ من السبت القادم
            if ($today_day === 'Saturday') {
                $base = date('Y-m-d', strtotime('+1 week'));
            } elseif (in_array($today_day, ['Thursday', 'Friday'])) {
                $base = date('Y-m-d', strtotime('next saturday +1 week'));
            } else {
                $base = date('Y-m-d', strtotime('next saturday'));
            }
        } else {
            // الأسبوع الحالي: ابدأ من سبت هذا الأسبوع
            if ($today_day === 'Saturday') {
                $base = date('Y-m-d');
            } elseif (in_array($today_day, ['Thursday', 'Friday'])) {
                $base = date('Y-m-d', strtotime('next saturday'));
            } else {
                $base = date('Y-m-d', strtotime('last saturday'));
            }
        }

        $target_date = date('Y-m-d', strtotime($english_day, strtotime($base)));

        // ---- توليد الفترات الزمنية ----
        $current_time = strtotime($work_from);
        $end_time     = strtotime($work_to);

        while ($current_time < $end_time) {
            $slot_start = date("H:i", $current_time);
            $slot_end   = date("H:i", $current_time + ($duration * 60));

            if (strtotime($slot_end) > $end_time) break;

            $time_range = $slot_start . " - " . $slot_end;

            $check_stmt = $pdo->prepare("SELECT id FROM clinic_slots WHERE booking_date = ? AND time_range = ?");
            $check_stmt->execute([$target_date, $time_range]);

            if ($check_stmt->rowCount() == 0) {
                $insert_stmt = $pdo->prepare("INSERT INTO clinic_slots (booking_day, booking_date, time_range, is_booked) VALUES (?, ?, ?, 0)");
                $insert_stmt->execute([$day, $target_date, $time_range]);
            }

            $current_time += ($duration * 60);
        }
    }

    header("Location: ?view=" . $week_type);
    exit();
}

// ============================================================
// حساب الأسبوع المعروض (منطق مصحح ونظيف)
// ============================================================
$view_week = $_GET['view'] ?? 'current';

$today_day = date('l'); // اليوم الحالي بالإنجليزية

// إيجاد سبت بداية الأسبوع الحالي
if ($today_day === 'Saturday') {
    $base_saturday = new DateTime(); // اليوم هو السبت
} elseif (in_array($today_day, ['Thursday', 'Friday'])) {
    // الخميس والجمعة: الأسبوع الحالي انتهى، نعرض السبت القادم كـ "حالي"
    $base_saturday = new DateTime('next saturday');
} else {
    // الأحد → الأربعاء: نعود للسبت الماضي
    $base_saturday = new DateTime('last saturday');
}

// إذا طلب المستخدم الأسبوع المستقبلي نضيف أسبوعاً كاملاً
if ($view_week === 'next') {
    $base_saturday->modify('+1 week');
}

$saturday_view_week = $base_saturday->format('Y-m-d');

$wednesday = clone $base_saturday;
$wednesday->modify('+4 days');
$wednesday_view_week = $wednesday->format('Y-m-d');

$saturday_ts = strtotime($saturday_view_week);

// ============================================================
// جلب المواعيد من قاعدة البيانات (من اليوم فصاعداً)
// ============================================================
$all_slots = [];
$query = $pdo->prepare("SELECT cs.*, u.name AS patient_name
FROM clinic_slots cs
LEFT JOIN users u ON cs.user_id = u.id
WHERE cs.booking_date BETWEEN ? AND ?
ORDER BY cs.booking_date ASC, cs.time_range ASC
");
$query->execute([$saturday_view_week, $wednesday_view_week]);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $all_slots[$row['booking_day']][] = $row;
}
?>

<main class="p-4 sm:p-8 flex-1 max-w-7xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50" dir="rtl">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">الجدولة الذكية وإدارة المواعيد 📅</h1>
            <p class="text-slate-400 text-xs font-bold">توليد المواعيد الآلي، تصفح المواعيد المستقبلية، الحجز السريع وتعديل الأوقات بدقة متناهية.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex rounded-xl border border-slate-200 p-1 bg-slate-100 gap-1 font-bold text-xs">
                <a href="?view=current" class="px-5 py-3 rounded-lg transition-all flex items-center gap-1.5 <?= $view_week !== 'next' ? 'bg-white text-sky-600 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800' ?>">
                    <span>⚡</span> الأسبوع الحالي
                </a>
                <a href="?view=next" class="px-5 py-3 rounded-lg transition-all flex items-center gap-1.5 <?= $view_week === 'next' ? 'bg-white text-sky-600 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800' ?>">
                    <span>🔮</span> الأسبوع المستقبلي
                </a>
            </div>

            <button onclick="openModal('generateSlotsModal')" class="bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-black text-xs px-5 py-3.5 rounded-xl transition-all flex items-center gap-2 shadow-sm">
                ⚙️ توليد وتقسيم المواعيد الآلي
            </button>
        </div>
    </div>

    <!-- Schedule Grid -->
    <div class="bg-white border border-slate-100 rounded-[24px] p-6 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
            <h2 class="text-sm font-black text-slate-800 flex items-center gap-2">
                <span class="w-2.5 h-4 bg-sky-500 rounded-full"></span>
                جدول <?= $view_week === 'next' ? 'الأسبوع المستقبلي القادم' : 'الأسبوع الحالي الفعلي' ?>
                <span class="text-slate-400 font-normal text-xs">
                    (من <?= date('Y/m/d', strtotime($saturday_view_week)) ?> إلى <?= date('Y/m/d', strtotime($wednesday_view_week)) ?>)
                </span>
            </h2>
            <div class="flex items-center gap-4 text-xs font-bold">
                <span class="flex items-center gap-1.5 text-emerald-600"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-md block"></span> متاح</span>
                <span class="flex items-center gap-1.5 text-rose-600"><span class="w-2.5 h-2.5 bg-rose-500 rounded-md block"></span> محجوز 🔒</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <?php foreach ($clinic_work_days as $day_name):
                $day_date = date('Y-m-d', strtotime($days_map[$day_name], $saturday_ts));
            ?>
            <div class="bg-slate-50/60 border border-slate-100 rounded-2xl p-3 space-y-3">
                <div class="text-center border-b border-slate-200/70 pb-2">
                    <span class="block font-black text-slate-800 text-sm"><?= $day_name ?></span>
                    <span class="block text-[10px] text-slate-400 font-black mt-0.5 bg-slate-200/50 rounded-md py-0.5 mx-4"><?= $day_date ?></span>
                </div>

                <div class="space-y-2">
                    <?php if (isset($all_slots[$day_name]) && count($all_slots[$day_name]) > 0): ?>
                        <?php foreach ($all_slots[$day_name] as $slot): ?>

                            <div id="slot-container-<?= $slot['id'] ?>" class="relative group transition-all">
                                <?php if ($slot['is_booked'] == 0): ?>

                                    <div id="slot-card-<?= $slot['id'] ?>" class="bg-emerald-50 border border-emerald-200/70 p-3 rounded-xl text-center group-hover:border-emerald-400 transition-all">
                                        <span id="time-text-<?= $slot['id'] ?>" class="block font-black text-emerald-800 text-xs"><?= htmlspecialchars($slot['time_range']) ?></span>
                                        <button
                                            onclick="selectSlot(<?= $slot['id'] ?>, '<?= addslashes($day_name) ?> (<?= $slot['booking_date'] ?>)', '<?= addslashes($slot['time_range']) ?>')"
                                            class="text-[10px] text-emerald-600 font-bold block w-full mt-1 hover:underline">
                                            احجز الآن +
                                        </button>

                                        <div class="absolute inset-0 bg-emerald-900/90 rounded-xl opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-all duration-200 px-2">
                                            <button onclick="openUpdateModal(<?= $slot['id'] ?>, '<?= addslashes($slot['time_range']) ?>')" class="bg-white/20 hover:bg-white text-white hover:text-emerald-900 px-2 py-1 rounded-md text-[10px] font-black transition-all">
                                                ✏️ تعديل
                                            </button>
                                            <button onclick="deleteSlot(<?= $slot['id'] ?>)" class="bg-rose-600 hover:bg-rose-700 text-white px-2 py-1 rounded-md text-[10px] font-black transition-all">
                                                🗑️ حذف
                                            </button>
                                        </div>
                                    </div>

                                <?php else: ?>

                                    <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-center">
                                        <span class="block font-bold text-rose-400 text-xs line-through"><?= htmlspecialchars($slot['time_range']) ?></span>
                                        <span class="text-[9px] text-rose-700 font-black block mt-1 truncate"
                                              title="<?= htmlspecialchars($slot['patient_name'] ?? '') ?>">
                                            🔒 <?= htmlspecialchars($slot['patient_name'] ?? 'محجوز') ?>
                                        </span>
                                    </div>

                                <?php endif; ?>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-slate-400 text-[11px] font-bold">لا يوجد مواعيد</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>
</main>

<!-- ============================================================ -->
<!-- Modal: توليد المواعيد -->
<!-- ============================================================ -->
<div id="generateSlotsModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" dir="rtl">
    <div class="bg-white rounded-[28px] max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">⚙️ توليد مواعيد العيادة تلقائياً</h3>
            <button onclick="closeModal('generateSlotsModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="generate">

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">توليد المواعيد لأي أسبوع؟</label>
                <select name="week_type" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-amber-500 focus:bg-white transition-all">
                    <option value="current" <?= $view_week === 'current' ? 'selected' : '' ?>>الأسبوع الحالي الفعلي</option>
                    <option value="next"    <?= $view_week === 'next'    ? 'selected' : '' ?>>الأسبوع المستقبلي القادم</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 pr-1">أيام العمل المشمولة</label>
                <div class="grid grid-cols-3 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-700">
                    <?php foreach ($clinic_work_days as $day): ?>
                    <label class="flex items-center gap-1.5">
                        <input type="checkbox" name="days[]" value="<?= $day ?>" checked class="rounded text-sky-500">
                        <?= $day ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">بداية الدوام</label>
                    <input type="time" name="work_from" value="09:00" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-amber-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">نهاية الدوام</label>
                    <input type="time" name="work_to" value="15:00" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-amber-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">مدة الكشف/الجلسة</label>
                <select name="slot_duration" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-amber-500 transition-all">
                    <option value="30">30 دقيقة</option>
                    <option value="60" selected>60 دقيقة (ساعة)</option>
                    <option value="90">90 دقيقة</option>
                </select>
            </div>

            <button type="submit" class="w-full h-12 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-black text-sm transition-all shadow-sm">
                توليد وتقسيم الساعات الآن ⚡
            </button>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- Modal: تثبيت حجز مريض -->
<!-- ============================================================ -->
<div id="addBookingModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" dir="rtl">
    <div class="bg-white rounded-[28px] max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">📅 تثبيت حجز لمريض</h3>
            <button onclick="closeModal('addBookingModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form id="ajaxBookingForm" class="space-y-4">
            <input type="hidden" name="slot_id" id="modal_slot_id">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اسم المريض</label>
                <input type="text" name="patient_name" required placeholder="مثال: أحمد رأفت"
                       class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اليوم والتاريخ</label>
                    <input type="text" id="modal_booking_day" readonly
                           class="w-full h-11 bg-slate-100 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-500 outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">التوقيت</label>
                    <input type="text" id="modal_booking_time" readonly
                           class="w-full h-11 bg-slate-100 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-500 outline-none cursor-not-allowed">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">الخدمة المطلوبة</label>
                <select name="service" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 transition-all">
                    <option value="علاج عصب">علاج عصب وسن</option>
                    <option value="تنظيف ليزر">تنظيف وتبييض ليزر</option>
                    <option value="تقويم">تركيب وتعديل تقويم</option>
                </select>
            </div>
            <button type="submit" class="w-full h-12 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-black text-sm transition-all shadow-sm">
                تأكيد الحجز الفوري لمريض 🔒
            </button>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- Modal: تعديل وقت الموعد -->
<!-- ============================================================ -->
<div id="updateSlotModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" dir="rtl">
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-slate-900">✏️ تعديل النطاق الزمني للموعد</h3>
            <button onclick="closeModal('updateSlotModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form id="ajaxUpdateForm" class="space-y-4">
            <input type="hidden" id="update_slot_id">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">تعديل الوقت نصياً</label>
                <input type="text" id="update_time_range" required placeholder="مثال: 09:00 - 10:00"
                       class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 transition-all">
            </div>
            <button type="submit" class="w-full h-11 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs transition-all shadow-sm">
                تحديث التوقيت فوراً 💾
            </button>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- JavaScript -->
<!-- ============================================================ -->
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function selectSlot(id, day, timeRange) {
    document.getElementById('modal_slot_id').value = id;
    document.getElementById('modal_booking_day').value = day;
    document.getElementById('modal_booking_time').value = timeRange;
    openModal('addBookingModal');
}

function openUpdateModal(id, currentRange) {
    document.getElementById('update_slot_id').value = id;
    document.getElementById('update_time_range').value = currentRange;
    openModal('updateSlotModal');
}

// 1. حجز مريض عبر AJAX
document.getElementById('ajaxBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const slotId   = document.getElementById('modal_slot_id').value;
    const service  = this.querySelector('[name="service"]').value;
    const patient  = this.querySelector('[name="patient_name"]').value;

    fetch(window.location.pathname + '?api=book', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal('addBookingModal');
            const container = document.getElementById('slot-container-' + slotId);
            container.innerHTML = `
                <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-center">
                    <span class="block font-bold text-rose-400 text-xs line-through">${document.getElementById('modal_booking_time').value}</span>
                    <span class="text-[9px] text-rose-700 font-black block mt-1 truncate">🔒 ${data.patient} (${service})</span>
                </div>`;
            this.reset();
        } else {
            alert('خطأ: ' + data.error);
        }
    }).catch(err => console.error(err));
});

// 2. تعديل التوقيت عبر AJAX
document.getElementById('ajaxUpdateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const slotId  = document.getElementById('update_slot_id').value;
    const newTime = document.getElementById('update_time_range').value;

    const data = new FormData();
    data.append('slot_id', slotId);
    data.append('new_time_range', newTime);

    fetch(window.location.pathname + '?api=update_slot', { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal('updateSlotModal');
            document.getElementById('time-text-' + slotId).innerText = res.new_time;
        } else {
            alert('خطأ: ' + res.error);
        }
    }).catch(err => console.error(err));
});

// 3. حذف الموعد عبر AJAX
function deleteSlot(slotId) {
    if (!confirm('هل أنت متأكد من حذف هذا الموعد نهائياً من جدول العيادة؟')) return;

    const data = new FormData();
    data.append('slot_id', slotId);

    fetch(window.location.pathname + '?api=delete_slot', { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            const container = document.getElementById('slot-container-' + slotId);
            container.style.transform = 'scale(0.8)';
            container.style.opacity   = '0';
            container.style.transition = 'all 0.25s ease';
            setTimeout(() => container.remove(), 250);
        } else {
            alert('خطأ: ' + res.error);
        }
    }).catch(err => console.error(err));
}
</script>

<?php include ('include/footer.php'); ?>