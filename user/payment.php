<?php include('../include/header.php');
$id = $_GET['id'] ?? null;
$user_id = $_SESSION['id'] ?? null;
if (!$id || !$user_id) {
    die("خطأ: بيانات غير مكتملة.");
}

$stmt = $pdo->prepare("SELECT cs.*, s.name as service_name, s.price  as service_price
                       FROM clinic_slots cs 
                       JOIN services s ON cs.service_id = s.id 
                       WHERE cs.id = ? AND cs.user_id = ?  and cs.status='1'");
$stmt->execute([$id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("هذا الموعد غير موجود أو لا تملك صلاحية الوصول إليه.");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['img'])) {
 
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['id'];
    
    // 1. رفع الصورة
    $upload_dir = '../docotor/upload/payments/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $file_name = time() . '_' . basename($_FILES['img']['name']);
    $target_file = $upload_dir . $file_name;
    
    if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
        
        try {

            $sql_payment = "INSERT INTO payments (
            date_id, user_id,doctor_id,price, status,created_at) 
                            VALUES (?, ?,1,?, '3', NOW())";
            $stmt_payment = $pdo->prepare($sql_payment);
   $stmt_payment->execute([$booking_id, $user_id,$booking['service_price']]);

            $sql_update = "UPDATE clinic_slots SET status = '2' WHERE id = ? AND user_id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([$booking_id, $user_id]);
            $_SESSION['success']='تمت عملية الدفع بنجاح';

$stmt = $pdo->prepare('INSERT INTO notifications (`message`, `link`) VALUES (?, ?)');
$ms = 'تم دفع    لجلسة   '.$booking['id'].'من قبل ' . $_SESSION['name'];
$stmt->execute([$ms, 'dates.php']);
            header("Location:dashboard.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();  
            echo "حدث خطأ: " . $e->getMessage();
        }
    } else {
        echo "فشل رفع الملف.";
    }
}
?>
<main class="relative pt-36 pb-24 bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.08),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.05),transparent_35%)]">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-10 text-center md:text-right">
            <span class="bg-white/70 backdrop-blur-md border border-white/40 px-4 py-2 rounded-full text-emerald-700 font-bold text-sm inline-flex mb-4 shadow-sm">💳 بوابة الدفع الآمنة</span>
            <h1 class="text-4xl font-black text-slate-900">تأكيد حجزك وإرسال الإشعار</h1>
            <p class="text-slate-500 mt-2 text-base">يرجى تحويل المبلغ المطلوب وإرفاق صورة الوصل لتأكيد موعدك الطبي بشكل نهائي.</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 shadow-xl shadow-slate-100/50">
                    <h3 class="text-lg font-black text-slate-900 mb-4 pb-3 border-b border-slate-100">تفاصيل الخدمة</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-2xl">😁</span>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">جلسة تبييض ليزر</h4>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400">التاريخ:</span>
                                <span class="font-bold text-slate-800"><?php echo $booking['booking_day'] .'-'.$booking['booking_date']?> </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">الوقت:</span>
                                <span class="font-bold text-slate-800"><?php echo $booking['time_range']?></span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-dashed border-slate-200 flex justify-between items-center">
                            <span class="font-bold text-slate-900 text-sm">المبلغ المطلوب:</span>
                            <span class="text-2xl font-black text-emerald-600"><?php echo $booking['service_price']?></span>
                        </div>
                    </div>
                </div>

                <!-- بطاقة معلومات الحساب البنكي أو المحفظة للتحويل -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-[32px] p-6 shadow-xl">
                    <h3 class="text-base font-bold mb-3 flex items-center gap-2">ℹ️ طرق التحويل المتاحة:</h3>
                    <div class="space-y-3 text-xs text-slate-300 leading-relaxed">
                        <p class="bg-white/5 p-2 rounded-xl border border-white/10">🏦 <strong>حساب بنك فلسطين:</strong><br>1234567 - فرع نابلس</p>
                        <p class="bg-white/5 p-2 rounded-xl border border-white/10">📱 <strong>محفظة Jawwal Pay:</strong><br>0599000000</p>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-[32px] p-6 lg:p-8 shadow-xl shadow-slate-100/50">
                <h3 class="text-xl font-black text-slate-900 mb-6">إرفاق إشعار الدفع الالكتروني</h3>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>?id=<?php echo $id?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="booking_id" value="<?php echo $id; ?>">  
                <div>
                        <label class="block text-slate-700 font-bold mb-2 text-sm">صورة إشعار أو وصل الدفع:</label>
                        <div class="relative group border-2 border-dashed border-slate-200 hover:border-sky-400 rounded-3xl bg-slate-50/50 p-8 text-center transition duration-300 flex flex-col items-center justify-center cursor-pointer">
                            <div class="w-16 h-16 bg-sky-50 text-sky-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition duration-300">
                                📸
                            </div>
                            
                            <p class="text-sm font-bold text-slate-700 mb-1">اضغط هنا لرفع صورة الوصل</p>
                            <p class="text-xs text-slate-400">الصيغ المقبولة: JPG, PNG, PDF (الحد الأقصى: 5MB)</p>
                            
                            <!-- حقل رفع الملف المخفي الذكي الممتد فوق المساحة كاملة -->
                            <input type="file" name="img" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileName(this)">
                        </div>
                        
                        <!-- مكان يظهر فيه اسم الملف المرفوع تلقائياً تأكيداً للمريض -->
                        <p id="file-name-display" class="text-xs font-bold text-emerald-600 mt-2 hidden flex items-center gap-1.5">
                            ✓ تم اختيار الملف: <span id="file-name-text" class="text-slate-600 font-normal"></span>
                        </p>
                    </div>

                    <!-- ملاحظة حول المراجعة -->
                    <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl text-xs text-amber-800 leading-relaxed">
                        ⚠️ <strong>ملاحظة:</strong> يتم مراجعة وتدقيق إشعارات الدفع من قبل قسم الحسابات في العيادة خلال أقل من ساعة، وسيتم إشعارك بتأكيد الحجز النهائي عبر رسالة نصية وحسابك هنا.
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <button type="submit" class="h-14 rounded-2xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-black text-base shadow-xl shadow-sky-100 hover:scale-[1.01] transition transform duration-300">
                            تأكيد وإرسال الوصل
                        </button>
                        <a href="my-bookings.php" class="h-14 rounded-2xl bg-slate-100 text-slate-600 font-bold text-base hover:bg-slate-200 transition flex items-center justify-center">
                            العودة لمواعيـدي
                        </a>
                    </div>

                </form>
            </div>

        </div>

    </div>
</main>

<!-- FOOTER -->
<footer class="bg-slate-900 py-8 text-center text-slate-500 text-sm">
    © 2026 جميع الحقوق محفوظة لـ DentalCare Clinic
</footer>
<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        const text = document.getElementById('file-name-text');
        if (input.files && input.files.length > 0) {
            text.innerText = input.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }
</script>
<?php include('../include/footer.php')?>