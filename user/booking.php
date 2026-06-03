<?php include("include/header.php");


$success_msg = "";
$error_msg = "";
$current_user_id = $_SESSION['id']; 
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

// 4. استعلام ديناميكي لجلب الموعد القادم مع اسم الخدمة مباشرة باستخدام JOIN
$upcoming_appointment = null;
try {
    // افترضنا هنا أن اسم جدول الخدمات هو services واسم حقل الاسم هو name
    $query = "SELECT cs.booking_day, cs.booking_date, cs.time_range, s.name as service_name 
              FROM clinic_slots cs 
              LEFT JOIN services s ON cs.service_id = s.id 
              WHERE cs.user_id = ? AND cs.is_booked = 1 
              LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$current_user_id]);
    $upcoming_appointment = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // خطأ صامت
}

// 5. جلب الخدمات لعرضها في القائمة المنسدلة (تم تصحيح الاستعلام والجدول الافتراضي services)
try {
    $sers = $pdo->prepare('SELECT id, name FROM services WHERE status = 1');
    $sers->execute();
    $services = $sers->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
}

?>

<!-- SECTION: BOOKING ONLY -->
<section class="relative pt-36 pb-24 overflow-hidden bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.12),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.08),transparent_35%)] min-h-[calc(100vh-80px)] flex items-center">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
        <div class="grid lg:grid-cols-2 gap-14 items-center">
            
            <!-- LEFT COLUMN: TEXT & STATS -->
            <div>
                <span class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-3 rounded-full text-sky-700 font-bold inline-flex mb-8 shadow-sm">
                    ✨ استشارات طبية متخصصة - احجز الآن
                </span>

                <h1 class="text-5xl lg:text-7xl font-black leading-tight text-slate-900 mb-8">
                    رعايتك الصحية <span class="text-sky-500">تبدأ بخطوة بسيطة</span>
                </h1>

                <p class="text-slate-600 text-xl leading-loose mb-10 max-w-2xl">
                    احجز موعد استشارتك الطبية مع نخبة من الأطباء المتخصصين. نوفر لك رعاية متكاملة بأحدث الأساليب العلاجية مع نظام حجز ذكي يوفر وقتك وجهدك.
                </p>

                <!-- FEATURES -->
                <div class="flex flex-wrap gap-4 mb-10">
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-4 rounded-2xl font-bold text-slate-700 shadow-sm">
                        ✔ أطباء استشاريون
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-4 rounded-2xl font-bold text-slate-700 shadow-sm">
                        ✔ حجز إلكتروني مرن
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-4 rounded-2xl font-bold text-slate-700 shadow-sm">
                        ✔ خصوصية وأمان تام
                    </div>
                </div>

                <!-- STATS -->
                <div class="grid grid-cols-3 gap-5">
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl p-5 text-center shadow-sm">
                        <h2 class="text-3xl font-black text-slate-900">+20</h2>
                        <p class="text-slate-500 mt-2">طبيب متخصص</p>
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl p-5 text-center shadow-sm">
                        <h2 class="text-3xl font-black text-slate-900">99%</h2>
                        <p class="text-slate-500 mt-2">تشخيص دقيق</p>
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl p-5 text-center shadow-sm">
                        <h2 class="text-3xl font-black text-slate-900">24/7</h2>
                        <p class="text-slate-500 mt-2">طوارئ واستشارات</p>
                    </div>
                </div>
            </div>
            
            <!-- RIGHT COLUMN: BOOKING CARD -->
            <div class="relative" id="booking">
                <div class="absolute -top-10 -left-10 w-72 h-72 bg-sky-300/30 rounded-full blur-3xl"></div>
                <div class="relative z-10 bg-white rounded-[40px] p-8 lg:p-10 shadow-2xl border border-slate-100">
                    <div class="mb-8 text-center">
                        <h2 class="text-4xl font-black text-slate-900 mb-4">طلب موعد استشارة</h2>
                        <p class="text-slate-500 text-lg">يرجى ملء البيانات لتأكيد موعدك</p>
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

                    <!-- TRUST -->
                    <div class="flex items-center justify-center gap-6 mt-8 text-slate-500 font-bold text-sm flex-wrap">
                        <span>✔ مراجعة فورية للطلب</span>
                        <span>✔ سرية البيانات</span>
                        <span>✔ تذكير تلقائي عبر الواتساب</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- FOOTER -->
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

<?php include("include/footer.php")?>