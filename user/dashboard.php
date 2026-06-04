<?php
include("../include/header.php"); 
$current_user_id = $_SESSION['id']; 
if (isset($_GET['get_slots']) && isset($_GET['day'])) {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    $selected_day = $_GET['day'];

    try {
        $query = "SELECT id, time_range FROM clinic_slots WHERE booking_day = ? AND is_booked = 0";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$selected_day]);
        $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($slots, JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit; 
}
$success_msg = "";
$error_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    $slot_id = $_POST['slot_id']; 
    $service_id = $_POST['specialty']; 

    if (!empty($slot_id) && !empty($service_id)) {
        try {
            $update_query = "UPDATE clinic_slots SET is_booked = 1, user_id = ?, service_id = ? WHERE id = ?";
            $stmt = $pdo->prepare($update_query);
            if ($stmt->execute([$current_user_id, $service_id, $slot_id])) {
                $success_msg = "تم تسجيل وتأكيد موعدك بنجاح! 🎉";
            } else {
                $error_msg = "عذراً، حدث خطأ أثناء تأكيد الحجز. يرجى المحاولة مرة أخرى.";
            }
        } catch (PDOException $e) {
            $error_msg = "خطأ في قاعدة البيانات: " . $e->getMessage();
        }
    } else {
        $error_msg = "يرجى ملء جميع الحقول واختيار الوقت المتاح.";
    }
}
$upcoming_appointment = null;
try {
$query = "SELECT cs.*, s.name as service_name 
FROM clinic_slots cs 
LEFT JOIN services s ON cs.service_id = s.id 
WHERE cs.user_id = ? 
  AND cs.is_booked = 1 
ORDER BY cs.booking_date ASC, cs.time_range ASC
LIMIT 5";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$current_user_id]);
    $upcoming_appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);


