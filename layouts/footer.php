            </main>
        </div>
    </div>
    <script src="../assets/sweetalert2.all.min.js"></script>
    <script src="../../assets/sweetalert2.all.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-menu');
        const closeBtn = document.getElementById('close-menu');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        openBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
    <script src="/path/ke/assets/js/sweetalert2.all.min.js"></script>

<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Kategori?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace("index.php?id=" + id);
            }
        })
    }

    <?php if (!empty($pesan)): ?>
        Swal.fire({
            title: '<?= $pesan ?>',
            icon: '<?= $tipe ?>',
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true
        }).then(() => {
            const url = new URL(window.location);
            url.searchParams.delete('pesan');
            url.searchParams.delete('tipe');
            window.history.replaceState({}, document.title, url.pathname);
        });
    <?php endif; ?>
</script>
</body>

</html>