<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav class="bg-blue-600 shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-2">
                <i class="fas fa-wallet text-white text-2xl"></i>
                <a href="<?php echo BASE_URL; ?>" class="text-white text-xl font-bold">SmartBudget</a>
            </div>
            <div id="navLinks" class="hidden md:flex space-x-6">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>dashboard" class="text-white hover:text-blue-200">Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>income" class="text-white hover:text-blue-200">Incomes</a>
                    <a href="<?php echo BASE_URL; ?>expense" class="text-white hover:text-blue-200">Expenses</a>
                    <a href="<?php echo BASE_URL; ?>category" class="text-white hover:text-blue-200">Categories</a>
                    <a href="<?php echo BASE_URL; ?>auth/logout" class="text-white hover:text-blue-200">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>auth/login" class="text-white hover:text-blue-200">Login</a>
                    <a href="<?php echo BASE_URL; ?>auth/register" class="text-white hover:text-blue-200">Register</a>
                <?php endif; ?>
            </div>
            <button id="menu_toggle" class="md:hidden text-white" onclick="toggleMenu()">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>
</nav>

<script>
function toggleMenu() {
    const navLinks = document.getElementById('navLinks');
    navLinks.classList.toggle('hidden');
}
</script>