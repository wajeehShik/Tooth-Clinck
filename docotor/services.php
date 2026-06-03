<?php 
include ('include/header.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    
    if ($_GET['action'] === 'add') {
        $name = trim($_POST['title']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $sessions_count = intval($_POST['sessions']);
        $duration_time = trim($_POST['duration']);
        
        if (!empty($name)) {
            $stmt = $pdo->prepare("INSERT INTO services (name, description, price, sessions_count, duration_time, status) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->execute([$name, $description, $price, $sessions_count, $duration_time]);
            $_SESSION['success'] = "تم إضافة الخدمة العلاجية بنجاح! 🎉";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if ($_GET['action'] === 'edit') {
        $service_id = intval($_POST['service_id']);
        $name = trim($_POST['title']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $sessions_count = intval($_POST['sessions']);
        $duration_time = trim($_POST['duration']);
        
        if ($service_id > 0 && !empty($name)) {
            $stmt = $pdo->prepare("UPDATE services SET name = ?, description = ?, price = ?, sessions_count = ?, duration_time = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $sessions_count, $duration_time, $service_id]);
            $_SESSION['success'] = "تم تحديث بيانات الخدمة بنجاح! ✨";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_to_delete = intval($_GET['id']);
    if ($id_to_delete > 0) {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id_to_delete]);
        $_SESSION['success'] = "تم حذف الخدمة بنجاح 🗑️";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
$stmt = $pdo->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="p-4 sm:p-8 flex-1 max-w-6xl w-full mx-auto space-y-6 pt-32 pb-24 bg-slate-50">
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-2xl text-sm font-semibold shadow-sm flex items-center gap-2">
            <p>✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        </div>
    <?php endif; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-slate-100 p-6 rounded-[24px] shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">إدارة خدمات عيادة الأسنان 🦷</h1>
            <p class="text-slate-400 text-xs font-bold">يمكنك إضافة، تعديل، أو حذف الخدمات الطبية المعروضة للمرضى وتحديد أسعارها وجلساتها.</p>
        </div>
        <button onclick="openModal('addServiceModal')" class="bg-sky-500 hover:bg-sky-600 text-white font-black text-xs px-5 py-3.5 rounded-xl shadow-md shadow-sky-100 transition-all flex items-center gap-2 shrink-0">
            <span>➕</span> إضافة خدمة جديدة
        </button>
    </div>

    <div class="bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-bold">
                        <th class="p-5">أيقونة واسم الخدمة</th>
                        <th class="p-5">الوصف المختصر</th>
                        <th class="p-5">عدد الجلسات</th>
                        <th class="p-5">التكلفة (شيكل)</th>
                        <th class="p-5">مدة الجلسة</th>
                        <th class="p-5 text-left">العمليات والتحكم</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-xs sm:text-sm text-slate-700">
                    
                    <?php if (empty($services)): ?>
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400 font-bold">
                                لا توجد خدمات مضافة حالياً. اضغط على "إضافة خدمة جديدة" للبدء.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($services as $service): ?>
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 font-bold flex items-center justify-center text-lg shrink-0">🦷</div>
                                        <h4 class="font-bold text-slate-900 text-sm"><?php echo htmlspecialchars($service['name']); ?></h4>
                                    </div>
                                </td>
                                <td class="p-5 text-slate-400 text-xs max-w-xs truncate" title="<?php echo htmlspecialchars($service['description']); ?>">
                                    <?php echo htmlspecialchars($service['description']); ?>
                                    </td>
                                <td class="p-5"><span class="text-sky-600 bg-sky-50 px-2.5 py-1 rounded-md font-bold text-xs"><?php echo htmlspecialchars($service['sessions_count']); ?> جلسات</span></td>
                                <td class="p-5 font-black text-slate-900"><?php echo htmlspecialchars($service['price']); ?> شيكل</td>
                                <td class="p-5 text-slate-500 font-semibold"><?php echo htmlspecialchars($service['duration_time']); ?></td>
                                <td class="p-5 text-left">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button onclick='openEditModal(<?php echo json_encode($service, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold px-3 py-2 rounded-lg border border-amber-100 text-xs transition-all flex items-center gap-1">✏️ تعديل</button>
                                        <a href="?action=delete&id=<?php echo $service['id']; ?>" onclick="return confirm('هل أنت متأكد تماماً من حذف هذه الخدمة؟')" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold px-3 py-2 rounded-lg border border-rose-100 text-xs transition-all flex items-center gap-1">❌ حذف</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</main>


<div id="addServiceModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">✨ إضافة خدمة علاجية جديدة</h3>
            <button onclick="closeModal('addServiceModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form action="?action=add" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اسم الخدمة</label>
                <input type="text" name="title" required placeholder="مثال: تركيب زيركون" class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">الوصف المختصر للخدمة</label>
                <textarea name="description" rows="3" required placeholder="اكتب تفاصيل الخدمة والمميزات للمريض..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">السعر (بالشيكل)</label>
                    <input type="number" name="price" required placeholder="300" class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">عدد الجلسات</label>
                    <input type="number" name="sessions" required placeholder="3" class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">زمن الجلسة المتوقع (بالدقائق)</label>
                <input type="text" name="duration" required placeholder="مثال: 45 دقيقة" class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="w-full h-12 mt-2 rounded-xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-black text-sm shadow-md shadow-sky-100 hover:scale-[1.01] transition-all">
                حفظ وإضافة الخدمة
            </button>
        </form>
    </div>
</div>

<div id="editServiceModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-slate-900">✏️ تعديل بيانات الخدمة</h3>
            <button onclick="closeModal('editServiceModal')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
        </div>
        <form action="?action=edit" method="POST" class="space-y-4">
            <input type="hidden" id="edit_service_id" name="service_id" value="">
            
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">اسم الخدمة</label>
                <input type="text" id="edit_title" name="title" value="" required class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">الوصف المختصر للخدمة</label>
                <textarea id="edit_description" name="description" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">السعر (بالشيكل)</label>
                    <input type="number" id="edit_price" name="price" value="" required class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">عدد الجلسات</label>
                    <input type="number" id="edit_sessions" name="sessions" value="" required class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5 pr-1">زمن الجلسة المتوقع</label>
                <input type="text" id="edit_duration" name="duration" value="" required class="w-full h-12 bg-slate-50 border border-slate-200 rounded-xl px-4 text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="w-full h-12 mt-2 rounded-xl bg-gradient-to-l from-amber-500 to-amber-600 text-white font-black text-sm shadow-md shadow-amber-100 hover:scale-[1.01] transition-all">
                تحديث وحفظ التعديلات
            </button>
        </form>
    </div>
</div>


<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
function openEditModal(serviceData) {
    document.getElementById('edit_service_id').value = serviceData.id;
    document.getElementById('edit_title').value = serviceData.name;
    document.getElementById('edit_description').value = serviceData.description;
    document.getElementById('edit_price').value = serviceData.price;
    document.getElementById('edit_sessions').value = serviceData.sessions_count;
    document.getElementById('edit_duration').value = serviceData.duration_time;
    
    openModal('editServiceModal');
}
</script>

<?php include ('include/footer.php')?>