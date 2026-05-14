<?php
include __DIR__ . '/../../layouts/layout.php';
include __DIR__ . '/../../koneksi.php';

if (isset($_POST['create'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $join_date = mysqli_real_escape_string($conn, $_POST['join_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $query = mysqli_query($conn, "INSERT INTO anggota (full_name, email, phone, join_date, status, address) VALUES ('$full_name', '$email', '$phone', '$join_date', '$status', '$address')");
    if ($query) {
        $pesan = "Data Anggota Berhasil Ditambahkan!";
        $tipe  = "success";
        $redirect = 'index.php';
    } else {
        $pesan = "Data Anggota Gagal Ditambahkan!";
        $tipe  = "error";
        $redirect = 'add.php';
    }
}
?>
<div class="p-2 max-w-2xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="/simperpus/menu/management-member/"
            class="hover:text-blue-700 transition-colors">Members</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-slate-900 dark:text-white font-medium">Add New Member</span>
    </div>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Add New Member</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Fill in the details to register a new library member.</p>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-6">
        <form action="" method="POST" class="space-y-6">

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="full_name">
                    Full Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="full_name" name="full_name" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors"
                    placeholder="e.g. Budi Santoso" />
            </div>

            <!-- Email & Phone (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="email">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors"
                        placeholder="name@email.com" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="phone">
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors"
                        placeholder="+62 8xx-xxxx-xxxx" />
                </div>
            </div>

            <!-- Join Date & Status (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5"
                        for="join_date">
                        Join Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="join_date" name="join_date" required value="<?= date('Y-m-d') ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="status">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
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
                    placeholder="Enter full address..."></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                <a href="/simperpus/menu/management-member/"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Cancel
                </a>
                <button type="submit" name="create"
                    class="bg-blue-700 hover:bg-blue-700/90 text-white px-6 py-2.5 rounded-lg font-bold text-sm inline-flex items-center gap-2 shadow-lg shadow-blue-700/20 transition-all">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Save Member
                </button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>