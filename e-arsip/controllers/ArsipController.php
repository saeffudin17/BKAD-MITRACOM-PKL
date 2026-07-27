<?php
require_once 'config/database.php';

$action = $_GET['action'] ?? '';

// ==========================================
// 1. HALAMAN TAMPILAN (VIEWS)
// ==========================================

if ($page === 'arsip_list') {
    // Tampilkan List Data Arsip
    $stmt = $pdo->query("
        SELECT a.*, i.nama_instansi, b.nomor_bindex, r.nama_rak 
        FROM arsip_sp2d a 
        LEFT JOIN instansi i ON a.id_instansi = i.id 
        LEFT JOIN bindex b ON a.id_bindex = b.id 
        LEFT JOIN rak r ON a.id_rak = r.id 
        WHERE a.is_deleted = 0 
        ORDER BY a.created_at DESC
    ");
    $arsipList = $stmt->fetchAll();
    
    $title = "List Data Arsip SP2D - E-Arsip";
    require 'views/arsip/list.php';
    exit;
}

if ($page === 'arsip_input') {
    // Ambil master data untuk form dropdown
    $instansi = $pdo->query("SELECT id, nama_instansi FROM instansi WHERE status_aktif = 'Aktif' ORDER BY nama_instansi ASC")->fetchAll();
    
    // Ambil master kegiatan
    try {
        $kegiatan_list = $pdo->query("SELECT id, jenis_kegiatan, nama_kegiatan FROM kegiatan WHERE status_aktif = 'Aktif' ORDER BY nama_kegiatan ASC")->fetchAll();
    } catch (Exception $e) {
        $kegiatan_list = [];
    }
    
    // Bindex dan Rak di-join agar user mudah memilih
    $bindex = $pdo->query("
        SELECT b.id, b.nomor_bindex, r.nama_rak, r.id as id_rak 
        FROM bindex b 
        JOIN rak r ON b.id_rak = r.id 
        WHERE r.status = 'Tersedia' 
        ORDER BY b.nomor_bindex ASC
    ")->fetchAll();
    
    // Cek apakah ini mode Edit
    $is_edit = false;
    $arsip_edit = null;
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt_edit = $pdo->prepare("SELECT * FROM arsip_sp2d WHERE id = ?");
        $stmt_edit->execute([$id]);
        $arsip_edit = $stmt_edit->fetch();
        if($arsip_edit) {
            $is_edit = true;
        }
    }
    
    $title = $is_edit ? "Edit Arsip SP2D - E-Arsip" : "Input Arsip SP2D - E-Arsip";
    require 'views/arsip/input.php';
    exit;
}

if ($page === 'arsip_detail') {
    // Tampilkan Detail Data Arsip
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("
        SELECT a.*, i.nama_instansi, b.nomor_bindex, r.nama_rak, u.nama as nama_petugas 
        FROM arsip_sp2d a 
        LEFT JOIN instansi i ON a.id_instansi = i.id 
        LEFT JOIN bindex b ON a.id_bindex = b.id 
        LEFT JOIN rak r ON a.id_rak = r.id 
        LEFT JOIN users u ON a.id_user = u.id 
        WHERE a.id = ? AND a.is_deleted = 0
    ");
    $stmt->execute([$id]);
    $arsip = $stmt->fetch();
    
    if(!$arsip){
        $_SESSION['flash_msg'] = "Arsip tidak ditemukan.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . base_url('index.php?page=arsip_list'));
        exit;
    }
    
    $title = "Detail Arsip SP2D - E-Arsip";
    require 'views/arsip/detail.php';
    exit;
}

// ==========================================
// 2. PROSES AKSI (CRUD & WORKFLOW)
// ==========================================

if ($page === 'arsip_action') {
    
    // Aksi Tambah (Insert)
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_instansi = (int)$_POST['id_instansi'];
        $nomor_sp2d = trim($_POST['nomor_sp2d']);
        $nomor_spm = trim($_POST['nomor_spm']);
        $tanggal_spm = $_POST['tanggal_spm'];
        $jenis_kegiatan = trim($_POST['jenis_kegiatan']);
        $nama_kegiatan = trim($_POST['nama_kegiatan']);
        $tanggal_sp2d = $_POST['tanggal_sp2d'];
        $tanggal_arsip = $_POST['tanggal_arsip'];
        $id_bindex = (int)$_POST['id_bindex'];
        $jumlah_halaman = (int)$_POST['jumlah_halaman'];
        $jumlah_sp2d = (int)$_POST['jumlah_sp2d'];
        $status_arsip = $_POST['status_arsip'] ?? 'Diproses';
        $catatan = trim($_POST['catatan']);
        
        // Cari ID Rak dari Bindex yang dipilih
        $stmt_rak = $pdo->prepare("SELECT id_rak FROM bindex WHERE id = ?");
        $stmt_rak->execute([$id_bindex]);
        $bindex_data = $stmt_rak->fetch();
        $id_rak = $bindex_data ? $bindex_data['id_rak'] : 0;
        
        $id_user = $_SESSION['user_id'];
        
        // --- Tahap 10: Upload PDF ---
        $file_pdf = null;
        if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == 0) {
            $allowed_ext = ['pdf'];
            $file_name = $_FILES['file_pdf']['name'];
            $file_size = $_FILES['file_pdf']['size'];
            $file_tmp  = $_FILES['file_pdf']['tmp_name'];
            
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Validasi format
            if (!in_array($file_ext, $allowed_ext)) {
                $_SESSION['flash_msg'] = "Gagal! Format file harus PDF.";
                $_SESSION['flash_type'] = "error";
                header("Location: " . base_url('index.php?page=arsip_input'));
                exit;
            }
            
            // Validasi ukuran (Maksimal 10 MB = 10 * 1024 * 1024 bytes)
            if ($file_size > 10485760) {
                $_SESSION['flash_msg'] = "Gagal! Ukuran file maksimal 10 MB.";
                $_SESSION['flash_type'] = "error";
                header("Location: " . base_url('index.php?page=arsip_input'));
                exit;
            }
            
            // Ambil Nama Instansi untuk rename file
            $stmt_ins = $pdo->prepare("SELECT nama_instansi FROM instansi WHERE id = ?");
            $stmt_ins->execute([$id_instansi]);
            $instansi_data = $stmt_ins->fetch();
            $nama_instansi = $instansi_data ? $instansi_data['nama_instansi'] : 'Unknown';
            
            // Format rename pdf: [Nomor SP2D].pdf
            // Sanitasi: Ganti garis miring dengan strip agar tidak dianggap folder, lalu hapus karakter aneh.
            $safe_nosp2d = str_replace(['/', '\\'], '-', $nomor_sp2d);
            $safe_nosp2d = preg_replace('/[^A-Za-z0-9 \-_\.]/', '', $safe_nosp2d);
            
            $base_filename = "{$safe_nosp2d}";
            $new_filename = $base_filename . '.pdf';
            
            // Cek apakah file sudah ada, jika ya, tambahkan timestamp
            $upload_dir = 'uploads/';
            if (file_exists($upload_dir . $new_filename)) {
                $new_filename = $base_filename . '-' . time() . '.pdf';
            }
            
            if (move_uploaded_file($file_tmp, $upload_dir . $new_filename)) {
                $file_pdf = $new_filename;
                
                // Jika upload berhasil, kita asumsikan status arsip langsung lompat ke 'PDF Diupload'
                // Namun karena requirement workflow bilang bisa manual, biarkan sesuai inputan dropdown, 
                // atau kita paksa jadi minimal "PDF Diupload". Di sini kita tetap hormati form input.
            } else {
                $_SESSION['flash_msg'] = "Terjadi kesalahan saat mengunggah file ke server.";
                $_SESSION['flash_type'] = "error";
                header("Location: " . base_url('index.php?page=arsip_input'));
                exit;
            }
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO arsip_sp2d 
                (id_instansi, nomor_sp2d, nomor_spm, tanggal_spm, jenis_kegiatan, nama_kegiatan, tanggal_sp2d, tanggal_arsip, id_bindex, id_rak, jumlah_halaman, jumlah_sp2d, status_arsip, catatan, id_user, file_pdf) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_instansi, $nomor_sp2d, $nomor_spm, $tanggal_spm, $jenis_kegiatan, $nama_kegiatan, $tanggal_sp2d, $tanggal_arsip, $id_bindex, $id_rak, $jumlah_halaman, $jumlah_sp2d, $status_arsip, $catatan, $id_user, $file_pdf]);
            
            $_SESSION['flash_msg'] = "Arsip SP2D berhasil ditambahkan.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Tambah', ?)");
            $log->execute([$id_user, "Input arsip SP2D baru: $nomor_sp2d"]);
            
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Nomor SP2D sudah ada. Gunakan nomor yang berbeda.";
            } else {
                $_SESSION['flash_msg'] = "Gagal menambah arsip: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=arsip_list'));
        exit;
    }
    
    // Aksi Edit
    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_POST['id'];
        $id_instansi = (int)$_POST['id_instansi'];
        $nomor_sp2d = trim($_POST['nomor_sp2d']);
        $nomor_spm = trim($_POST['nomor_spm']);
        $tanggal_spm = $_POST['tanggal_spm'];
        $jenis_kegiatan = trim($_POST['jenis_kegiatan']);
        $nama_kegiatan = trim($_POST['nama_kegiatan']);
        $tanggal_sp2d = $_POST['tanggal_sp2d'];
        $tanggal_arsip = $_POST['tanggal_arsip'];
        $id_bindex = (int)$_POST['id_bindex'];
        $jumlah_halaman = (int)$_POST['jumlah_halaman'];
        $jumlah_sp2d = (int)$_POST['jumlah_sp2d'];
        $status_arsip = $_POST['status_arsip'];
        $catatan = trim($_POST['catatan']);
        
        $stmt_rak = $pdo->prepare("SELECT id_rak FROM bindex WHERE id = ?");
        $stmt_rak->execute([$id_bindex]);
        $id_rak = $stmt_rak->fetchColumn() ?: 0;
        
        // Ambil arsip lama
        $stmt_old = $pdo->prepare("SELECT file_pdf FROM arsip_sp2d WHERE id = ?");
        $stmt_old->execute([$id]);
        $old = $stmt_old->fetch();
        $file_pdf = $old ? $old['file_pdf'] : null;
        
        // Cek jika ada upload PDF baru
        if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == 0) {
            $file_ext = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));
            if ($file_ext === 'pdf' && $_FILES['file_pdf']['size'] <= 10485760) {
                $stmt_ins = $pdo->prepare("SELECT nama_instansi FROM instansi WHERE id = ?");
                $stmt_ins->execute([$id_instansi]);
                $nama_ins = $stmt_ins->fetchColumn() ?: 'Unknown';
                
                // Format rename pdf: [Nomor SP2D].pdf
                // Sanitasi: Ganti garis miring dengan strip agar tidak dianggap folder, lalu hapus karakter aneh.
                $safe_nosp2d = str_replace(['/', '\\'], '-', $nomor_sp2d);
                $safe_nosp2d = preg_replace('/[^A-Za-z0-9 \-_\.]/', '', $safe_nosp2d);
                
                $base_filename = "{$safe_nosp2d}";
                $new_filename = $base_filename . '-' . time() . '.pdf';
                
                if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], 'uploads/' . $new_filename)) {
                    if ($file_pdf && file_exists('uploads/' . $file_pdf)) unlink('uploads/' . $file_pdf);
                    $file_pdf = $new_filename;
                }
            }
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE arsip_sp2d SET 
                id_instansi=?, nomor_sp2d=?, nomor_spm=?, tanggal_spm=?, jenis_kegiatan=?, nama_kegiatan=?, 
                tanggal_sp2d=?, tanggal_arsip=?, id_bindex=?, id_rak=?, 
                jumlah_halaman=?, jumlah_sp2d=?, status_arsip=?, catatan=?, file_pdf=? 
                WHERE id=?");
            $stmt->execute([$id_instansi, $nomor_sp2d, $nomor_spm, $tanggal_spm, $jenis_kegiatan, $nama_kegiatan, $tanggal_sp2d, $tanggal_arsip, $id_bindex, $id_rak, $jumlah_halaman, $jumlah_sp2d, $status_arsip, $catatan, $file_pdf, $id]);
            
            $_SESSION['flash_msg'] = "Arsip SP2D berhasil diupdate.";
            $_SESSION['flash_type'] = "success";
            
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Mengupdate arsip SP2D: $nomor_sp2d"]);
        } catch (PDOException $e) {
            $_SESSION['flash_msg'] = "Gagal mengupdate arsip: " . $e->getMessage();
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=arsip_list'));
        exit;
    }
    
    // Aksi Hapus (Soft Delete / Pindah ke Recycle Bin)
    if ($action === 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt_get = $pdo->prepare("SELECT nomor_sp2d FROM arsip_sp2d WHERE id = ?");
        $stmt_get->execute([$id]);
        $data_arsip = $stmt_get->fetch();
        
        if($data_arsip){
            $stmt = $pdo->prepare("UPDATE arsip_sp2d SET is_deleted = 1 WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['flash_msg'] = "Arsip berhasil dipindahkan ke Recycle Bin.";
            $_SESSION['flash_type'] = "success";
            
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
            $log->execute([$_SESSION['user_id'], "Soft Delete arsip SP2D: " . $data_arsip['nomor_sp2d']]);
        }
        
        header("Location: " . base_url('index.php?page=arsip_list'));
        exit;
    }
    
    // Aksi Download PDF
    if ($action === 'download_pdf' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt = $pdo->prepare("SELECT file_pdf, nomor_sp2d FROM arsip_sp2d WHERE id = ?");
        $stmt->execute([$id]);
        $arsip = $stmt->fetch();
        
        if ($arsip && !empty($arsip['file_pdf'])) {
            $filepath = 'uploads/' . $arsip['file_pdf'];
            
            if (file_exists($filepath)) {
                // Log Activity
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Download', ?)");
                $log->execute([$_SESSION['user_id'], "Download PDF SP2D: " . $arsip['nomor_sp2d']]);
                
                // Force Download Header
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                readfile($filepath);
                exit;
            } else {
                $_SESSION['flash_msg'] = "File PDF tidak ditemukan di server.";
                $_SESSION['flash_type'] = "error";
            }
        } else {
            $_SESSION['flash_msg'] = "Arsip belum memiliki lampiran PDF.";
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=arsip_list'));
        exit;
    }
}
