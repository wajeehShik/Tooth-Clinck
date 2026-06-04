<?php
$clinic_info = null;
try {
    $stmt = $pdo->query("SELECT * FROM clinic WHERE id = 1");
    $clinic_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}
$name = $clinic_info['name'] ?? 'DentalCare';
$desc = $clinic_info['description'] ?? 'عيادة متخصصة في تقديم خدمات طب الأسنان الحديثة بأعلى جودة.';
$phone = $clinic_info['phone'] ?? '+970 599 000 000';
$fb = $clinic_info['facebook'] ?? '#';
$ig = $clinic_info['instagram'] ?? '#';
$tw = $clinic_info['x_twitter'] ?? '#';
?>

<footer class="bg-slate-900 py-14" id="contact">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-10">
            <div>
                <h2 class="text-3xl font-black text-white mb-6"><?php echo htmlspecialchars($name); ?></h2>
                <p class="text-slate-400 leading-loose"><?php echo htmlspecialchars($desc); ?></p>
            </div>

            <div>
                <h3 class="text-xl font-black text-white mb-6">معلومات التواصل</h3>
                <div class="space-y-4 text-slate-400">
                    <p>📍 غزة - فلسطين</p>
                    <p>📞 <?php echo htmlspecialchars($phone); ?></p>
                    <p>✉️ info@dentalcare.com</p>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-black text-white mb-6">روابط سريعة</h3>
                <div class="space-y-4 text-slate-400">
                    <a href="#" class="block hover:text-white transition">سياسة الخصوصية</a>
                    <a href="#faq" class="block hover:text-white transition">الأسئلة الشائعة</a>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-black text-white mb-6">تابعنا</h3>
                <div class="flex gap-4">
                    <a href="<?php echo htmlspecialchars($fb); ?>" target="_blank" class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl hover:bg-sky-600 transition">f</a>
                    <a href="<?php echo htmlspecialchars($ig); ?>" target="_blank" class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl hover:bg-pink-600 transition">in</a>
                    <a href="<?php echo htmlspecialchars($tw); ?>" target="_blank" class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl hover:bg-blue-400 transition">𝕏</a>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 mt-14 pt-8 text-center text-slate-500">
            © <?php echo date("Y"); ?> جميع الحقوق محفوظة لـ <?php echo htmlspecialchars($name); ?>
        </div>
    </div>
</footer>

<a href="#booking" class="fixed bottom-5 left-5 z-[100] block">
    <button class="w-16 h-16 rounded-full bg-gradient-to-l from-sky-500 to-sky-600 text-white text-2xl shadow-2xl shadow-sky-300 hover:scale-110 transition transform duration-300 flex items-center justify-center">
        🦷
    </button>
</a>

<?php ob_end_flush(); ?>