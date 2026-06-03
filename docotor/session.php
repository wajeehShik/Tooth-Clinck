<?php include ('include/header.php')?>

<!-- المحتوى الرئيسي للوحة تحكم الحجوزات والجلسات -->
<main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    
    <!-- شريط علوي مع أزرار التحكم -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">إدارة الحجوزات وجلسات المرضى 📅</h1>
            <p class="text-slate-400 text-xs font-bold">جدولة مواعيد العيادة، متابعة حالات الدفع، التقييمات، وإضافة جلسات المتابعة الدورية.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- زر إضافة جلسة لمريض سابق -->
            <button onclick="openModal('addSessionModal')" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-xs px-4 py-3.5 rounded-xl transition-all flex items-center gap-2">
                <span>🦷</span> إضافة جلسة لمريض سابق
            </button>
            <!-- زر إضافة حجز جديد -->
            <button onclick="openModal('addBookingModal')" class="bg-sky-500 hover:bg-sky-600 text-white font-black text-xs px-5 py-3.5 rounded-xl shadow-md shadow-sky-100 transition-all flex items-center gap-2">
                <span>📅</span> إضافة حجز جديد
            </button>
        </div>
    </div>

    <!-- جدول الحجوزات الشامل -->
    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                        <th class="p-5">اسم المريض والخدمة</th>
                        <th class="p-5">الموعد والتوقيت</th>
                        <th class="p-5">الحالة الطبية</th>
                        <th class="p-5">حالة الدفع</th>
                        <th class="p-5">التقييم</th>
                        <th class="p-5">ملاحظات المريض</th>
                        <th class="p-5 text-left">إجراءات سريعة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                    
                    <!-- مثال 1: وجيه أحمد (قيد الانتظار - مع خيار إنهاء الموعد السريع) -->
                    <tr id="booking-row-1" class="hover:bg-slate-50/40 transition-colors">
                        <td class="p-5">
                            <div class="flex items-center gap-2">
                                <span id="status-indicator-1" class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">وجيه أحمد</h4>
                                    <span class="text-[10px] text-slate-400 block font-bold">علاج عصب وسن</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="text-slate-800 font-bold block">الأربعاء القادم</span>
                            <span class="text-[11px] text-slate-400 font-semibold block">09:00 AM - 11:00 AM</span>
                        </td>
                        <td class="p-5">
                            <span id="status-badge-1" class="bg-amber-50 text-amber-700 border border-amber-100 px-2.5 py-1 rounded-md font-bold text-xs transition-all">قيد الانتظار</span>
                        </td>
                        <td class="p-5">
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-2.5 py-1 rounded-md font-bold text-xs">لم يدفع بعد</span>
                        </td>
                        <td class="p-5">
                            <span class="text-slate-300 font-bold text-xs">—</span>
                        </td>
                        <td class="p-5 text-slate-400 text-xs max-w-[180px] truncate" title="يعاني من ألم حاد عند شرب الماء البارد">
                            يعاني من ألم حاد عند شرب الماء البارد...
                        </td>
                        <td class="p-5 text-left flex items-center justify-end gap-1.5">
                            <!-- زر إنهاء الجلسة الذكي والمطلوب -->
                            <button onclick="completeSession(1)" id="complete-btn-1" title="تحديث إلى: تم انتهاء الجلسة" class="bg-emerald-50 hover:bg-emerald-500 hover:text-white text-emerald-600 font-bold p-2 rounded-lg border border-emerald-100 text-xs transition-all flex items-center gap-1">
                                <span>✓</span> إنهاء الجلسة
                            </button>
                            <button class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold p-2 rounded-lg border border-slate-200 text-xs transition-all">⚙️</button>
                        </td>
                    </tr>

                    <!-- مثال 2: مريض منتهي ودافع ومقيم جاهز -->
                    <tr id="booking-row-2" class="hover:bg-slate-50/40 transition-colors bg-emerald-50/10">
                        <td class="p-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">محمد علي</h4>
                                    <span class="text-[10px] text-slate-400 block font-bold">تنظيف وتبييض ليزر</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="text-slate-800 font-bold block">أمس، الأحد</span>
                            <span class="text-[11px] text-slate-400 font-semibold block">01:00 PM - 02:00 PM</span>
                        </td>
                        <td class="p-5">
                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 px-2.5 py-1 rounded-md font-bold text-xs">تم انتهاء الجلسة ✓</span>
                        </td>
                        <td class="p-5">
                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-md font-bold text-xs">تم الدفع الكلي</span>
                        </td>
                        <td class="p-5">
                            <span class="text-amber-500 font-bold text-xs">⭐⭐⭐⭐⭐</span>
                        </td>
                        <td class="p-5 text-slate-400 text-xs max-w-[180px] truncate">
                            طلب موعد مسائي يتناسب مع عمله.
                        </td>
                        <td class="p-5 text-left flex items-center justify-end gap-1.5">
                            <span class="text-emerald-600 font-bold text-xs px-2 py-1 bg-emerald-100/50 rounded-lg">مكتمل</span>
                            <button class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold p-2 rounded-lg border border-slate-200 text-xs transition-all">⚙️</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</main>


<!-- ================= النوافذ المنبثقة (Modals) ================= -->

