<?php 
include("../include/header.php");
$user_id = $_SESSION['id'];
$today = date('Y-m-d');
$stmt = $pdo->prepare("
    SELECT cs.*, s.name as service_name 
    FROM clinic_slots cs
    JOIN services s ON cs.service_id = s.id
    WHERE cs.user_id = ? 
    AND cs.booking_date >= CURDATE()
    AND  (cs.status='1' or cs.status='2')
 
    ORDER BY cs.booking_date ASC, cs.time_range ASC
");
$stmt->execute([$user_id]);
$upcoming = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT cs.*, s.name as service_name 
    FROM clinic_slots cs
    JOIN services s ON cs.service_id = s.id
    WHERE cs.user_id = ? 
    AND (
        cs.booking_date < CURDATE() 
        OR cs.is_booked = 1 
    AND  (cs.status='3' or cs.status='4')
 
    )
    ORDER BY cs.booking_date DESC
");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>

<main class="pt-32 pb-24 px-6 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-2xl">📅</div>
                <div>
                    <p class="text-xs text-slate-400 font-bold">المواعيد القادمة</p>
                    <p class="text-xl font-black text-slate-900"><?= count($upcoming) ?></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl">✓</div>
                <div>
                    <p class="text-xs text-slate-400 font-bold">المواعيد السابقة</p>
                    <p class="text-xl font-black text-slate-900"><?= count($history) ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900">⏳ المواعيد القادمة</h2>
                <a href="booking.php" class="text-sm font-bold text-sky-600">+ حجز جديد</a>
            </div>
            <div class="p-8 space-y-4">
                <?php if (empty($upcoming)): ?>
                    <p class="text-center text-slate-400 font-bold py-4">لا توجد مواعيد قادمة حالياً.</p>
                <?php else: foreach ($upcoming as $app): ?>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-2xl border border-slate-100">🦷</div>
                            <div>
                                <p class="font-black text-slate-900"><?= htmlspecialchars($app['service_name']) ?></p>
                                <p class="text-xs text-slate-500 font-bold"><?= $app['booking_date'] ?> | <?= $app['time_range'] ?></p>
                            </div>
                        </div>
                  <?php if($app['status'] == '1'): ?>
                    <div class="flex gap-[20px]">
                      <a href="/tooth/user/payment.php?id=<?php echo $app['id']; ?>" 
                         class="text-white bg-sky-500 hover:bg-sky-600 font-bold text-sm px-4 py-2 rounded-xl transition">💳 دفع</a>
                      <a href="cancel.php?id=<?php echo $app['id']; ?>" 
                         class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">إلغاء</a>
                     </div>
                         <?php elseif($app['status'] == '2'): ?>
                      <span class="text-slate-400 text-sm font-bold bg-slate-100 px-4 py-2 rounded-xl"> بانتظار موعد الجلسة</span>
                 <?php elseif($app['status'] == '3'): ?>
                      <a href="ratting.php?id=<?php echo $app['id']; ?>" 
                         class="text-emerald-600 hover:text-emerald-700 font-bold text-sm bg-emerald-50 hover:bg-emerald-100 px-4 py-2 rounded-xl transition">⭐ تقييم الجلسة</a>
                   <?php else:   $stmt=$pdo->prepare('select rating from  ratings where slot_id=?  ');
    $stmt->execute([$app['id']]);
    $rating=$stmt->fetch();    endif; ?>    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-100">
                <h2 class="text-xl font-black text-slate-900">📁 سجل الزيارات السابقة</h2>
            </div>
            <div class="overflow-x-auto p-8">
                <table class="w-full text-right">
                    <thead>
                        <tr class="text-slate-400 font-bold text-sm">
                            <th class="pb-4">الخدمة</th>
                            <th class="pb-4">التاريخ</th>
                            <th class="pb-4">الحالة</th>
                            <th class="pb-4 text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($history as $app): ?>
                        <tr>
                            <td class="py-5 font-black text-slate-900"><?= htmlspecialchars($app['service_name']) ?></td>
                            <td class="py-5 text-slate-500"><?= $app['booking_date'] ?></td>
                            <td class="py-5"><span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold">مكتمل</span></td>
                            <td class="py-5 text-center">

                                <?php if($app['status']=='3'){?>
                                <a href="ratting.php?id=<?php echo $app['id']?>" class="text-sky-600 font-bold text-sm">⭐ تقييم</a>
                            <?php }if($app['status']=='4'){
    $stmt=$pdo->prepare('select rating from  ratings where slot_id=? ');
    $stmt->execute([$app['id']]);
    $rating=$stmt->fetch();   ?>
    <div class="flex text-amber-400 text-2xl">
        <?php 
            $stars = (int)$rating['rating'];   
            for ($i = 1; $i <= 5; $i++) {
                echo ($i <= $stars) ? '★' : '☆';
            }
}?>
                            
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>