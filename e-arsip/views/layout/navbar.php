        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Top Navbar -->
            <header class="top-navbar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center flex-grow-1 me-3">
                    <button class="btn btn-light me-3 flex-shrink-0" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <!-- Global Search Form -->
                    <form action="index.php" method="GET" class="d-none d-md-flex w-100" style="max-width: 400px;">
                        <input type="hidden" name="page" value="arsip_list">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted ps-3 pe-1"><i class="fa-solid fa-search"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="Cari No SP2D / Instansi..." aria-label="Search" required>
                        </div>
                    </form>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light rounded-circle" id="themeToggle" aria-label="Toggle Dark Mode" data-bs-toggle="tooltip" title="Ubah Tema">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-body" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                            $userName = htmlspecialchars($_SESSION['user_nama'] ?? 'User');
                            $urlName = urlencode($userName);
                            ?>
                            <img src="https://ui-avatars.com/api/?name=<?= $urlName ?>&background=4361EE&color=fff" alt="Profile" width="38" height="38" class="rounded-circle me-2 border shadow-sm">
                            <span class="d-none d-md-inline" style="color: var(--text-color);"><?= $userName ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                            <li><a class="dropdown-item" href="<?= base_url('index.php?page=profile') ?>"><i class="fa-solid fa-user me-2"></i> Profil Saya</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('index.php?page=change_password') ?>"><i class="fa-solid fa-key me-2"></i> Ganti Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('index.php?page=logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Content Wrapper -->
            <div class="content-wrapper">
