<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Arsip SP2D</title>
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --bg-color: #f8f9fa;
            --text-main: #2b2d42;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
        }
        
        [data-theme="dark"] {
            --bg-color: #0f172a;
            --text-main: #f8fafc;
            --glass-bg: rgba(30, 41, 59, 0.85);
            --glass-border: rgba(51, 65, 85, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        body {
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Gradients */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: moveBlobs 15s infinite alternate ease-in-out;
            opacity: 0.7;
        }

        .shape-1 {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            top: -150px;
            left: -150px;
        }

        .shape-2 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--success), #4895ef);
            bottom: -100px;
            right: -100px;
            animation-delay: -5s;
        }

        @keyframes moveBlobs {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, 100px) scale(1.1); }
            100% { transform: translate(-50px, 50px) scale(0.9); }
        }

        /* Glassmorphism Card */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 0 20px;
            perspective: 1000px;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUpFade {
            to { transform: translateY(0); opacity: 1; }
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            transform: rotate(-10deg);
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .login-card:hover .brand-icon {
            transform: rotate(0deg) scale(1.05);
        }

        h3 {
            color: var(--text-main);
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.5px;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.95rem;
            font-weight: 400;
        }

        [data-theme="dark"] .subtitle { color: #94a3b8; }

        /* Custom Inputs */
        .form-floating > .form-control {
            background-color: transparent;
            border: 2px solid rgba(148, 163, 184, 0.3);
            border-radius: 12px;
            color: var(--text-main);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-floating > .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }

        .form-floating > label {
            color: #64748b;
            font-weight: 500;
        }
        
        [data-theme="dark"] .form-floating > label { color: #94a3b8; }

        .input-group-text.password-toggle {
            background: transparent;
            border: 2px solid rgba(148, 163, 184, 0.3);
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Connect borders */
        #password { border-right: none; border-radius: 12px 0 0 12px; }
        #password:focus + .password-toggle { border-color: var(--primary); }

        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.25);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(67, 97, 238, 0.35);
        }
        
        .btn-login::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-20deg);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover::after { left: 150%; }

        /* Theme Toggle */
        .theme-toggle-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 100;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .theme-toggle-btn:hover {
            transform: rotate(30deg) scale(1.1);
        }
    </style>
</head>
<body>

    <!-- Background Shapes -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <!-- Theme Toggle -->
    <button class="theme-toggle-btn" id="themeToggleLogin" title="Ganti Tema">
        <i class="fa-solid fa-moon"></i>
    </button>

    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="brand-icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            
            <h3>E-Arsip SP2D</h3>
            <p class="subtitle">Platform Manajemen Arsip Digital Terpadu</p>

            <?php if(isset($_SESSION['error_login'])): ?>
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 py-2 px-3 mb-4 d-flex align-items-center" style="font-size:0.9rem;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <?= $_SESSION['error_login'] ?>
                </div>
                <?php unset($_SESSION['error_login']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['success_logout'])): ?>
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 py-2 px-3 mb-4 d-flex align-items-center" style="font-size:0.9rem;">
                    <i class="fa-solid fa-check-circle me-2"></i> <?= $_SESSION['success_logout'] ?>
                </div>
                <?php unset($_SESSION['success_logout']); ?>
            <?php endif; ?>

            <form action="<?= base_url('index.php?page=login_process') ?>" method="POST">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus autocomplete="off">
                    <label for="username"><i class="fa-solid fa-user me-2"></i>Username Anda</label>
                </div>
                
                <div class="input-group mb-4">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fa-solid fa-lock me-2"></i>Kata Sandi</label>
                    </div>
                    <span class="input-group-text password-toggle" id="togglePassword">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                    <div class="form-check">
                        <input class="form-check-input shadow-none" type="checkbox" id="remember" name="remember" style="border-color: rgba(148, 163, 184, 0.5);">
                        <label class="form-check-label text-muted small fw-medium" for="remember">
                            Biarkan saya tetap masuk
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    Masuk ke Sistem <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>
            
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Setup Theme from LocalStorage
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            updateThemeIcon(savedTheme);

            // Theme Toggle Logic
            $('#themeToggleLogin').click(function() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });

            function updateThemeIcon(theme) {
                if(theme === 'dark') {
                    $('#themeToggleLogin').html('<i class="fa-solid fa-sun text-warning"></i>');
                } else {
                    $('#themeToggleLogin').html('<i class="fa-solid fa-moon"></i>');
                }
            }

            // Toggle Password Logic
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Add subtle mouse movement effect to login card
            document.addEventListener('mousemove', function(e) {
                const card = document.querySelector('.login-card');
                const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
                const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
                card.style.transform = `translateY(0) rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
            });
            
            document.addEventListener('mouseleave', function() {
                const card = document.querySelector('.login-card');
                card.style.transform = `translateY(0) rotateY(0deg) rotateX(0deg)`;
                card.style.transition = 'all 0.5s ease';
                setTimeout(() => { card.style.transition = 'none'; }, 500);
            });
        });
    </script>
</body>
</html>
