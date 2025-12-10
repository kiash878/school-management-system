<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - School Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Enhanced Hero Section */
        .hero-section {
            position: relative;
            background: var(--navy);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/img/hero-bg.jpg') center/cover;
            opacity: 0.1;
            z-index: 0;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s infinite linear;
            z-index: 1;
        }

        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 800px;
            animation: fadeInUp 1s ease-out;
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

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
            animation: slideInLeft 1s ease-out 0.3s both;
        }

        @keyframes slideInLeft {
            0% {
                opacity: 0;
                transform: translateX(-100px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            margin-bottom: 3rem;
            opacity: 0.9;
            animation: slideInRight 1s ease-out 0.6s both;
        }

        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(100px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-buttons {
            animation: fadeInUp 1s ease-out 0.9s both;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0 10px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
            z-index: -1;
        }

        .btn-hero:hover::before {
            left: 100%;
        }

        .btn-primary-hero {
            background: var(--amber);
            color: white;
            box-shadow: 0 8px 25px rgba(237, 170, 37, 0.4);
        }

        .btn-primary-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(237, 170, 37, 0.5);
            color: white;
        }

        .btn-secondary-hero {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.8);
        }

        .btn-secondary-hero:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-3px);
            color: white;
        }

        /* Floating Elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 2;
        }

        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: floatUpDown 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 50%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .floating-element:nth-child(4) {
            top: 30%;
            right: 30%;
            animation-delay: 1s;
        }

        @keyframes floatUpDown {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Enhanced Navbar */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--navy);
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

        /* Section Enhancements */
        .section-padding {
            padding: 5rem 0;
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--warning-color);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            transform: scale(1.1);
            color: var(--primary-color);
        }

        /* About Section */
        #about {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            position: relative;
            overflow: hidden;
        }

        #about::before {
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

        .about-features {
            display: flex;
            justify-content: space-around;
            margin: 3rem 0;
            gap: 2rem;
        }

        .feature-item {
            text-align: center;
            flex: 1;
            padding: 2rem 1rem;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        /* Stats Section */
        .stats-section {
            background: var(--teal);
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.5;
        }

        .stat-item {
            position: relative;
            padding: 2rem 1rem;
            text-align: center;
            z-index: 2;
        }

        .stat-number {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: white;
        }

        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Admissions Section */
        .bg-light-blue {
            background: var(--sage);
            position: relative;
        }

        .admission-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            height: 100%;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .admission-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--warning-color));
        }

        .admission-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.2);
        }

        .admission-icon {
            font-size: 4rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .admission-card:hover .admission-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .admission-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .admission-description {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        /* Contact Section */
        #contact {
            background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%);
            position: relative;
        }

        .contact-info {
            padding: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            transition: all 0.3s ease;
            border-left: 4px solid var(--warning-color);
        }

        .contact-item:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .contact-icon {
            font-size: 1.5rem;
            color: var(--warning-color);
            margin-right: 1rem;
            width: 50px;
            text-align: center;
        }

        .contact-form {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

        /* Button Enhancements */
        .btn-warning {
            background: var(--amber);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 25px;
            padding: 0.75rem 2rem;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(237, 170, 37, 0.3);
            color: white;
        }

        .btn-outline-dark {
            border: 2px solid var(--text-dark);
            color: var(--text-dark);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-dark:hover {
            background: var(--text-dark);
            color: white;
            transform: translateY(-2px);
        }

        /* Section Titles */
        .section-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 1rem;
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

        .section-subtitle {
            font-size: 1.3rem;
            color: var(--text-light);
            margin-bottom: 3rem;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--warning-color));
        }

        /* Scroll Down Indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 1.5rem;
            animation: bounce 2s infinite;
            z-index: 10;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-10px);
            }
            60% {
                transform: translateX(-50%) translateY(-5px);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn-hero {
                margin: 0;
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap text-warning me-2"></i> SchoolERP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#admissions">Admissions</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-3">
                        <a href="login.php" class="btn btn-warning px-4 rounded-pill">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="home" class="hero-section">
        <!-- Floating Background Elements -->
        <div class="floating-elements">
            <div class="floating-element">
                <i class="fas fa-graduation-cap" style="font-size: 3rem;"></i>
            </div>
            <div class="floating-element">
                <i class="fas fa-book" style="font-size: 2.5rem;"></i>
            </div>
            <div class="floating-element">
                <i class="fas fa-atom" style="font-size: 3.5rem;"></i>
            </div>
            <div class="floating-element">
                <i class="fas fa-microscope" style="font-size: 2.8rem;"></i>
            </div>
        </div>

        <!-- Hero Content -->
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Empowering the Future</h1>
                <p class="hero-subtitle">Excellence in Education, Character, and Leadership.<br>Where Dreams Take Flight and Knowledge Transforms Lives.</p>
                
                <div class="hero-buttons d-flex flex-wrap justify-content-center align-items-center">
                    <a href="#admissions" class="btn btn-hero btn-primary-hero">
                        <i class="fas fa-rocket me-2"></i>Apply Now
                    </a>
                    <a href="#about" class="btn btn-hero btn-secondary-hero">
                        <i class="fas fa-play me-2"></i>Learn More
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <div class="row mt-5 text-center">
                    <div class="col-6 col-md-3">
                        <h3 class="fw-bold mb-0">1000+</h3>
                        <small class="opacity-75">Students</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <h3 class="fw-bold mb-0">50+</h3>
                        <small class="opacity-75">Expert Teachers</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <h3 class="fw-bold mb-0">100%</h3>
                        <small class="opacity-75">Pass Rate</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <h3 class="fw-bold mb-0">25</h3>
                        <small class="opacity-75">Years Excellence</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                         class="img-fluid rounded-4 shadow-lg" alt="Students in classroom">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h2 class="section-title text-center text-lg-start">Why Choose Us?</h2>
                    <p class="section-subtitle text-center text-lg-start">We provide a world-class learning environment that focuses on holistic development and prepares students for the future.</p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="fas fa-chalkboard-teacher feature-icon"></i>
                            <h5 class="fw-bold">Expert Teachers</h5>
                            <p class="text-muted small">Qualified educators with years of experience</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-laptop-code feature-icon"></i>
                            <h5 class="fw-bold">Modern Labs</h5>
                            <p class="text-muted small">State-of-the-art facilities and equipment</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-futbol feature-icon"></i>
                            <h5 class="fw-bold">Sports</h5>
                            <p class="text-muted small">Comprehensive sports and athletics programs</p>
                        </div>
                    </div>
                    
                    <div class="text-center text-lg-start">
                        <a href="history.php" class="btn btn-outline-dark rounded-pill px-4">
                            <i class="fas fa-history me-2"></i>Read Our History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section text-white section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-white">Our Achievements</h2>
                <p class="section-subtitle text-white opacity-75">Numbers that speak for our excellence</p>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Happy Students</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Expert Teachers</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Pass Rate</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">25</div>
                        <div class="stat-label">Years of Excellence</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Admissions Section -->
    <section id="admissions" class="section-padding bg-light-blue">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Admissions Process</h2>
                <p class="section-subtitle">Join our community today - Simple steps to start your journey</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="admission-card text-center">
                        <i class="fas fa-file-alt admission-icon text-primary"></i>
                        <h4 class="admission-title">1. Apply Online</h4>
                        <p class="admission-description">Fill out our simple online application form to get started. It takes just a few minutes to complete.</p>
                        <div class="d-flex justify-content-center">
                            <div class="btn btn-outline-primary btn-sm">Quick & Easy</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="admission-card text-center">
                        <i class="fas fa-user-check admission-icon text-success"></i>
                        <h4 class="admission-title">2. Interview</h4>
                        <p class="admission-description">Schedule a meeting with our academic department to discuss your goals and our programs.</p>
                        <div class="d-flex justify-content-center">
                            <div class="btn btn-outline-success btn-sm">Personal Touch</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mx-auto">
                    <div class="admission-card text-center">
                        <i class="fas fa-credit-card admission-icon text-warning"></i>
                        <h4 class="admission-title">3. Pay Fees</h4>
                        <p class="admission-description">Secure your spot by paying the admission fees via MPESA or bank transfer.</p>
                        <a href="login.php" class="btn btn-warning text-white mt-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Login to Pay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">Have questions? We'd love to hear from you. Reach out to us directly.</p>
            </div>
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h3 class="fw-bold mb-4">Contact Information</h3>
                        <p class="text-muted mb-4">Feel free to reach out through any of the following channels. We're here to help!</p>
                        
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Address</h5>
                                <span class="text-muted">123 Education Lane, Nairobi, Kenya</span>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-phone contact-icon"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Phone</h5>
                                <span class="text-muted">+254 700 000 000</span>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-envelope contact-icon"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Email</h5>
                                <span class="text-muted">info@school.com</span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-clock contact-icon"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Office Hours</h5>
                                <span class="text-muted">Monday - Friday: 8:00 AM - 5:00 PM</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="contact-form">
                        <h3 class="fw-bold mb-4">Send us a Message</h3>
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control" placeholder="Your Email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Subject" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
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
    
    <script>
        // Enhanced Hero Section Interactions
        document.addEventListener('DOMContentLoaded', function() {
            
            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Navbar scroll effect
            const navbar = document.querySelector('.navbar');
            let lastScrollTop = 0;
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Add background blur effect when scrolling
                if (scrollTop > 50) {
                    navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                    navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                    navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.05)';
                }
                
                lastScrollTop = scrollTop;
            });

            // Typing animation for hero subtitle
            const subtitle = document.querySelector('.hero-subtitle');
            const originalText = subtitle.innerHTML;
            
            function typeWriter(element, text, speed = 50) {
                element.innerHTML = '';
                let i = 0;
                
                function type() {
                    if (i < text.length) {
                        if (text.charAt(i) === '<') {
                            // Handle HTML tags
                            let tagEnd = text.indexOf('>', i);
                            element.innerHTML += text.substring(i, tagEnd + 1);
                            i = tagEnd + 1;
                        } else {
                            element.innerHTML += text.charAt(i);
                            i++;
                        }
                        setTimeout(type, speed);
                    }
                }
                type();
            }

            // Start typing animation after initial animations
            setTimeout(() => {
                typeWriter(subtitle, originalText, 30);
            }, 1500);

            // Parallax effect for floating elements
            const floatingElements = document.querySelectorAll('.floating-element');
            
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                
                floatingElements.forEach((element, index) => {
                    const speed = (index + 1) * 0.3;
                    element.style.transform = `translateY(${rate * speed}px) rotate(${scrolled * 0.1}deg)`;
                });
            });

            // Interactive button hover effects
            const heroButtons = document.querySelectorAll('.btn-hero');
            
            heroButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.05)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Animated counter for stats
            const statsNumbers = document.querySelectorAll('.hero-content h3');
            let statsAnimated = false;
            
            function animateStats() {
                if (statsAnimated) return;
                
                statsNumbers.forEach((stat, index) => {
                    const finalNumber = stat.textContent;
                    const isPercentage = finalNumber.includes('%');
                    const isPlus = finalNumber.includes('+');
                    const numericValue = parseInt(finalNumber.replace(/[^0-9]/g, ''));
                    
                    let current = 0;
                    const increment = numericValue / 100;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= numericValue) {
                            current = numericValue;
                            clearInterval(timer);
                        }
                        
                        let displayValue = Math.floor(current);
                        if (isPercentage) displayValue += '%';
                        if (isPlus && current >= numericValue) displayValue += '+';
                        
                        stat.textContent = displayValue;
                    }, 20);
                });
                
                statsAnimated = true;
            }

            // Trigger stats animation when hero section is in view
            const heroSection = document.querySelector('.hero-section');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(animateStats, 2000);
                    }
                });
            });
            
            observer.observe(heroSection);

            // Dynamic background particles
            function createParticle() {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: rgba(255,255,255,0.3);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1;
                    left: ${Math.random() * 100}%;
                    top: 100%;
                    animation: particleFloat ${3 + Math.random() * 4}s linear forwards;
                `;
                
                heroSection.appendChild(particle);
                
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }, 7000);
            }

            // Add CSS for particle animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes particleFloat {
                    0% {
                        transform: translateY(0) scale(0);
                        opacity: 0;
                    }
                    10% {
                        opacity: 1;
                        transform: translateY(-10vh) scale(1);
                    }
                    90% {
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(-100vh) scale(0);
                        opacity: 0;
                    }
                }
                
                .btn-hero {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                }
                
                .hero-content {
                    animation: fadeInUp 1s ease-out;
                }
                
                .navbar {
                    transition: all 0.3s ease !important;
                }
            `;
            document.head.appendChild(style);

            // Create particles periodically
            setInterval(createParticle, 800);

            // Add scroll-triggered reveal animations for sections
            const sections = document.querySelectorAll('section');
            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            sections.forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(50px)';
                section.style.transition = 'all 0.8s ease-out';
                sectionObserver.observe(section);
            });
        });
    </script>
</body>
</html>