$countStmt = $pdo->prepare("SELECT COUNT(*) FROM clinic_slots 
                            WHERE user_id = ? 
                            AND is_booked = 1 
                            AND booking_date >= CURDATE()");
$countStmt->execute([$current_user_id]);
$total_upcoming = $countStmt->fetchColumn();



$paymentStmt = $pdo->prepare("SELECT SUM(price) as total_paid 
                              FROM payments 
                              WHERE user_id = ?");
$paymentStmt->execute([$current_user_id]);
$paymentResult = $paymentStmt->fetch(PDO::FETCH_ASSOC);

$total_paid = $paymentResult['total_paid'] ?? 0;


$payments_stmt = $pdo->prepare("
    SELECT p.*, s.name as service_name 
    FROM payments p
    LEFT JOIN clinic_slots cs ON p.date_id = cs.id
    LEFT JOIN services s ON cs.service_id = s.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC limit 3
");
$payments_stmt->execute([$current_user_id]);
$payments = $payments_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}
try {
    $sers = $pdo->prepare('SELECT id, name FROM services WHERE status = 1');
    $sers->execute();
    $services = $sers->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
}
?>
<main class="min-h-screen bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.08),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.05),transparent_35%)] pt-32 pb-24 bg-slate-50">
  
<div class="max-w-7xl mx-auto px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-12 bg-white/70 backdrop-blur-md border border-slate-200/60 p-8 rounded-[32px] shadow-sm">
            <div>
                <h1 class="text-3xl font-black text-slate-900 mb-2">مرحباً بك، أحمد محمد 👋</h1>
                <p class="text-slate-500 text-lg">تابع مواعيدك الطبية، مدفوعاتك، وقم بجدولة استشارات جديدة بكل سهولة.</p>
       <a href="edit_profile.php" class="bg-slate-800 mt-5 flex text-white px-6 py-2 rounded-xl font-bold hover:bg-slate-900 transition">
    تعديل الحساب
</a>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-sky-50 border border-sky-100 rounded-2xl px-6 py-4 text-center min-w-[120px]">
                    <span class="block text-2xl font-black text-sky-600"><?php echo $total_upcoming ?></span>
                    <span class="text-slate-500 text-sm font-bold">مواعيد قادمة</span>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl px-6 py-4 text-center min-w-[120px]">
                    <span class="block text-2xl font-black text-emerald-600"><?php echo $total_paid?></span>
                    <span class="text-slate-500 text-sm font-bold">إجمالي المدفوعات</span>
                </div>
            </div>
        </div>
        <div class="grid lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 space-y-8">
                
                <?php if(!empty($success_msg)): ?>
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl font-bold">
                        <?php echo $success_msg; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($error_msg)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl font-bold">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                            <span class="text-sky-500">📅</span> المواعيد القادمة
                        </h2>
                        <span class="text-sm bg-sky-100 text-sky-700 font-bold px-3 py-1 rounded-full">مؤكدة</span>
                    </div><div class="space-y-4">

        <?php foreach ($upcoming_appointment as $appointment): ?>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 border border-slate-100 rounded-2xl hover:border-sky-200 transition bg-slate-50/50 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center text-2xl">🦷</div>
                    <div>
                        <h3 class="font-black text-slate-900 text-lg">
                            <?php echo htmlspecialchars($appointment['service_name'] ?? 'استشارة طبية'); ?>
                        </h3>
                        <p class="text-slate-500 text-sm mt-1">حجز مؤكد مخصص لك بالعيادة</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                    <div class="text-left sm:text-right">
                        <span class="block font-black text-slate-800">
                            <?php echo htmlspecialchars($appointment['booking_day'] . ' / ' . $appointment['booking_date']); ?>
                        </span>
                        <span class="text-slate-500 text-sm">
                            الساعة: <?php echo htmlspecialchars($appointment['time_range']); ?>
                        </span>
                    </div>
                    <?php 
                    
                    if($appointment['status']=='0'){?>
                    <a href="payment.php?id=<?php echo $appointment['id']?>" class="text-red-500 hover:text-red-700 font-bold text-sm bg-green-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">دفع</a>
                    <a class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">إلغاء</a>
                   
                   
                   <?php }else if($appointment['status']=='1'){?>

                    <a href="ratting.php?id=<?php echo $appointment['id']?>" class="text-red-500 hover:text-red-700 font-bold text-sm bg-green-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">تقييم الجلسة</a>

                   <?php }else{?>
انتهت الجلسة
                   <?php }?>
                </div>
            </div>
        <?php endforeach; ?>
</div>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3 mb-6">
                        <span class="text-emerald-500">💳</span> الدفعات المالية الأخيرة
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-bold text-sm">
                                    <th class="pb-4 font-black">رقم الفاتورة</th>
                                    <th class="pb-4 font-black">الخدمة / العيادة</th>
                                    <th class="pb-4 font-black">التاريخ</th>
                                    <th class="pb-4 font-black">المبلغ</th>
                                </tr>
                            </thead>
                         <tbody class="divide-y divide-slate-50 text-slate-600 font-bold">
    <?php if (count($payments) > 0): ?>
        <?php foreach ($payments as $payment): ?>
            <tr>
                <td class="py-4 text-slate-900">#INV-<?php echo $payment['id']; ?></td>
                <td class="py-4"><?php echo $payment['service_name'] ?? 'خدمة عامة'; ?></td>
                <td class="py-4 text-slate-400 text-sm"><?php echo date('d M Y', strtotime($payment['created_at'])); ?></td>
                <td class="py-4 text-slate-900 font-black">$<?php echo $payment['price']; ?></td>
             
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="py-4 text-center text-slate-400">لا توجد دفعات مالية مسجلة حالياً</td>
        </tr>
    <?php endif; ?>
</tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-[32px] p-6 lg:p-8 shadow-2xl border border-slate-100/80 relative overflow-hidden">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-sky-300/20 rounded-full blur-2xl"></div>
                
                <div class="relative z-10">
                    <div class="mb-6 text-center">
                        <span class="text-3xl">✨</span>
                        <h2 class="text-2xl font-black text-slate-900 mt-2 mb-1">إنشاء حجز جديد</h2>
                        <p class="text-slate-400 text-sm">اختر اليوم والخدمة لتأكيد موعدك</p>
                    </div>

                    <form action="" method="POST" class="space-y-4">
                        
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-600 pr-2">التخصص والخدمة</label>
                            <select name="specialty" required class="w-full h-14 rounded-xl border border-slate-200 px-4 outline-none focus:border-sky-400 transition bg-white text-slate-700 font-bold">
                                <?php if(!empty($services)): ?>
                                    <?php foreach($services as $ser): ?>
                                        <option value="<?php echo $ser['id']; ?>"> <?php echo htmlspecialchars($ser['name']); ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">لا توجد خدمات متاحة</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-600 pr-2">اختر اليوم</label>
                            <select id="booking_day" name="booking_day" required class="w-full h-14 rounded-xl border border-slate-200 px-4 outline-none focus:border-sky-400 transition bg-white text-slate-700 font-bold">
                                <option value="">اختر اليوم المناسب</option>
                                <option value="السبت">السبت</option>
                                <option value="الأحد">الأحد</option>
                                <option value="الإثنين">الإثنين</option>
                                <option value="الثلاثاء">الثلاثاء</option>
                                <option value="الأربعاء">الأربعاء</option>
                                <option value="الخميس">الخميس</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-600 pr-2">الوقت المتاح</label>
                            <select id="booking_time" name="slot_id" required disabled 
                                    class="w-full h-14 rounded-xl border border-slate-200 px-4 outline-none focus:border-sky-400 transition bg-white text-slate-700 font-bold disabled:bg-slate-100 disabled:text-slate-400">
                                <option value="">اختر اليوم أولاً</option>
                            </select>
                        </div>

                        <button type="submit" name="submit_booking" class="w-full h-14 mt-4 rounded-xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-black text-lg shadow-lg shadow-sky-100 hover:scale-[1.01] transition transform duration-200">
                            تأكيد الحجز الفوري
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
document.getElementById('booking_day').addEventListener('change', function() {
    const dayValue = this.value;
    const timeSelect = document.getElementById('booking_time');
    
    timeSelect.innerHTML = '<option value="">جاري تحميل الأوقات المتاحة...</option>';
    timeSelect.disabled = true;

    if (!dayValue) {
        timeSelect.innerHTML = '<option value="">اختر اليوم أولاً</option>';
        return;
    }

    // تم تغيير الرابط ليعود لنفس الصفحة ديناميكياً بدلاً من كتابة اسم الملف ثابتاً
    fetch(`?get_slots=true&day=${encodeURIComponent(dayValue)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            timeSelect.innerHTML = '';
            
            if (data.error) {
                console.error('Server Error:', data.error);
                timeSelect.innerHTML = '<option value="">حدث خطأ في قاعدة البيانات</option>';
                return;
            }

            if (data.length === 0) {
                timeSelect.innerHTML = '<option value="">لا توجد مواعيد متاحة في هذا اليوم</option>';
                timeSelect.disabled = true;
            } else {
                timeSelect.innerHTML = '<option value="">اختر الوقت المناسب</option>';
                data.forEach(slot => {
                    timeSelect.innerHTML += `<option value="${slot.id}">${slot.time_range}</option>`;
                });
                timeSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            timeSelect.innerHTML = '<option value="">حدث خطأ أثناء تحميل البيانات</option>';
        });
});
</script>

<?php include("../include/footer.php")?>