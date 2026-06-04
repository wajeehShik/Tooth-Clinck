<?php
include("../include/header.php");
$user_id = $_SESSION['id'];
$success_msg = "";
$error_msg = "";

// 1. معالجة التحديث في حال تم إرسال النموذج (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    try {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET  phone = ?, address = ?, password = ? WHERE id = ?");
            $stmt->execute([  $phone, $address, $hashed_password, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET  phone = ?, address = ? WHERE id = ?");
            $stmt->execute([  $phone, $address, $user_id]);
        }
        
        // إعادة التوجيه لـ dashboard بعد النجاح
        header("Location: dashboard.php?updated=1");
        exit;
    } catch (PDOException $e) {
        $error_msg = "حدث خطأ: " . $e->getMessage();
    }
}

// 2. جلب بيانات المستخدم لعرضها في النموذج
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main class="pt-32 pb-24 bg-slate-50">
    <div class="max-w-2xl mx-auto px-6">
        <div class="bg-white p-8 rounded-[32px] shadow-sm border border-slate-100">
            <h2 class="text-2xl font-black mb-6">تعديل الملف الشخصي</h2>
            
            <?php if($error_msg): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-4 font-bold"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4"> <!-- الـ action فارغ يعني لنفس الصفحة -->
             
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-1">رقم الهاتف</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full h-14 rounded-xl border border-slate-200 px-4" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-1">العنوان</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" class="w-full h-14 rounded-xl border border-slate-200 px-4" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-1">كلمة السر الجديدة (اتركها فارغة لعدم التغيير)</label>
                    <input type="password" name="password" class="w-full h-14 rounded-xl border border-slate-200 px-4">
                </div>
                <button type="submit" class="w-full h-14 bg-sky-600 text-white rounded-xl font-bold hover:bg-sky-700 transition">حفظ التغييرات</button>
            </form>
        </div>
    </div>
</main>
<?php include("../include/footer.php"); ?>