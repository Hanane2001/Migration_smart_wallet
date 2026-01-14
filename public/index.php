<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget - Personal Finance Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <?php include("../app/views/layouts/header.php"); ?>
    <section class="py-20 bg-gradient-to-r from-blue-500 to-purple-600 h-screen">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold text-white mb-6">Take Control of Your Finances</h1>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                Track your income, manage expenses, and achieve your financial goals with SmartBudget.
                Simple, intuitive, and completely free.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="auth/register.php" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">Get Started</a>
                <a href="auth/login.php" class="bg-blue-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-900 transition">Login</a>
            </div>
        </div>
    </section>
    <!-- <script src="assets/js/main.js"></script> -->
</body>
</html>