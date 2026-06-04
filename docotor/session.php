<?php include ('include/header.php');
$stmt=$pdo->prepare("SELECT cs.*, u.name as patient_name, s.name as service_name 
FROM clinic_slots cs
INNER JOIN users u ON cs.user_id = u.id
INNER JOIN services s ON cs.service_id = s.id
ORDER BY 
    cs.booking_date ASC, 
    cs.time_range ASC;  ");
    $stmt->execute();
    $bookings=$stmt->fetchAll();
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    $booking_id = $_POST['id'];
    $sql = "UPDATE clinic_slots SET status = '2' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$booking_id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
}
?>
<main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">إدارة الحجوزات وجلسات المرضى 📅</h1>
            <p class="text-slate-400 text-xs font-bold">جدولة مواعيد العيادة، متابعة حالات الدفع، التقييمات، وإضافة جلسات المتابعة الدورية.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openModal('addSessionModal')" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-xs px-4 py-3.5 rounded-xl transition-all flex items-center gap-2">
                <span>🦷</span> إضافة جلسة لمريض سابق
            </button>
          
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                        <th class="p-5">اسم المريض والخدمة</th>
                        <th class="p-5">الموعد والتوقيت</th>
                        <th class="p-5">حالة الدفع</th>
                        <th class="p-5">ملاحظات المريض</th>
                        <th class="p-5 text-left">إجراءات سريعة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                   <?php foreach($bookings as $booking){?>
                    <tr id="booking-row-1" class="hover:bg-slate-50/40 transition-colors">
                        <td class="p-5">
                            <div class="flex items-center gap-2">
                                <span id="status-indicator-1" class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm"> <?php echo $booking['patient_name']?></h4>
                                    <span class="text-[10px] text-slate-400 block font-bold"><?php echo $booking['service_name']?></span>
                                </div>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="text-slate-800 font-bold block"><?php echo $booking['booking_day']?> القادم</span>
                            <span class="text-[11px] text-slate-400 font-semibold block"><?php echo $booking['time_range']?></span>
                        </td>
                        <td class="p-5">
                            <?php
if($booking['status']=='0'){?>
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-2.5 py-1 rounded-md font-bold text-xs">لم يدفع بعد</span>
<?php
}else if($booking['status']=='1'){
?>
                            <span class="bg-green-200 text-shadow-black border border-green-950 px-2.5 py-1 rounded-md font-bold text-xs">  دفع ابنتظار الموعد</span>

<?php }else { ?>
                            <span class="bg-green-200 text-shadow-black border border-green-950 px-2.5 py-1 rounded-md font-bold text-xs">  انتهت جلسة وتقييم</span>


<?php }?>
                        </td>
                        <td class="p-5 text-slate-400 text-xs max-w-[180px] truncate">
<?php echo $booking['notes']?>                        </td>
                        <td class="p-5 text-left flex items-center justify-end gap-1.5">
<?php if($booking['status']=='0'){?>


                        <span>x</span> انتظار الدفع 
<?php }else if($booking['status']=='1'){?>
<form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
    <input type="hidden" name="action" value="end_session">
    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
    

<button type="submit" class="bg-emerald-50 hover:bg-emerald-500 hover:text-white text-emerald-600 font-bold p-2 rounded-lg border border-emerald-100 text-xs transition-all flex items-center gap-1 cursor-pointer">
        <span>✓</span> إنهاء الجلسة
    </button>
</form>

<?php }else{
    $stmt=$pdo->prepare('select rating from  ratings where slot_id=? ');
    $stmt->execute([$booking['id']]);
    $rating=$stmt->fetch();    
  if (!empty($rating)){ ?>
    <div class="flex text-amber-400 text-2xl">
        <?php 
            $stars = (int)$rating; // الرقم من قاعدة البيانات
            for ($i = 1; $i <= 5; $i++) {
                echo ($i <= $stars) ? '★' : '☆';
            }
        ?>
    </div>
<?php }else{ ?>
    <span class="text-slate-300  text-2xl">بدون تقييم</span>
<?php } ?>
    

          <?php }?>
          
                        </td>
                    </tr>
<?php }?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<div id="addBookingModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-xl w-full p-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">📅 جدولة حجز موعد جديد</h3>
            <button onclick="closeModal('addBookingModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form action="process-booking.php?action=create" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اسم المريض ثلاثي</label>
                    <input type="text" name="patient_name" required placeholder="مثال: وجيه أحمد" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">الخدمة المطلوبة</label>
                    <select name="service_id" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="1">علاج وحشوة عصب</option>
                        <option value="2">تبييض الأسنان بالليزر</option>
                        <option value="3">تنظيف وإزالة الجير</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اليوم</label>
                    <select name="booking_day" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="السبت">السبت</option>
                        <option value="الأحد">الأحد</option>
                        <option value="الإثنين">الإثنين</option>
                        <option value="الثلاثاء">الثلاثاء</option>
                        <option value="الأربعاء" selected>الأربعاء القادم</option>
                        <option value="الخميس">الخميس</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">من الساعة</label>
                    <input type="time" name="time_from" value="09:00" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">إلى الساعة</label>
                    <input type="time" name="time_to" value="11:00" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">الحالة الطبية والموعد</label>
                    <select name="status" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="pending">قيد الانتظار / مؤكد</option>
                        <option value="done">تم انتهاء الجلسة واكتمل العلاج</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">حالة الدفع</label>
                    <select name="payment_status" id="paymentStatusSelect" onchange="toggleRatingSection()" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="unpaid">لم يدفع بعد</option>
                        <option value="paid">دفع (خ خالص)</option>
                    </select>
                </div>
            </div>

            <!-- قسم التقييم: يظهر فقط إذا دفع -->
            <div id="ratingSection" class="hidden">
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">تقييم المريض للخدمة والعيادة</label>
                <select name="rating" class="w-full h-11 bg-amber-50/50 border border-amber-200 rounded-xl px-4 text-xs font-bold text-amber-800 focus:outline-none focus:border-amber-500 transition-all">
                    <option value="">بدون تقييم حالياً</option>
                    <option value="5">⭐⭐⭐⭐⭐ ممتاز جداً</option>
                    <option value="4">⭐⭐⭐⭐ جيد جداً</option>
                    <option value="3">⭐⭐⭐ متوسط</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">ملاحظات وشكوى المريض</label>
                <textarea name="patient_notes" rows="3" placeholder="اكتب أي ملاحظات طبية أو أعراض يشكو منها المريض هنا..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all resize-none"></textarea>
            </div>

            <button type="submit" class="w-full h-12 mt-2 rounded-xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-black text-sm shadow-md hover:scale-[1.01] transition-all">
                حفظ وتأكيد الحجز الحالي
            </button>
        </form>
    </div>
