<?php include ('include/header.php');

// جلب جميع الإشعارات مرتبة حسب الأحدث
$stmt = $pdo->query("SELECT * FROM notifications ORDER BY created_at DESC");
$notifications = $stmt->fetchAll();
?>

<!-- المحتوى الرئيسي لصفحة الإشعارات والاشتراكات -->
<main class="p-4 sm:p-8 flex-1 max-w-4xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
  <div class="space-y-3">
    <?php foreach($notifications as $n): 
        // تحديد الألوان حسب الحالة (يمكنك تخصيصها حسب نوع الإشعار)
        $is_read = $n['is_read'] == 1;
        $border_color = $is_read ? 'border-slate-100' : 'border-r-sky-500';
    ?>
    <div id="notification-<?= $n['id'] ?>" class="bg-white border-r-4 <?= $border_color ?> border border-slate-100 p-5 rounded-[20px] shadow-sm flex items-start justify-between gap-4 transition-all <?= $is_read ? 'opacity-75' : '' ?>">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-lg shrink-0">🔔</div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-black text-slate-900 text-sm"><?= htmlspecialchars($n['message']) ?></h3>
                    <?php if(!$is_read): ?>
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                    <?php endif; ?>
                </div>
                <span class="text-[10px] text-slate-400 font-bold mt-2 block"><?= date('Y-m-d H:i', strtotime($n['created_at'])) ?></span>
            </div>
        </div>
        
        <div class="flex items-center gap-1.5 shrink-0">
            <?php if(!$is_read): ?>
                <button onclick="markAsRead(<?= $n['id'] ?>)" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-slate-600 text-xs transition-all border border-transparent">✓</button>
            <?php endif; ?>
            <a href="<?= $n['link'] ?>" class="bg-slate-50 hover:bg-sky-500 hover:text-white text-slate-700 font-bold px-3 py-1.5 rounded-lg border border-slate-200 text-xs transition-all">عرض</a>
        </div>
    </div>
    <?php endforeach; ?>
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