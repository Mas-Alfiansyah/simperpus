<?php
include __DIR__ . '/../../layouts/layout.php';
include __DIR__ . '/../../koneksi.php';

$id = intval($_GET['id'] ?? 0);
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id = $id");
$tx = mysqli_fetch_array($query);

if (!$tx) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $member_id = intval($_POST['member_id']);
    $book_id = intval($_POST['book_id']);
    $borrow_date = mysqli_real_escape_string($conn, $_POST['borrow_date']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $return_date = !empty($_POST['return_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['return_date']) . "'" : "NULL";
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $update = mysqli_query($conn, "UPDATE transaksi SET member_id=$member_id, book_id=$book_id, borrow_date='$borrow_date', due_date='$due_date', return_date=$return_date, status='$status', notes='$notes' WHERE id=$id");

    if ($update) {
        $pesan = "Data Transaksi Berhasil Diupdate!";
        $tipe  = "success";
        $redirect = 'index.php';
    } else {
        $pesan = "Gagal Mengupdate Transaksi!";
        $tipe  = "error";
        $redirect = 'edit.php?id=' . $id;
    }
}

$get_members = mysqli_query($conn, "SELECT id, full_name, email FROM anggota WHERE status = 'active' OR id = " . $tx['member_id']);
$get_books = mysqli_query($conn, "SELECT id, judul, pengarang, stok FROM buku WHERE stok > 0 OR id = " . $tx['book_id']);
?>
<div class="p-2 max-w-2xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="/simperpus/menu/management-transaction/" class="hover:text-blue-700 transition-colors">Transactions</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-slate-900 dark:text-white font-medium">Edit Transaction</span>
    </div>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Transaction</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Update loan details or record a book return.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-6">
        <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $id ?>" />

            <!-- TX ID (read-only) -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Transaction ID</label>
                <input type="text" value="TX-<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?>" readonly class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-500 cursor-not-allowed" />
            </div>

            <!-- Member -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="member_id">
                    Member <span class="text-rose-500">*</span>
                </label>
                <select id="member_id" name="member_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors">
                    <?php if ($get_members): ?>
                        <?php while ($member = mysqli_fetch_assoc($get_members)): ?>
                            <option value="<?= $member['id'] ?>" <?= $member['id'] == $tx['member_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($member['full_name'] . ' (' . $member['email'] . ')') ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Book -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="book_id">
                    Book <span class="text-rose-500">*</span>
                </label>
                <select id="book_id" name="book_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors">
                    <?php if ($get_books): ?>
                        <?php while ($book = mysqli_fetch_assoc($get_books)): ?>
                            <option value="<?= $book['id'] ?>" <?= $book['id'] == $tx['book_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($book['judul'] . ' — ' . $book['pengarang']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="borrow_date">
                        Borrow Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="borrow_date" name="borrow_date" required value="<?= htmlspecialchars($tx['borrow_date']) ?>" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="due_date">
                        Due Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="due_date" name="due_date" required value="<?= htmlspecialchars($tx['due_date']) ?>" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors" />
                </div>
            </div>

            <!-- Return Date & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="return_date">
                        Return Date
                        <span class="text-slate-400 font-normal">(leave empty if not returned)</span>
                    </label>
                    <input type="date" id="return_date" name="return_date" value="<?= htmlspecialchars($tx['return_date'] ?? '') ?>" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="status">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors">
                        <option value="active" <?= $tx['status'] === 'active' ? 'selected' : '' ?>>Active Loan</option>
                        <option value="returned" <?= $tx['status'] === 'returned' ? 'selected' : '' ?>>Returned</option>
                        <option value="overdue" <?= $tx['status'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors resize-none" placeholder="Additional notes..."><?= htmlspecialchars($tx['notes'] ?? '') ?></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onClick="return konfirmasiHapus(<?= $id ?>);" class="px-4 py-2.5 rounded-lg text-sm font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                    Delete
                </button>
                <div class="flex items-center gap-3">
                    <a href="/simperpus/menu/management-transaction/" class="px-6 py-2.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" name="update" class="bg-blue-700 hover:bg-blue-700/90 text-white px-6 py-2.5 rounded-lg font-bold text-sm inline-flex items-center gap-2 shadow-lg shadow-blue-700/20 transition-all">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Update Transaction
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>