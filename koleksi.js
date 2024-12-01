// Mendapatkan elemen modal dan tombol
var modal = document.getElementById("modalTambahBuku");
var btn = document.getElementById("tambahBukuBtn");
var span = document.getElementById("closeModal");

// Ketika tombol 'Tambah Buku' diklik, tampilkan modal
btn.onclick = function() {
    modal.style.display = "block";
}

// Ketika tombol 'X' di klik, sembunyikan modal
span.onclick = function() {
    modal.style.display = "none";
}

// Ketika klik di luar modal, sembunyikan modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Mengirim data form ke server dengan AJAX
$(document).ready(function() {
    $('#formTambahBuku').on('submit', function(e) {
        e.preventDefault(); // Mencegah form dari submit biasa

        var formData = $(this).serialize(); // Mengambil data form

        $.ajax({
            url: 'tambah_buku.php', // Script untuk memproses data
            type: 'POST',
            data: {
                id_buku: $('#id_buku').val(),
                id_perpustakaan: $('#id_perpustakaan').val(), // Menambahkan id_perpustakaan
                judul_buku: $('#judul_buku').val(),
                pengarang: $('#pengarang').val(),
                penerbit: $('#penerbit').val(),
                tahun_terbit: $('#tahun_terbit').val(),
                jumlah_buku: $('#jumlah_buku').val(),
            },
            success: function(response) {
                alert('Buku berhasil ditambahkan');
                modal.style.display = "none";
        
                // Menambahkan buku yang baru ditambahkan ke tabel
                $('#book-table-body').append(`
                    <tr>
                        <td>${$('#id_buku').val()}</td>
                        <td>${$('#judul_buku').val()}</td>
                        <td>${$('#pengarang').val()}</td>
                        <td>${$('#penerbit').val()}</td>
                        <td>${$('#tahun_terbit').val()}</td>
                        <td>${$('#jumlah_buku').val()}</td>
                        <td>
                            <a href="#" class="edit-btn">Edit</a>
                            <a href="#" class="delete-btn">Hapus</a>
                        </td>
                    </tr>
                `);
        
                // Reset form input
                $('#formTambahBuku')[0].reset();
            },
            error: function() {
                alert('Terjadi kesalahan, coba lagi');
            }
        });
    });
});
