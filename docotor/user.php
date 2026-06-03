<?php include ('include/header.php')?>

<!-- المحتوى الرئيسي للملف الطبي الشامل للمريض -->
<main class="p-4 sm:p-8 flex-1 max-w-7xl w-full mx-auto space-y-6">

    <!-- شريط علوي تعريفي بالصفحة -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
        <div>
            <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full text-xs font-bold inline-block mb-2">📁 الملف الطبي الموحد</span>
            <h1 class="text-2xl font-black text-slate-900">ملف المريض: محمد عبد الله العتيبي</h1>
            <p class="text-xs text-slate-500 mt-1">إدارة شاملة لبيانات المريض، الحجوزات، فواتير الدفع، والتقييمات المسجلة.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-400">حالة الملف:</span>
            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-bold border border-emerald-100">● نشط ومكتمل</span>
        </div>
    </div>

    <!-- تقسيم الصفحة إلى عمودين -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
        
        <!-- العمود الأيمن: البيانات الشخصية والملخص المالي والتنفيذي -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- بطاقة الهوية الشخصية -->
            <div class="bg-white border border-slate-100 rounded-[24px] p-6 shadow-sm text-center relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-l from-sky-500 to-sky-600"></div>
                <div class="w-20 h-20 bg-sky-100 text-sky-600 font-black text-2xl flex items-center justify-center rounded-3xl mx-auto mb-4 shadow-md shadow-sky-50">
                    مح
                </div>
                <h3 class="font-black text-slate-900 text-base">محمد عبد الله العتيبي</h3>
                <span class="text-[10px] bg-slate-100 text-slate-500 px-2.5 py-1 rounded-md font-bold mt-1 inline-block">الملف: #PA-8921</span>
                
                <hr class="my-4 border-slate-50">
                
                <!-- تفاصيل البيانات الشخصية -->
                <div class="space-y-3 text-right text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">رقم الجوال:</span>
                        <span class="font-bold text-slate-800 dir-ltr">+966 50 111 2222</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">العمر / الجنس:</span>
                        <span class="font-bold text-slate-800">28 سنة • ذكر</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">تاريخ التسجيل:</span>
                        <span class="font-bold text-slate-800">10 يناير 2026</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">العنوان:</span>
                        <span class="font-bold text-slate-800">نابلس، رفيديا</span>
                    </div>
                </div>
            </div>

            <!-- بطاقة إحصائيات سريعة خاصة بالمريض -->
            <div class="bg-slate-900 text-white rounded-[24px] p-5 shadow-sm space-y-4">
                <h4 class="text-xs font-black text-sky-400 border-b border-white/10 pb-2">📊 الملخص المالي والتنفيذي</h4>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                        <span class="text-[10px] text-slate-400 block mb-1">إجمالي المدفوع</span>
                        <span class="text-lg font-black text-emerald-400">190$</span>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                        <span class="text-[10px] text-slate-400 block mb-1">جلسات منجزة</span>
                        <span class="text-lg font-black text-sky-400">6</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- العمود الأيسر: تبويبات البيانات الشاملة (الحجوزات، الدفعات، التقييمات) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- أزرار التبديل بين الأقسام (Tabs) -->
            <div class="bg-white border border-slate-100 p-2 rounded-2xl shadow-sm flex items-center gap-2 overflow-x-auto">
                <button onclick="switchTab('bookings-tab', this)" class="tab-btn px-4 py-2.5 bg-sky-500 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-2 shrink-0">
                    🗓️ سجل الحجوزات المجدولة
                </button>
                <button onclick="switchTab('payments-tab', this)" class="tab-btn px-4 py-2.5 bg-slate-50 text-slate-600 hover:bg-slate-100 text-xs font-bold rounded-xl transition-all flex items-center gap-2 shrink-0">
                    💳 الفواتير وإيصالات الدفع
                </button>
                <button onclick="switchTab('ratings-tab', this)" class="tab-btn px-4 py-2.5 bg-slate-50 text-slate-600 hover:bg-slate-100 text-xs font-bold rounded-xl transition-all flex items-center gap-2 shrink-0">
                    ⭐ تقييمات المريض للجلسات
                </button>
            </div>

            <!-- محتوى التبويبات -->
            <div id="tab-contents">
                
                <!-- التبويب الأول: الحجوزات -->
                <div id="bookings-tab" class="tab-content block">
                    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="font-black text-slate-900 text-sm">مواعيد وحجوزات المريض</h3>
                            <span class="text-xs font-bold text-sky-600">إجمالي الحجوزات: 3</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-right border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 text-xs font-bold">
                                        <th class="p-4">الخدمة المطلوبة</th>
                                        <th class="p-4">التاريخ والوقت</th>
                                        <th class="p-4">التكلفة</th>
                                        <th class="p-4">حالة الحجز</th>
                                        <th class="p-4 text-left">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                                    <tr>
                                        <td class="p-4 font-bold text-slate-900">حشو الأسنان وجذور</td>
                                        <td class="p-4 text-xs">04 يونيو 2026 • 04:30 م</td>
                                        <td class="p-4 font-bold">50$</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded-lg bg-amber-50 text-amber-700 text-[11px] font-bold border border-amber-100">قيد انتظار التأكيد</span>
                                        </td>
                                        <td class="p-4 text-left">
                                            <button onclick="openModal('booking-modal-1')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg text-xs transition-colors">تفاصيل الحجز 🔍</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-4 font-bold text-slate-900">تنظيف وتلميع الأسنان</td>
                                        <td class="p-4 text-xs">12 يناير 2026 • 10:30 ص</td>
                                        <td class="p-4 font-bold">30$</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100">✓ حجز مكتمل</span>
                                        </td>
                                        <td class="p-4 text-left">
                                            <button onclick="openModal('booking-modal-2')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg text-xs transition-colors">تفاصيل الحجز 🔍</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- التبويب الثاني: الفواتير والدفعات -->
                <div id="payments-tab" class="tab-content hidden">
                    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50">
                            <h3 class="font-black text-slate-900 text-sm">سجل الفواتير الحسابية وإيصالات الرفع</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-right border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 text-xs font-bold">
                                        <th class="p-4">رقم الفاتورة</th>
                                        <th class="p-4">الخدمة الطبية</th>
                                        <th class="p-4">المبلغ المدفوع</th>
                                        <th class="p-4">طريقة التحويل</th>
                                        <th class="p-4 text-left">إشعار الدفع</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                                    <tr>
                                        <td class="p-4 font-mono font-bold text-slate-400">#INV-9921</td>
                                        <td class="p-4 font-bold text-slate-900">جلسة تبييض ليزر احترافي</td>
                                        <td class="p-4 font-black text-emerald-600">80$</td>
                                        <td class="p-4 text-xs text-slate-500">محفظة Jawwal Pay</td>
                                        <td class="p-4 text-left">
                                            <button onclick="openModal('receipt-modal-1')" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold px-3 py-1.5 rounded-lg text-xs transition-colors flex items-center gap-1 inline-flex justify-end">
                                                👁️ عرض صورة الوصل
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- التبويب الثالث: التقييمات والملاحظات -->
                <div id="ratings-tab" class="tab-content hidden">
                    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50">
                            <h3 class="font-black text-slate-900 text-sm">آراء وتقييمات المريض للجلسات</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <!-- بطاقة التقييم الأول -->
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-2">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm">خدمة: تنظيف وتلميع الأسنان</h4>
                                    <div class="text-yellow-400 text-xs tracking-tighter">⭐⭐⭐⭐⭐</div>
                                </div>
                                <p class="text-xs text-slate-600 bg-white border border-slate-100/60 p-3 rounded-xl leading-relaxed">
                                    "الطبيب ممتاز والعيادة نظيفة جداً ولم أشعر بأي ألم، سأكرر الزيارة بالتأكيد."
                                </p>
                                <span class="text-[10px] text-slate-400 font-semibold block text-left">تم التقييم بتاريخ: 12 يناير 2026</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</main>

