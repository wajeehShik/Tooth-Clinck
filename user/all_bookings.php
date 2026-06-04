<?php 
include("../include/header.php");

// جلب المواعيد الخاصة بالمستخدم الحالي
$user_id = $_SESSION['user_id']; // تأكد من وجود الـ Session
$today = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT cs.*, s.name as service_name 
    FROM clinic_slots cs
    JOIN services s ON cs.service_id = s.id
    WHERE cs.user_id = ?
    ORDER BY cs.booking_date DESC
");
$stmt->execute([$user_id]);
$all_appointments = $stmt->fetchAll();

// تقسيم البيانات برمجياً
$upcoming = array_filter($all_appointments, fn($a) => $a['booking_date'] >= $today && $a['is_booked'] == 1);
$history = array_filter($all_appointments, fn($a) => $a['booking_date'] < $today || $a['is_booked'] == 0);

?>
<main class="relative pt-36 pb-24 overflow-hidden bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.08),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.05),transparent_35%)]">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div>
                <span class="bg-white/70 backdrop-blur-md border border-white/40 px-4 py-2 rounded-full text-sky-700 font-bold text-sm inline-flex mb-4 shadow-sm">
                    🗓️ إدارة الحجوزات
                </span>
                <h1 class="text-4xl font-black text-slate-900">جدول مواعيدك الطبية</h1>
                <p class="text-slate-500 mt-2 text-lg">تابع حالة حجزك الحالي وتصفح أرشيف زياراتك للعيادة.</p>
            </div>
            
            <div class="flex items-center gap-4 self-start md:self-center">
                <div class="bg-white rounded-2xl p-4 border border-slate-200/60 shadow-sm flex items-center gap-4 min-w-[140px]">
                    <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl">⏳</div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold">المقبلة</p>
                        <p class="text-xl font-black text-slate-900">1</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/60 shadow-sm flex items-center gap-4 min-w-[140px]">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">✓</div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold">المكتملة</p>
                        <p class="text-xl font-black text-slate-900">3</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            
            <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-xl shadow-slate-100/50 overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        <span class="inline-block w-3 h-3 rounded-full bg-sky-500 animate-pulse"></span>
                        المواعيد القادمة والمستقبلية
                    </h2>
                    <a href="index.php#booking" class="text-sm font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 transition">
                        + حجز موعد جديد
                    </a>
                </div>
                
                <div class="p-6 lg:p-8">
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="text-slate-400 font-bold text-sm border-b border-slate-100 pb-4">
                                    <th class="pb-4 pr-4">الخدمة المطلوبة</th>
                                    <th class="pb-4">التاريخ والوقت</th>
                                    <th class="pb-4">الحالة</th>
                                    <th class="pb-4 text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                             <?php foreach ($upcoming as $app): ?>
    <tr class="hover:bg-slate-50/50 transition">
        <td class="py-5 pr-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-100 flex items-center justify-center text-2xl">🦷</div>
                <div>
                    <p class="font-black text-slate-900 text-base"><?= htmlspecialchars($app['service_name']) ?></p>
                </div>
            </div>
        </td>
        <td class="py-5">
            <p class="font-bold text-slate-900"><?= $app['booking_date'] ?></p>
            <p class="text-xs text-slate-500 mt-0.5"><?= $app['time_range'] ?></p>
        </td>
        <td class="py-5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200/50">
                ● مؤكد
            </span>
        </td>
        <td class="py-5 text-center">
            <button class="px-4 py-2 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition">إلغاء</button>
        </td>
    </tr>
    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="block md:hidden space-y-4">
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-cyan-100 flex items-center justify-center text-2xl">🦷</div>
                                <div>
                                    <h4 class="font-black text-slate-900">حشو الأسنان وجذور</h4>
                                    <p class="text-xs text-slate-400">تكلفة تقريبية: 50$</p>
                                </div>
                            </div>
                            <div class="space-y-2 border-t border-b border-slate-200/60 my-4 py-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">الموعد:</span>
                                    <span class="font-bold text-slate-800">04 يونيو 2026 - 04:30 م</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-400">الحالة:</span>
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100">قيد الانتظار</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <button class="w-full h-11 text-sm font-bold text-slate-600 bg-slate-100 rounded-xl">تعديل</button>
                                <button class="w-full h-11 text-sm font-bold text-rose-600 bg-rose-50 rounded-xl">إلغاء</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-xl shadow-slate-100/50 overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        📁 سجل الزيارات السابقة
                    </h2>
                </div>
                
                <div class="p-6 lg:p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="text-slate-400 font-bold text-sm border-b border-slate-100 pb-4">
                                    <th class="pb-4 pr-4">الخدمة</th>
                                    <th class="pb-4">تاريخ الزيارة</th>
                                    <th class="pb-4">الحالة</th>
                                    <th class="pb-4 text-center">ملف الفحص</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium text-sm md:text-base">
      <?php foreach ($history as $app): ?>
    <tr class="hover:bg-slate-50/50 transition">
        <td class="py-5 pr-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-sky-100 flex items-center justify-center text-xl">🪥</div>
                <p class="font-black text-slate-900"><?= htmlspecialchars($app['service_name']) ?></p>
            </div>
        </td>
        <td class="py-5 text-slate-600"><?= $app['booking_date'] ?></td>
        <td class="py-5">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/40">
                <?= $app['is_booked'] == 1 ? '✓ مكتملة' : '✕ ملغاة' ?>
            </span>
        </td>
        <td class="py-5 text-center">
            <?php if($app['is_booked'] == 1): ?>
            <button onclick="openRatingModal(<?= $app['id'] ?>)" class="px-4 py-2 rounded-xl bg-amber-50 text-amber-700 font-bold text-xs hover:bg-amber-100 transition">
                ⭐ تقييم الخدمة
            </button>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 bg-sky-50/50 border border-sky-100 p-6 rounded-2xl flex items-start gap-4">
            <span class="text-2xl">💡</span>
            <p class="text-slate-600 text-sm leading-loose">
                <strong>ملاحظة هامة جداً:</strong> يمكنك تعديل الموعد المحجوز أو إلغاؤه إلكترونياً قبل الموعد الفعلي بـ 24 ساعة كحد أقصى. في حال واجهتك مشكلة ضيق الوقت أو الطوارئ يرجى الاتصال هاتفياً بالدعم الفني مباشرة لضمان حقوق حجزك.
            </p>
        </div>

    </div>
