<?php include ('include/header.php')?>

<!-- المحتوى الرئيسي لصفحة الإشعارات والاشتراكات -->
<main class="p-4 sm:p-8 flex-1 max-w-4xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    
    <!-- شريط علوي مع التحكم في الإشعارات -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">مركز الإشعارات والاشتراكات 🔔</h1>
            <p class="text-slate-400 text-xs font-bold">تابع المشتركين الجدد، الحجوزات الأخيرة، والأنشطة التي تحدث في عيادتك الآن.</p>
        </div>
        <div>
            <!-- زر لتحديد الكل كمقروء -->
            <button onclick="markAllAsRead()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-4 py-2.5 rounded-xl transition-all">
                ✓ تعيين الكل كمقروء
            </button>
        </div>
    </div>

    <!-- قائمة الإشعارات الذكية -->
    <div class="space-y-3">
        
        <!-- إشعار 1: اشتراك مريض جديد تماماً (غير مقروء) -->
        <div id="notification-1" class="bg-white border-r-4 border-r-sky-500 border border-slate-100 p-5 rounded-[20px] shadow-sm flex items-start justify-between gap-4 transition-all relative">
            <div class="flex items-start gap-3.5">
                <!-- أيقونة نوع الإشعار -->
                <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center text-lg shrink-0">
                    ✨
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-black text-slate-900 text-sm">مريض جديد انضم للعيادة</h3>
                        <span class="bg-sky-50 text-sky-700 font-black text-[10px] px-2 py-0.5 rounded">عضو جديد</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse" id="dot-1"></span>
                    </div>
                    <p class="text-slate-600 text-xs mt-1.5 font-medium leading-relaxed">
                        قام المريض <span class="font-bold text-slate-900">أحمد رأفت</span> بإنشاء ملف طبي جديد في موقع العيادة واشترك في نظام المتابعة.
                    </p>
                    <span class="text-[10px] text-slate-400 font-bold mt-2 block">منذ 5 دقائق</span>
                </div>
            </div>
            
            <!-- أزرار التحكم السريع -->
            <div class="flex items-center gap-1.5 shrink-0">
                <button onclick="markAsRead(1)" id="read-btn-1" title="تحديد كمقروء" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-slate-600 text-xs transition-all border border-transparent hover:border-slate-100">
                    ✓
                </button>
                <a href="patient-profile.php?id=1" class="bg-slate-50 hover:bg-sky-500 hover:text-white text-slate-700 font-bold px-3 py-1.5 rounded-lg border border-slate-200 text-xs transition-all">
                    الملف الطبي
                </a>
            </div>
        </div>

        <!-- إشعار 2: وجيه حجز موعد جديد (غير مقروء) -->
        <div id="notification-2" class="bg-white border-r-4 border-r-amber-500 border border-slate-100 p-5 rounded-[20px] shadow-sm flex items-start justify-between gap-4 transition-all relative">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-lg shrink-0">
                    📅
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-black text-slate-900 text-sm">حجز موعد قيد الانتظار</h3>
                        <span class="bg-amber-50 text-amber-700 font-black text-[10px] px-2 py-0.5 rounded">موعد جديد</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse" id="dot-2"></span>
                    </div>
                    <p class="text-slate-600 text-xs mt-1.5 font-medium leading-relaxed">
                        قام المريض <span class="font-bold text-slate-900">وجيه أحمد</span> بحجز موعد لـ <span class="text-amber-700 font-bold">علاج عصب وسن</span> يوم الأربعاء القادم من الساعة 9:00 حتى 11:00 صباحاً.
                    </p>
                    <span class="text-[10px] text-slate-400 font-bold mt-2 block">منذ 20 دقيقة</span>
                </div>
            </div>
            
            <div class="flex items-center gap-1.5 shrink-0">
                <button onclick="markAsRead(2)" id="read-btn-2" title="تحديد كمقروء" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-slate-600 text-xs transition-all border border-transparent hover:border-slate-100">
                    ✓
                </button>
                <a href="bookings.php" class="bg-slate-50 hover:bg-amber-500 hover:text-white text-slate-700 font-bold px-3 py-1.5 rounded-lg border border-slate-200 text-xs transition-all">
                    عرض الحجوزات
                </a>
            </div>
        </div>

        <!-- إشعار 3: مريض دفع واشترك في خطة علاجية (مقروء مسبقاً) -->
        <div class="bg-white border-r-4 border-r-emerald-500 border border-slate-100 p-5 rounded-[20px] shadow-sm flex items-start justify-between gap-4 opacity-75">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-lg shrink-0">
                    💳
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-black text-slate-900 text-sm">تأكيد عملية دفع واشتراك</h3>
                        <span class="bg-emerald-50 text-emerald-700 font-black text-[10px] px-2 py-0.5 rounded">مدفوعات</span>
                    </div>
                    <p class="text-slate-600 text-xs mt-1.5 font-medium leading-relaxed">
                        أكمل المريض <span class="font-bold text-slate-900">محمد علي</span> دفع رسوم جلسة <span class="text-emerald-700 font-bold">تنظيف وتبييض ليزر</span> وتم إغلاق الفاتورة بنجاح.
                    </p>
                    <span class="text-[10px] text-slate-400 font-bold mt-2 block">أمس، الساعة 04:30 مساءً</span>
                </div>
            </div>
            
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="text-emerald-600 bg-emerald-50 font-bold text-xs px-2.5 py-1 rounded-lg">خالص ✓</span>
            </div>
        </div>

    </div>
</main>

<!-- كود الـ JavaScript لإضافة حركات تفاعلية سريعة للإشعارات -->
<script>
// دالة لجعل الإشعار مقروءاً (تغيير الألوان وإخفاء نقطة التنبيه)
function markAsRead(id) {
    const notif = document.getElementById('notification-' + id);
    const dot = document.getElementById('dot-' + id);
    const btn = document.getElementById('read-btn-' + id);
    
    // إضعاف السطر وإزالة نقطة النبض لإشعار المريض
    notif.classList.add('opacity-75');
    if(dot) dot.remove();
    if(btn) btn.remove();
}

// دالة لتحديد كل الإشعارات كمقروءة دفعة واحدة
function markAllAsRead() {
    markAsRead(1);
    markAsRead(2);
}
</script>

<?php include ('include/footer.php')?>