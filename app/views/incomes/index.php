<?php include('../app/views/layouts/header.php'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Income Management</h1>
            <p class="text-gray-600">Manage all your income transactions</p>
        </div>
        <button onclick="showAddForm()" class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition">Add New Income</button>
    </div>

    <!-- Messages -->
    <?php if (isset($_GET['message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php 
            $messages = [
                'added' => 'Income added successfully!',
                'updated' => 'Income updated successfully!',
                'deleted' => 'Income deleted successfully!'
            ];
            echo $messages[$_GET['message']] ?? 'Operation completed successfully!';
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?php 
            $errors = [
                'insert_failed' => 'Failed to add income. Please try again.',
                'update_failed' => 'Failed to update income. Please try again.',
                'delete_failed' => 'Failed to delete income. Please try again.',
                'not_found' => 'Income not found.'
            ];
            echo $errors[$_GET['error']] ?? 'An error occurred!';
            ?>
        </div>
    <?php endif; ?>

    <!-- Add Form -->
    <div id="addForm" class="hidden bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Add New Income</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>income/create" class="space-y-4">
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
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">Save Income</button>
                <button type="button" onclick="hideAddForm()" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Income List -->
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
                    <?php if (!empty($incomes)): ?>
                        <?php foreach ($incomes as $inc): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $inc['id_in']; ?></td>
                            <td class="px-6 py-4 font-semibold text-green-600">
                                $<?php echo number_format($inc['amount_in'], 2); ?>
                            </td>
                            <td class="px-6 py-4"><?php echo $inc['date_in']; ?></td>
                            <td class="px-6 py-4"><?php echo $inc['category_name'] ?? 'Uncategorized'; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($inc['description_in']); ?></td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="<?php echo BASE_URL; ?>income/edit/<?php echo $inc['id_in']; ?>" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition">Edit</a>
                                    <a href="<?php echo BASE_URL; ?>income/delete/<?php echo $inc['id_in']; ?>" onclick="return confirm('Are you sure you want to delete this income?')" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <p>No income records found. Add your first income!</p>
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