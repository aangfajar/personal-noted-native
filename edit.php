<?php
require_once 'config/database.php';
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];
$id      = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data catatan milik user aktif
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $id, 'user_id' => $user_id]);
$note = $stmt->fetch();

if (!$note) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']);
    $language = trim($_POST['language']);
    $content  = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("UPDATE notes SET title = :title, language = :language, content = :content WHERE id = :id AND user_id = :user_id");
        $stmt->execute([
            'title'    => $title,
            'language' => $language ?: 'Text',
            'content'  => $content,
            'id'       => $id,
            'user_id'  => $user_id
        ]);

        header("Location: index.php");
        exit;
    } else {
        $error = "Judul dan Isi Catatan tidak boleh kosong!";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="max-w-2xl mx-auto bg-slate-800 p-6 rounded-xl border border-slate-700 shadow-xl">
    <h2 class="text-2xl font-bold mb-6 text-white">Edit Catatan</h2>

    <?php if ($error): ?>
        <div class="bg-rose-500/10 border border-rose-500 text-rose-400 p-3 rounded-lg text-sm mb-4">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Judul Catatan</label>
            <input type="text" name="title" value="<?= htmlspecialchars($note['title']); ?>" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Bahasa / Kategori</label>
            <input type="text" name="language" value="<?= htmlspecialchars($note['language']); ?>" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Isi Catatan / Code Snippet</label>
            <textarea name="content" rows="8" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 font-mono text-sm focus:outline-none focus:border-indigo-500"><?= htmlspecialchars($note['content']); ?></textarea>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="index.php" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg text-sm transition">Batal</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg text-sm transition">Perbarui Catatan</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>