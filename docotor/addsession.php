<?php
include("include/header.php");
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['get_slots']) && $_GET['get_slots'] == 'true' && isset($_GET['day'])) {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $pdo->prepare("SELECT id, time_range FROM clinic_slots WHERE booking_day = ? AND is_booked = 0");
        $stmt->execute([$_GET['day']]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}
$success_msg = $error_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    $user_id=$_POST['user_id'];
    $slot_id = $_POST['slot_id'] ?? '';
    $service_id = $_POST['specialty'] ?? '';
    $status_payment=$_POST['status_payment'];


    if (!empty($slot_id) && !empty($service_id)) {
        try {
            $stmt = $pdo->prepare("UPDATE clinic_slots SET status=?, is_booked = 1, user_id = ?, service_id = ? WHERE id = ? AND is_booked = 0");
            if ($stmt->execute([$status_payment,$user_id, $service_id, $slot_id])) {
                $success_msg = "تم تأكيد موعدك بنجاح! 🎉";
                header("Location:session.php");
                exit;
            } else {
                $error_msg = "عذراً، هذا الموعد تم حجزه للتو.";
            }
        } catch (PDOException $e) {
            $error_msg = "حدث خطأ تقني.";
        }
    }
}
$services = $pdo->query("SELECT id, name FROM services WHERE status = 1")->fetchAll(PDO::FETCH_ASSOC);
$stmt=$pdo->prepare("select * from users");
$stmt->execute();
$users=$stmt->fetchAll();
?>

<main class="min-h-screen bg-slate-50 pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-6">
        <?php if($success_msg): ?>
            <div class="mb-8 bg-emerald-50 text-emerald-800 p-4 rounded-2xl font-bold border border-emerald-200"><?php echo $success_msg; ?></div>
        <?php endif; ?>
            <div class="bg-white rounded-[40px] p-10 shadow-2xl border border-slate-100">
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="space-y-6">
                    <div>
                        <label class="block font-bold text-slate-600 mb-2">اختر مريض</label>
                        <select name="user_id" required class="w-full h-14 rounded-xl border border-slate-200 px-4">
                            <option value="">اختر مريض</option>
                            <?php foreach($users as $user){?>
                            <option value="<?php echo $user['id']?>"><?php echo $user['name']?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 mb-2">حالة الدفع </label>
                        <select  name="status_payment" required class="w-full h-14 rounded-xl border border-slate-200 px-4">
                            <option value="">اختر اليوم</option>
                            <option value="1">لم يدفع</option>
                            <option value="2">دفع</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-2">التخصص والخدمة</label>
                        <select name="specialty" required class="w-full h-14 rounded-xl border border-slate-200 px-4">
                            <?php foreach($services as $ser): ?>
                                <option value="<?php echo $ser['id']; ?>"><?php echo htmlspecialchars($ser['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-2">اختر اليوم</label>
                        <select id="booking_day" name="booking_day" required class="w-full h-14 rounded-xl border border-slate-200 px-4">
                            <option value="">اختر اليوم</option>
                            <option value="السبت">السبت</option>
                            <option value="الأحد">الأحد</option>
                            <option value="الإثنين">الإثنين</option>
                            <option value="الثلاثاء">الثلاثاء</option>
                            <option value="الأربعاء">الأربعاء</option>
                            <option value="الخميس">الخميس</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 mb-2">الوقت المتاح</label>
                        <select id="booking_time" name="slot_id" required disabled class="w-full h-14 rounded-xl border border-slate-200 px-4 disabled:bg-slate-100">
                            <option value="">اختر اليوم أولاً</option>
                        </select>
                    </div>

                    <button type="submit" name="submit_booking" class="w-full h-16 rounded-xl bg-sky-600 text-white font-black text-lg hover:bg-sky-700 transition">تأكيد الحجز</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('booking_day').addEventListener('change', function() {
    const dayValue = this.value;
    const timeSelect = document.getElementById('booking_time');
    if(!dayValue) return;

    timeSelect.innerHTML = '<option>جاري التحميل...</option>';
    fetch(`?get_slots=true&day=${encodeURIComponent(dayValue)}`)
        .then(res => res.json())
        .then(data => {
            timeSelect.innerHTML = '<option value="">اختر الوقت</option>';
            data.forEach(slot => {
                timeSelect.innerHTML += `<option value="${slot.id}">${slot.time_range}</option>`;
            });
            timeSelect.disabled = false;
        });
});
</script>

<?php include("include/footer.php"); ?>