<!-- 1. نافذة إضافة حجز جديد -->
<div id="addBookingModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-xl w-full p-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">📅 جدولة حجز موعد جديد</h3>
            <button onclick="closeModal('addBookingModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form action="process-booking.php?action=create" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اسم المريض ثلاثي</label>
                    <input type="text" name="patient_name" required placeholder="مثال: وجيه أحمد" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">الخدمة المطلوبة</label>
                    <select name="service_id" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="1">علاج وحشوة عصب</option>
                        <option value="2">تبييض الأسنان بالليزر</option>
                        <option value="3">تنظيف وإزالة الجير</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اليوم</label>
                    <select name="booking_day" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="السبت">السبت</option>
                        <option value="الأحد">الأحد</option>
                        <option value="الإثنين">الإثنين</option>
                        <option value="الثلاثاء">الثلاثاء</option>
                        <option value="الأربعاء" selected>الأربعاء القادم</option>
                        <option value="الخميس">الخميس</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">من الساعة</label>
                    <input type="time" name="time_from" value="09:00" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">إلى الساعة</label>
                    <input type="time" name="time_to" value="11:00" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">الحالة الطبية والموعد</label>
                    <select name="status" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="pending">قيد الانتظار / مؤكد</option>
                        <option value="done">تم انتهاء الجلسة واكتمل العلاج</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">حالة الدفع</label>
                    <select name="payment_status" id="paymentStatusSelect" onchange="toggleRatingSection()" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                        <option value="unpaid">لم يدفع بعد</option>
                        <option value="paid">دفع (خ خالص)</option>
                    </select>
                </div>
            </div>

            <!-- قسم التقييم: يظهر فقط إذا دفع -->
            <div id="ratingSection" class="hidden">
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">تقييم المريض للخدمة والعيادة</label>
                <select name="rating" class="w-full h-11 bg-amber-50/50 border border-amber-200 rounded-xl px-4 text-xs font-bold text-amber-800 focus:outline-none focus:border-amber-500 transition-all">
                    <option value="">بدون تقييم حالياً</option>
                    <option value="5">⭐⭐⭐⭐⭐ ممتاز جداً</option>
                    <option value="4">⭐⭐⭐⭐ جيد جداً</option>
                    <option value="3">⭐⭐⭐ متوسط</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">ملاحظات وشكوى المريض</label>
                <textarea name="patient_notes" rows="3" placeholder="اكتب أي ملاحظات طبية أو أعراض يشكو منها المريض هنا..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all resize-none"></textarea>
            </div>

            <button type="submit" class="w-full h-12 mt-2 rounded-xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-black text-sm shadow-md hover:scale-[1.01] transition-all">
                حفظ وتأكيد الحجز الحالي
            </button>
        </form>
    </div>
</div>


<!-- 2. نافذة إضافة جلسة جديدة لمرضى مسجلين سابقاً -->
<div id="addSessionModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-md w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">🦷 إضافة جلسة علاجية لمريض سابق</h3>
            <button onclick="closeModal('addSessionModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form action="process-booking.php?action=add_session" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اختر المريض المسجل</label>
                <select name="existing_patient_id" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                    <option value="101">وجيه أحمد (حالة: علاج عصب)</option>
                    <option value="102">محمد علي (حالة: تقويم أسنان)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">رقم الجلسة الحالية</label>
                    <input type="number" name="session_number" placeholder="مثال: الجلسة الثانية (2)" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">التاريخ المخصص</label>
                    <input type="date" name="session_date" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1 font-bold text-emerald-600">الإجراء الذي تم في هذه الجلسة</label>
                <textarea name="session_procedure" rows="3" placeholder="مثال: تم سحب العصب المتبقي وتطهير القنوات بشكل كامل وتحضير السن للحشوة الدائمة المرة القادمة." required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all resize-none"></textarea>
            </div>
            <button type="submit" class="w-full h-12 mt-2 rounded-xl bg-gradient-to-l from-emerald-500 to-emerald-600 text-white font-black text-sm shadow-md hover:scale-[1.01] transition-all">
                تأكيد وحفظ الجلسة في ملف المريض
            </button>
        </form>
    </div>
</div>


<!-- كود الـ JavaScript المطور للتحكم في النوافذ والإنهاء السريع -->
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function toggleRatingSection() {
    const paymentStatus = document.getElementById('paymentStatusSelect').value;
    const ratingSection = document.getElementById('ratingSection');
    if(paymentStatus === 'paid') {
        ratingSection.classList.remove('hidden');
    } else {
        ratingSection.classList.add('hidden');
    }
}

// دالة الإنهاء السريع الذكية للجلسات والمواعيد بنقرة واحدة
function completeSession(id) {
    // 1. تحويل المظهر فوراً للأخضر للدلالة على اكتمال الموعد بنجاح
    document.getElementById('status-indicator-' + id).className = "w-2 h-2 rounded-full bg-emerald-500";
    
    const badge = document.getElementById('status-badge-' + id);
    badge.className = "bg-emerald-50 text-emerald-700 border border-emerald-100 px-2.5 py-1 rounded-md font-bold text-xs transition-all";
    badge.innerText = "تم انتهاء الجلسة ✓";
    
    // 2. إضفاء طابع الخلفية المكتملة على السطر الكامل وتغيير شكل الزر
    document.getElementById('booking-row-' + id).classList.add('bg-emerald-50/10');
    
    const btn = document.getElementById('complete-btn-' + id);
    btn.outerHTML = `<span class="text-emerald-600 font-bold text-xs px-2 py-1 bg-emerald-100/50 rounded-lg">مكتمل</span>`;

    // 3. (اختياري للخلفية) يمكنك هنا إرسال طلب أجاكس (AJAX) سريع لتحديث قاعدة البيانات بدون تنشيط الصفحة
    /*
    fetch('process-booking.php?action=quick_complete&id=' + id)
    .then(response => response.json())
    .then(data => console.log('تم التحديث في قاعدة البيانات'));
    */
    
    alert('🎉 رائـع! تم تحويل حالة الجلسة إلى (تم انتهاء الجلسة واكتمل العلاج) بنجاح.');
}
</script>

<?php include ('include/footer.php')?>