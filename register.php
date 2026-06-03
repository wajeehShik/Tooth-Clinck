<?php
include("include/header.php");

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'doctor') {
        header("Location: docotor/dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: user/dashboard.php"); 
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if (empty($name)) {
        $errors[] = "الاسم مطلوب";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "البريد الإلكتروني غير صحيح";
    }
    if (strlen($password) < 6) {
        $errors[] = "كلمة المرور يجب أن تكون 6 أحرف على الأقل";
    }
    if ($password !== $confirm_password) {
        $errors[] = "كلمة المرور غير متطابقة";
    }
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        $errors[] = "البريد الإلكتروني مستخدم مسبقاً";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: register.php");
        exit;
    }
$hashedPassword = sha1($password);

$stmt = $pdo->prepare("
    INSERT INTO users (name, email, phone, password)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$name, $email, $phone, $hashedPassword]);
$newUserId = $pdo->lastInsertId();
    $_SESSION['success'] = "تم إنشاء الحساب بنجاح 🎉";
            session_regenerate_id(true);

       $_SESSION['id']    = $newUserId; 
$_SESSION['name']  = $name;      
$_SESSION['email'] = $email;    
            $_SESSION['role'] = 'user';
    header("Location:index.php");
    exit;
}

?>
<section class="min-h-screen mt-[100px] mb-[100px] flex items-center justify-center px-6 py-10">

    <div class="w-full max-w-6xl grid lg:grid-cols-2 rounded-[40px] overflow-hidden shadow-2xl bg-white">

        <div class="hidden lg:flex items-center justify-center p-16 bg-gradient-to-br from-sky-500 to-cyan-600 relative overflow-hidden order-2">
            
            <div class="absolute w-72 h-72 bg-white/10 rounded-full blur-3xl top-0 left-0"></div>
            <div class="absolute w-72 h-72 bg-white/10 rounded-full blur-3xl bottom-0 right-0"></div>

            <div class="relative text-center text-white">
                <div class="text-6xl mb-8">🦷</div>
                <h1 class="text-5xl font-black mb-6">انضم إلى DentalCare</h1>
                <p class="text-xl text-white/90 leading-loose">
                    أنشئ حسابك الآن واحجز مواعيدك بسهولة وادخل عالم الرعاية الطبية الذكية.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-center p-8 lg:p-16 order-1">

            <div class="w-full max-w-md text-right">

                <div class="mb-10">
                    <span class="bg-white/75 backdrop-blur-md border border-white/40 px-5 py-3 rounded-full text-sky-700 font-bold inline-flex mb-6 shadow-sm">
                        🧾 إنشاء حساب جديد
                    </span>
                    <h2 class="text-5xl font-black text-slate-900 mb-4">حساب جديد</h2>
                    <p class="text-slate-500 text-lg">املأ البيانات لإنشاء حسابك</p>
                </div>
<?php if (!empty($_SESSION['errors'])): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>
                <form class="space-y-5 text-right" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

                    <input type="text" placeholder="الاسم الكامل"  name="name"
                           class="w-full h-[62px] rounded-[18px] border border-slate-200 px-5 outline-none transition-all duration-300 bg-white text-right focus:border-sky-400 focus:ring-4 focus:ring-sky-500/10">

                    <input type="email" placeholder="البريد الإلكتروني"  name="email"
                           class="w-full h-[62px] rounded-[18px] border border-slate-200 px-5 outline-none transition-all duration-300 bg-white text-right focus:border-sky-400 focus:ring-4 focus:ring-sky-500/10">

                    <input type="text" placeholder="رقم الهاتف" name="phone"
                           class="w-full h-[62px] rounded-[18px] border border-slate-200 px-5 outline-none transition-all duration-300 bg-white text-right focus:border-sky-400 focus:ring-4 focus:ring-sky-500/10">

                    <input type="password" placeholder="كلمة المرور"  name="password"
                           class="w-full h-[62px] rounded-[18px] border border-slate-200 px-5 outline-none transition-all duration-300 bg-white text-right focus:border-sky-400 focus:ring-4 focus:ring-sky-500/10">

                    <input type="password" placeholder="تأكيد كلمة المرور"  name="confirm_password"
                           class="w-full h-[62px] rounded-[18px] border border-slate-200 px-5 outline-none transition-all duration-300 bg-white text-right focus:border-sky-400 focus:ring-4 focus:ring-sky-500/10">
                    <button type="submit"
                            class="w-full h-16 rounded-2xl bg-gradient-to-l from-sky-500 to-sky-700 text-white font-black text-xl shadow-xl shadow-sky-200 hover:scale-[1.02] transition-transform duration-300">
                        إنشاء الحساب
                    </button>

                </form>

                <p class="text-center text-slate-500 mt-8 text-lg">
                    لديك حساب بالفعل؟
                    <a href="login.php" class="text-sky-500 font-black hover:text-sky-600 transition-colors">تسجيل الدخول</a>
                </p>

            </div>

        </div>

    </div>
</section>
<?php include("include/footer.php"); ?>