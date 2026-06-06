<?php include('../include/header.php');
// التأكد من وجود معرف الموعد
$id = $_GET['id'] ?? null; 
$user_id = $_SESSION['id'] ?? null;

if (!$id || !$user_id) {
    die("خطأ: بيانات غير مكتملة.");
}
$stmt = $pdo->prepare("SELECT cs.*, s.name as service_name FROM clinic_slots cs 
                       JOIN services s ON cs.service_id = s.id 
                       WHERE cs.id = ? AND cs.user_id = ?");
$stmt->execute([$id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("الموعد غير موجود.");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'])) {
    $rating = (int)$_POST['rating'];
    $notes = htmlspecialchars($_POST['notes']);
    $sql = "INSERT INTO ratings (slot_id, user_id, rating, notes, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $user_id, $rating, $notes]);
    $sql_update = "UPDATE clinic_slots SET status = '4' WHERE id = ? AND user_id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([$id, $user_id]);

    $_SESSION['success'] = 'شكراً لتقييمك!';
    header("Location: all_bookings.php");
    exit;
}
?>

<main class="relative pt-36 pb-24 bg-[radial-gradient(circle_at_top_right,rgba(251,191,36,0.05),transparent_30%)]">
    <div class="max-w-2xl mx-auto px-6">
        <div class="bg-white border border-slate-200 rounded-[32px] p-8 shadow-xl">
            <h1 class="text-2xl font-black text-slate-900 mb-2">كيف كانت تجربتك؟</h1>
            <p class="text-slate-500 mb-8">يسعدنا سماع رأيك حول جلسة: <?php echo $booking['service_name']; ?></p>

        <form method="POST" class="space-y-6">
    
    <div class="text-center">
        <label class="block text-sm font-bold text-slate-700 mb-4">
            تقييمك للجلسة
        </label>

        <div id="star-rating" class="flex justify-center gap-2">
            <?php for($i=1; $i<=5; $i++): ?>
                <input
                    type="radio"
                    name="rating"
                    value="<?= $i ?>"
                    id="star<?= $i ?>"
                    class="hidden"
                    required
                >

                <label
                    for="star<?= $i ?>"
                    class="star cursor-pointer text-5xl text-slate-300 transition-all duration-200 hover:scale-110"
                    data-rating="<?= $i ?>"
                >
                    ★
                </label>
            <?php endfor; ?>
        </div>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">
            ملاحظات إضافية (اختياري)
        </label>

        <textarea
            name="notes"
            rows="4"
            class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 focus:ring-2 focus:ring-amber-400 outline-none"
            placeholder="شاركنا رأيك..."
        ></textarea>
    </div>

    <button
        type="submit"
        class="w-full h-14 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition"
    >
        إرسال التقييم
    </button>

</form>

        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const stars = document.querySelectorAll('.star');
    const inputs = document.querySelectorAll('input[name="rating"]');

    function paintStars(rating) {
        stars.forEach((star, index) => {

            if ((index + 1) <= rating) {
                star.classList.remove('text-slate-300');
                star.classList.add('text-amber-400');
            } else {
                star.classList.remove('text-amber-400');
                star.classList.add('text-slate-300');
            }

        });
    }

    stars.forEach((star, index) => {

        star.addEventListener('mouseenter', () => {
            paintStars(index + 1);
        });

        star.addEventListener('click', () => {

            inputs[index].checked = true;
            paintStars(index + 1);

        });

    });
    document.getElementById('star-rating').addEventListener('mouseleave', () => {

        const selected = document.querySelector('input[name="rating"]:checked');

        if (selected) {
            paintStars(parseInt(selected.value));
        } else {
            paintStars(0);
        }

    });

});
</script>
