<?php
require_once '../config/database.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute([
                'username' => $username,
                'email'    => $email,
                'password' => $hashedPassword
            ]);

            header("Location: login.php?registered=success");
            exit;
        } catch (PDOException $e) {
            $error = "Username atau Email sudah terdaftar!";
        }
    } else {
        $error = "Harap isi semua kolom!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="max-w-md mx-auto bg-slate-800 p-6 rounded-xl border border-slate-700 shadow-xl mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center text-white">Daftar Akun DevNotes</h2>

    <?php if ($error): ?>
        <div class="bg-rose-500/10 border border-rose-500 text-rose-400 p-3 rounded-lg text-sm mb-4">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Username</label>
            <input type="text" name="username" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Email</label>
            <input type="email" name="email" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Password</label>
            <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 rounded-lg transition mt-2">
            Daftar Sekarang
        </button>
    </form>

    <p class="text-xs text-slate-400 text-center mt-4">
        Sudah punya akun? <a href="login.php" class="text-indigo-400 hover:underline">Login di sini</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>