<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out alternate;
        }
        
        .blob-1 {
            width: 400px;
            height: 400px;
            background: #4facfe;
            top: -100px;
            left: -100px;
            border-radius: 50%;
        }

        .blob-2 {
            width: 500px;
            height: 500px;
            background: #00f2fe;
            bottom: -150px;
            right: -100px;
            border-radius: 50%;
            animation-delay: -5s;
        }

        /* Glassmorphism Container */
        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            padding: 3rem 4rem;
            text-align: center;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            max-width: 600px;
            width: 90%;
            animation: popup 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .glitch-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            background: linear-gradient(to right, #2b5876, #4e4376);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            position: relative;
            display: inline-block;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .error-desc {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.5);
        }

        .btn-home:active {
            transform: translateY(0);
        }

        .icon-bounce {
            animation: bounceLeft 2s infinite;
        }

        /* Animations */
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 50px) scale(1.1); }
        }

        @keyframes popup {
            0% { opacity: 0; transform: scale(0.8) translateY(30px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        @keyframes bounceLeft {
            0%, 20%, 50%, 80%, 100% { transform: translateX(0); }
            40% { transform: translateX(-5px); }
            60% { transform: translateX(-3px); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .error-code { font-size: 6rem; }
            .error-title { font-size: 1.4rem; }
            .container { padding: 2rem; }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
        <div class="glitch-wrapper">
            <div class="error-code">404</div>
        </div>
        <h2 class="error-title">Oops! Tersesat di Ruang Arsip?</h2>
        <p class="error-desc">
            Halaman yang Anda cari mungkin telah dipindahkan, diganti namanya, atau ditaruh di rak bindex yang salah.
        </p>
        <a href="<?= base_url('index.php?page=dashboard') ?>" class="btn-home">
            <i class="fa-solid fa-arrow-left icon-bounce"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Mouse Parallax Script for extra cool effect -->
    <script>
        document.addEventListener('mousemove', function(e) {
            const container = document.querySelector('.container');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 40;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 40;
            container.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
            container.style.transition = 'none'; // Disable transition for smooth mouse tracking
        });

        document.addEventListener('mouseleave', function() {
            const container = document.querySelector('.container');
            container.style.transform = `rotateY(0deg) rotateX(0deg)`;
            container.style.transition = 'all 0.5s ease'; // Re-enable transition when mouse leaves
        });
    </script>
</body>
</html>
