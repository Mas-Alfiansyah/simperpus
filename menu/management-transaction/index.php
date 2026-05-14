<?php
include __DIR__ . '/../../koneksi.php';

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : "";
$tipe  = isset($_GET['tipe']) ? $_GET['tipe'] : "";
$redirect = 'index.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete = mysqli_query($conn, "DELETE FROM peminjam WHERE id = $id");
    if ($delete) {
        header('Location: index.php?pesan=' . urlencode('peminjam Berhasil Dihapus!') . '&tipe=success');
        exit;
    } else {
        header('Location: index.php?pesan=' . urlencode('Gagal Menghapus peminjam!') . '&tipe=error');
        exit;
    }
}

$daftar_peminjam = [];
$query = mysqli_query($conn, "SELECT peminjam.*, anggota.full_name, buku.judul FROM peminjam JOIN anggota ON peminjam.anggota_id = anggota.id JOIN buku ON peminjam.buku_id = buku.id ORDER BY peminjam.id DESC");
if ($query) {
    while ($data = mysqli_fetch_assoc($query)) {
        $daftar_peminjam[] = $data;
    }
}

// Statistik
$total_peminjam = count($daftar_peminjam);
$aktif_loan = 0;
$returned_loan = 0;
$overdue_loan = 0;

foreach ($daftar_peminjam as $tx) {
    if ($tx['status'] === 'active') {
        $aktif_loan++;
    } elseif ($tx['status'] === 'returned') {
        $returned_loan++;
    } elseif ($tx['status'] === 'overdue') {
        $overdue_loan++;
    }
}

include __DIR__ . '/../../layouts/layout.php';
?>
<header class="h-16 border-b rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between px-8 mb-6">
    <div class="flex items-center gap-4">
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Daftar peminjam Peminjaman</h2>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative hidden sm:block">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
            <input class="pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm w-64 focus:ring-2 focus:ring-blue-700" placeholder="Cari peminjam atau buku..." type="text" />
        </div>
        <a href="add.php" class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-blue-700/90 transition-all">
            <span class="material-symbols-outlined text-sm">add_box</span>
            <span>Tambah peminjam</span>
        </a>
    </div>
</header>

<div class="p-2 overflow-auto">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-symbols-outlined text-blue-700 bg-blue-700/10 p-2 rounded-lg">receipt_long</span>
                <span class="text-blue-700 text-xs font-bold">Total</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total peminjam</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($total_peminjam) ?></h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-symbols-outlined text-amber-500 bg-amber-500/10 p-2 rounded-lg">bookmark</span>
                <span class="text-amber-500 text-xs font-bold">Active</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Pinjaman Aktif</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($aktif_loan) ?></h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-symbols-outlined text-emerald-500 bg-emerald-500/10 p-2 rounded-lg">fact_check</span>
                <span class="text-emerald-500 text-xs font-bold">Returned</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Sudah Dikembalikan</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($returned_loan) ?></h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-symbols-outlined text-rose-500 bg-rose-500/10 p-2 rounded-lg">warning</span>
                <span class="text-rose-500 text-xs font-bold">Overdue</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Terlambat</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($overdue_loan) ?></h3>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold">Data Peminjaman</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola catatan peminjam peminjaman dan pengembalian buku.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Judul Buku</th>
                        <th class="px-6 py-4">Tgl Pinjam</th>
                        <th class="px-6 py-4">Jatuh Tempo</th>
                        <th class="px-6 py-4">Tgl Kembali</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php if (empty($daftar_peminjam)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                Belum ada data peminjam.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daftar_peminjam as $index => $tx): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white"><?= $index + 1 ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($tx['full_name']) ?></td>
                                <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-300"><?= htmlspecialchars($tx['judul']) ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400"><?= date('d M Y', strtotime($tx['borrow_date'])) ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400"><?= date('d M Y', strtotime($tx['due_date'])) ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    <?= !empty($tx['return_date']) ? date('d M Y', strtotime($tx['return_date'])) : '-' ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($tx['status'] === 'returned'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Returned
                                        </span>
                                    <?php elseif ($tx['status'] === 'overdue'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400">
                                            Overdue
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                            Active
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="edit.php?id=<?= $tx['id'] ?>" class="p-2 text-slate-400 hover:text-blue-700 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <button type="button" onClick="return konfirmasiHapus(<?= $tx['id'] ?>);" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>