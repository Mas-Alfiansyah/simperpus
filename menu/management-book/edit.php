<?php include __DIR__ . '/../../layouts/layout.php';
include __DIR__ . '/../../koneksi.php';

$id = $_GET['id'];
$sql = mysqli_query($conn, "SELECT * FROM buku WHERE id = $id");
$buku = mysqli_fetch_assoc($sql);


$sqlkategori = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori WHERE status = 'active'");

if(isset($_POST['edit'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $kategori_id = $_POST['kategori_id'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];

    $edit = mysqli_query($conn, "
    UPDATE buku SET judul='$judul', pengarang='$pengarang', kategori_id='$kategori_id', 
    tahun_terbit='$tahun_terbit', stok='$stok' WHERE id = $id");

    if($edit){
        $pesan = "Buku berhasil diupdate!";
        $tipe = "success";
        $redirect = "index.php";
    }else{
        $pesan = "Gagal mengupdate buku!";
        $tipe = "error";
        $redirect = "edit.php?id=$id";
    }
}



?>
<div class="p-2 max-w-2xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="/simperpus/menu/management-book/" class="hover:text-blue-700 transition-colors">Books</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-slate-900 dark:text-white font-medium">Edit Book</span>
    </div>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Book</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Update book information in the catalog.</p>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-6">
        <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>" />

            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="title">
                    Nama Buku <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="judul" required value="<?= $buku['judul'] ?>"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors" />
            </div>

            <!-- Author & ISBN (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="author">
                        Pengarang <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="author" name="pengarang" required value="<?= $buku['pengarang'] ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors" />
                </div>
                <!-- <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="isbn">
                        ISBN <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="isbn" name="isbn" required value="978-0735211292"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors" />
                </div> -->
            </div>

            <!-- Category & Year (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="category">
                        Category <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" name="kategori_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors">
                        <?php while($kat = mysqli_fetch_assoc($sqlkategori)) : ?>
                        <option value="<?= $kat['id'] ?>"><?= $kat['nama_kategori'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="year">
                        Tahun terbit <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="year" name="tahun_terbit" required min="1000" max="2099" value="<?= $buku['tahun_terbit'] ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors" />
                </div>
            </div>

            <!-- Publisher & Stock (2 cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5"
                        for="publisher">
                        Publisher
                    </label>
                    <input type="text" id="publisher" name="publisher" value="Avery"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors" />
                </div> -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="stock">
                        Stock <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stok" required min="0" value="<?= $buku['stok'] ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 transition-colors" />
                </div>
            </div>

            <!-- Description -->
            <!-- <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5" for="description">
                    Description
                </label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-700/50 focus:border-blue-700 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 transition-colors resize-none"
                    placeholder="Brief description of the book...">No matter your goals, Atomic Habits offers a proven framework for improving every day.</textarea>
            </div> -->

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button"
                    class="px-4 py-2.5 rounded-lg text-sm font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                    Delete Book
                </button>
                <div class="flex items-center gap-3">
                    <a href="/simperpus/menu/management-book/"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" name="edit"
                        class="bg-blue-700 hover:bg-blue-700/90 text-white px-6 py-2.5 rounded-lg font-bold text-sm inline-flex items-center gap-2 shadow-lg shadow-blue-700/20 transition-all">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Update Book
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>