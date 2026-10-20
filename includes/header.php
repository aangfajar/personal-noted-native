<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevNotes - Personal Snippet Manager</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col font-sans">

    <!-- Navbar -->
    <nav class="bg-slate-800 border-b border-slate-700 py-4 shadow-md">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <a href="/index.php" class="text-xl font-bold tracking-wide text-indigo-400 hover:text-indigo-300">
                ⚡ DevNotes
            </a>
            
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-sm text-slate-400">
                        Hi, <strong class="text-slate-200"><?= htmlspecialchars($_SESSION['username']); ?></strong>
                    </span>
                    <a href="/create.php" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm px-3 py-1.5 rounded-lg transition font-medium">
                        + Catatan Baru
                    </a>
                    <a href="/auth/logout.php" class="text-sm text-rose-400 hover:text-rose-300 transition">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="/auth/login.php" class="text-sm text-slate-300 hover:text-white transition">Login</a>
                    <a href="/auth/register.php" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm px-3 py-1.5 rounded-lg transition font-medium">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Container Utama -->
    <main class="max-w-6xl mx-auto px-4 py-8 flex-grow w-full">