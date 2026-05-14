<?php
include __DIR__ . '/../../layouts/layout.php';
include __DIR__ . '/../../koneksi.php';

$id = intval($_GET['id'] ?? 0);
$query = mysqli_query($conn, "SELECT * FROM anggota WHERE id = $id");
$anggota = mysqli_fetch_array($query);

if (!$anggota) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $join_date = mysqli_real_escape_string($conn, $_POST['join_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $update_query = mysqli_query($conn, "UPDATE anggota SET full_name='$full_name', email='$email', phone='$phone', join_date='$join_date', status='$status', address='$address' WHERE id=$id");

    if ($update_query) {
        $pesan = "Data Anggota Berhasil Diupdate!";
        $tipe  = "success";
        $redirect = 'index.php';
    } else {
        $pesan = "Data Anggota Gagal Diupdate!";
        $tipe  = "error";
        $redirect = 'edit.php?id=' . $id;
    }
}
?>
<div class="p-2 max-w-2xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="/simperpus/menu/management-member/"
            class="hover:text-blue-700 transition-colors">Members</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-slate-900 dark:text-white font-medium">Edit Member</span>
    </div>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Member</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Update the member's information below.</p>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-6">
        <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $id ?>" />

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="full_name">
                    Full Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="full_name" name="full_name" required value="<?= htmlspecialchars($anggota['full_name']) ?>"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors" />
            </div>

            <!-- Email & Phone (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="email">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($anggota['email']) ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="phone">
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($anggota['phone'] ?? '') ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors" />
                </div>
            </div>

            <!-- Join Date & Status (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5"
                        for="join_date">
                        Join Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="join_date" name="join_date" required value="<?= htmlspecialchars($anggota['join_date']) ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="status">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors">
                        <option value="active" <?= ($anggota['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($anggota['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        <option value="suspended" <?= ($anggota['status'] === 'suspended') ? 'selected' : '' ?>>Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="address">
                    Address
                </label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors resize-none"
                    placeholder="Enter full address..."><?= htmlspecialchars($anggota['address'] ?? '') ?></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onClick="return konfirmasiHapus(<?= $id ?>);"
                    class="px-4 py-2.5 rounded-lg text-sm font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                    Delete Member
                </button>
                <div class="flex items-center gap-3">
                    <a href="/simperpus/menu/management-member/"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" name="update"
                        class="bg-blue-700 hover:bg-blue-700/90 text-white px-6 py-2.5 rounded-lg font-bold text-sm inline-flex items-center gap-2 shadow-lg shadow-blue-700/20 transition-all">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Update Member
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>