<!-- ======================================================== -->
<!-- SECTION: ALL POPUPS & MODALS (قسم النوافذ المنبثقة الذكية) -->
<!-- ======================================================== -->

<!-- 1. بوب اب: تفاصيل حجز الموعد الأول -->
<div id="booking-modal-1" class="custom-modal fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-md rounded-[24px] p-6 shadow-xl border border-slate-100 text-right animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-2">
            <h3 class="text-base font-black text-slate-900">تفاصيل موعد الحجز</h3>
            <button onclick="closeModal('booking-modal-1')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
        </div>
        <div class="space-y-3 text-xs leading-loose text-slate-600">
            <p>🦷 <strong>نوع الخدمة الطبية:</strong> حشو الأسنان وجذور</p>
            <p>👨‍⚕️ <strong>الطبيب المعالج:</strong> د. سليم أحمد</p>
            <p>📅 <strong>التاريخ المحدد:</strong> 04 يونيو 2026</p>
            <p>⏰ <strong>الوقت الدقيق:</strong> الساعة 04:30 مساءً</p>
            <p>💵 <strong>السعر الإجمالي:</strong> <span class="font-bold text-slate-900">50$</span></p>
        </div>
        <button onclick="closeModal('booking-modal-1')" class="w-full mt-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">إغلاق النافذة</button>
    </div>
