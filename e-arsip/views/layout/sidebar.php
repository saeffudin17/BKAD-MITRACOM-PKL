        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header d-flex justify-content-center align-items-center flex-column py-4">
                <div class="brand-icon-sidebar mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-color), #3f37c9); color: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transform: rotate(-5deg); box-shadow: 0 5px 15px rgba(67,97,238,0.3);">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <span class="fw-bolder" style="font-size: 1.25rem; letter-spacing: -0.5px;">E-Arsip <span style="color: var(--primary-color);">SP2D</span></span>
            </div>
            <div class="sidebar-menu">
                <a href="<?= base_url('index.php?page=dashboard') ?>" class="nav-link <?= (!isset($_GET['page']) || $_GET['page'] == 'dashboard') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house fa-fw"></i> Dashboard
                </a>
                
                <?php $role = $_SESSION['user_role'] ?? ''; ?>
                
                <?php if (in_array($role, ['Admin', 'Operator', 'Reviewer', 'Direktur'])): ?>
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Pengelolaan Arsip</h6>
                <?php if (in_array($role, ['Admin', 'Operator'])): ?>
                <a href="<?= base_url('index.php?page=arsip_input') ?>" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'arsip_input') ? 'active' : '' ?>">
                    <i class="fa-solid fa-plus-circle fa-fw"></i> Input Arsip SP2D
                </a>
                <?php endif; ?>
                <a href="<?= base_url('index.php?page=arsip_list') ?>" class="nav-link <?= (isset($_GET['page']) && in_array($_GET['page'], ['arsip_list', 'arsip_detail'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-list fa-fw"></i> List Data Arsip
                </a>
                <?php endif; ?>

                <?php if (in_array($role, ['Admin', 'Operator', 'Direktur'])): ?>
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Master Data</h6>
                <a href="<?= base_url('index.php?page=instansi') ?>" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'instansi') ? 'active' : '' ?>">
                    <i class="fa-solid fa-building fa-fw"></i> Data Instansi
                </a>
                <a href="<?= base_url('index.php?page=kegiatan') ?>" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'kegiatan') ? 'active' : '' ?>">
                    <i class="fa-solid fa-briefcase fa-fw"></i> Data Kegiatan
                </a>
                <a href="<?= base_url('index.php?page=bindex') ?>" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'bindex') ? 'active' : '' ?>">
                    <i class="fa-solid fa-boxes-stacked fa-fw"></i> Data Bindex
                </a>
                <a href="<?= base_url('index.php?page=rak') ?>" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'rak') ? 'active' : '' ?>">
                    <i class="fa-solid fa-server fa-fw"></i> Data Rak
                </a>
                <?php endif; ?>

                <?php if (in_array($role, ['Admin', 'Reviewer', 'Direktur'])): ?>
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Laporan & Pengaturan</h6>
                <a href="<?= base_url('index.php?page=laporan') ?>" class="nav-link <?= (isset($_GET['page']) && in_array($_GET['page'], ['laporan', 'laporan_cetak'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-print fa-fw"></i> Cetak Laporan
                </a>
                <?php endif; ?>
                
                <?php if ($role === 'Admin'): ?>
                <a href="<?= base_url('index.php?page=users') ?>" class="nav-link <?= (isset($_GET['page']) && in_array($_GET['page'], ['users', 'users_action'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-users fa-fw"></i> Manajemen User
                </a>
                <?php endif; ?>
                
                <?php if (in_array($role, ['Admin', 'Direktur'])): ?>
                <a href="<?= base_url('index.php?page=logs') ?>" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'logs') ? 'active' : '' ?>">
                    <i class="fa-solid fa-history fa-fw"></i> Activity Log
                </a>
                <?php endif; ?>
                
                <?php if ($role === 'Admin'): ?>
                <a href="<?= base_url('index.php?page=recycle_bin') ?>" class="nav-link <?= (isset($_GET['page']) && in_array($_GET['page'], ['recycle_bin', 'recycle_bin_action'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-trash-can fa-fw"></i> Recycle Bin
                </a>
                <a href="<?= base_url('index.php?page=backup_restore') ?>" class="nav-link <?= (isset($_GET['page']) && in_array($_GET['page'], ['backup_restore', 'backup_action'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-database fa-fw"></i> Backup Database
                </a>
                <?php endif; ?>
                <a href="<?= base_url('index.php?page=logout') ?>" class="nav-link text-danger mt-3">
                    <i class="fa-solid fa-right-from-bracket fa-fw"></i> Logout
                </a>
            </div>
        </nav>
        <!-- End Sidebar -->