</main>
<!-- Rating Modal -->
<div id="ratingModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">

    <div class="bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden animate-[fadeIn_.3s_ease]">

        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-black text-slate-900">
                    تقييم الزيارة
                </h3>
                <p class="text-slate-500 text-sm mt-1">
                    شاركنا رأيك لتحسين جودة الخدمة
                </p>
            </div>

            <button onclick="closeRatingModal()"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 transition">
                ✕
            </button>
        </div>

        <form class="p-6 space-y-6">

            <div class="text-center">
                <h4 class="font-black text-slate-800 mb-4">
                    كيف كانت تجربتك؟
                </h4>

                <div class="flex justify-center gap-3 text-4xl">

                    <button type="button" class="star text-slate-300 transition hover:scale-110" data-rate="1">★</button>
                    <button type="button" class="star text-slate-300 transition hover:scale-110" data-rate="2">★</button>
                    <button type="button" class="star text-slate-300 transition hover:scale-110" data-rate="3">★</button>
                    <button type="button" class="star text-slate-300 transition hover:scale-110" data-rate="4">★</button>
                    <button type="button" class="star text-slate-300 transition hover:scale-110" data-rate="5">★</button>

                </div>

                <input type="hidden" id="ratingValue" name="rating">
            </div>

            <div>
                <label class="block mb-2 font-bold text-slate-700">
                    تعليقك
                </label>

                <textarea
                    rows="5"
                    class="w-full rounded-2xl border border-slate-200 p-4 focus:outline-none focus:border-sky-500"
                    placeholder="اكتب ملاحظاتك هنا..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">

                <button type="button"
                        onclick="closeRatingModal()"
                        class="h-14 rounded-2xl bg-slate-100 font-bold text-slate-700 hover:bg-slate-200 transition">
                    إلغاء
                </button>

                <button type="submit"
                        class="h-14 rounded-2xl bg-gradient-to-l from-sky-500 to-cyan-500 text-white font-black hover:scale-[1.02] transition">
                    إرسال التقييم
                </button>

            </div>

        </form>

    </div>

</div>
<script>

function openRatingModal() {
    document.getElementById('ratingModal')
        .classList.remove('hidden');

    document.getElementById('ratingModal')
        .classList.add('flex');
}

function closeRatingModal() {
    document.getElementById('ratingModal')
        .classList.add('hidden');

    document.getElementById('ratingModal')
        .classList.remove('flex');
}

const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('ratingValue');

stars.forEach(star => {

    star.addEventListener('click', function () {

        const value = this.dataset.rate;

        ratingInput.value = value;

        stars.forEach(s => {
            s.classList.remove('text-amber-400');
            s.classList.add('text-slate-300');
        });

        stars.forEach(s => {
            if (s.dataset.rate <= value) {
                s.classList.remove('text-slate-300');
                s.classList.add('text-amber-400');
            }
        });
    });
});
window.addEventListener('click', function(e){

    const modal = document.getElementById('ratingModal');

    if(e.target === modal){
        closeRatingModal();
    }

});

</script>