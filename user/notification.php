<?php include("include/header.php")?>

<main class="min-h-screen bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.08),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.05),transparent_35%)] pt-32 pb-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
        
        <!-- 1. WELCOME BANNER & STATS -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-white/70 backdrop-blur-md border border-slate-200/60 p-8 rounded-[32px] shadow-sm">
            <div>
                <h1 class="text-3xl font-black text-slate-900 mb-2">مرحباً بك، أحمد محمد 👋</h1>
                <p class="text-slate-500 text-lg">تابع مواعيدك الطبية، مدفوعاتك، وقم بجدولة استشارات جديدة بكل سهولة.</p>
            </div>
            
            <!-- Quick Stats Counters -->
            <div class="flex gap-4">
                <div class="bg-sky-50 border border-sky-100 rounded-2xl px-6 py-4 text-center min-w-[120px]">
                    <span class="block text-2xl font-black text-sky-600">2</span>
                    <span class="text-slate-500 text-sm font-bold">مواعيد قادمة</span>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl px-6 py-4 text-center min-w-[120px]">
                    <span class="block text-2xl font-black text-emerald-600">350$</span>
                    <span class="text-slate-500 text-sm font-bold">إجمالي المدفوعات</span>
                </div>
            </div>
        </div>

        <!-- ================= قسم الإشعارات الجديد للمستخدِم ================= -->
        <div class="bg-white rounded-[32px] p-6 lg:p-8 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                    <span class="text-sky-500">🔔</span> التنبيهات والإشعارات الأخيرة
                </h2>
                <button onclick="clearAllNotifications()" class="text-xs font-bold text-slate-400 hover:text-sky-600 transition">مسح الكل</button>
            </div>

            <div id="notifications-container" class="space-y-3">
                
                <!-- إشعار 1: تذكير بموعد الغد -->
                <div id="notif-1" class="flex items-start justify-between p-4 bg-amber-50/60 border border-amber-100 rounded-2xl gap-4 transition-all duration-300">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-lg shrink-0">⏰</div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-black text-slate-900 text-sm">تذكير بموعدك غداً!</h4>
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            </div>
                            <p class="text-slate-600 text-xs mt-1 font-medium">مرحباً أحمد، نود تذكيرك بأن لديك موعداً غداً مع <span class="font-bold text-amber-800">د. أحمد السعيد</span> في تمام الساعة <span class="font-bold border-b border-dashed border-amber-400">10:30 صباحاً</span>.</p>
                        </div>
                    </div>
                    <button onclick="dismissNotification('notif-1')" class="text-slate-400 hover:text-slate-600 font-bold text-xs p-1">✕</button>
                </div>

                <!-- إشعار 2: تأكيد الدفع -->
                <div id="notif-2" class="flex items-start justify-between p-4 bg-emerald-50/40 border border-emerald-100 rounded-2xl gap-4 transition-all duration-300">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-lg shrink-0">💳</div>
                        <div>
                            <h4 class="font-black text-slate-900 text-sm">تم تأكيد الدفعة المالية بنجاح</h4>
                            <p class="text-slate-600 text-xs mt-1 font-medium">تم استلام مبلغ <span class="font-bold text-emerald-700">60$</span> بنجاح لفاتورة كشفية عيادة القلب رقم <span class="font-mono text-slate-700">#INV-9021</span>.</p>
                        </div>
                    </div>
                    <button onclick="dismissNotification('notif-2')" class="text-slate-400 hover:text-slate-600 font-bold text-xs p-1">✕</button>
                </div>

            </div>
        </div>
        <!-- ================================================================= -->

        <!-- MAIN GRID CONTENT -->
        <div class="grid lg:grid-cols-3 gap-8 items-start">
            
            <!-- LEFT COLUMN: UPCOMING APPOINTMENTS & PAYMENTS -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 2. UPCOMING APPOINTMENTS SECTION -->
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                            <span class="text-sky-500">📅</span> المواعيد القادمة
                        </h2>
                        <span class="text-sm bg-sky-100 text-sky-700 font-bold px-3 py-1 rounded-full">مؤكدة</span>
                    </div>

                    <div class="space-y-4">
                        <!-- Appointment Card 1 -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 border border-slate-100 rounded-2xl hover:border-sky-200 transition bg-slate-50/50 gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center text-2xl">🩺</div>
                                <div>
                                    <h3 class="font-black text-slate-900 text-lg">د. أحمد السعيد (الطب العام)</h3>
                                    <p class="text-slate-500 text-sm mt-1">الاستشارة الدورية السنوية</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                                <div class="text-left sm:text-right">
                                    <span class="block font-black text-slate-800">15 يونيو 2026</span>
                                    <span class="text-slate-500 text-sm">الساعة 10:30 صباحاً</span>
                                </div>
                                <button class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">
                                    إلغاء
                                </button>
                            </div>
                        </div>

                        <!-- Appointment Card 2 -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 border border-slate-100 rounded-2xl hover:border-sky-200 transition bg-slate-50/50 gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-2xl">❤️</div>
                                <div>
                                    <h3 class="font-black text-slate-900 text-lg">د. يوسف علي (عيادة القلب)</h3>
                                    <p class="text-slate-500 text-sm mt-1">فحص تخطيط القلب المجهد</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                                <div class="text-left sm:text-right">
                                    <span class="block font-black text-slate-800">28 يونيو 2026</span>
                                    <span class="text-slate-500 text-sm">الساعة 04:15 مساءً</span>
                                </div>
                                <button class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">
                                    إلغاء
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. PAYMENTS HISTORY SECTION -->
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3 mb-6">
                        <span class="text-emerald-500">💳</span> الدفعات المالية الأخيرة
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-bold text-sm">
                                    <th class="pb-4 font-black">رقم الفاتورة</th>
                                    <th class="pb-4 font-black">الخدمة / العيادة</th>
                                    <th class="pb-4 font-black">التاريخ</th>
                                    <th class="pb-4 font-black">المبلغ</th>
                                    <th class="pb-4 font-black text-left">حالة الدفع</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-slate-600 font-bold">
                                <tr>
                                    <td class="py-4 text-slate-900">#INV-9021</td>
                                    <td class="py-4">كشفية عيادة القلب</td>
                                    <td class="py-4 text-slate-400 text-sm">25 مايو 2026</td>
                                    <td class="py-4 text-slate-900 font-black">60$</td>
                                    <td class="py-4 text-left"><span class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">مدفوع</span></td>
                                </tr>
                                <tr>
                                    <td class="py-4 text-slate-900">#INV-8840</td>
                                    <td class="py-4">تحاليل مخبرية شاملة</td>
                                    <td class="py-4 text-slate-400 text-sm">12 مايو 2026</td>
                                    <td class="py-4 text-slate-900 font-black">150$</td>
                                    <td class="py-4 text-left"><span class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">مدفوع</span></td>
                                </tr>
                                <tr>
                                    <td class="py-4 text-slate-900">#INV-8512</td>
                                    <td class="py-4">كشفية عيادة الأطفال (تأمين)</td>
                                    <td class="py-4 text-slate-400 text-sm">01 مايو 2026</td>
                                    <td class="py-4 text-slate-900 font-black">35$</td>
                                    <td class="py-4 text-left"><span class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">مدفوع</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: CREATE NEW BOOKING -->
            <div class="bg-white rounded-[32px] p-6 lg:p-8 shadow-2xl border border-slate-100/80 relative overflow-hidden">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-sky-300/20 rounded-full blur-2xl"></div>
                
                <div class="relative z-10">
                    <div class="mb-6 text-center">
                        <span class="text-3xl">✨</span>
                        <h2 class="text-2xl font-black text-slate-900 mt-2 mb-1">إنشاء حجز جديد</h2>
                        <p class="text-slate-400 text-sm">اختر موعداً جديداً بكل سهولة</p>
                    </div>

                    <form action="process-dashboard-booking.php" method="POST" class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-600 pr-2">التخصص</label>
                            <select name="specialty" required class="w-full h-14 rounded-xl border border-slate-200 px-4 outline-none focus:border-sky-400 transition bg-white text-slate-700 font-bold">
                                <option value="">اختر التخصص الطبي</option>
                                <option value="general">الطب العام</option>
                                <option value="pediatrics">طب الأطفال</option>
                                <option value="cardiology">أمراض القلب</option>
                                <option value="internal">الباطنية والغدد</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-600 pr-2">الطبيب المعالج</label>
                            <select name="doctor" required class="w-full h-14 rounded-xl border border-slate-200 px-4 outline-none focus:border-sky-400 transition bg-white text-slate-700 font-bold">
                                <option value="">اختر الطبيب المتاح</option>
                                <option value="doc1">د. أحمد السعيد (استشاري)</option>
                                <option value="doc2">د. سارة عمر (أخصائي أول)</option>
                                <option value="doc3">د. يوسف علي (استشاري)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-sm font-bold text-slate-600 pr-2">التاريخ</label>
                                <input type="date" name="date" required class="w-full h-14 rounded-xl border border-slate-200 px-4 outline-none focus:border-sky-400 transition text-slate-700 font-bold">
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-bold text-slate-600 pr-2">الوقت</label>
                                <input type="time" name="time" required class="w-full h-14 rounded-xl border border-slate-200 px-4 outline-none focus:border-sky-400 transition text-slate-700 font-bold">
                            </div>
                        </div>

                        <button type="submit" class="w-full h-14 mt-4 rounded-xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-black text-lg shadow-lg shadow-sky-100 hover:scale-[1.01] transition transform duration-200">
                            تأكيد الحجز الفوري
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- JAVASCRIPT FOR NOTIFICATIONS INTERACTION -->
<script>
function dismissNotification(id) {
    const notif = document.getElementById(id);
    notif.classList.add('opacity-0', 'scale-95');
    setTimeout(() => {
        notif.remove();
        checkEmptyNotifications();
    }, 300);
}

function clearAllNotifications() {
    const container = document.getElementById('notifications-container');
    container.innerHTML = `
        <div class="text-center py-6 text-slate-400 font-medium text-sm">
            ✨ لا توجد إشعارات جديدة حالياً. أنت على اطلاع دائم بكل شيء!
        </div>
    `;
}

function checkEmptyNotifications() {
    const container = document.getElementById('notifications-container');
    if (container.children.length === 0) {
        clearAllNotifications();
    }
}
</script>

<!-- FOOTER -->
<?php include("include/footer.php")?>
</body>
</html>