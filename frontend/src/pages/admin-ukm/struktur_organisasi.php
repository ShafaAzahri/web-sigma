<?php
session_start();
if (!isset($_SESSION['id_ukm'])) {
    header('Location: /index.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struktur Organisasi UKM</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Sidebar Container -->
    <div id="sidebar-container"></div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Struktur Organisasi</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            <button class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-divisi">
                                <i class="fas fa-sitemap"></i> Kelola Divisi
                            </button>
                            <button class="btn btn-info mr-2" data-toggle="modal" data-target="#modal-jabatan">
                                <i class="fas fa-tasks"></i> Kelola Jabatan
                            </button>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah" id="add-btn">
                                <i class="fas fa-plus"></i> Tambah Pengurus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="table-struktur" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>NIM</th>
                                            <th>Nama</th>
                                            <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Periode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan diisi oleh JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

   <!-- Modal Tambah Pengurus -->
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pengurus</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-tambah" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <select class="form-control" id="nim" name="nim" required>
                                <option value="">Pilih Mahasiswa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_divisi">Divisi</label>
                            <select class="form-control" id="id_divisi" name="id_divisi" required>
                                <option value="">Pilih Divisi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_jabatan_divisi">Jabatan</label>
                            <select class="form-control" id="id_jabatan_divisi" name="id_jabatan_divisi" required>
                                <option value="">Pilih Jabatan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_periode">Periode</label>
                            <select class="form-control" id="id_periode" name="id_periode" required>
                                <option value="">Pilih Periode</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                <label class="custom-file-label" for="foto">Pilih file</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                            <div id="preview-foto" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pengurus -->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Pengurus</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id_struktur" name="id_struktur">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>NIM</label>
                            <input type="text" class="form-control" id="edit_nim" readonly>
                            <input type="hidden" name="nim" id="edit_nim_hidden">
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" id="edit_nama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_divisi">Divisi</label>
                            <select class="form-control" id="edit_id_divisi" name="id_divisi" required>
                                <option value="">Pilih Divisi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_jabatan_divisi">Jabatan</label>
                            <select class="form-control" id="edit_id_jabatan_divisi" name="id_jabatan_divisi" required>
                                <option value="">Pilih Jabatan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_periode">Periode</label>
                            <select class="form-control" id="edit_id_periode" name="id_periode" required>
                                <option value="">Pilih Periode</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_foto">Foto</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="edit_foto" name="foto" accept="image/*">
                                <label class="custom-file-label" for="edit_foto">Pilih file</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                            <div id="edit_preview_foto" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Divisi -->
    <div class="modal fade" id="modal-divisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Kelola Divisi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-divisi">
                        <input type="hidden" id="id_divisi_edit" name="id_divisi">
                        <div class="form-group">
                            <label for="nama_divisi">Nama Divisi</label>
                            <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe_divisi">Tipe</label>
                            <select class="form-control" id="tipe_divisi" name="tipe_divisi" required>
                                <option value="">Pilih Tipe</option>
                                <option value="inti">Inti</option>
                                <option value="divisi">Divisi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Divisi</button>
                    </form>
                    <hr>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped" id="table-divisi">
                            <thead>
                                <tr>
                                    <th>Nama Divisi</th>
                                    <th>Tipe</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Jabatan -->
    <div class="modal fade" id="modal-jabatan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Kelola Jabatan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-jabatan">
                        <input type="hidden" id="id_jabatan_divisi_edit" name="id_jabatan_divisi">
                        <div class="form-group">
                            <label for="jabatan_id_divisi">Divisi</label>
                            <select class="form-control" id="jabatan_id_divisi" name="id_divisi" required>
                                <option value="">Pilih Divisi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_jabatan">Nama Jabatan</label>
                            <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
                        </div>
                        <div class="form-group">
                            <label for="hierarki_jabatan">Hierarki</label>
                            <input type="number" class="form-control" id="hierarki_jabatan" name="hierarki" min="1" max="2" required>
                            <small class="form-text text-muted">Level 1 adalah hierarki tertinggi</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Jabatan</button>
                    </form>
                    <hr>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped" id="table-jabatan">
                            <thead>
                                <tr>
                                    <th>Divisi</th>
                                    <th>Jabatan</th>
                                    <th>Hierarki</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- REQUIRED SCRIPTS -->
<script src="/frontend/src/pages/admin/plugins/jquery/jquery.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="/frontend/src/pages/admin/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script type="module">
    import SidebarManager from '/frontend/src/pages/admin-ukm/js/sidebar.js';
    window.SidebarManager = SidebarManager;
    document.addEventListener('DOMContentLoaded', function() {
        SidebarManager.init();
    });
</script>
<script>
$(document).ready(function () {
    const id_ukm = <?php echo $_SESSION['id_ukm']; ?>;
    
    // Initialize plugins
    bsCustomFileInput.init();

    // Load data awal
    loadStruktur();
    loadMahasiswa();
    loadDivisi();
    loadPeriode();
    loadJabatanAll();

    // Function untuk load mahasiswa

    function loadMahasiswa(currentNim = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_mahasiswa&id_ukm=${id_ukm}${currentNim ? `&current_nim=${currentNim}` : ''}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const select = $('#nim');
                    select.empty();
                    select.append('<option value="">Pilih Mahasiswa</option>');
                    data.forEach(function(item) {
                        select.append(`<option value="${item.nim}">${item.nim} - ${item.nama_lengkap}</option>`);
                    });
                    resolve();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    reject(error);
                }
            });
        });
    }

    // UBAH fungsi loadDivisi menjadi seperti ini:
    function loadDivisi() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/backend/controllers/admin-ukm/divisi.php?id_ukm=${id_ukm}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Update dropdown divisi di semua form yang membutuhkan
                    const selectElements = [
                        '#id_divisi',                 // Form tambah pengurus
                        '#edit_id_divisi',            // Form edit pengurus
                        '#jabatan_id_divisi'          // Form kelola jabatan
                    ];

                    selectElements.forEach(selector => {
                        const select = $(selector);
                        select.empty();
                        select.append('<option value="">Pilih Divisi</option>');
                        data.forEach(item => {
                            select.append(`<option value="${item.id_divisi}">${item.nama_divisi}</option>`);
                        });
                    });

                    // Update tabel divisi
                    const tbody = $('#table-divisi tbody');
                    tbody.empty();
                    data.forEach(item => {
                        tbody.append(`
                            <tr>
                                <td>${item.nama_divisi}</td>
                                <td>${item.tipe_divisi}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-divisi" data-id="${item.id_divisi}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-divisi" data-id="${item.id_divisi}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                    resolve(data);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    reject(error);
                }
            });
        });
    }

// UBAH fungsi loadPeriode menjadi seperti ini:
function loadPeriode() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_periode',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update dropdown periode di form tambah
                const selectTambah = $('#id_periode');
                selectTambah.empty();
                selectTambah.append('<option value="">Pilih Periode</option>');
                
                // Update dropdown periode di form edit
                const selectEdit = $('#edit_id_periode');
                selectEdit.empty();
                selectEdit.append('<option value="">Pilih Periode</option>');
                
                data.forEach(function(item) {
                    const optionHtml = `<option value="${item.id_periode}">${item.tahun_mulai} - ${item.tahun_selesai}</option>`;
                    selectTambah.append(optionHtml);
                    selectEdit.append(optionHtml);
                });
                resolve();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                reject(error);
            }
        });
    });
}

    // Function untuk load jabatan berdasarkan divisi
    function loadJabatanDivisi(id_divisi, targetSelector) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/backend/controllers/admin-ukm/jabatan_divisi.php?id_divisi=${id_divisi}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const select = $(targetSelector);
                select.empty();
                select.append('<option value="">Pilih Jabatan</option>');
                data.forEach(item => {
                    select.append(`<option value="${item.id_jabatan_divisi}">${item.nama_jabatan}</option>`);
                });
                resolve(data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading jabatan:', error);
                reject(error);
            }
        });
    });
}

    // Function untuk load semua jabatan (untuk tabel)
    function loadJabatanAll() {
        $.ajax({
            url: `/backend/controllers/admin-ukm/jabatan_divisi.php?id_ukm=${id_ukm}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data semua jabatan:', data);
                const tbody = $('#table-jabatan tbody');
                tbody.empty();
                
                data.forEach(function(item) {
                    tbody.append(`
                        <tr>
                            <td>${item.nama_divisi}</td>
                            <td>${item.nama_jabatan}</td>
                            <td>${item.hierarki}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-jabatan" data-id="${item.id_jabatan_divisi}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-jabatan" data-id="${item.id_jabatan_divisi}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }

    // Function untuk load struktur organisasi
    function loadStruktur() {
        $.ajax({
            url: `/backend/controllers/admin-ukm/struktur_organisasi.php?id_ukm=${id_ukm}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Data struktur:', response);
                if (response.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                    return;
                }
                
                if (Array.isArray(response)) {
                    populateTable(response);
                } else {
                    console.error('Data yang diterima bukan array:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }

    // Function untuk populate tabel struktur
    function populateTable(data) {
        const tbody = $('#table-struktur tbody');
        tbody.empty();
        data.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td><img src='/frontend/public/assets/${item.foto_path || 'default.png'}' width='50' height='50' class="img-circle" alt='Foto'></td>
                    <td>${item.nim || 'N/A'}</td>
                    <td>${item.nama || 'N/A'}</td>
                    <td>${item.nama_divisi || 'N/A'}</td>
                    <td>${item.nama_jabatan || 'N/A'}</td>
                    <td>${item.periode || 'N/A'}</td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${item.id_struktur}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id_struktur}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Event handler untuk perubahan divisi
    // Form Tambah
    $('#id_divisi').on('change', function() {
        const id_divisi = $(this).val();
        if (id_divisi) {
            loadJabatanDivisi(id_divisi, '#id_jabatan_divisi').then(() => {
                // Refresh tampilan jabatan setelah data dimuat
                console.log('Jabatan berhasil dimuat');
            }).catch(error => {
                console.error('Error loading jabatan:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat data jabatan'
                });
            });
        } else {
            $('#id_jabatan_divisi').empty().append('<option value="">Pilih Jabatan</option>');
        }
    });
    
    

// Form Edit
$('#edit_id_divisi').on('change', function() {
    const id_divisi = $(this).val();
    if (id_divisi) {
        loadJabatanDivisi(id_divisi, '#edit_id_jabatan_divisi');
    } else {
        $('#edit_id_jabatan_divisi').empty().append('<option value="">Pilih Jabatan</option>');
    }
});

    // Form Tambah Pengurus
    $('#form-tambah').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('id_ukm', id_ukm);

        $.ajax({
            url: '/backend/controllers/admin-ukm/struktur_organisasi.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pengurus baru berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modal-tambah').modal('hide');
                        loadStruktur();
                        loadMahasiswa();
                        $('#form-tambah')[0].reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghubungi server'
                });
            }
        });
    });

    // Form Edit Pengurus
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('id_ukm', id_ukm);

        $.ajax({
            url: '/backend/controllers/admin-ukm/struktur_organisasi.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data pengurus berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modal-edit').modal('hide');
                        loadStruktur();
                        $('#form-edit')[0].reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghubungi server'
                });
            }
        });
    });

    // Form Divisi
    $('#form-divisi').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('id_ukm', id_ukm);

        $.ajax({
            url: '/backend/controllers/admin-ukm/divisi.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data divisi berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#form-divisi')[0].reset();
                        loadDivisi();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghubungi server'
                });
            }
        });
    });

    // Form Jabatan
    $('#form-jabatan').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: '/backend/controllers/admin-ukm/jabatan_divisi.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data jabatan berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#form-jabatan')[0].reset();
                        loadJabatanAll();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghubungi server'
                });
            }
        });
    });

    // UBAH event handler Edit Pengurus menjadi seperti ini:
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        
        // Load data divisi dan periode terlebih dahulu
        Promise.all([
            loadDivisi(),
            loadPeriode()
        ]).then(() => {
            // Setelah data divisi dan periode ter-load, ambil data pengurus
            $.ajax({
                url: '/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=' + id,
                method: 'GET',
                success: function(data) {
                    // Set nilai untuk id_struktur, nim, dan nama
                    $('#edit_id_struktur').val(data.id_struktur);
                    $('#edit_nim').val(data.nim);
                    $('#edit_nim_hidden').val(data.nim);
                    $('#edit_nama').val(data.nama_lengkap);
                    
                    // Set nilai untuk divisi
                    $('#edit_id_divisi').val(data.id_divisi);
                    
                    // Load jabatan untuk divisi yang dipilih dan set nilainya
                    loadJabatanDivisi(data.id_divisi, '#edit_id_jabatan_divisi').then(() => {
                        $('#edit_id_jabatan_divisi').val(data.id_jabatan_divisi);
                    });
                    
                    // Set nilai untuk periode
                    $('#edit_id_periode').val(data.id_periode);
                    
                    // Set preview foto jika ada
                    if (data.foto_path) {
                        $('#edit_preview_foto').html(`<img src="/frontend/public/assets/${data.foto_path}" width="100" alt="Preview Foto">`);
                    }
                    
                    // Tampilkan modal
                    $('#modal-edit').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengambil data pengurus'
                    });
                }
            });
        }).catch(error => {
            console.error('Error loading initial data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal memuat data awal'
            });
        });
    });

    // TAMBAHKAN event handler untuk perubahan divisi pada form edit
    $('#edit_id_divisi').on('change', function() {
        const id_divisi = $(this).val();
        if (id_divisi) {
            loadJabatanDivisi(id_divisi).then((data) => {
                const select = $('#edit_id_jabatan_divisi');
                select.empty();
                select.append('<option value="">Pilih Jabatan</option>');
                data.forEach(function(item) {
                    select.append(`<option value="${item.id_jabatan_divisi}">${item.nama_jabatan}</option>`);
                });
            }).catch(error => {
                console.error('Error loading jabatan:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat data jabatan'
                });
            });
        } else {
            $('#edit_id_jabatan_divisi').empty().append('<option value="">Pilih Jabatan</option>');
        }
    });

    // TAMBAHKAN preview foto untuk form edit
    $('#edit_foto').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#edit_preview_foto').html(`<img src="${e.target.result}" width="100" alt="Preview Foto">`);
            }
            reader.readAsDataURL(file);
        }
    });

    // Delete Pengurus
   $(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pengurus akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Data pengurus berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            loadStruktur();
                            loadMahasiswa(); // Reload untuk memperbarui daftar mahasiswa
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Gagal menghapus data pengurus'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghubungi server'
                    });
                }
            });
        }
    });
});

// Edit Divisi
$(document).on('click', '.edit-divisi', function() {
    const id = $(this).data('id');
    $.ajax({
        url: `/backend/controllers/admin-ukm/divisi.php?id_divisi=${id}`,
        method: 'GET',
        success: function(data) {
            $('#id_divisi_edit').val(data.id_divisi);
            $('#nama_divisi').val(data.nama_divisi);
            $('#tipe_divisi').val(data.tipe_divisi);
            $('#deskripsi').val(data.deskripsi);
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal mengambil data divisi'
            });
        }
    });
});

// Delete Divisi
$(document).on('click', '.delete-divisi', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data divisi akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/backend/controllers/admin-ukm/divisi.php?id_divisi=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data divisi berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            loadDivisi();
                            loadJabatanAll();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan'
                        });
                    }
                }
            });
        }
    });
});

// Edit Jabatan
$(document).on('click', '.edit-jabatan', function() {
    const id = $(this).data('id');
    $.ajax({
        url: `/backend/controllers/admin-ukm/jabatan_divisi.php?id_jabatan_divisi=${id}`,
        method: 'GET',
        success: function(data) {
            $('#id_jabatan_divisi_edit').val(data.id_jabatan_divisi);
            $('#jabatan_id_divisi').val(data.id_divisi);
            $('#nama_jabatan').val(data.nama_jabatan);
            $('#hierarki_jabatan').val(data.hierarki);
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal mengambil data jabatan'
            });
        }
    });
});

// Delete Jabatan
$(document).on('click', '.delete-jabatan', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data jabatan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/backend/controllers/admin-ukm/jabatan_divisi.php?id_jabatan_divisi=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data jabatan berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            loadJabatanAll();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan'
                        });
                    }
                }
            });
        }
    });
});

// UBAH event handler reset modal menjadi seperti ini
$('.modal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
    $('#preview-foto, #edit_preview_foto').empty();
    $('.custom-file-label').html('Pilih file');
});

// Preview foto yang dipilih
$('#foto').on('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#preview-foto').html(`<img src="${e.target.result}" width="100" alt="Preview Foto">`);
        }
        reader.readAsDataURL(file);
    }
});

// Global logout function for sidebar
window.logout = function() {
    SidebarManager.logout();
};
});
</script>