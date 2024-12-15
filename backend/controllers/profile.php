<?php
session_start();
require_once '../config/config.php';
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['username'])) {
        throw new Exception('User not logged in');
    }
        // Debug logging
        error_log("Processing profile update for user: " . $_SESSION['username']);
    
        if (isset($_FILES['profilePicture'])) {
            error_log("File upload details: " . print_r($_FILES['profilePicture'], true));
        }

    $nim = $_SESSION['username'];
    
    // Start transaction
    $pdo->beginTransaction();

    // Get form data
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : null;
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : null;
    $program_studi = isset($_POST['program_studi']) ? trim($_POST['program_studi']) : null;
    $kelas = isset($_POST['kelas']) ? trim($_POST['kelas']) : null;
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : null;
    $no_whatsapp = isset($_POST['no_whatsapp']) ? trim($_POST['no_whatsapp']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    
    // Inisialisasi query update
    $updateFields = [];
    $params = [];

    // Handle file upload terlebih dahulu
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profilePicture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, PNG and GIF are allowed.');
        }
        
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxFileSize) {
            throw new Exception('File is too large. Maximum size is 5MB.');
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $nim . '_' . time() . '.' . $extension;
        $uploadDir = __DIR__ . '/../../frontend/public/assets/profile/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Delete old profile picture
        $stmt = $pdo->prepare("SELECT foto_path FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);
        $oldPhoto = $stmt->fetchColumn();
        
        if ($oldPhoto && file_exists($uploadDir . $oldPhoto) && $oldPhoto !== 'pp.jpg') {
            unlink($uploadDir . $oldPhoto);
        }

        // Upload new file
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            throw new Exception('Failed to upload file.');
        }

        // Update foto_path di database
        $updatePhotoStmt = $pdo->prepare("UPDATE mahasiswa SET foto_path = ? WHERE nim = ?");
        if (!$updatePhotoStmt->execute([$filename, $nim])) {
            throw new Exception('Failed to update photo in database.');
        }
    }

    // Build query untuk field lainnya
    if ($nama !== null) {
        $updateFields[] = 'nama_lengkap = ?';
        $params[] = $nama;
    }
    if ($jenis_kelamin !== null) {
        $updateFields[] = 'jenis_kelamin = ?';
        $params[] = $jenis_kelamin;
    }
    if ($program_studi !== null) {
        $updateFields[] = 'id_program_studi = ?';
        $params[] = $program_studi;
    }
    if ($kelas !== null) {
        $updateFields[] = 'kelas = ?';
        $params[] = $kelas;
    }
    if ($alamat !== null) {
        $updateFields[] = 'alamat = ?';
        $params[] = $alamat;
    }
    if ($no_whatsapp !== null) {
        $updateFields[] = 'no_whatsapp = ?';
        $params[] = $no_whatsapp;
    }
    if ($email !== null) {
        $updateFields[] = 'email = ?';
        $params[] = $email;
    }

    // Update data lainnya jika ada
    if (!empty($updateFields)) {
        $sql = "UPDATE mahasiswa SET " . implode(', ', $updateFields) . " WHERE nim = ?";
        $params[] = $nim;  // Tambahkan nim ke parameter terakhir
        
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute($params)) {
            throw new Exception('Failed to update profile data.');
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Profile updated successfully'
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Profile update error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'details' => debug_backtrace()
    ]);
}
?>