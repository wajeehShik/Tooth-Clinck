<?php include("include/header.php");


$stmt = $pdo->prepare("SELECT * FROM clinic WHERE id = 1");
$stmt->execute();
$clinic = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);
    $facebook = trim($_POST['facebook']);
    $instagram = trim($_POST['instagram']);
    $x_twitter = trim($_POST['x_twitter']);

    $errors = [];
    $logo_name = $clinic['logo']; 
    if (empty($name)) {
        $errors[] = "اسم العيادة مطلوب";
    }
    if (empty($phone)) {
        $errors[] = "رقم هاتف العيادة مطلوب";
    }
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $file_size = $_FILES['logo']['size'];
        $max_size = 3 * 1024 * 1024; 

        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "صيغة الصورة غير مدعومة. الامتدادات المسموحة: (PNG, JPG, JPEG, WEBP)";
        }
        if ($file_size > $max_size) {
            $errors[] = "حجم الصورة كبير جداً، الحد الأقصى المسموح به هو 3 ميجابايت";
        }
        if (empty($errors)) {
            $upload_dir = "upload/settings/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $new_logo_name = "logo_" . time() . "_" . uniqid() . "." . $file_extension;
            $destination = $upload_dir . $new_logo_name;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                if (!empty($clinic['logo']) && file_exists($upload_dir . $clinic['logo'])) {
                    unlink($upload_dir . $clinic['logo']);
                }
                $logo_name = $new_logo_name;
            } else {
                $errors[] = "حدث خطأ غير متوقع أثناء رفع الصورة إلى السيرفر";
            }
        }
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $update_stmt = $pdo->prepare("
        UPDATE clinic SET 
            name = ?, 
            phone = ?, 
            description = ?, 
            facebook = ?, 
            instagram = ?, 
            x_twitter = ?, 
            logo = ? 
        WHERE id = 1
    ");
    
    $update_stmt->execute([$name, $phone, $description, $facebook, $instagram, $x_twitter, $logo_name]);
    $_SESSION['success'] = "تم تحديث إعدادات العيادة بنجاح 🎉";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
    <div class="pr-0 lg:pr-10 w-full min-h-screen flex flex-col transition-all duration-300">
        <main class="p-4 sm:p-8 flex-1 max-w-4xl w-full mx-auto  sm:space-y-8">
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
            <form  
                    action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6 sm:space-y-8">
                
                <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm relative">
                    <div class="h-32 bg-gradient-to-r from-sky-400 via-sky-500 to-indigo-500 w-full"></div>
                    
                    <div class="px-6 pb-6 flex flex-col items-center -mt-16 relative z-10 text-center">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-3xl overflow-hidden bg-slate-100 border-4 border-white shadow-xl flex items-center justify-center text-slate-400 relative">
                                <img id="logoPreview" src="upload/settings/<?php echo $clinic['logo']?>" alt="شعار العيادة" class="w-full h-full object-cover">
                            </div>
                            <label for="logoInput" class="absolute bottom-1 left-1 bg-sky-500 hover:bg-sky-600 text-white w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer shadow-lg shadow-sky-300 transition-all duration-200 hover:scale-110">
                                📷
                                <input type="file" id="logoInput" name="logo" accept="image/*" class="hidden">
                            </label>
                        </div>
                        
                        <h3 id="cardClinicName" class="text-lg font-black text-slate-900 mt-4 leading-none">عيادة <?php echo $clinic['name']?></h3>
                        <p class="text-xs text-slate-400 font-medium mt-1.5">الملف الرسمي ومعلومات الاتصال</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 rounded-[28px] p-6 sm:p-8 shadow-sm space-y-6">
                    
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-50 flex items-center gap-2">
                            <span class="text-sky-500">🏥</span> البيانات الأساسية
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">اسم العيادة</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">✏️</span>
                                    <input type="text" id="inputClinicName" name="name" value="<?php echo $clinic['name']?>" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200" required>
                                </div>
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">رقم الهاتف أو الجوال</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">📞</span>
                                    <input type="tel" name="phone" value="<?php echo $clinic['phone']?>" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 text-left dir-ltr focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200" required>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5 mt-4">
                            <label class="text-xs font-bold text-slate-500">وصف العيادة أو النبذة التعريفية</label>
                            <div class="relative">
                                <span class="absolute right-4 top-4 text-slate-400 text-sm">📝</span>
                                <textarea name="description" rows="3" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200 resize-none">
                                    <?php echo $clinic['description']?>
                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-50 flex items-center gap-2">
                            <span class="text-sky-500">🌐</span> حسابات التواصل الاجتماعي
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">رابط Facebook</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">👤</span>
                                    <input type="url" name="facebook" 
                                    value="<?php echo $clinic['facebook']?>"
                                     class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 text-left focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">رابط Instagram</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">📸</span>
                                    <input type="url" name="instagram" value="<?php echo $clinic['instagram']?>" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 text-left focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500">رابط X (تويتر سابقاً)</label>
                                <div class="relative">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🐦</span>
                                    <input type="url" name="x_twitter" value="<?php echo $clinic['x_twitter']?>" class="w-full bg-slate-50/50 border border-slate-100 rounded-xl pr-11 pl-4 py-3 text-sm font-semibold text-slate-800 text-left focus:outline-none focus:border-sky-500 focus:bg-white transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>

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

        if(openSidebarBtn) openSidebarBtn.addEventListener('click', toggleSidebar);
        if(closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

        // معاينة فورية لشعار العيادة المرفوع حديثاً
        const logoInput = document.getElementById('logoInput');
        const logoPreview = document.getElementById('logoPreview');

        if(logoInput) {
            logoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        logoPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
        // تحديث اسم العيادة في الكرت العلوي فوراً أثناء الكتابة
        const inputClinicName = document.getElementById('inputClinicName');
        const cardClinicName = document.getElementById('cardClinicName');

        if(inputClinicName) {
            inputClinicName.addEventListener('input', function() {
                cardClinicName.textContent = this.value.trim() || 'عيادة الطبية';
            });
        }
    </script>
    <?php include("include/footer.php")?>