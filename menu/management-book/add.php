<?php include __DIR__ . '/../../layouts/layout.php'; 
include __DIR__ . '/../../koneksi.php';

if(isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $kategori_id = $_POST['kategori_id'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];

    $tambah_data = mysqli_query($conn, 
    "INSERT INTO buku (judul, pengarang, kategori_id, tahun_terbit, stok) 
    VALUES ('$judul', '$pengarang', '$kategori_id', '$tahun_terbit', '$stok')");

    if($tambah_data){
        $pesan = "Buku berhasil ditambahkan!";
        $tipe = "success";
        $redirect = "index.php";
    }else{
        $pesan = "Gagal menambahkan buku!";
        $tipe = "error";
        $redirect = "add.php";}
}

$getkategori = mysqli_query($conn, 
"SELECT id, nama_kategori FROM kategori WHERE status = 'active'");
?>
<div class="p-2 max-w-2xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="/simperpus/menu/management-book/" class="hover:text-blue-700 transition-colors">Books</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-slate-900 dark:text-white font-medium">Add New Book</span>
    </div>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Add New Book</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Fill in the details to add a new book to the catalog.</p>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-6">
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">

            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="title">
                    Nama Buku <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="judul" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors"
                    placeholder="e.g. Atomic Habits" />
            </div>

            <!-- <div class="mb-4">
                <label>Cover Buku</label>
                <input type="file" name="foto_buku" class="w-full border p-2" accept="image/*">
                <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, JPEG (Max 2MB)</p>
            </div> -->

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="author">
                    Pengarang <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="author" name="pengarang" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors"
                    placeholder="e.g. James Clear" />
            </div>

            <!-- Category & Year (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="category">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" name="kategori_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors">
                        <option value="">Select category...</option>
                        <?php while($data = mysqli_fetch_assoc($getkategori)) :?>
                            <option value="<?= $data['id']; ?>"><?= $data['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="year">
                        Tahun Terbit <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="year" name="tahun_terbit" required min="1000" max="2099"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors"
                        placeholder="e.g. 2018" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="stock">
                    Stok <span class="text-rose-500">*</span>
                </label>
                <input type="number" id="stock" name="stok" required min="0"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors"
                    placeholder="e.g. 10" />
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                <a href="/simperpus/menu/management-book/"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Cancel
                </a>
                <button type="submit" name="tambah"
                    class="bg-blue-700 hover:bg-blue-700/90 text-white px-6 py-2.5 rounded-lg font-bold text-sm inline-flex items-center gap-2 shadow-lg shadow-blue-700/20 transition-all">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Save Book
                </button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>