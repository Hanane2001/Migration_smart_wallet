<?php include('../app/views/layouts/header.php'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Incomes</p>
                    <h3 class="text-2xl font-bold text-gray-800">$<?php echo number_format($totalIncome, 2); ?></h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Expenses</p>
                    <h3 class="text-2xl font-bold text-gray-800">$<?php echo number_format($totalExpense, 2); ?></h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Current Balance</p>
                    <h3 class="text-2xl font-bold <?php echo $balance >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                        $<?php echo number_format($balance, 2); ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">This Month</p>
                    <h3 class="text-xl font-bold text-gray-800">
                        +$<?php echo number_format($monthStats['income'], 2); ?> / -$<?php echo number_format($monthStats['expense'], 2); ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-8 mb-8">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow p-8 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">Add New Income</h3>
            </div>
            <p class="mb-6">Record your latest income source quickly.</p>
            <a href="<?php echo BASE_URL; ?>income" class="inline-block bg-white text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Go to Incomes</a>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow p-8 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">Add New Expense</h3>
            </div>
            <p class="mb-6">Track your spending and manage expenses.</p>
            <a href="<?php echo BASE_URL; ?>expense" class="inline-block bg-white text-red-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Go to Expenses</a>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="w-full md:w-[70%] lg:w-[50%] mx-auto flex flex-col items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Monthly Overview</h2>
        <canvas class="mt-5 mb-5 w-full" id="chartjs_bar"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Transactions</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentTransactions as $transaction): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3"><?php echo $transaction['date']; ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($transaction['description']); ?></td>
                        <td class="px-4 py-3"><?php echo $transaction['category']; ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs <?php echo $transaction['type'] == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo ucfirst($transaction['type']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold <?php echo $transaction['type'] == 'income' ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo $transaction['type'] == 'income' ? '+' : '-'; ?>
                            $<?php echo number_format($transaction['amount'], 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    var gradientGreen = ctx.createLinearGradient(0, 0, 0, 400);
    gradientGreen.addColorStop(0, "#22c55e"); 
    gradientGreen.addColorStop(1, "#16a34a"); 

    var gradientRed = ctx.createLinearGradient(0, 0, 0, 400);
    gradientRed.addColorStop(0, "#ef4444");
    gradientRed.addColorStop(1, "#b91c1c");

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chartData['labels']); ?>,
            datasets: [{
                label: 'Income',
                data: <?php echo json_encode($chartData['income']); ?>,
                backgroundColor: gradientGreen
            }, {
                label: 'Expense',
                data: <?php echo json_encode($chartData['expense']); ?>,
                backgroundColor: gradientRed
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }]
            }
        }
    });
</script>

<?php include('../app/views/layouts/footer.php'); ?>