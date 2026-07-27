<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
  </ol>
</nav>

<!-- Style Tambahan Khusus Dashboard -->
<style>
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #3f37c9, #4895ef);
        border-radius: 20px;
        color: white;
        padding: 2.5rem 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.2);
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
        animation: spin 25s linear infinite;
        pointer-events: none;
    }
    
    @keyframes spin { 100% { transform: rotate(360deg); } }

    /* Stats Cards */
    .card-stat {
        border-radius: 18px;
        border: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        position: relative;
        z-index: 1;
        color: white;
    }
    
    .card-stat:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .card-stat::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(180deg, rgba(255,255,255,0.2) 0%, transparent 100%);
        z-index: -1;
    }

    /* Gradients */
    .grad-primary { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .grad-success { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .grad-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
    .grad-info { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); }
    .grad-secondary { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); }
    
    .icon-overlay {
        position: absolute;
        right: -10px;
        bottom: -15px;
        font-size: 6.5rem;
        opacity: 0.2;
        transform: rotate(-15deg);
        transition: all 0.5s ease;
        z-index: 0;
    }
    
    .card-stat:hover .icon-overlay {
        transform: rotate(0deg) scale(1.1);
        opacity: 0.35;
    }
    
    .dashboard-title {
        font-weight: 800;
        letter-spacing: -0.5px;
    }
    
    .glass-panel {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    
    .stat-value {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1px;
        text-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: relative;
        z-index: 2;
    }
    
    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1px;
        opacity: 0.9;
        text-transform: uppercase;
        position: relative;
        z-index: 2;
    }
    
    @media (max-width: 768px) {
        .welcome-banner {
            padding: 1.5rem 1.2rem !important;
        }
        .dashboard-title {
            font-size: 1.4rem !important;
        }
        .stat-value {
            font-size: 2rem !important;
        }
        .icon-overlay {
            font-size: 5rem;
            right: -5px;
            bottom: -5px;
        }
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner mb-4 d-flex align-items-center justify-content-between">
    <div class="position-relative" style="z-index: 2;">
        <h3 class="dashboard-title mb-1" style="font-size: 1.8rem;">Selamat datang kembali, <?= htmlspecialchars($_SESSION['user_nama'] ?? 'User') ?>! 👋</h3>
        <p class="mb-0 text-white-50" style="font-size: 1rem; font-weight: 400;">Berikut adalah ringkasan aktivitas dan status pengelolaan arsip SP2D Anda hari ini.</p>
    </div>
    <div class="d-none d-md-block position-relative" style="z-index: 2; opacity: 0.9;">
        <i class="fa-solid fa-chart-pie fa-4x text-white"></i>
    </div>
</div>

<!-- Stats Cards Top -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-stat grad-primary h-100 shadow-sm">
            <div class="card-body p-4">
                <h6 class="stat-label mb-2">Total Arsip</h6>
                <div class="stat-value"><?= number_format($total_arsip) ?></div>
                <i class="fa-solid fa-file-invoice icon-overlay"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat grad-success h-100 shadow-sm">
            <div class="card-body p-4">
                <h6 class="stat-label mb-2">Total Instansi</h6>
                <div class="stat-value"><?= number_format($total_instansi) ?></div>
                <i class="fa-solid fa-building icon-overlay"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat grad-warning h-100 shadow-sm">
            <div class="card-body p-4 text-adaptive">
                <h6 class="stat-label mb-2">Total Bindex</h6>
                <div class="stat-value text-adaptive"><?= number_format($total_bindex) ?></div>
                <i class="fa-solid fa-boxes-stacked icon-overlay text-adaptive" style="opacity: 0.15;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Bottom -->
<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card card-stat grad-info h-100 shadow-sm">
            <div class="card-body p-4">
                <h6 class="stat-label mb-2">Arsip Masuk Hari Ini</h6>
                <div class="stat-value"><?= number_format($arsip_hari_ini) ?></div>
                <i class="fa-solid fa-calendar-day icon-overlay"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-stat grad-secondary h-100 shadow-sm">
            <div class="card-body p-4 text-adaptive">
                <h6 class="stat-label mb-2">Arsip Masuk Bulan Ini</h6>
                <div class="stat-value text-adaptive"><?= number_format($arsip_bulan_ini) ?></div>
                <i class="fa-solid fa-calendar-week icon-overlay text-adaptive" style="opacity: 0.15;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Activities -->
<div class="row g-4 mb-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card glass-panel h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-chart-column me-2"></i>Statistik Arsip (Tahun <?= date('Y') ?>)</h6>
                <button class="btn btn-sm btn-light border rounded-circle shadow-sm" title="Download"><i class="fa-solid fa-download"></i></button>
            </div>
            <div class="card-body px-4 pb-4">
                <canvas id="arsipChart" height="110"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-lg-4">
        <div class="card glass-panel h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-clock-rotate-left me-2"></i>Aktivitas Terbaru</h6>
            </div>
            <div class="card-body px-4 pb-4" style="overflow-y: auto; max-height: 380px;">
                <?php if (empty($recent_activities)): ?>
                    <div class="text-center text-muted mt-5">
                        <i class="fa-regular fa-folder-open fa-3x mb-3 opacity-50"></i>
                        <p class="small">Belum ada aktivitas terekam.</p>
                    </div>
                <?php else: ?>
                    <div class="mt-3">
                        <?php foreach ($recent_activities as $log): 
                            $icon = 'fa-circle-info';
                            $color = 'primary';
                            if ($log['action'] == 'Login') { $icon = 'fa-right-to-bracket'; $color = 'success'; }
                            if ($log['action'] == 'Logout') { $icon = 'fa-right-from-bracket'; $color = 'secondary'; }
                            if ($log['action'] == 'Tambah' || $log['action'] == 'Upload') { $icon = 'fa-plus'; $color = 'primary'; }
                            if ($log['action'] == 'Edit') { $icon = 'fa-pen'; $color = 'warning'; }
                            if ($log['action'] == 'Hapus') { $icon = 'fa-trash'; $color = 'danger'; }
                        ?>
                        <div class="d-flex mb-3 align-items-start position-relative">
                            <!-- Timeline line -->
                            <div class="position-absolute" style="left: 20px; top: 40px; bottom: -15px; width: 2px; background: var(--border-color); z-index: 1;"></div>
                            
                            <div class="bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> rounded-circle shadow-sm me-3 position-relative" style="width: 42px; height: 42px; min-width: 42px; display: flex; align-items: center; justify-content: center; z-index: 2;">
                                <i class="fa-solid <?= $icon ?>"></i>
                            </div>
                            <div class="pt-1 pb-2 border-bottom w-100">
                                <p class="mb-1 text-sm" style="line-height: 1.4;"><strong><?= htmlspecialchars($log['nama']) ?></strong> <span class="opacity-75"><?= htmlspecialchars($log['description']) ?></span></p>
                                <small class="text-muted fw-medium"><i class="fa-regular fa-clock me-1"></i> <?= date('d M Y, H:i', strtotime($log['created_at'])) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Preview -->
<div class="card glass-panel mb-4">
    <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-table me-2"></i>5 Arsip SP2D Terbaru</h6>
        <a href="<?= base_url('index.php?page=arsip_list') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableArsip">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 rounded-start">No SP2D</th>
                        <th class="border-0">Instansi</th>
                        <th class="border-0">Tanggal</th>
                        <th class="border-0">Bindex</th>
                        <th class="border-0">Status</th>
                        <th class="border-0 rounded-end text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($arsip_terbaru)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data arsip.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($arsip_terbaru as $arsip): ?>
                        <tr>
                            <td class="fw-medium"><?= htmlspecialchars($arsip['nomor_sp2d']) ?></td>
                            <td><?= htmlspecialchars($arsip['nama_instansi']) ?></td>
                            <td><?= date('d-m-Y', strtotime($arsip['tanggal_arsip'])) ?></td>
                            <td><span class="badge bg-light border" style="color: inherit;"><?= htmlspecialchars($arsip['nomor_bindex']) ?></span></td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                if($arsip['status_arsip'] == 'Diproses') $badgeClass = 'bg-primary';
                                elseif($arsip['status_arsip'] == 'Dikembalikan') $badgeClass = 'bg-danger';
                                ?>
                                <span class="badge <?= $badgeClass ?> rounded-pill px-3"><?= $arsip['status_arsip'] ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('index.php?page=arsip_detail&id='.$arsip['id']) ?>" class="btn btn-sm btn-light border rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Detail"><i class="fa-solid fa-eye text-primary"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Script kustom untuk halaman Dashboard (Chart.js Injection)
$custom_scripts = "
<script>
    $(document).ready(function() {
        $('#tableArsip').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: false
        });

        // Tooltip Initialize
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Setup Chart.js dengan Data PHP
        const chartData = " . $grafik_bulan_json . ";
        const ctx = document.getElementById('arsipChart').getContext('2d');
        
        // Cek mode untuk warna font chart
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
        Chart.defaults.color = isDarkMode ? '#adb5bd' : '#6c757d';
        
        // Buat gradien untuk chart bar
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(67, 97, 238, 0.8)');
        gradient.addColorStop(1, 'rgba(67, 97, 238, 0.2)');

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Arsip Masuk',
                    data: chartData,
                    backgroundColor: gradient,
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 0,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(67, 97, 238, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: isDarkMode ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 14, family: 'Outfit' },
                        bodyFont: { size: 14, family: 'Outfit' },
                        displayColors: false,
                        cornerRadius: 8
                    }
                },
                animation: {
                    y: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            }
        });
    });
</script>
";
include 'views/layout/footer.php'; 
?>
