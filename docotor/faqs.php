<?php 
include('include/header.php');

// التأكد من وجود CSRF Token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// معالجة الإضافة
if (isset($_POST['add'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    if ($question && $answer) {
        $stmt = $pdo->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");
        $stmt->execute([$question, $answer]);
        $_SESSION['success'] = "تمت إضافة السؤال بنجاح";
    }
    header("Location: faqs.php");
    exit;
}

// معالجة التعديل
if (isset($_POST['update'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die("CSRF ERROR");
    $stmt = $pdo->prepare("UPDATE faqs SET question=?, answer=? WHERE id=?");
    $stmt->execute([$_POST['question'], $_POST['answer'], $_POST['id']]);
    $_SESSION['success'] = "تم تحديث السؤال بنجاح";
    header("Location: faqs.php");
    exit;
}

// معالجة الحذف
if (isset($_POST['delete'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die("CSRF ERROR");
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id=?");
    $stmt->execute([$_POST['id']]);
    $_SESSION['success'] = "تم حذف السؤال بنجاح";
    header("Location: faqs.php");
    exit;
}

$faqs = $pdo->query("SELECT * FROM faqs ORDER BY id DESC")->fetchAll();
?>

<main class="p-4 sm:p-8 flex-1">
    <?php if (isset($_SESSION['success'])): ?>
        <div id="toast-success" class="fixed top-5 left-5 bg-white p-4 rounded-2xl shadow-xl border border-emerald-100 z-50 text-emerald-800 font-bold">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-900">الأسئلة الشائعة</h2>
            <p class="text-xs text-slate-400">إدارة الأسئلة والإجابات</p>
        </div>
        <button onclick="openAddModal()" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-xl font-bold transition-all">
            + إضافة سؤال
        </button>
    </div>

    <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-slate-50 text-slate-500 text-sm">
                <tr>
                    <th class="p-4">السؤال</th>
                    <th class="p-4">الإجابة</th>
                    <th class="p-4 text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $faq): ?>
                <tr class="border-t hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-semibold"><?= htmlspecialchars($faq['question']) ?></td>
                    <td class="p-4 text-slate-500"><?= htmlspecialchars($faq['answer']) ?></td>
                    <td class="p-4 flex justify-center gap-2">
                        <button onclick="openEditModal(this)" 
                                data-id="<?= $faq['id'] ?>" 
                                data-question="<?= htmlspecialchars($faq['question']) ?>" 
                                data-answer="<?= htmlspecialchars($faq['answer']) ?>"
                                class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg font-bold">تعديل</button>
                        <button onclick="openDeleteModal(<?= $faq['id'] ?>)" 
                                class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg font-bold">حذف</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="faqModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-xl">
        <h3 id="modalTitle" class="text-xl font-black mb-4">إضافة سؤال</h3>
        <form method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
            <input type="hidden" name="id" id="faqId">
            <input id="faqQuestion" type="text" name="question" required placeholder="اكتب السؤال هنا" class="w-full border p-3 rounded-xl mb-3">
            <textarea id="faqAnswer" name="answer" required placeholder="اكتب الإجابة هنا" class="w-full border p-3 rounded-xl h-32"></textarea>
            
            <div class="flex justify-end gap-3 mt-5">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 rounded-xl">إلغاء</button>
                <button type="submit" name="add" id="addBtn" class="px-4 py-2 bg-sky-500 text-white rounded-xl">حفظ</button>
                <button type="submit" name="update" id="updateBtn" class="px-4 py-2 bg-amber-500 text-white rounded-xl hidden">تحديث</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-xl text-center">
        <h3 class="text-xl font-black mb-4">تأكيد الحذف؟</h3>
        <form method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
            <input type="hidden" name="id" id="deleteId">
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-slate-100 rounded-xl">إلغاء</button>
                <button type="submit" name="delete" class="px-4 py-2 bg-rose-500 text-white rounded-xl">نعم، احذف</button>
            </div>
        </form>
    </div>
</div>

<script>
// الدوال البرمجية لإدارة المودال
function openAddModal() {
    document.getElementById('modalTitle').innerText = "إضافة سؤال";
    document.getElementById('faqId').value = "";
    document.getElementById('faqQuestion').value = "";
    document.getElementById('faqAnswer').value = "";
    document.getElementById('addBtn').classList.remove('hidden');
    document.getElementById('updateBtn').classList.add('hidden');
    document.getElementById('faqModal').classList.remove('hidden');
    document.getElementById('faqModal').classList.add('flex');
}

function openEditModal(btn) {
    document.getElementById('modalTitle').innerText = "تعديل السؤال";
    document.getElementById('faqId').value = btn.dataset.id;
    document.getElementById('faqQuestion').value = btn.dataset.question;
    document.getElementById('faqAnswer').value = btn.dataset.answer;
    document.getElementById('addBtn').classList.add('hidden');
    document.getElementById('updateBtn').classList.remove('hidden');
    document.getElementById('faqModal').classList.remove('hidden');
    document.getElementById('faqModal').classList.add('flex');
}

function openDeleteModal(id) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeModal() { document.getElementById('faqModal').classList.add('hidden'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); }
</script>

<?php include('include/footer.php'); ?>