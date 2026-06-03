<?php include('include/header.php');?>
<!-- HERO -->
<section class="relative pt-36 pb-24 overflow-hidden bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.12),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.08),transparent_35%)]">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-14 items-center">
            <!-- CONTENT -->
            <div>
                <span class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-3 rounded-full text-sky-700 font-bold inline-flex mb-8 shadow-sm">
                    🔥 مواعيد متاحة اليوم - تأكيد فوري
                </span>
                <h1 class="text-5xl lg:text-7xl font-black leading-tight text-slate-900 mb-8">
                    احجز موعد <span class="text-sky-500">أسنانك خلال دقيقة</span>
                </h1>
                <p class="text-slate-600 text-xl leading-loose mb-10 max-w-2xl">
                    عيادة متخصصة بأحدث تقنيات طب الأسنان مع فريق طبي محترف وتجربة حجز سهلة وسريعة بدون انتظار.
                </p>

                <!-- FEATURES -->
                <div class="flex flex-wrap gap-4 mb-10">
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-4 rounded-2xl font-bold text-slate-700 shadow-sm">
                        ✔ +10,000 مريض
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-4 rounded-2xl font-bold text-slate-700 shadow-sm">
                        ✔ تأكيد فوري للحجز
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 px-5 py-4 rounded-2xl font-bold text-slate-700 shadow-sm">
                        ✔ أحدث الأجهزة الطبية
                    </div>
                </div>

                <!-- STATS -->
                <div class="grid grid-cols-3 gap-5">
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl p-5 text-center shadow-sm">
                        <h2 class="text-3xl font-black text-slate-900">98%</h2>
                        <p class="text-slate-500 mt-2">رضا المرضى</p>
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl p-5 text-center shadow-sm">
                        <h2 class="text-3xl font-black text-slate-900">+15</h2>
                        <p class="text-slate-500 mt-2">سنة خبرة</p>
                    </div>
                    <div class="bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl p-5 text-center shadow-sm">
                        <h2 class="text-3xl font-black text-slate-900">اليوم</h2>
                        <p class="text-slate-500 mt-2">مواعيد متاحة</p>
                    </div>
                </div>
            </div>

            <!-- BOOKING CARD -->
            <div class="relative" id="booking">
                <div class="absolute -top-10 -left-10 w-72 h-72 bg-sky-300/30 rounded-full blur-3xl"></div>
                <div class="relative z-10 bg-white rounded-[40px] p-8 lg:p-10 shadow-2xl border border-slate-100">
                    <div class="mb-8 text-center">
                        <h2 class="text-4xl font-black text-slate-900 mb-4">احجز موعدك الآن</h2>
                        <p class="text-slate-500 text-lg">الحجز يستغرق أقل من دقيقة</p>
                    </div>

                    <div class="space-y-5">
                        <input type="text" placeholder="الاسم الكامل" class="w-full h-16 rounded-2xl border border-slate-200 px-6 outline-none focus:border-sky-400 transition">
                        <input type="text" placeholder="رقم الهاتف" class="w-full h-16 rounded-2xl border border-slate-200 px-6 outline-none focus:border-sky-400 transition">
                        <select class="w-full h-16 rounded-2xl border border-slate-200 px-6 outline-none focus:border-sky-400 transition bg-white">
                            <option>اختر الخدمة</option>
                            <option>تنظيف الأسنان</option>
                            <option>حشو الأسنان</option>
                            <option>تبييض الأسنان</option>
                            <option>تقويم الأسنان</option>
                        </select>

                        <div class="grid grid-cols-2 gap-4">
                            <input type="date" class="w-full h-16 rounded-2xl border border-slate-200 px-6 outline-none focus:border-sky-400 transition">
                            <input type="time" class="w-full h-16 rounded-2xl border border-slate-200 px-6 outline-none focus:border-sky-400 transition">
                        </div>

                        <button class="w-full h-16 rounded-2xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-black text-xl shadow-xl shadow-sky-200 hover:scale-[1.02] transition transform duration-300">
                            احجز الآن
                        </button>
                    </div>

                    <!-- TRUST -->
                    <div class="flex items-center justify-center gap-6 mt-8 text-slate-500 font-bold text-sm flex-wrap">
                        <span>✔ تأكيد سريع</span>
                        <span>✔ بياناتك آمنة</span>
                        <span>✔ بدون انتظار</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="py-28 bg-white" id="services">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-20">
            <span class="text-sky-500 font-black text-lg">خدماتنا</span>
            <h2 class="text-[42px] font-900 text-slate-900 leading-[1.3] mt-5">خدمات متكاملة لصحة أسنانك</h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- CARD 1 -->
            <div class="bg-white border border-slate-200 rounded-[32px] p-8 transition duration-400 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(15,23,42,0.08)]">
                <div class="w-20 h-20 rounded-[28px] bg-sky-100 flex items-center justify-center text-4xl mb-8">🪥</div>
                <h3 class="text-3xl font-black text-slate-900 mb-5">تنظيف الأسنان</h3>
                <p class="text-slate-600 leading-loose mb-8">تنظيف احترافي للحفاظ على صحة الأسنان واللثة.</p>
                <div class="flex items-center justify-between">
                    <span class="font-black text-sky-500 text-xl">30$</span>
                    <span class="text-slate-500">30 دقيقة</span>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="bg-white border border-slate-200 rounded-[32px] p-8 transition duration-400 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(15,23,42,0.08)]">
                <div class="w-20 h-20 rounded-[28px] bg-cyan-100 flex items-center justify-center text-4xl mb-8">🦷</div>
                <h3 class="text-3xl font-black text-slate-900 mb-5">حشو الأسنان</h3>
                <p class="text-slate-600 leading-loose mb-8">علاج التسوس باستخدام أحدث تقنيات الحشو.</p>
                <div class="flex items-center justify-between">
                    <span class="font-black text-sky-500 text-xl">50$</span>
                    <span class="text-slate-500">45 دقيقة</span>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="bg-white border border-slate-200 rounded-[32px] p-8 transition duration-400 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(15,23,42,0.08)]">
                <div class="w-20 h-20 rounded-[28px] bg-emerald-100 flex items-center justify-center text-4xl mb-8">😁</div>
                <h3 class="text-3xl font-black text-slate-900 mb-5">تبييض الأسنان</h3>
                <p class="text-slate-600 leading-loose mb-8">ابتسامة أكثر إشراقاً باستخدام تقنيات حديثة وآمنة.</p>
                <div class="flex items-center justify-between">
                    <span class="font-black text-sky-500 text-xl">80$</span>
                    <span class="text-slate-500">ساعة</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section class="py-28" id="about">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <img src="https://images.unsplash.com/photo-1606811841689-23dfddce3e95?q=80&w=1400&auto=format&fit=crop" class="rounded-[40px] shadow-2xl" alt="Dental Clinic">
            </div>
            <div>
                <span class="text-sky-500 font-black text-lg">عن العيادة</span>
                <h2 class="text-[42px] font-900 text-slate-900 leading-[1.3] mt-5 mb-8">رعاية طبية متقدمة لابتسامة مثالية</h2>
                <p class="text-slate-600 text-lg leading-loose mb-8">
                    نسعى لتقديم أفضل خدمات طب الأسنان من خلال فريق طبي متخصص وأحدث الأجهزة والتقنيات الحديثة لضمان راحة المرضى وتحقيق أفضل النتائج العلاجية.
                </p>

                <div class="space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-sky-100 flex items-center justify-center text-2xl">✓</div>
                        <p class="font-bold text-slate-700 text-lg">فريق طبي متخصص وذو خبرة</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl">✓</div>
                        <p class="font-bold text-slate-700 text-lg">أحدث الأجهزة والتقنيات الطبية</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="py-28 bg-white" id="reviews">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-20">
            <span class="text-sky-500 font-black text-lg">آراء المرضى</span>
            <h2 class="text-[42px] font-900 text-slate-900 leading-[1.3] mt-5">ماذا يقول مرضانا؟</h2>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="bg-white border border-slate-200 rounded-[32px] p-8 transition duration-400 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(15,23,42,0.08)]">
                <div class="flex text-yellow-400 text-2xl mb-6">⭐⭐⭐⭐⭐</div>
                <p class="text-slate-600 leading-loose mb-8">تجربة رائعة جداً، فريق محترف وتعامل ممتاز.</p>
                <h3 class="font-black text-slate-900">أحمد محمد</h3>
            </div>

            <div class="bg-white border border-slate-200 rounded-[32px] p-8 transition duration-400 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(15,23,42,0.08)]">
                <div class="flex text-yellow-400 text-2xl mb-6">⭐⭐⭐⭐⭐</div>
                <p class="text-slate-600 leading-loose mb-8">أفضل عيادة أسنان من حيث النظافة والاهتمام بالمريض.</p>
                <h3 class="font-black text-slate-900">سارة خالد</h3>
            </div>

            <div class="bg-white border border-slate-200 rounded-[32px] p-8 transition duration-400 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(15,23,42,0.08)]">
                <div class="flex text-yellow-400 text-2xl mb-6">⭐⭐⭐⭐⭐</div>
                <p class="text-slate-600 leading-loose mb-8">الخدمة ممتازة والنتائج رائعة، أنصح الجميع بها.</p>
                <h3 class="font-black text-slate-900">محمد يوسف</h3>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-28 bg-slate-50" id="faq">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-sky-500 font-black text-lg">الأسئلة الشائعة</span>
            <h2 class="text-[42px] font-900 text-slate-900 leading-[1.3] mt-5">لديك استفسار؟ تجد إجابته هنا</h2>
        </div>

        <div class="space-y-6">
            <div class="p-6 bg-white border border-slate-200 rounded-[24px] hover:border-sky-300 transition duration-300 shadow-sm">
                <h3 class="text-xl font-black text-slate-900 mb-3 flex items-center gap-3">
                    <span class="text-sky-500 text-2xl">🤔</span>كيف يمكنني تعديل أو إلغاء موعد الحجز الخاص بي؟
                </h3>
                <p class="text-slate-600 leading-loose pr-9">
                    يمكنك تعديل الموعد أو إلغاؤه بسهولة من خلال التواصل معنا مباشرة عبر رقم الهاتف أو من خلال رسائل الواتساب قبل موعدك بـ 24 ساعة على الأقل.
                </p>
            </div>

            <div class="p-6 bg-white border border-slate-200 rounded-[24px] hover:border-sky-300 transition duration-300 shadow-sm">
                <h3 class="text-xl font-black text-slate-900 mb-3 flex items-center gap-3">
                    <span class="text-sky-500 text-2xl">⏳</span>كم من الوقت تستغرق جلسة تبييض الأسنان?
                </h3>
                <p class="text-slate-600 leading-loose pr-9">
                    استغرق الجلسة الاحترافية لتبييض الأسنان في العيادة ما بين 45 إلى 60 دقيقة تقريباً، وتظهر النتائج المذهلة بشكل فوري بعد انتهاء الجلسة.
                </p>
            </div>

            <div class="p-6 bg-white border border-slate-200 rounded-[24px] hover:border-sky-300 transition duration-300 shadow-sm">
                <h3 class="text-xl font-black text-slate-900 mb-3 flex items-center gap-3">
                    <span class="text-sky-500 text-2xl">💳</span>هل تقبل العيادة بطاقات التأمين الصحي؟
                </h3>
                <p class="text-slate-600 leading-loose pr-9">
                    نعم، نحن نتعاقد مع مجموعة واسعة من شركات التأمين الصحي المحلية والدولية. يمكنك مراجعتنا لتأكيد تغطية شركتك قبل البدء بالعلاج.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-slate-900 py-14" id="contact">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-10">
            <div>
                <h2 class="text-3xl font-black text-white mb-6">DentalCare</h2>
                <p class="text-slate-400 leading-loose">عيادة متخصصة في تقديم خدمات طب الأسنان الحديثة بأعلى جودة.</p>
            </div>

            <div>
                <h3 class="text-xl font-black text-white mb-6">معلومات التواصل</h3>
                <div class="space-y-4 text-slate-400">
                    <p>📍 نابلس - فلسطين</p>
                    <p>📞 +970 599 000 000</p>
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
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl">f</div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl">in</div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl">▶</div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 mt-14 pt-8 text-center text-slate-500">
            © 2026 جميع الحقوق محفوظة لـ DentalCare Clinic
        </div>
    </div>
</footer>

<!-- FLOATING BUTTON -->
<a href="#booking" class="fixed bottom-5 left-5 z-[100] block">
    <button class="w-16 h-16 rounded-full bg-gradient-to-l from-sky-500 to-sky-600 text-white text-2xl shadow-2xl shadow-sky-300 hover:scale-110 transition transform duration-300">
        🦷
    </button>
</a>

</body>
</html>