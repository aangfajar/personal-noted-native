<?php
require_once 'config/database.php';
require_once 'includes/auth_check.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']);
    $language = trim($_POST['language']);
    $content  = trim($_POST['content']);
    $user_id  = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, language, content) VALUES (:user_id, :title, :language, :content)");
        $stmt->execute([
            'user_id'  => $user_id,
            'title'    => $title,
            'language' => $language ?: 'Text',
            'content'  => $content
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
    <h2 class="text-2xl font-bold mb-6 text-white">Tambah Catatan Baru</h2>

    <?php if ($error): ?>
        <div class="bg-rose-500/10 border border-rose-500 text-rose-400 p-3 rounded-lg text-sm mb-4">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Judul Catatan</label>
            <input type="text" name="title" required placeholder="Contoh: Cara koneksi PDO di PHP" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Bahasa / Kategori</label>
            <input type="text" name="language" placeholder="PHP, JS, SQL, Docker, dll" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Isi Catatan / Code Snippet</label>
            <textarea name="content" rows="8" required placeholder="Tuliskan catatan atau kode di sini..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 font-mono text-sm focus:outline-none focus:border-indigo-500"></textarea>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="index.php" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg text-sm transition">Batal</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg text-sm transition">Simpan Catatan</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>