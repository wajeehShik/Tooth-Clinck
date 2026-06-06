<?php include ('include/header.php');
$stmt=$pdo->prepare("SELECT cs.*, u.name as patient_name, s.name as service_name 
FROM clinic_slots cs
INNER JOIN users u ON cs.user_id = u.id
INNER JOIN services s ON cs.service_id = s.id
ORDER BY 
    cs.booking_date ASC, 
    cs.time_range ASC;  ");
    $stmt->execute();
    $bookings=$stmt->fetchAll();
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    $booking_id = $_POST['id'];
    $sql = "UPDATE clinic_slots SET status = '3' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$booking_id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
}
?>
<main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">إدارة الحجوزات وجلسات المرضى 📅</h1>
            <p class="text-slate-400 text-xs font-bold">جدولة مواعيد العيادة، متابعة حالات الدفع، التقييمات، وإضافة جلسات المتابعة الدورية.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a  href="addsession.php" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-xs px-4 py-3.5 rounded-xl transition-all flex items-center gap-2">
                <span>🦷</span> إضافة جلسة لمريض 
</a>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                        <th class="p-5">اسم المريض والخدمة</th>
                        <th class="p-5">الموعد والتوقيت</th>
                        <th class="p-5">حالة الدفع</th>
                        <th class="p-5">ملاحظات المريض</th>
                        <th class="p-5 text-left">إجراءات سريعة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                   <?php foreach($bookings as $booking){?>
                    <tr id="booking-row-1" class="hover:bg-slate-50/40 transition-colors">
                        <td class="p-5">
                            <div class="flex items-center gap-2">
                                <span id="status-indicator-1" class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm"> <?php echo $booking['patient_name']?></h4>
                                    <span class="text-[10px] text-slate-400 block font-bold"><?php echo $booking['service_name']?></span>
                                </div>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="text-slate-800 font-bold block"><?php echo $booking['booking_day']  .'<br>'.$booking['booking_date']  ?> </span>
                            <span class="text-[11px] text-slate-400 font-semibold block"><?php echo $booking['time_range']?></span>
                        </td>
                        <td class="p-5">
                            <?php
if($booking['status']=='1'){?>
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-2.5 py-1 rounded-md font-bold text-xs">لم يدفع بعد</span>
<?php
}else if($booking['status']=='2'){
?>
                            <span class="bg-green-200 text-shadow-black border border-green-950 px-2.5 py-1 rounded-md font-bold text-xs">  دفع ابنتظار الموعد</span>

<?php }elseif($booking['status']=='3') { ?>
                            <span class="bg-green-200 text-shadow-black border border-green-950 px-2.5 py-1 rounded-md font-bold text-xs">      بانتظار التقيم</span>
<?php }else{?>
    
                            <span class="bg-green-200 text-shadow-black border border-green-950 px-2.5 py-1 rounded-md font-bold text-xs">   انتهت الجلسة </span>
    <?php
    }?>
                        </td>
                        <td class="p-5 text-slate-400 text-xs max-w-[180px] truncate">
<?php echo $booking['notes']??''?>                        </td>
                        <td class="p-5 text-left flex items-center justify-end gap-1.5">
<?php if($booking['status']=='1'){?>
                        <span>x</span> انتظار الدفع 
<?php }else if($booking['status']=='2'){?>
<form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
    <input type="hidden" name="action" value="end_session">
    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
    

<button type="submit" class="bg-emerald-50 hover:bg-emerald-500 hover:text-white text-emerald-600 font-bold p-2 rounded-lg border border-emerald-100 text-xs transition-all flex items-center gap-1 cursor-pointer">
        <span>✓</span> تم انتهاء الجلسة 
    </button>
</form>

<?php }else{
    $stmt=$pdo->prepare('select rating from  ratings where slot_id=? ');
    $stmt->execute([$booking['id']]);
    $rating=$stmt->fetch();    
  if (!empty($rating)){ ?>
    <div class="flex text-amber-400 text-2xl">
        <?php 
            $stars = (int)$rating['rating'];   
            for ($i = 1; $i <= 5; $i++) {
                echo ($i <= $stars) ? '★' : '☆';
            }
        ?>
    </div>
<?php }else{ ?>
    <span class="text-slate-300  text-2xl">بانتظار  تقييم</span>
<?php } ?>
    

          <?php }?>
          
                        </td>
                    </tr>
<?php }?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include ('include/footer.php')?>