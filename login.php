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
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die('CSRF Error');
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "يرجى تعبئة جميع الحقول";
    } else {

        // فحص الدكتور
        $stmt = $pdo->prepare("
            SELECT id,name,email,password
            FROM doctors
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            $doctor &&
            sha1($password) === $doctor['password']
        ) {
            session_regenerate_id(true);

            $_SESSION['id'] = $doctor['id'];
            $_SESSION['name'] = $doctor['name'];
            $_SESSION['email'] = $doctor['email'];
            $_SESSION['role'] = 'doctor';

            header("Location:docotor/dashboard.php");
            exit;
        }

        // فحص المستخدم
        $stmt = $pdo->prepare("
            SELECT id,name,email,password
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            $user &&
     sha1($password) === $user['password']
        ) {

            session_regenerate_id(true);

            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'user';

            header("Location: user/dashboard.php");
            exit;
        }

        $_SESSION['error'] =
            "البريد الإلكتروني أو كلمة المرور غير صحيحة";
    }
}

?>

<section class="w-full mt-[100px] mb-[100px] flex items-center justify-center px-6">

    <div class="w-full max-w-6xl grid lg:grid-cols-2 rounded-[40px] overflow-hidden shadow-2xl bg-white my-auto">

        <div class="hidden lg:flex relative items-center justify-center p-16 bg-gradient-to-br from-sky-500 to-cyan-600 overflow-hidden">

            <div class="relative z-10 text-center text-white">

                <div class="w-28 h-28 rounded-[35px] bg-white/20 mx-auto flex items-center justify-center text-6xl mb-10">
                    🦷
                </div>

                <h1 class="text-5xl font-black leading-tight mb-6">
                    مرحباً بك في DentalCare
                </h1>

            </div>

        </div>

        <div class="flex items-center justify-center p-8 lg:p-16 bg-white">

            <div class="w-full max-w-md">

                <h2 class="text-5xl font-black text-slate-900 mb-6">
                    تسجيل الدخول
                </h2>

                <?php if(isset($_SESSION['error'])): ?>

                    <div class="mb-5 bg-red-100 border border-red-300 text-red-700 p-4 rounded-xl">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>

                    <?php unset($_SESSION['error']); ?>

                <?php endif; ?>

                <form
                    method="POST"
                    action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                    class="space-y-6"
                >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= $_SESSION['csrf_token'] ?>"
                    >

                    <div>

                        <label class="block mb-3 font-bold text-slate-700">
                            البريد الإلكتروني
                        </label>

                        <input
                            type="email"
                            name="email"
                            required
                            class="w-full h-16 rounded-2xl border border-slate-200 px-6"
                        >

                    </div>

                    <div>

                        <label class="block mb-3 font-bold text-slate-700">
                            كلمة المرور
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full h-16 rounded-2xl border border-slate-200 px-6"
                        >

                    </div>

                    <button
                        type="submit"
                        class="w-full h-16 rounded-2xl bg-sky-600 text-white font-bold"
                    >
                        تسجيل الدخول
                    </button>

                </form>

                <p class="text-center mt-8">
                    ليس لديك حساب؟
                    <a href="register.php" class="text-sky-600 font-bold">
                        إنشاء حساب
                    </a>
                </p>

            </div>

        </div>

    </div>

</section>

<?php include("include/footer.php"); ?>