</div>

<!-- 2. بوب اب: تفاصيل حجز الموعد الثاني -->
<div id="booking-modal-2" class="custom-modal fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-md rounded-[24px] p-6 shadow-xl border border-slate-100 text-right animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-2">
            <h3 class="text-base font-black text-slate-900">تفاصيل موعد الحجز</h3>
            <button onclick="closeModal('booking-modal-2')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
        </div>
        <div class="space-y-3 text-xs leading-loose text-slate-600">
            <p>🪥 <strong>نوع الخدمة الطبية:</strong> تنظيف وتلميع الأسنان</p>
            <p>👨‍⚕️ <strong>الطبيب المعالج:</strong> د. سليم أحمد</p>
            <p>📅 <strong>التاريخ المحدد:</strong> 12 يناير 2026</p>
            <p>⏰ <strong>الوقت الدقيق:</strong> الساعة 10:30 صباحاً</p>
            <p>💵 <strong>السعر الإجمالي:</strong> <span class="font-bold text-slate-900">30$</span></p>
        </div>
        <button onclick="closeModal('booking-modal-2')" class="w-full mt-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">إغلاق النافذة</button>
    </div>
</div>

<!-- 3. بوب اب: عرض صورة إشعار/وصل الدفع المرفوع -->
<div id="receipt-modal-1" class="custom-modal fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-lg rounded-[28px] p-6 shadow-2xl border border-slate-100 text-right animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-3">
            <div>
                <h3 class="text-base font-black text-slate-900">إشعار الدفع الإلكتروني المرفوع</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">الفاتورة رقم: #INV-9921 • القيمة: 80$</p>
            </div>
            <button onclick="closeModal('receipt-modal-1')" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 font-bold text-xs transition-colors flex items-center justify-center">✕</button>
        </div>
        
        <!-- مساحة محاكاة عرض صورة الوصل الحقيقي المرفوع من المريض -->
        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 text-center overflow-hidden flex flex-col items-center justify-center min-h-[260px] relative group">
            <!-- هنا يتم استدعاء مسار الصورة الحقيقي من قاعدة البيانات مستقبلاً داخل وسم img -->
            <div class="text-4xl mb-2">📄</div>
            <p class="text-xs font-bold text-slate-700">شاشة محاكاة إيصال التحويل الناجح</p>
            <p class="text-[11px] text-slate-400 mt-1 font-mono">JawwalPay_Transfer_2026_05_05.png</p>
            <div class="absolute inset-0 bg-slate-900/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none rounded-2xl"></div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5">
            <a href="#" download class="py-2.5 bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs rounded-xl transition-colors text-center shadow-md shadow-sky-100">تحميل إيصال القبض</a>
            <button onclick="closeModal('receipt-modal-1')" class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition-colors">إغلاق وتراجع</button>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- JAVASCRIPT FUNCTIONS FOR TABS & INTERACTIVE MODALS -->
<!-- ======================================================== -->
<script>
    // 1. دالة التبديل الذكي والسلس بين التبويبات (Tabs)
    function switchTab(tabId, button) {
        // إخفاء كل المحتويات أولاً
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.replace('block', 'hidden'));
        
        // إظهار المحتوى المختار
        document.getElementById(tabId).classList.replace('hidden', 'block');
        
        // إعادة تهيئة تصميم كافة الأزرار لتصبح غير نشطة
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('bg-sky-500', 'text-white', 'shadow-sm');
            btn.classList.add('bg-slate-50', 'text-slate-600', 'hover:bg-slate-100');
        });
        
        // تمييز الزر النشط حالياً
        button.classList.remove('bg-slate-50', 'text-slate-600', 'hover:bg-slate-100');
        button.classList.add('bg-sky-500', 'text-white', 'shadow-sm');
    }

    // 2. دالة فتح أي نافذة منبثقة (Popup Modal) بمجرد تمرير الـ ID الخاص بها
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if(modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // منع التمرير في الخلفية أثناء فتح البوب اب
        }
    }

    // 3. دالة إغلاق النافذة المنبثقة
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if(modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // إعادة تفعيل التمرير
        }
    }
</script>

<?php include('include/footer.php')?>