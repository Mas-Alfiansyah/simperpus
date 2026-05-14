<?php
include __DIR__ . '/../../koneksi.php';

// pesan & tipe bisa datang dari query string
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : "";
$tipe  = isset($_GET['tipe']) ? $_GET['tipe'] : "";

// Logika Hapus — jika id ditemukan, lakukan delete dan segera redirect
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete = mysqli_query($conn, "DELETE FROM anggota WHERE id = $id");
    if ($delete) {
        header('Location: index.php?pesan=' . urlencode('Anggota Berhasil Dihapus!') . '&tipe=success');
        exit;
    } else {
        header('Location: index.php?pesan=' . urlencode('Gagal Menghapus Anggota!') . '&tipe=error');
        exit;
    }
}

$daftar_anggota = [];
$query = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id DESC");
while ($data = mysqli_fetch_assoc($query)) {
    $daftar_anggota[] = $data;
}

// Menghitung statistik anggota
$total_anggota = count($daftar_anggota);
$aktif_anggota = 0;
$suspend_anggota = 0;
$baru_anggota = 0;

$bulan_ini = date('Y-m');

foreach ($daftar_anggota as $anggota) {
    if ($anggota['status'] === 'active') {
        $aktif_anggota++;
    } elseif ($anggota['status'] === 'suspended') {
        $suspend_anggota++;
    }
    if (substr($anggota['join_date'], 0, 7) === $bulan_ini) {
        $baru_anggota++;
    }
}

include __DIR__ . '/../../layouts/layout.php';
?>
<header
    class="h-16 border-b rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between px-8 mb-6">
    <div class="flex items-center gap-4">
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Daftar Anggota</h2>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative hidden sm:block">
            <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
            <input
                class="pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm w-64 focus:ring-2 focus:ring-blue-700"
                placeholder="Cari nama atau email..." type="text" />
        </div>
        <a href="add.php"
            class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-blue-700/90 transition-all">
            <span class="material-symbols-outlined text-sm">person_add</span>
            <span>Tambah Anggota</span>
        </a>
    </div>
</header>
<!-- Dashboard Content -->
<div class="p-2 overflow-auto">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-symbols-outlined text-blue-700 bg-blue-700/10 p-2 rounded-lg">group</span>
                <span class="text-emerald-500 text-xs font-bold">Total</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Anggota</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($total_anggota) ?></h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span
                    class="material-symbols-outlined text-emerald-500 bg-emerald-500/10 p-2 rounded-lg">how_to_reg</span>
                <span class="text-emerald-500 text-xs font-bold">Aktif</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Anggota Aktif</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($aktif_anggota) ?></h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-symbols-outlined text-amber-500 bg-amber-500/10 p-2 rounded-lg">person_off</span>
                <span class="text-rose-500 text-xs font-bold">Suspend</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Ditangguhkan</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($suspend_anggota) ?></h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-symbols-outlined text-indigo-500 bg-indigo-500/10 p-2 rounded-lg">verified</span>
                <span class="text-emerald-500 text-xs font-bold">Baru</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Bulan Ini</p>
            <h3 class="text-2xl font-bold mt-1"><?= number_format($baru_anggota) ?></h3>
        </div>
    </div>
    <!-- Table Section -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div
            class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold">Data Anggota</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola informasi dan status keanggotaan
                    perpustakaan.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead
                    class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4">Tanggal Gabung</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    <?php if (empty($daftar_anggota)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                Belum ada data anggota.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daftar_anggota as $index => $anggota): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white"><?= $index + 1 ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 flex items-center justify-center text-xs font-bold">
                                            <?= strtoupper(substr($anggota['full_name'], 0, 1)) ?>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($anggota['full_name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($anggota['email']) ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($anggota['phone'] ?? '-') ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400"><?= date('d M Y', strtotime($anggota['join_date'])) ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($anggota['status'] === 'active'): ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Aktif
                                        </span>
                                    <?php elseif ($anggota['status'] === 'suspended'): ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400">
                                            Ditangguhkan
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                            Tidak Aktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="edit.php?id=<?= $anggota['id'] ?>"
                                            class="p-2 text-slate-400 hover:text-blue-700 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <button type="button" onClick="return konfirmasiHapus(<?= $anggota['id'] ?>);"
                                            class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
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