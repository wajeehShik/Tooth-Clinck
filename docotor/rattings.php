<?php 
include ('include/header.php');

// استعلام جلب التقييمات
$stmt = $pdo->prepare("
    SELECT r.*, u.name as patient_name, s.name as service_name 
    FROM ratings r
    INNER JOIN users u ON r.user_id = u.id
    INNER JOIN clinic_slots cs ON r.slot_id = cs.id
    INNER JOIN services s ON cs.service_id = s.id
    ORDER BY r.created_at DESC
");
$stmt->execute();
$ratings = $stmt->fetchAll();
?>
<main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    <div class="bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <h1 class="text-2xl font-black text-slate-900 mb-1">⭐ سجل تقييمات المرضى</h1>
        <p class="text-slate-400 text-xs font-bold">عرض آراء وتقييمات المرضى حول الخدمات المقدمة في العيادة.</p>
    </div>

    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                        <th class="p-5">المريض</th>
                        <th class="p-5">الخدمة</th>
                        <th class="p-5">التقييم</th>
                        <th class="p-5">الملاحظات</th>
                        <th class="p-5 text-left">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                    <?php if (count($ratings) > 0): ?>
                        <?php foreach($ratings as $rating): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="p-5 font-bold text-slate-900"><?= htmlspecialchars($rating['patient_name']) ?></td>
                            <td class="p-5 text-emerald-600"><?= htmlspecialchars($rating['service_name']) ?></td>
                            <td class="p-5">
                                <div class="flex text-amber-400 text-lg">
                                    <?php 
                                    $stars = (int)$rating['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $stars) ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="p-5 text-slate-500 max-w-[200px] truncate"><?= htmlspecialchars($rating['notes']) ?></td>
                            <td class="p-5 text-left text-slate-400 text-xs font-bold"><?= date('Y-m-d', strtotime($rating['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 font-bold">لا توجد تقييمات مسجلة حالياً.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include ('include/footer.php') ?>