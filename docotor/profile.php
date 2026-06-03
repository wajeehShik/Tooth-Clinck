
    <?php
     include("include/header.php");
     $user_id = $_SESSION['id'] ?? 1;
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];
    $img_name = $user['img'] ?? ''; 
    if (empty($name)) {
        $errors[] = "الاسم الكامل مطلوب";
    }
    if (empty($phone)) {
        $errors[] = "رقم الجوال مطلوب";
    }
    if (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            $errors[] = "كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "كلمة المرور الجديدة وتأكيدها غير متطابقين";
        }
    }
    if (isset($_FILES['img']) && $_FILES['img']['error'] === 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        $file_size = $_FILES['img']['size'];
        $max_size = 3 * 1024 * 1024; 

        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "صيغة الصورة غير مدعومة. الامتدادات المسموحة: (PNG, JPG, JPEG, WEBP)";
        }
        if ($file_size > $max_size) {
            $errors[] = "حجم الصورة كبير جداً، الحد الأقصى المسموح به هو 3 ميجابايت";
        }
        if (empty($errors)) {
            $upload_dir = "upload/users/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $new_img_name = "user_" . time() . "_" . uniqid() . "." . $file_extension;
            $destination = $upload_dir . $new_img_name;

            if (move_uploaded_file($_FILES['img']['tmp_name'], $destination)) {
                if (!empty($user['img']) && file_exists($upload_dir . $user['img'])) {
                    unlink($upload_dir . $user['img']);
                }
                $img_name = $new_img_name;
            } else {
                $errors[] = "حدث خطأ أثناء حفظ الصورة على السيرفر";
            }
        }
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    if (!empty($new_password)) {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $update_stmt = $pdo->prepare("
            UPDATE doctors SET name = ?, phone = ?, img = ?, password = ? WHERE id = ?
        ");
        $update_stmt->execute([$name, $phone, $img_name, $hashedPassword, $user_id]);
    } else {
        $update_stmt = $pdo->prepare("
            UPDATE doctors SET name = ?, phone = ?, img = ? WHERE id = ?
        ");
        $update_stmt->execute([$name, $phone, $img_name, $user_id]);
    }
    $_SESSION['name'] = $name;

    $_SESSION['success'] = "تم تحديث معلومات ملفك الشخصي بنجاح 🎉";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
    ?>
    <div class="pr-0 lg:pr-72 w-full min-h-screen flex flex-col transition-all duration-300">
        <main class="p-4 sm:p-8 flex-1 max-w-4xl w-full mx-auto space-y-6 sm:space-y-8">
           <?php if (!empty($_SESSION['errors'])): ?>
            <div class="bg-red-50 border border-red-100 text-red-700 p-5 rounded-[22px] text-sm font-semibold space-y-1 shadow-sm">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p class="flex items-center gap-2">⚠️ <?= $error ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-5 rounded-[22px] text-sm font-semibold shadow-sm flex items-center gap-2">
                <p><?= $_SESSION['success'] ?></p>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" class="space-y-6 sm:space-y-8">
                
                <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm relative">
                    <div class="h-32 bg-gradient-to-r from-sky-400 via-sky-500 to-indigo-500 w-full"></div>
                    <div class="px-6 pb-6 flex flex-col items-center -mt-16 relative z-10 text-center">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-3xl overflow-hidden bg-slate-100 border-4 border-white shadow-xl flex items-center justify-center text-slate-400 relative">
                                <img id="imgPreview" src="upload/users/<?php echo $user['img']?>" alt="صورة الطبيب" class="w-full h-full object-cover">
                            </div>
                            <label for="imgInput" class="absolute bottom-1 left-1 bg-sky-500 hover:bg-sky-600 text-white w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer shadow-lg shadow-sky-300 transition-all duration-200 hover:scale-110">
                                📷
                                <input type="file" id="imgInput" name="img" accept="image/*" class="hidden">
                            </label>
                        </div>
                        
                        <h3 id="cardUserName" class="text-lg font-black text-slate-900 mt-4 leading-none"><?php echo $user['name']?> </h3>
                        <p class="text-xs text-slate-400 font-medium mt-1.5">مدير النظام والعيادة الفنية</p>
                    </div>
                </div>
                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-50 flex items-center gap-2">
                            <span class="text-sky-500">👤</span> البيانات الأساسية
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- حقل الاسم -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">الاسم الكامل</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">✏️</span>
                                    <input type="text" id="inputName" name="name" value="<?php echo $user['name']?>" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200" required>
                                </div>
                            </div>
                            
                            <!-- حقل الجوال -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">رقم الجوال</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">📞</span>
                                    <input type="tel" name="phone" value="<?php echo  $user['phone']?>" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 text-left dir-ltr focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- القسم الثاني: الأمان والسرية -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-50 flex items-center gap-2">
                            <span class="text-sky-500">🔒</span> الأمان وكلمة المرور
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- كلمة المرور الجديدة -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">كلمة المرور الجديدة</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔑</span>
                                    <input type="password" name="new_password"  class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200">
                                </div>
                                <p class="text-[10px] text-slate-400 mr-1">اتركه فارغاً إذا كنت لا تريد تغييرها.</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">تأكيد كلمة المرور الجديدة</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🛡️</span>
                                    <input type="password" name="confirm_password" placeholder="••••••••" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- أزرار التحكم وحفظ التعديلات -->
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-50">
                        <button type="button" class="px-5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl text-sm font-bold transition-colors">
                            إلغاء التعديلات
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-sm font-bold shadow-md shadow-sky-100 transition-all duration-200 hover:scale-[1.01]">
                            حفظ التغييرات
                        </button>
                    </div>

                </div>
            </form>
        </main>
    </div>

    <!-- كود جافاسكريبت للتحكم ومعاينة المخرجات حياً -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');

        function toggleSidebar() {
            const isOpen = !sidebar.classList.contains('translate-x-full');
            if (isOpen) {
                sidebar.classList.add('translate-x-full');
                sidebarOverlay.classList.add('hidden');
                sidebarOverlay.classList.remove('opacity-100');
            } else {
                sidebar.classList.remove('translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => sidebarOverlay.classList.add('opacity-100'), 10);
            }
        }

        openSidebarBtn.addEventListener('click', toggleSidebar);
        closeSidebarBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // التفاعل والـ UX: معاينة حية وفورية للصورة عند رفعها من الجهاز
        const imgInput = document.getElementById('imgInput');
        const imgPreview = document.getElementById('imgPreview');

        imgInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // التفاعل والـ UX: تحديث الاسم فورياً في هيدر الملف الشخصي وفي القائمة الجانبية أثناء الكتابة
        const inputName = document.getElementById('inputName');
        const cardUserName = document.getElementById('cardUserName');
        const sidebarUserName = document.getElementById('sidebarUserName');
        const userInitials = document.getElementById('userInitials');

        inputName.addEventListener('input', function() {
            const val = this.value.trim() || 'مستخدم النظام';
            cardUserName.textContent = val;
            sidebarUserName.textContent = val;
            if(val.startsWith('د.')) {
                userInitials.textContent = 'د.' + (val.split(' ')[1] ? val.split(' ')[1][0] : '');
            } else {
                userInitials.textContent = val[0] + (val.split(' ')[1] ? val.split(' ')[1][0] : '');
            }
        });
    </script>
    <?php include("include/footer.php")?>