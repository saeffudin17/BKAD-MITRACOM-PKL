<?php
require_once 'config/database.php';

// Hak Akses Khusus Admin
if ($_SESSION['user_role'] !== 'Admin') {
    $_SESSION['flash_msg'] = "Hanya Administrator yang dapat melakukan Backup & Restore Database.";
    $_SESSION['flash_type'] = "error";
    header("Location: " . base_url('index.php?page=dashboard'));
    exit;
}

$action = $_GET['action'] ?? '';

if ($page === 'backup_restore') {
    $title = "Backup & Restore Database - E-Arsip";
    require 'views/backup/index.php';
    exit;
}

if ($page === 'backup_action') {
    
    // ---------------------------------------------------------
    // AKSI BACKUP (Generate & Download SQL File)
    // ---------------------------------------------------------
    if ($action === 'backup') {
        try {
            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            $sqlScript = "-- E-Arsip SP2D Database Backup\n";
            $sqlScript .= "-- Waktu Backup: " . date('Y-m-d H:i:s') . "\n\n";
            $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                // Generate CREATE TABLE
                $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
                $sqlScript .= $row[1] . ";\n\n";
                
                // Generate INSERT INTO
                $stmt = $pdo->query("SELECT * FROM `$table`");
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0) {
                    $sqlScript .= "INSERT INTO `$table` VALUES \n";
                    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
                    $insertions = [];
                    foreach ($rows as $data) {
                        $values = [];
                        foreach ($data as $val) {
                            if (is_null($val)) {
                                $values[] = "NULL";
                            } else {
                                $values[] = $pdo->quote($val);
                            }
                        }
                        $insertions[] = "(" . implode(", ", $values) . ")";
                    }
                    $sqlScript .= implode(",\n", $insertions) . ";\n\n";
                }
            }
            
            $sqlScript .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            // Catat Log
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Download', 'Melakukan Backup Database Keseluruhan')");
            $log->execute([$_SESSION['user_id']]);
            
            // Force Download as .sql file
            $backup_file_name = 'backup_earsip_' . date('Y-m-d_H-i-s') . '.sql';
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($sqlScript));
            if (ob_get_length()) {
                ob_clean();
            }
            flush();
            echo $sqlScript;
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['flash_msg'] = "Gagal melakukan backup: " . $e->getMessage();
            $_SESSION['flash_type'] = "error";
            header("Location: " . base_url('index.php?page=backup_restore'));
            exit;
        }
    }
    
    // ---------------------------------------------------------
    // AKSI RESTORE (Upload & Execute SQL File)
    // ---------------------------------------------------------
    if ($action === 'restore' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file_sql']) && $_FILES['file_sql']['error'] == 0) {
            $file_name = $_FILES['file_sql']['name'];
            $file_tmp  = $_FILES['file_sql']['tmp_name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if ($file_ext !== 'sql') {
                $_SESSION['flash_msg'] = "Format file harus .sql!";
                $_SESSION['flash_type'] = "error";
                header("Location: " . base_url('index.php?page=backup_restore'));
                exit;
            }
            
            try {
                // Baca isi file SQL
                $sql_content = file_get_contents($file_tmp);
                
                // Eksekusi secara langsung (PDO exec bisa mengeksekusi multiple statements jika konfigurasinya mengizinkan)
                // Disable FK checks to prevent drop errors during restore
                $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
                $pdo->exec($sql_content);
                $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
                
                $_SESSION['flash_msg'] = "Database berhasil dipulihkan (Restore)!";
                $_SESSION['flash_type'] = "success";
                
                // Catat Log
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', 'Melakukan Restore Database dari file SQL')");
                $log->execute([$_SESSION['user_id']]);
                
            } catch (PDOException $e) {
                // Pastikan kembalikan ke 1 jika terjadi error
                $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
                $_SESSION['flash_msg'] = "Gagal memulihkan database: " . $e->getMessage();
                $_SESSION['flash_type'] = "error";
            }
        } else {
            $_SESSION['flash_msg'] = "Gagal mengupload file database!";
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=backup_restore'));
        exit;
    }
}
