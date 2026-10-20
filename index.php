<?php
require_once 'config/database.php';
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];

// Fitur Search sederhana
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id AND (title LIKE :search OR language LIKE :search) ORDER BY created_at DESC");
    $stmt->execute([
        'user_id' => $user_id,
        'search'  => "%$search%"
    ]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $user_id]);
}

$notes = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white">Koleksi Catatan Saya</h1>
        <p class="text-slate-400 text-sm">Kelola semua potongan kode dan catatan teknis kamu di sini.</p>
    </div>

    <!-- Form Search -->
    <form action="" method="GET" class="flex gap-2">
        <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Cari judul / bahasa..." class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
        <button type="submit" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm px-4 py-2 rounded-lg transition">Cari</button>
        <?php if ($search !== ''): ?>
            <a href="index.php" class="bg-rose-600/20 text-rose-400 text-sm px-3 py-2 rounded-lg flex items-center hover:bg-rose-600/30">Reset</a>
        <?php endif; ?>
    </form>
</div>

<?php if (empty($notes)): ?>
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-8 text-center">
        <p class="text-slate-400 text-sm">Belum ada catatan<?= $search ? ' yang cocok dengan pencarian' : '' ?>.</p>
        <a href="create.php" class="inline-block mt-4 text-indigo-400 hover:underline text-sm">+ Tambah Catatan Pertama</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($notes as $note): ?>
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-slate-100 line-clamp-1"><?= htmlspecialchars($note['title']); ?></h3>
                        <span class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs px-2 py-0.5 rounded font-mono">
                            <?= htmlspecialchars($note['language']); ?>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4"><?= date('d M Y, H:i', strtotime($note['created_at'])); ?></p>
                    
                    <pre class="bg-slate-900 border border-slate-700/50 rounded-lg p-3 text-xs text-slate-300 font-mono overflow-x-auto max-h-40 mb-4"><code><?= htmlspecialchars($note['content']); ?></code></pre>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-700/50 text-xs">
                    <a href="edit.php?id=<?= $note['id']; ?>" class="text-slate-400 hover:text-indigo-400 transition">Edit</a>
                    <a href="delete.php?id=<?= $note['id']; ?>" onclick="return confirm('Yakin ingin menghapus catatan ini?')" class="text-slate-400 hover:text-rose-400 transition">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>