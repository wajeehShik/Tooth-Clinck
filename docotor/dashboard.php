<?php include ('include/header.php');

$user=$pdo->prepare("select count(*) as count_user from users ");
$user->execute();
$users=$user->fetch();
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM clinic_slots 
    WHERE status = 1 
    AND booking_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
");
$stmt->execute();
$weekly_booked_count = $stmt->fetchColumn();
$stmt = $pdo->prepare("
    SELECT SUM(price) as total 
    FROM payments 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_monthly_revenue = $row['total'] ?? 0;

$stmt = $pdo->prepare("SELECT cs.*, u.name as patient_name, u.phone, s.name as service_name
FROM clinic_slots cs
INNER JOIN users u ON cs.user_id = u.id
INNER JOIN services s ON cs.service_id = s.id
WHERE STR_TO_DATE(cs.booking_date, '%Y-%m-%d') = CURDATE()

ORDER BY cs.time_range ASC;
");
$stmt->execute();
$today_sessions = $stmt->fetchAll();
?>
        <main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6">
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
                        <span class="text-xs font-bold text-slate-400">الجلسات  هذا الاسبوع</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $weekly_booked_count ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-xl text-emerald-500">🦷</div>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400"> اجمالي مدفوعات هذا الشهر  </span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $total_monthly_revenue ?> </h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-xl text-amber-500">⏳</div>
                </div>
            </div>
      <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-2">
        <span class="text-lg">📅</span>
        <h3 class="text-slate-800 font-black text-sm">حجوزات المرضي اليوم </h3>
    </div>
</div>
            <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                                <th class="p-5">المريض والملف</th>
                                <th class="p-5">البيانات الشخصية</th>
                                <th class="p-5">حالة عدد الجلسات</th>
                                <th class="p-5"> موعد الجلسة</th>
                            </tr>
                        </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
    <?php if (count($today_sessions) > 0): ?>
        <?php foreach ($today_sessions as $session): ?>
        <tr class="hover:bg-slate-50/40 transition-colors">
            <td class="p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 font-bold flex items-center justify-center text-sm">
                        <?= mb_substr($session['patient_name'], 0, 2) ?>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900"><?= htmlspecialchars($session['patient_name']) ?></h4>
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold mt-1 block">رقم الملف: #PA-<?= $session['user_id'] ?></span>
                    </div>
                </div>
            </td>
            <td class="p-5">
                <p class="text-slate-900 font-semibold"><?= $session['phone'] ?></p>
            </td>
            <td class="p-5 font-bold text-emerald-600">
                <?= htmlspecialchars($session['service_name']) ?>
            </td>
            <td class="p-5 text-xs text-slate-500 font-semibold">
                <div class="text-[10px] text-black font-black"><?= $session['time_range'] ?></div></td>
        
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="p-10 text-center text-slate-400 font-bold">لا توجد مواعيد مسجلة لهذا اليوم.</td>
        </tr>
    <?php endif; ?>
</tbody>
                    </table>
                </div>
              
            </div>

        </main>
<?php include('include/footer.php')?>