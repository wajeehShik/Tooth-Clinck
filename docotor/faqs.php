<?php 
include('include/header.php');
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
if (isset($_POST['add'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    if ($question && $answer) {
        $stmt = $pdo->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");
        $stmt->execute([$question, $answer]);
        $_SESSION['success'] = "تمت إضافة السؤال بنجاح";
    }
    header("Location:faqs.php");
    exit;
}
if (isset($_POST['update'])) {

    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die("CSRF ERROR");
    }

    $stmt = $pdo->prepare("UPDATE faqs SET question=?, answer=? WHERE id=?");
    $stmt->execute([
        $_POST['question'],
        $_POST['answer'],
        $_POST['id']
    ]);
    $_SESSION['success'] = "تم تحديث السؤال بنجاح";

    header("Location:faqs.php");
    exit;
}
if (isset($_POST['delete'])) {

    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die("CSRF ERROR");
    }

    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id=?");
    $stmt->execute([$_POST['id']]);
    $_SESSION['success'] = "تم حذف السؤال بنجاح";

    header("Location:faqs.php");
    exit;
}
$faqs = $pdo->query("SELECT * FROM faqs ORDER BY id DESC")->fetchAll();
?>
<main class="p-4 sm:p-8 flex-1">
<?php if (isset($_SESSION['success'])): ?>
        <div id="toast-success" class="fixed top-5 left-5 flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-2xl shadow-xl border border-emerald-100 z-50 transition-opacity duration-500" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-50 rounded-xl">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
            </div>
            <div class="ms-3 text-sm font-bold text-emerald-800"><?= $_SESSION['success']; ?></div>
        </div>
        <?php unset($_SESSION['success']); // حذف الرسالة من الجلسة حتى لا تظهر مجدداً عند تحديث الصفحة ?>
    <?php endif; ?>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">الأسئلة الشائعة</h2>
            <p class="text-xs text-slate-400">إدارة الأسئلة الشائعة</p>
        </div>

        <button onclick="openAddModal()"
            class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-xl font-bold">
            + إضافة سؤال
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">

        <table class="w-full text-right">
            <thead class="bg-slate-50 text-slate-500 text-sm">
                <tr>
                    <th class="p-4">السؤال</th>
                    <th class="p-4">الإجابة</th>
                    <th class="p-4">الإجراءات</th>
                </tr>
            </thead>

            <tbody>
<?php foreach ($faqs as $faq): ?>
<tr class="border-t">

<td class="p-4 font-semibold"><?= htmlspecialchars($faq['question']) ?></td>
<td class="p-4 text-slate-500"><?= htmlspecialchars($faq['answer']) ?></td>

<td class="p-4 flex gap-2">

<!-- EDIT -->
<button
onclick="openEditModal(this)"
data-id="<?= $faq['id'] ?>"
data-question="<?= htmlspecialchars($faq['question']) ?>"
data-answer="<?= htmlspecialchars($faq['answer']) ?>"
class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg font-bold">
تعديل
</button>

<!-- DELETE FORM (SECURE) -->
<form method="POST" onsubmit="return confirm('تأكيد الحذف؟')">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <input type="hidden" name="id" value="<?= $faq['id'] ?>">
    <button name="delete"
        class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg font-bold">
        حذف
    </button>
</form>

</td>

</tr>
<?php endforeach; ?>
            </tbody>
        </table>

    </div>
</main>
<!-- ================= ADD / EDIT MODAL ================= -->
<div id="faqModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-xl rounded-3xl p-6 shadow-xl">

        <h3 id="modalTitle" class="text-xl font-black mb-4">إضافة سؤال</h3>
<form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">

    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <input type="hidden" name="id" id="faqId">

    <input id="faqQuestion" type="text" name="question" class="w-full border p-3 rounded-xl">
    
    <textarea id="faqAnswer" name="answer" class="w-full border p-3 rounded-xl"></textarea>

    <div class="flex justify-end gap-3 mt-5">

        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 rounded-xl">
            إلغاء
        </button>

        <!-- ADD -->
        <button type="submit" name="add" id="addBtn"
            class="px-4 py-2 bg-sky-500 text-white rounded-xl">
            حفظ
        </button>

        <!-- UPDATE -->
        <button type="submit" name="update" id="updateBtn"
            class="px-4 py-2 bg-amber-500 text-white rounded-xl hidden">
            تحديث
        </button>

    </div>

</form>
        </div>

    </div>
</div>
<!-- ================= DELETE MODAL ================= -->
<div id="deleteModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-xl text-center">

        <div class="text-5xl mb-3">⚠️</div>

        <h3 class="text-xl font-black mb-2">تأكيد الحذف</h3>
        <p class="text-slate-500 mb-5">هل أنت متأكد أنك تريد حذف هذا السؤال؟</p>

        <input type="hidden" id="deleteId">

        <div class="flex justify-center gap-3">

            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-slate-100 rounded-xl font-bold">
                إلغاء
            </button>

            <button class="px-4 py-2 bg-rose-500 text-white rounded-xl font-bold">
                حذف
            </button>

        </div>

    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>

const faqModal = document.getElementById('faqModal');
const deleteModal = document.getElementById('deleteModal');

const modalTitle = document.getElementById('modalTitle');
const faqQuestion = document.getElementById('faqQuestion');
const faqAnswer = document.getElementById('faqAnswer');
const faqId = document.getElementById('faqId');

const deleteId = document.getElementById('deleteId');
// ADD
function openAddModal() {
    modalTitle.innerText = "إضافة سؤال";

    faqId.value = "";
    faqQuestion.value = "";
    faqAnswer.value = "";

    faqModal.classList.remove('hidden');
    faqModal.classList.add('flex');
}
function openEditModal(btn) {

    modalTitle.innerText = "تعديل السؤال";

    faqId.value = btn.dataset.id;
    faqQuestion.value = btn.dataset.question;
    faqAnswer.value = btn.dataset.answer;

    faqModal.classList.remove('hidden');
    faqModal.classList.add('flex');
}
function openDeleteModal(id) {
    deleteId.value = id;

    deleteModal.classList.remove('hidden');
    deleteModal.classList.add('flex');
}
function closeModal() {
    faqModal.classList.add('hidden');
    faqModal.classList.remove('flex');
}

function closeDeleteModal() {
    deleteModal.classList.add('hidden');
    deleteModal.classList.remove('flex');
}
faqModal.addEventListener('click', e => {
    if (e.target === faqModal) closeModal();
});

deleteModal.addEventListener('click', e => {
    if (e.target === deleteModal) closeDeleteModal();
});
document.addEventListener("DOMContentLoaded", function() {
    const toast = document.getElementById('toast-success');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 500); // الانتظار حتى ينتهي تأثير الاختفاء الناعم
        }, 3000); // 3000 مللي ثانية = 3 ثوانٍ
    }
});
</script>

<?php include('include/footer.php'); ?>