</div>
<div id="addSessionModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-md w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">🦷 إضافة جلسة علاجية لمريض سابق</h3>
            <button onclick="closeModal('addSessionModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form action="process-booking.php?action=add_session" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اختر المريض المسجل</label>
                <select name="existing_patient_id" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                    <option value="101">وجيه أحمد (حالة: علاج عصب)</option>
                    <option value="102">محمد علي (حالة: تقويم أسنان)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">رقم الجلسة الحالية</label>
                    <input type="number" name="session_number" placeholder="مثال: الجلسة الثانية (2)" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">التاريخ المخصص</label>
                    <input type="date" name="session_date" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1 font-bold text-emerald-600">الإجراء الذي تم في هذه الجلسة</label>
                <textarea name="session_procedure" rows="3" placeholder="مثال: تم سحب العصب المتبقي وتطهير القنوات بشكل كامل وتحضير السن للحشوة الدائمة المرة القادمة." required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all resize-none"></textarea>
            </div>
            <button type="submit" class="w-full h-12 mt-2 rounded-xl bg-gradient-to-l from-emerald-500 to-emerald-600 text-white font-black text-sm shadow-md hover:scale-[1.01] transition-all">
                تأكيد وحفظ الجلسة في ملف المريض
            </button>
        </form>
    </div>
</div>
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function toggleRatingSection() {
    const paymentStatus = document.getElementById('paymentStatusSelect').value;
    const ratingSection = document.getElementById('ratingSection');
    if(paymentStatus === 'paid') {
        ratingSection.classList.remove('hidden');
    } else {
        ratingSection.classList.add('hidden');
    }
}
</script>

<?php include ('include/footer.php')?>