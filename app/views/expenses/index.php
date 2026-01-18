<?php include('../app/views/layouts/header.php'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Expense Management</h1>
            <p class="text-gray-600">Manage all your expense transactions</p>
        </div>
        <button onclick="showAddForm()" class="bg-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-600 transition">Add New Expense</button>
    </div>

    <!-- Messages -->
    <?php if (isset($_GET['message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php 
            $messages = [
                'added' => 'Expense added successfully!',
                'updated' => 'Expense updated successfully!',
                'deleted' => 'Expense deleted successfully!'
            ];
            echo $messages[$_GET['message']] ?? 'Operation completed successfully!';
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?php 
            $errors = [
                'insert_failed' => 'Failed to add expense. Please try again.',
                'update_failed' => 'Failed to update expense. Please try again.',
                'delete_failed' => 'Failed to delete expense. Please try again.',
                'not_found' => 'Expense not found.'
            ];
            echo $errors[$_GET['error']] ?? 'An error occurred!';
            ?>
        </div>
    <?php endif; ?>

    <!-- Add Form -->
    <div id="addForm" class="hidden bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Add New Expense</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>expense/create" class="space-y-4">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Amount ($)</label>
                    <input type="number" step="0.01" name="amount" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id_cat']; ?>">
                                <?php echo htmlspecialchars($cat['name_cat']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Description</label>
                    <input type="text" name="description" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional description">
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">Save Expense</button>
                <button type="button" onclick="hideAddForm()" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Expense List -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Amount</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($expenses)): ?>
                        <?php foreach ($expenses as $exp): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $exp['id_ex']; ?></td>
                            <td class="px-6 py-4 font-semibold text-red-600">
                                $<?php echo number_format($exp['amount_ex'], 2); ?>
                            </td>
                            <td class="px-6 py-4"><?php echo $exp['date_ex']; ?></td>
                            <td class="px-6 py-4"><?php echo $exp['category_name'] ?? 'Uncategorized'; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($exp['description_ex']); ?></td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="<?php echo BASE_URL; ?>expense/edit/<?php echo $exp['id_ex']; ?>" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition">Edit</a>
                                    <a href="<?php echo BASE_URL; ?>expense/delete/<?php echo $exp['id_ex']; ?>" onclick="return confirm('Are you sure you want to delete this expense?')" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <p>No expense records found. Add your first expense!</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function showAddForm() {
        document.getElementById('addForm').classList.remove('hidden');
    }
    
    function hideAddForm() {
        document.getElementById('addForm').classList.add('hidden');
    }
</script>
<?php include('../app/views/layouts/footer.php'); ?>