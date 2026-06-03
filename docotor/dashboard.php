<?php include ('include/header.php');

$user=$pdo->prepare("select count(*) as count_user from users ");
$user->execute();
$users=$user->fetch();


?>
        <!-- المحتوى الرئيسي -->
        <main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6">
            
            <!-- أولاً: بطاقات إحصائية سريعة في الأعلى لتعزيز لوحة التحكم -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400">إجمالي الحالات المسجلة</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1"><?php echo $users['count_user']?></h3>
                    </div>
                    <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-xl text-sky-500">👥</div>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400">الجلسات المنجزة هذا الشهر</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1">320</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-xl text-emerald-500">🦷</div>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400">جلسات قيد المتابعة اليوم</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1">14 مريضاً</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-xl text-amber-500">⏳</div>
                </div>
            </div>

            <!-- ثانياً: شريط البحث والتصفية المتطور -->
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:w-80">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
                    <input type="text" placeholder="ابحث باسم المريض أو رقم الجوال..." class="w-full bg-slate-50 border border-slate-100 rounded-xl pr-11 pl-4 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <select class="bg-slate-50 border border-slate-100 text-slate-500 text-xs font-bold px-3 py-2.5 rounded-xl focus:outline-none">
                        <option>ترتيب حسب: الأحدث جزئياً</option>
                        <option>الأكثر جلسات</option>
                        <option>الأقل جلسات</option>
                    </select>
                </div>
            </div>

            <!-- ثالثاً: جدول بيانات وجلسات المرضى الفخم (Responsive Table) -->
            <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                                <th class="p-5">المريض والملف</th>
                                <th class="p-5">البيانات الشخصية</th>
                                <th class="p-5">حالة عدد الجلسات</th>
                                <th class="p-5">آخر زيارة</th>
                                <th class="p-5 text-left">التحكم والعمليات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                            
                            <!-- المريض الأول -->
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 font-bold flex items-center justify-center text-sm shrink-0">
                                            مح
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">محمد عبد الله العتيبي</h4>
                                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold mt-1 inline-block">رقم الملف: #PA-8921</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="space-y-1">
                                        <p class="text-slate-900 font-semibold dir-ltr text-right">📱 +966 50 111 2222</p>
                                        <p class="text-xs text-slate-400">العمر: 28 سنة • ذكر</p>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="space-y-1.5 w-40">
                                        <div class="flex justify-between items-center text-[11px] font-bold">
                                            <span class="text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md">6 جلسات منجزة</span>
                                            <span class="text-slate-400">الهدف: 8</span>
                                        </div>
                                        <!-- شريط التقدم للجلسات المنجزة -->
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-sky-500 rounded-full" style="width: 75%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 text-xs text-slate-500 font-semibold">اليوم، 10:30 ص</td>
                                <td class="p-5 text-left">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button title="إضافة جلسة فورية" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors">⚡</button>
                                        <button class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold px-3 py-1.5 rounded-xl border border-slate-100 text-xs transition-all">الملف الطبي</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- المريض الثاني -->
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 font-bold flex items-center justify-center text-sm shrink-0">
                                            سا
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">سارة أحمد الماجد</h4>
                                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold mt-1 inline-block">رقم الملف: #PA-4412</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="space-y-1">
                                        <p class="text-slate-900 font-semibold dir-ltr text-right">📱 +966 55 333 4444</p>
                                        <p class="text-xs text-slate-400">العمر: 34 سنة • أنثى</p>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="space-y-1.5 w-40">
                                        <div class="flex justify-between items-center text-[11px] font-bold">
                                            <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">12 جلسة (مكتمل)</span>
                                            <span class="text-slate-400">الهدف: 12</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: 100%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 text-xs text-slate-500 font-semibold">أمس، 04:15 م</td>
                                <td class="p-5 text-left">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button title="إضافة جلسة فورية" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors">⚡</button>
                                        <button class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold px-3 py-1.5 rounded-xl border border-slate-100 text-xs transition-all">الملف الطبي</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- المريض الثالث -->
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 font-bold flex items-center justify-center text-sm shrink-0">
                                            خا
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">خالد منصور السديري</h4>
                                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold mt-1 inline-block">رقم الملف: #PA-3109</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="space-y-1">
                                        <p class="text-slate-900 font-semibold dir-ltr text-right">📱 +966 53 777 8888</p>
                                        <p class="text-xs text-slate-400">العمر: 45 سنة • ذكر</p>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="space-y-1.5 w-40">
                                        <div class="flex justify-between items-center text-[11px] font-bold">
                                            <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">2 جلسة منجزة</span>
                                            <span class="text-slate-400">الهدف: 10</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-amber-500 rounded-full" style="width: 20%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 text-xs text-slate-500 font-semibold">25 مايو 2026</td>
                                <td class="p-5 text-left">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button title="إضافة جلسة فورية" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors">⚡</button>
                                        <button class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold px-3 py-1.5 rounded-xl border border-slate-100 text-xs transition-all">الملف الطبي</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- الترقيم لصفحات الجدول (Pagination) بأسفل الكارد -->
                <div class="bg-slate-50/50 px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                    <span>عرض 1-3 من أصل 1,248 مريض</span>
                    <div class="flex items-center gap-1.5">
                        <button class="p-2 bg-white border border-slate-100 rounded-lg text-slate-400 hover:text-slate-700">السابق</button>
                        <button class="w-8 h-8 bg-sky-500 text-white rounded-lg flex items-center justify-center">1</button>
                        <button class="w-8 h-8 bg-white border border-slate-100 rounded-lg flex items-center justify-center hover:bg-slate-50">2</button>
                        <button class="p-2 bg-white border border-slate-100 rounded-lg text-slate-400 hover:text-slate-700">التالي</button>
                    </div>
                </div>
            </div>

        </main>
<?php include('include/footer.php')?>