<?php 
include ('include/header.php');

// استعلام جلب الدفعات مع ربط البيانات
$stmt = $pdo->prepare("
    SELECT p.*, u.name as patient_name, s.name as service_name 
    FROM payments p
    INNER JOIN users u ON p.user_id = u.id
    INNER JOIN clinic_slots cs ON p.date_id = cs.id
    INNER JOIN services s ON cs.service_id = s.id
    ORDER BY p.created_at DESC
");
$stmt->execute();
$payments = $stmt->fetchAll();
?>

<main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    <div class="bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <h1 class="text-2xl font-black text-slate-900 mb-1">💳 سجل المعاملات المالية</h1>
        <p class="text-slate-400 text-xs font-bold">متابعة كافة عمليات الدفع وتفاصيل الحجوزات المالية.</p>
    </div>

    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                        <th class="p-5">المريض</th>
                        <th class="p-5">الخدمة</th>
                        <th class="p-5">المبلغ</th>
                        <th class="p-5">طريقة الدفع</th>
                        <th class="p-5">الحالة</th>
                        <th class="p-5 text-left">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                    <?php if (count($payments) > 0): ?>
                        <?php foreach($payments as $pay): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="p-5 font-bold text-slate-900"><?= htmlspecialchars($pay['patient_name']) ?></td>
                            <td class="p-5 text-emerald-600"><?= htmlspecialchars($pay['service_name']) ?></td>
                            <td class="p-5 font-black text-slate-800"><?= number_format($pay['price'], 2) ?> ر.س</td>
                            <td class="p-5 text-slate-600 uppercase"><?= htmlspecialchars($pay['payment_method']) ?></td>
                            <td class="p-5">
                                    <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg text-[10px] font-bold">مكتمل</span>
                           
                            </td>
                            <td class="p-5 text-left text-slate-400 text-xs font-bold"><?= date('Y-m-d', strtotime($pay['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400 font-bold">لا توجد معاملات مالية حالياً.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include ('include/footer.php') ?>