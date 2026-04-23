<?php 
include __DIR__ . '/../../koneksi.php';

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : "";
$tipe  = isset($_GET['tipe']) ? $_GET['tipe'] : "";
$redirect = "index.php"; // Ditambahkan untuk memperbaiki notifikasi SweetAlert yang tidak berfungsi

//hapus
if(isset($_GET['id']) && $_GET['id'] !== ''){
    // Mencegah SQL Injection (Best practice keamanan dasar untuk pemula)
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // 1. Ambil data buku untuk mendapatkan nama file gambarnya
    $get_buku = mysqli_query($conn, "SELECT foto_buku FROM buku WHERE id = '$id'");
    $buku_data = mysqli_fetch_assoc($get_buku);
    
    // 2. Hapus data dari database terlebih dahulu
    $delete = mysqli_query($conn, "DELETE FROM buku WHERE id = '$id'");
    
    if($delete) {
        // 3. Jika berhasil dihapus dari DB, baru hapus file gambarnya (jika ada dan bukan default)
        if($buku_data && $buku_data['foto_buku'] && $buku_data['foto_buku'] !== 'default.png') {
            $path_gambar = __DIR__ . '/../../assets/img/book/' . $buku_data['foto_buku'];
            if(file_exists($path_gambar)) {
                unlink($path_gambar); // fungsi PHP untuk menghapus file fisik
            }
        }
        
        header('Location: index.php?pesan=' . urlencode('Buku Berhasil Dihapus!') . '&tipe=success');
        exit;
    }else{
        header('Location: index.php?pesan=' . urlencode('Buku Gagal Dihapus!') . '&tipe=error'); // Typo kata 'Kategori' diperbaiki
        exit;
    }
}

$buku = [];
$sql = mysqli_query($conn, "SELECT * FROM buku");
while($data = mysqli_fetch_assoc($sql)) {
    $buku[] = $data;
}
include __DIR__ . '/../../layouts/layout.php'; 

?>
<div class="p-2 pb-0">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Book Management</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage library catalog, inventory stock, and
                availability.</p>
        </div>
        <a href="/manajemen-perpustakaan/menu/management-book/add.php"
            class="bg-blue-700 hover:bg-blue-700/90 text-white px-6 py-2.5 rounded-lg font-bold text-sm inline-flex items-center gap-2 shadow-lg shadow-blue-700/20 transition-all">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Add New Book
        </a>
    </div>
    <!-- Filters -->
    <div
        class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col lg:flex-row gap-4">
        <div class="flex-1 relative">
            <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-blue-700/50 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400"
                placeholder="Search by Title, Author or ISBN..." type="text" />
        </div>
        <div class="w-full lg:w-48">
            <select
                class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-blue-700/50 text-sm text-slate-900 dark:text-slate-100">
                <option>All Categories</option>
                <option>Fiction</option>
                <option>Science</option>
                <option>Philosophy</option>
                <option>Biography</option>
            </select>
        </div>
        <div class="w-full lg:w-48">
            <select
                class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-blue-700/50 text-sm text-slate-900 dark:text-slate-100">
                <option>Stock Status</option>
                <option>In Stock</option>
                <option>Low Stock</option>
                <option>Out of Stock</option>
            </select>
        </div>
        <button
            class="px-4 py-2.5 text-slate-500 hover:text-blue-700 dark:hover:text-blue-700 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium">
            <span class="material-symbols-outlined text-[20px]">tune</span>
            More Filters
        </button>
    </div>
</div>
<!-- Table Section -->
<div class="p-2">
    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-bottom border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Buku</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pengarang</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tahun terbit</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stock Status
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <!-- Row 1 - High Stock -->
                     <?php foreach($buku as $book): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 bg-slate-100 rounded shadow-sm flex-shrink-0 overflow-hidden">
                                    <img alt="Book Cover" class="w-full h-full object-cover"
                                        data-alt="Cover of a minimal book title The Silent Patient"
                                        src="<?= '../../assets/img/book/' . $book['foto_buku'] ?>" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white"><?= $book['nama_buku'] ?></p>
                                    <!-- <p class="text-xs text-slate-500 mt-0.5">ISBN: 978-1250301697</p> -->
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700 dark:text-slate-300"><?= $book['pengarang'] ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400"><?= $book['nama_kategori'] ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400"><?= $book['tahun_terbit'] ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full <?= $book['stok'] <= 5 ? ' bg-red-400' : ' bg-emerald-500'?>"></div>
                                <p class="text-sm font-semibold <?= $book['stok'] <= 5 ? ' text-red-400' : ' text-emerald-500'?>"><?= $book['stok'] ?> Available</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="edit.php?id=<?= $book['id'] ?>"
                                    class="p-2 text-slate-400 hover:text-blue-700 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                <button onclick="return konfirmasiHapus(<?= $book['id']; ?>);"
                                    class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div
            class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-slate-500 dark:text-slate-400">Showing <span
                    class="font-bold text-slate-900 dark:text-white">1</span> to <span
                    class="font-bold text-slate-900 dark:text-white">5</span> of <span
                    class="font-bold text-slate-900 dark:text-white">128</span> entries</p>
            <div class="flex items-center gap-1">
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-400 hover:bg-white dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-700 text-white font-bold text-sm">1</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 transition-colors text-sm font-medium">2</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 transition-colors text-sm font-medium">3</button>
                <span class="px-2 text-slate-400">...</span>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 transition-colors text-sm font-medium">26</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-400 hover:bg-white dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard Stats Preview -->
<div class="px-2 pb-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div
        class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-4 shadow-sm">
        <div class="w-12 h-12 rounded-lg bg-blue-700/10 text-blue-700 flex items-center justify-center">
            <span class="material-symbols-outlined">book_2</span>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Catalog</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">2,482</p>
        </div>
    </div>
    <div
        class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-4 shadow-sm">
        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <span class="material-symbols-outlined">inventory_2</span>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Stock</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">18,290</p>
        </div>
    </div>
    <div
        class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-4 shadow-sm">
        <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
            <span class="material-symbols-outlined">warning</span>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Low Stock</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">12</p>
        </div>
    </div>
    <div
        class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-4 shadow-sm">
        <div class="w-12 h-12 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
            <span class="material-symbols-outlined">block</span>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Unavailable</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">04</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>