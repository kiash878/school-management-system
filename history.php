<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our History - School Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --warning-color: #ff9f43;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            line-height: 1.6;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

        /* Enhanced Hero Section */
        .hero-section-small {
            position: relative;
            background: var(--navy);
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        .hero-section-small::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/img/history.jpg') center/cover;
            opacity: 0.2;
            z-index: 0;
        }

        .hero-section-small::after {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 2px, transparent 2px);
            background-size: 60px 60px;
            animation: float 25s infinite linear;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            animation: fadeInUp 1s ease-out;
        }

        .hero-section-small .display-3 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            color: white;
        }

        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Introduction Section */
        .intro-section {
            background: white;
            position: relative;
            overflow: hidden;
        }

        .intro-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .section-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--amber);
            border-radius: 2px;
        }

        /* Modern Timeline */
        .timeline-section {
            background: var(--sage);
            position: relative;
            padding: 6rem 0;
        }

        .timeline {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--teal);
            transform: translateX(-50%);
            border-radius: 2px;
            box-shadow: 0 0 20px rgba(10, 115, 115, 0.3);
        }

        .timeline-item {
            position: relative;
            margin: 3rem 0;
            opacity: 0;
            animation: slideInTimeline 0.8s ease-out forwards;
        }

        .timeline-item:nth-child(2) { animation-delay: 0.2s; }
        .timeline-item:nth-child(3) { animation-delay: 0.4s; }
        .timeline-item:nth-child(4) { animation-delay: 0.6s; }
        .timeline-item:nth-child(5) { animation-delay: 0.8s; }

        @keyframes slideInTimeline {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .timeline-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            width: 45%;
            margin: 0;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .timeline-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--amber);
            border-radius: 20px 20px 0 0;
        }

        .timeline-content:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(102, 126, 234, 0.2);
        }

        .timeline-left .timeline-content {
            margin-left: 0;
            margin-right: auto;
        }

        .timeline-right .timeline-content {
            margin-left: auto;
            margin-right: 0;
        }

        .timeline-marker {
            position: absolute;
            left: 50%;
            top: 2rem;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            background: var(--amber);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 8px white, 0 0 20px rgba(237, 170, 37, 0.3);
            z-index: 10;
        }

        .timeline-marker i {
            color: white;
            font-size: 1.5rem;
        }

        .year-badge {
            background: linear-gradient(45deg, var(--primary-color), var(--warning-color));
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .timeline-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .timeline-description {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.7;
        }

        /* Quote Section */
        .quote-section {
            background: var(--teal);
            position: relative;
            overflow: hidden;
        }

        .quote-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }

        .quote-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .quote-icon {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 2rem;
        }

        .quote-text {
            font-size: 1.8rem;
            font-style: italic;
            font-family: 'Playfair Display', serif;
            line-height: 1.4;
            margin-bottom: 2rem;
        }

        .quote-author {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .timeline::before {
                left: 2rem;
            }
            
            .timeline-content {
                width: calc(100% - 5rem);
                margin-left: 4rem !important;
                margin-right: 0 !important;
            }
            
            .timeline-marker {
                left: 2rem;
                width: 40px;
                height: 40px;
            }
            
            .timeline-marker i {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
            
            .quote-text {
                font-size: 1.4rem;
            }
        }
        /* Enhanced Navbar Styles */
        :root {
            --primary-color: #010221;
            --secondary-color: #0a7373;
            --accent-color: #b7bf99;
            --warning-color: #edaa25;
            --success-color: #0a7373;
            --danger-color: #c43302;
            --text-dark: #010221;
            --text-light: #6b7280;
            --navy: #010221;
            --teal: #0a7373;
            --sage: #b7bf99;
            --amber: #edaa25;
            --rust: #c43302;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            background: linear-gradient(45deg, var(--primary-color), var(--warning-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
            transition: all 0.3s ease;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-dark) !important;
            margin-left: 1rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: var(--warning-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--warning-color) !important;
        }

        .nav-link.active {
            color: var(--warning-color) !important;
        }

        .nav-link.active::after {
            width: 100%;
        }

        .btn-warning {
            background: linear-gradient(45deg, var(--warning-color), #ff6b35);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 159, 67, 0.3);
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap text-warning me-2"></i> SchoolERP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="history.php">History</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#admissions">Admissions</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>
                    <li class="nav-item ms-3">
                        <a href="login.php" class="btn btn-warning px-4 rounded-pill">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section-small">
        <div class="container">
            <div class="hero-content">
                <h1 class="display-3 fw-bold mb-3">Our Legacy</h1>
                <p class="lead fs-4">Building Futures Since 1995</p>
            </div>
        </div>
    </header>

    <!-- Introduction -->
    <section class="py-5 intro-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">A Tradition of Excellence</h2>
                    <p class="lead" style="color: var(--text-light); font-size: 1.3rem; line-height: 1.7;">
                        Founded with a vision to provide holistic education to the community, our school has grown from a humble single-building classroom to a premier educational institution. 
                        Our journey is a testament to the power of education and the resilience of our community.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="timeline-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-center">Our Journey Through Time</h2>
                <p class="lead" style="color: var(--text-light);">Milestones that shaped our educational legacy</p>
            </div>
            
            <div class="timeline">
                <div class="timeline-item timeline-left">
                    <div class="timeline-marker">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="timeline-content">
                        <span class="year-badge">1995</span>
                        <h3 class="timeline-title">The Beginning</h3>
                        <p class="timeline-description">The school was established by Dr. A. Smith with just 50 students and 5 teachers. The mission was simple: quality education for all, building the foundation of excellence that continues today.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-right">
                    <div class="timeline-marker">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="timeline-content">
                        <span class="year-badge">2000</span>
                        <h3 class="timeline-title">First Expansion</h3>
                        <p class="timeline-description">After five years of success, we inaugurated the Science Wing and the main Library, opening doors to advanced learning resources and expanding our academic capabilities.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-left">
                    <div class="timeline-marker">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="timeline-content">
                        <span class="year-badge">2010</span>
                        <h3 class="timeline-title">Sports Excellence</h3>
                        <p class="timeline-description">The school sports complex was completed, leading to our first National Championship victory in Football and Athletics. We proved that academic and athletic excellence go hand in hand.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-right">
                    <div class="timeline-marker">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="timeline-content">
                        <span class="year-badge">2018</span>
                        <h3 class="timeline-title">Digital Revolution</h3>
                        <p class="timeline-description">We integrated the Smart Classroom program and this ERP system to modernize our teaching and administrative processes, embracing technology for better education.</p>
                    </div>
                </div>
                
                <div class="timeline-item timeline-left">
                    <div class="timeline-marker">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="timeline-content">
                        <span class="year-badge">2025</span>
                        <h3 class="timeline-title">Future Ready</h3>
                        <p class="timeline-description">Today, we stand as a beacon of knowledge, with over 1000 students and a commitment to nurturing the next generation of leaders, innovators, and global citizens.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quote -->
    <section class="quote-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="quote-content text-center">
                        <i class="fas fa-quote-left quote-icon"></i>
                        <blockquote class="quote-text">
                            "Education is the passport to the future, for tomorrow belongs to those who prepare for it today."
                        </blockquote>
                        <div class="quote-author">
                            — <strong>Dr. A. Smith</strong>, Founding Principal
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> School Management System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
