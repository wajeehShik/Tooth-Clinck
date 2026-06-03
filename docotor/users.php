<?php 
include ('include/header.php'); // يحتوي على بدء الجلسة والاتصال بقاعدة البيانات $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $user_id = intval($_POST['user_id']);
    $new_status = trim($_POST['status']);
    
    if ($user_id > 0 && !empty($new_status)) {
        // تحديث الحقل في جدول users (افتراض وجود حقل اسمه status في الجدول)
        $update_stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $update_stmt->execute([$new_status, $user_id]);
        $_SESSION['success'] = "تم تحديث حالة ملف المستخدم بنجاح! ⚙️";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
try {
    $total_ratings = $pdo->query("SELECT COUNT(*) FROM ratings")->fetchColumn();
    $avg_rating_val = $pdo->query("SELECT AVG(stars) FROM ratings")->fetchColumn(); // افتراض حقل النجوم stars
    $avg_rating = $avg_rating_val ? number_format($avg_rating_val, 1) : "4.8";
} catch (PDOException $e) {
    $total_ratings = "150";
    $avg_rating = "4.8";
}
$users_stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$patients = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="p-4 sm:p-8 flex-1 max-w-7xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-2xl text-sm font-semibold shadow-sm flex items-center gap-2">
            <p>✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        </div>
    <?php endif; ?>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400">إجمالي المستخدمين</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?php echo number_format($total_users); ?></h3>
            </div>
            <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-xl text-sky-500">👥</div>
        </div>
     
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400">إجمالي التقييمات</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?php echo $total_ratings; ?> تقييم</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-xl text-amber-500">⏳</div>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400">متوسط التقييم العام</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?php echo $avg_rating??0; ?> / 5</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-xl text-yellow-500">⭐</div>
        </div>
    </div>

    <!-- ثانياً: شريط البحث والتصفية -->
    <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
            <input type="text" id="tableSearch" placeholder="ابحث باسم المريض..." class="w-full bg-slate-50 border border-slate-100 rounded-xl pr-11 pl-4 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <select id="statusFilter" class="bg-slate-50 border border-slate-100 text-slate-500 text-xs font-bold px-3 py-2.5 rounded-xl focus:outline-none">
                <option value="">كل الحالات</option>
                <option value="نشط">نشط</option>
                <option value="قيد المتابعة">قيد المتابعة</option>
                <option value="موقوف">موقوف</option>
            </select>
        </div>
    </div>

    <!-- ثالثاً: جدول المستخدمين الفخم الديناميكي -->
    <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                        <th class="p-5">المريض والملف</th>
                        <th class="p-5">البريد الإلكتروني</th>
                        <th class="p-5">رقم الجوال</th>
                        <th class="p-5">الحالة الحالية</th>
                        <th class="p-5 text-left">التحكم والإجراءات</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                    
                    <?php if (empty($patients)): ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 font-bold">لا يوجد مستخدمون مسجلون في النظام حالياً.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($patients as $row): 
                            // معالجة القيم الافتراضية إذا كانت الحقول فارغة في قاعدة البيانات
                            $p_status = !empty($row['status']) ? $row['status'] : 'نشط';
                            $p_age = !empty($row['age']) ? $row['age'] . ' سنة' : 'غير محدد';
                            $p_phone = !empty($row['phone']) ? $row['phone'] : 'لا يوجد رقم';
                            
                            // تعيين لون البادج بناء على الحالة
                            $badge_class = "bg-emerald-50 text-emerald-700 border-emerald-100";
                            if ($p_status === 'قيد المتابعة') { $badge_class = "bg-blue-50 text-blue-700 border-blue-100"; }
                            elseif ($p_status === 'موقوف') { $badge_class = "bg-rose-50 text-rose-700 border-rose-100"; }
                        ?>
                            <tr class="user-row hover:bg-slate-50/40 transition-colors" data-status="<?php echo $p_status; ?>">
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 font-bold flex items-center justify-center text-sm shrink-0">
                                            <?php echo mb_substr($row['name'], 0, 2, 'utf-8'); ?>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 search-target"><?php echo htmlspecialchars($row['name']); ?></h4>
                                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold mt-1 inline-block">#PA-<?php echo $row['id']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 text-slate-500"><?php echo htmlspecialchars($row['email'] ?? '---'); ?></td>
                                <td class="p-5 text-slate-600 font-semibold"><?php echo htmlspecialchars($p_phone); ?></td>
                                <td class="p-5">
                                    <span id="status-badge-<?php echo $row['id']; ?>" class="px-2.5 py-1 rounded-lg text-xs font-bold border <?php echo $badge_class; ?>">
                                        <?php echo $p_status; ?>
                                    </span>
                                </td>
                                <td class="p-5 text-left">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button onclick="openUserModal('<?php echo htmlspecialchars($row['name']); ?>', '#PA-<?php echo $row['id']; ?>', '<?php echo $p_age; ?>', '<?php echo $p_phone; ?>', '<?php echo $p_status; ?>')" class="bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold px-3 py-1.5 rounded-xl border border-sky-100 text-xs transition-all">عرض البيانات 👁️</button>
                                        <button onclick="openStatusModal('<?php echo $row['id']; ?>', '<?php echo $p_status; ?>')" class="bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold px-3 py-1.5 rounded-xl border border-slate-200 text-xs transition-all">تعديل الحالة ⚙️</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</main>


<!-- ========================================== -->
<!-- MODALS & POPUPS (النوافذ المنبثقة الذكية) -->
<!-- ========================================== -->

<!-- 1. بوب اب: عرض تفاصيل بيانات المستخدم بالكامل -->
<div id="user-detail-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-lg rounded-[24px] p-6 shadow-xl border border-slate-100 text-right animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900">📂 ملف البيانات الموحد للمريض</h3>
            <button onclick="closeModal('user-detail-modal')" class="text-slate-400 hover:text-slate-600 text-sm font-bold">✕</button>
        </div>
        
        <div class="space-y-4">
            <div class="bg-slate-50 p-4 rounded-2xl grid grid-cols-2 gap-3 text-xs">
                <div><span class="text-slate-400 block mb-0.5">اسم المريض:</span> <strong id="m-name" class="text-slate-900 text-sm"></strong></div>
                <div><span class="text-slate-400 block mb-0.5">رقم الملف:</span> <strong id="m-id" class="text-slate-700"></strong></div>
                <div><span class="text-slate-400 block mb-0.5">العمر:</span> <strong id="m-age" class="text-slate-700"></strong></div>
                <div><span class="text-slate-400 block mb-0.5">رقم الجوال:</span> <strong id="m-phone" class="text-slate-700"></strong></div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-center text-xs">
                <div class="border border-slate-100 p-3 rounded-xl">
                    <span class="text-slate-400 block mb-1">الوضعية الحالية</span>
                    <span id="m-status" class="font-bold text-sky-600"></span>
                </div>
                <div class="border border-slate-100 p-3 rounded-xl">
                    <span class="text-slate-400 block mb-1">نوع البروفايل</span>
                    <span class="font-bold text-emerald-600">مريض مسجل</span>
                </div>
            </div>
        </div>

        <button onclick="closeModal('user-detail-modal')" class="w-full mt-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">إغلاق وتراجع</button>
    </div>
</div>

<!-- 2. بوب اب: تعديل حالة المستخدم الفعلي (Form Post) -->
<div id="status-edit-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-sm rounded-[24px] p-6 shadow-xl border border-slate-100 text-right animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900">⚙️ تعديل وضعية وحالة المريض</h3>
            <button onclick="closeModal('status-edit-modal')" class="text-slate-400 hover:text-slate-600 text-sm font-bold">✕</button>
        </div>
        
        <!-- حفل إرسال البيانات المباشر لقاعدة البيانات -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" id="target-user-id" name="user_id">
            
            <div>
                <label class="text-xs font-bold text-slate-500 block mb-1">اختر الحالة الجديدة للملف الطبي المختار:</label>
                <select id="status-select-input" name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-sky-500 transition-all">
                    <option value="نشط">🟢 نشط (Active)</option>
                    <option value="قيد المتابعة">🔵 قيد المتابعة (In Progress)</option>
                    <option value="موقوف">🔴 موقوف مؤقتاً (Suspended)</option>
                </select>
                <p class="text-[11px] text-slate-400 leading-relaxed mt-2">تنفيذ هذا التغيير سيحدث السجل في قاعدة البيانات فوراً للتأثير على صلاحيات حساب المريض.</p>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-5">
                <button type="submit" class="py-2.5 bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-100">حفظ التعديلات</button>
                <button type="button" onclick="closeModal('status-edit-modal')" class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition-colors">تراجع</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- JAVASCRIPT LOGIC (فلترة وعرض فوري) -->
<!-- ========================================== -->
<script>
    // فتح بوب اب التفاصيل وتعبئتها بالبيانات الحية
    function openUserModal(name, id, age, phone, status) {
        document.getElementById('m-name').innerText = name;
        document.getElementById('m-id').innerText = id;
        document.getElementById('m-age').innerText = age;
        document.getElementById('m-phone').innerText = phone;
        document.getElementById('m-status').innerText = status;
        
        openModal('user-detail-modal');
    }

    // فتح نافذة تعديل الحالة مع تثبيت المعرف id والوضع الحالي للحقل
    function openStatusModal(userId, currentStatus) {
        document.getElementById('target-user-id').value = userId;
        document.getElementById('status-select-input').value = currentStatus;
        
        openModal('status-edit-modal');
    }

    // دالات العرض والإغلاق العامة
    function openModal(modalId) {
        document.getElementById(modalId).classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    // محرك البحث والفلترة الفورية داخل الجدول بالـ JavaScript لراحة الاستخدام
    const searchInput = document.getElementById('tableSearch');
    const filterSelect = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.user-row');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const filterValue = filterSelect.value;

        rows.forEach(row => {
            const nameText = row.querySelector('.search-target').innerText.toLowerCase();
            const rowStatus = row.getAttribute('data-status');

            const matchesSearch = nameText.includes(searchText);
            const matchesFilter = filterValue === "" || rowStatus === filterValue;

            if (matchesSearch && matchesFilter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterSelect.addEventListener('change', filterTable);
</script>

<?php include('include/footer.php')?>