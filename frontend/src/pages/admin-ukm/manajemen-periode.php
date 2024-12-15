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
    <title>Manajemen Pendaftaran UKM</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/frontend/src/pages/admin/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }

    .timeline > div {
        position: relative;
        margin-right: 10px;
        margin-bottom: 15px;
    }

    .time-label {
        margin-bottom: 20px;
    }

    .timeline-item {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 3px;
        margin-left: 60px;
        margin-right: 15px;
        margin-bottom: 15px;
        padding: 10px;
        background: #fff;
    }

    .timeline-item > .time {
        float: right;
        padding: 10px;
        color: #999;
    }

    .timeline-item > .timeline-header {
        margin: 0;
        color: #555;
        border-bottom: 1px solid #f4f4f4;
        padding: 10px;
        font-size: 16px;
        line-height: 1.1;
    }

    .timeline-item > .timeline-body {
        padding: 10px;
    }

    .timeline-item > .timeline-body ul {
        margin: 0;
        padding-left: 20px;
    }

    .timeline > div > i {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #fff;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }
</style>

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
                        <h1>Manajemen Pendaftaran</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Periode Pendaftaran Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Periode Pendaftaran</h3>
                    </div>
                    <div class="card-body">
                        <form id="form-periode">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Buka</label>
                                        <input type="datetime-local" class="form-control" name="tanggal_buka" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Tutup</label>
                                        <input type="datetime-local" class="form-control" name="tanggal_tutup" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Batas Waktu Tahap 1 (hari)</label>
                                        <input type="number" class="form-control" name="batas_waktu_tahap1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Batas Waktu Tahap 2 (hari)</label>
                                        <input type="number" class="form-control" name="batas_waktu_tahap2" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Batas Waktu Tahap 3 (hari)</label>
                                        <input type="number" class="form-control" name="batas_waktu_tahap3" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Periode</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Periode Aktif</h3>
                    </div>
                    <div class="card-body">
                        <div id="periode-info">
                            <div class="alert alert-info" id="no-periode" style="display: none;">
                                Belum ada periode pendaftaran yang aktif.
                            </div>
                            <div id="periode-details" style="display: none;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-box bg-info">
                                            <div class="info-box-content">
                                                <span class="info-box-text">Tahap 1</span>
                                                <span class="info-box-text mt-2" id="tahap1-date"></span>
                                                <span class="info-box-text" id="tahap1-duration"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-warning">
                                            <div class="info-box-content">
                                                <span class="info-box-text">Tahap 2</span>
                                                <span class="info-box-text mt-2" id="tahap2-date"></span>
                                                <span class="info-box-text" id="tahap2-duration"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-success">
                                            <div class="info-box-content">
                                                <span class="info-box-text">Tahap 3</span>
                                                <span class="info-box-text mt-2" id="tahap3-date"></span>
                                                <span class="info-box-text" id="tahap3-duration"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="timeline">
                                            <div id="timeline-items">
                                                <!-- Timeline items will be inserted here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


<!-- Required Scripts -->
<script src="/frontend/src/pages/admin/plugins/jquery/jquery.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/frontend/src/pages/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/frontend/src/pages/admin/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <!-- Import Sidebar Manager -->
    <script type="module">
        import SidebarManager from '/frontend/src/pages/admin-ukm/js/sidebar.js';
        window.SidebarManager = SidebarManager;

        document.addEventListener('DOMContentLoaded', function() {
            SidebarManager.init();
        });
    </script>

        <script src="/frontend/src/pages/admin-ukm/js/pendaftaran.js"></script>
        <script>
             // Global logout function for sidebar
            window.logout = function() {
                SidebarManager.logout();
            };
        </script>