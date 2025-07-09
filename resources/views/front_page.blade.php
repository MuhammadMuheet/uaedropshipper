<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arrbaab - Your Gateway to Global Commerce</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.png')}}"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        /* Enhanced Header Styles */
        .header {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 95%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            padding: 8px 2rem;
            border-radius: 100px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: #2563eb;
            backdrop-filter: none;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header:hover {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transform: translateX(-50%) translateY(-2px);
        }

        .header.scrolled:hover {
            background: #1e40af;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 10px rgba(37, 99, 235, 0.3);
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            position: relative;
        }

        .nav-menu a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #2563eb;
            transition: all 0.3s;
            transform: translateX(-50%);
        }

        .nav-menu a:hover::after {
            width: 80%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .login-btn,
        .register-btn {
            padding: 0.6rem 1.2rem;
            border: 1px solid;
            border-radius: 100px !important;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: white;
            font-size: 0.9rem;
        }

        .login-btn {
            background: linear-gradient(45deg, #2563eb, #3b82f6);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
        }

        .register-btn {
            background: transparent;
            border: 1px solid #ffffff;
            color: #ffffff;
            position: relative;
        }

        .register-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: #3b82f6;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.4s ease;
            z-index: -1;
        }

        .register-btn:hover::after {
            width: 200%;
            height: 200%;
        }

        .register-btn:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: white;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 2px;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
                top: 10px;
            }

            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: #2563eb;
                padding: 1rem;
                border-radius: 0 0 20px 20px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu li {
                text-align: center;
                margin: 0.5rem 0;
            }

            .auth-buttons {
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
            }

            .login-btn,
            .register-btn {
                width: 100%;
                padding: 0.8rem;
                color: white !important;
            }

            .register-btn {
                background-color: #2563eb;
            }

            .hamburger {
                display: flex;
                padding-right: 20px;
            }
        }

        /* Enhanced Hero Section with Graph */
        .hero {
            height: 100vh;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 30%, #1e40af 70%, #3b82f6 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            padding-top: 100px;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            width: 100%;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
            gap: 4rem;
        }

        .hero-text {
            flex: 1;
            text-align: left;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .cta-button {
            display: inline-block;
            background: white;
            color: #2563eb;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            animation: fadeInUp 1s ease 0.4s both;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        /* Sales Graph Section */
        .hero-graph {
            flex: 1;
            max-width: 500px;
            position: relative;
        }

        .graph-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 1s ease 0.6s both;
        }

        .graph-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .graph-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .graph-subtitle {
            font-size: 1rem;
            opacity: 0.8;
        }

        .chart-area {
            height: 200px;
            position: relative;
            margin-bottom: 1rem;
        }

        .chart-svg {
            width: 100%;
            height: 100%;
        }

        .chart-line {
            fill: none;
            stroke: #ffffff;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: drawLine 2s ease-in-out 1s forwards;
        }

        @keyframes drawLine {
            to {
                stroke-dashoffset: 0;
            }
        }

        .chart-dots {
            fill: #ffffff;
            r: 4;
            opacity: 0;
            animation: showDots 0.5s ease 3s forwards;
        }

        @keyframes showDots {
            to {
                opacity: 1;
            }
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Section Styles */
        .section {
            padding: 5rem 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #6b7280;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Grid System */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -1rem;
        }

        .col-1 {
            width: 8.33%;
        }

        .col-2 {
            width: 16.66%;
        }

        .col-3 {
            width: 25%;
        }

        .col-4 {
            width: 33.33%;
        }

        .col-6 {
            width: 50%;
        }

        .col-8 {
            width: 66.66%;
        }

        .col-12 {
            width: 100%;
        }

        .col-1,
        .col-2,
        .col-3,
        .col-4,
        .col-6,
        .col-8,
        .col-12 {
            padding: 0 1rem;
            margin-bottom: 2rem;
        }

        /* Portal Gallery */
        .portal-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            height: 100%;
            border: 2px solid transparent;
        }

        .portal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(37, 99, 235, 0.15);
            border-color: #2563eb;
        }

        .portal-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #2563eb;
            position: relative;
            overflow: hidden;
        }

        .portal-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(37, 99, 235, 0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .portal-card:hover .portal-image::before {
            transform: translateX(100%);
        }

        /* Steps Section */
        .step-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
        }

        .step-card:hover {
            border-color: #2563eb;
            transform: translateY(-5px);
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
        }

        /* Products Section */
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(37, 99, 235, 0.15);
            border-color: #2563eb;
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #2563eb;
        }

        .product-info {
            padding: 1.5rem;
        }

        /* Countries Section */
        .countries-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .country-hub {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            margin: 80px 0px;
        }

        .uae-center {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 40px rgba(37, 99, 235, 0.15);
            position: relative;
            z-index: 2;
            border: 3px solid #2563eb;
        }

        .uae-flag {
            width: 80px;
            height: 60px;
            background: linear-gradient(to bottom, #ff0000 25%, #00ff00 25%, #00ff00 50%, #ffffff 50%, #ffffff 75%, #000000 75%);
            border-radius: 5px;
        }

        .country-orbit {
            position: absolute;
            width: 400px;
            height: 400px;
            border: 2px dashed #2563eb;
            border-radius: 50%;
        }

        .country-point {
            position: absolute;
            width: 80px;
            height: 50px;
            background: white;
            border-radius: 10%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.1);
            font-size: 0.8rem;
            font-weight: 600;
            color: #2563eb;
            border: 2px solid #2563eb;
        }

        .country-point:nth-child(1) {
            top: 20px;
            left: 15%;
            transform: translateX(-50%);
        }
        .country-point:nth-child(2) {
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .country-point:nth-child(3) {
            top: 50%;
            right: -30px;
            transform: translateY(-50%);
        }

        .country-point:nth-child(4) {
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        .country-point:nth-child(5) {
            top: 50%;
            left: -30px;
            transform: translateY(-50%);
        }

        .country-point:nth-child(6) {
            top: 10%;
            right: 5%;
        }

        /* Features Section */
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            border-color: #2563eb;
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        /* Testimonials */
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .testimonial-card:hover {
            border-color: #2563eb;
            transform: translateY(-5px);
        }

        .testimonial-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        /* FAQ & Contact */
        .faq-contact {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .faq-item {
            background: white;
            border-radius: 10px;
            margin-bottom: 1rem;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .faq-item:hover {
            border-color: #2563eb;
        }

        .faq-question {
            padding: 1.5rem;
            background: white;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #2563eb;
        }

        .faq-answer {
            padding: 0 1.5rem 1.5rem;
            display: none;
            color: #6b7280;
        }

        .faq-answer.active {
            display: block;
        }

        .contact-form {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .contact-form:hover {
            border-color: #2563eb;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
        }

        .submit-btn {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: rgb(255, 255, 255);
            padding: 3rem 0;
            text-align: center;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #ffffff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                top: 10px;
                width: 98%;
                padding: 1rem;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 2rem;
                border-radius: 15px;
                margin-top: 1rem;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu a {
                color: #2563eb;
            }

            .hamburger {
                display: flex;
            }
            .hero {
                padding: 750px 1rem 650px 1rem;
            }

            .hero-content {
                flex-direction: column;
                text-align: center;
                gap: 3rem;
            }

            .hero-text {
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .hero-text p {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .col-1,
            .col-2,
            .col-3,
            .col-4,
            .col-6,
            .col-8 {
                width: 100%;
            }

            .country-orbit {
                width: 250px;
                height: 250px;
            }
            .country-hub {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            margin: 0px 0px;
        }
            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
            .country-point {
            position: absolute;
            width: 70px;
            height: 40px;
        }
        }

        @media (max-width: 480px) {

            .nav-container,
            .container {
                padding: 0 1rem;
            }

            .hero {
                padding: 750px 1rem 650px 1rem;
            }

            .section {
                padding: 3rem 0;
            }

            .graph-container {
                padding: 1.5rem;
            }
            .country-point {
            position: absolute;
            width: 70px;
            height: 40px;
        }
        .country-hub {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            margin: 0px 0px;
        }
        }

        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .whatsapp-btn {
            background: #25D366;
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .whatsapp-btn:hover {
            transform: scale(1.1);
        }

        .whatsapp-popup {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 15px;
            text-align: center;
            z-index: 1000;
        }

        .whatsapp-popup.active {
            display: block;
        }

        .whatsapp-popup p {
            margin-bottom: 10px;
            color: #333;
            font-weight: 500;
        }

        .whatsapp-popup a {
            display: inline-block;
            background: #25D366;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .whatsapp-popup a:hover {
            background: #1ebe57;
        }
    </style>
</head>

<body>
    <!-- Enhanced Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo"> <img src="logo-white.png" alt="Arrbaab-Logo" width="100" height="60"></div>
            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#portal">Portal</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#products">Products</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li class="auth-buttons">
                        <a href="/login"class="login-btn">Login</a>
                        <a href="/user-register" class="register-btn">Register</a>
                    </li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Enhanced Hero Section with Sales Graph -->
    <section id="hero" class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Start Your Dropshipping Empire Today</h1>
                <p>Connect with suppliers worldwide and build your e-commerce business with zero inventory risk. Join
                    thousands of successful dropshippers already earning with our platform.</p>
                <a href="#portal" class="cta-button">Explore Our Portal</a>
            </div>

            <div class="hero-graph">
                <div class="graph-container">
                    <div class="graph-header">
                        <div class="graph-title">Live Sales Dashboard</div>
                        <div class="graph-subtitle">Real-time dropshipping performance</div>
                    </div>

                    <div class="chart-area">
                        <svg class="chart-svg" viewBox="0 0 400 200">
                            <defs>
                                <linearGradient id="gradient" x1="0%" y1="0%" x2="0%"
                                    y2="100%">
                                    <stop offset="0%" style="stop-color:rgba(255,255,255,0.3);stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:rgba(255,255,255,0);stop-opacity:0" />
                                </linearGradient>
                            </defs>

                            <!-- Grid lines -->
                            <g stroke="rgba(255,255,255,0.1)" stroke-width="1">
                                <line x1="0" y1="40" x2="400" y2="40" />
                                <line x1="0" y1="80" x2="400" y2="80" />
                                <line x1="0" y1="120" x2="400" y2="120" />
                                <line x1="0" y1="160" x2="400" y2="160" />
                            </g>

                            <!-- Sales line -->
                            <path class="chart-line" d="M20,160 Q80,140 120,120 T200,100 T280,80 T360,60" />

                            <!-- Area under curve -->
                            <path fill="url(#gradient)"
                                d="M20,160 Q80,140 120,120 T200,100 T280,80 T360,60 L360,180 L20,180 Z" />

                            <!-- Data points -->
                            <circle class="chart-dots" cx="20" cy="160" />
                            <circle class="chart-dots" cx="120" cy="120" />
                            <circle class="chart-dots" cx="200" cy="100" />
                            <circle class="chart-dots" cx="280" cy="80" />
                            <circle class="chart-dots" cx="360" cy="60" />
                        </svg>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-value">AED 127K</span>
                            <span class="stat-label">Monthly Revenue</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">2.4K</span>
                            <span class="stat-label">Active Stores</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">89%</span>
                            <span class="stat-label">Success Rate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="portal" class="section">
        <div class="container">
            <h2 class="section-title">Our Dropshipping Portal</h2>
            <p class="section-subtitle">Discover our comprehensive platform designed to streamline your dropshipping
                journey from product sourcing to order fulfillment.</p>

            <div class="row">
                <div class="col-4">
                    <div class="portal-card">
                        <div class="portal-image">📊</div>
                        <h3>Analytics Dashboard</h3>
                        <p>Track your sales, monitor profit margins, and analyze market trends with our advanced
                            analytics tools. Make data-driven decisions to grow your business.</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="portal-card">
                        <div class="portal-image">🛍️</div>
                        <h3>Product Catalog</h3>
                        <p>Access thousands of trending products from verified suppliers. Browse categories, check
                            inventory levels, and import products to your store instantly.</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="portal-card">
                        <div class="portal-image">⚡</div>
                        <h3>Order Management</h3>
                        <p>Automate your order processing with our smart fulfillment system. Track shipments, manage
                            returns, and keep customers updated automatically.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section" style="background: #f8fafc;">
        <div class="container">
            <h2 class="section-title">How To Start Working</h2>
            <p class="section-subtitle">Get started with dropshipping in just three simple steps and begin earning
                within 24 hours.</p>

            <div class="row">
                <div class="col-4">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>Register Your Account</h3>
                        <p>Sign up for free and complete your profile. Verify your identity and connect your payment
                            methods to get started immediately.</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Choose Your Products</h3>
                        <p>Browse our curated product catalog and select items that match your niche. Import products to
                            your store with one-click integration.</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Start Selling</h3>
                        <p>Launch your marketing campaigns and start receiving orders. We handle fulfillment while you
                            focus on growing your business.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Products Section -->
    <section id="products" class="section">
        <div class="container">
            <h2 class="section-title">Popular Products</h2>
            <p class="section-subtitle">Discover our best-selling products that are generating high profits for our
                dropshippers worldwide.</p>

            <div class="row">
                <div class="col-3">
                    <div class="product-card">
                        <div class="product-image">📱</div>
                        <div class="product-info">
                            <h4>Electronics & Gadgets</h4>
                            <p>Smartphones, smartwatches, headphones, and trending tech accessories.</p>
                            <strong style="color: #2563eb;">30-60% Profit Margin</strong>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="product-card">
                        <div class="product-image">👕</div>
                        <div class="product-info">
                            <h4>Fashion & Apparel</h4>
                            <p>Trendy clothing, shoes, bags, and fashion accessories for all ages.</p>
                            <strong style="color: #2563eb;">40-70% Profit Margin</strong>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="product-card">
                        <div class="product-image">🏠</div>
                        <div class="product-info">
                            <h4>Home & Garden</h4>
                            <p>Home decor, kitchen gadgets, gardening tools, and lifestyle products.</p>
                            <strong style="color: #2563eb;">50-80% Profit Margin</strong>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="product-card">
                        <div class="product-image">💄</div>
                        <div class="product-info">
                            <h4>Beauty & Health</h4>
                            <p>Skincare, makeup, fitness equipment, and wellness products.</p>
                            <strong style="color: #2563eb;">60-90% Profit Margin</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Countries Section -->
    <section id="countries" class="section countries-section">
        <div class="container">
            <h2 class="section-title">Global Reach, UAE Focused</h2>
            <p class="section-subtitle">We primarily serve the UAE market with shipping connections to neighboring
                countries for expanded opportunities.</p>

            <div class="country-hub">
                <div class="country-orbit">
                    <img class="country-point" src="countries/Bangladesh.png" alt="Bangladesh flag" style="object-fit: cover; object-position: center;">
                    <img class="country-point" src="countries/Sri-lanka.png"  alt="Sri Lanka flag" style="object-fit: cover; object-position: center;">
                    <img class="country-point" src="countries/pakistan.png"  alt="Pakistan flag" style="object-fit: cover; object-position: center;">
                    <img class="country-point" src="countries/india.png"  alt="India flag" style="object-fit: cover; object-position: center;">
                    <img class="country-point" src="countries/Afghanistan.png" alt="Afghanistan flag" style="object-fit: cover; object-position: center;">
                    <img class="country-point" src="countries/iran.png" alt="Iran flag" style="object-fit: cover; object-position:center;">
                </div>
                <div class="uae-center">
                    <img src="countries/uae-flag.png" width="130">
                </div>
            </div>

            <div style="text-align: center; margin-top: 3rem;">
                <h3 style="color: #2563eb; margin-bottom: 1rem;">Primary Market: United Arab Emirates</h3>
                <p>Fast delivery, local support, and optimized logistics for the UAE market with expansion capabilities
                    to surrounding regions.</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container">
            <h2 class="section-title">Why Choose Arrbaab</h2>
            <p class="section-subtitle">Our platform is built with powerful features to maximize your dropshipping
                success and profitability.</p>

            <div class="row">
                <div class="col-3">
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h4>Lightning Fast</h4>
                        <p>Ultra-fast product imports, instant order processing, and rapid page load speeds for better
                            customer experience.</p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-card">
                        <div class="feature-icon">💳</div>
                        <h4>Easy Payments</h4>
                        <p>Multiple payment gateways, automated transactions, and instant payouts to your preferred
                            accounts.</p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-card">
                        <div class="feature-icon">📈</div>
                        <h4>Profit Calculator</h4>
                        <p>Real-time profit analysis, margin optimization tools, and ROI tracking for maximum
                            profitability.</p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-card">
                        <div class="feature-icon">🔄</div>
                        <h4>Advanced Restocking</h4>
                        <p>AI-powered inventory management, automatic reorder alerts, and supplier coordination for
                            seamless operations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section" style="background: #f8fafc;">
        <div class="container">
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle">Join thousands of successful dropshippers who have transformed their business
                with our platform.</p>

            <div class="row">
                <div class="col-4">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👨</div>
                        <p>"Arrbaab transformed my business completely. Within 3 months, I went from zero to $50k
                            monthly revenue. The platform is incredibly user-friendly!"</p>
                        <h5 style="margin-top: 1rem; color: #2563eb;">Ahmed Al-Rashid</h5>
                        <small style="color: #6b7280;">Dubai, UAE</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👩</div>
                        <p>"The automated order processing saved me 20 hours per week. Now I can focus on marketing and
                            scaling my business instead of manual tasks."</p>
                        <h5 style="margin-top: 1rem; color: #2563eb;">Sarah Johnson</h5>
                        <small style="color: #6b7280;">Abu Dhabi, UAE</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👨</div>
                        <p>"Best ROI I've ever seen! The profit calculator helped me optimize my margins and the
                            supplier network is top-notch. Highly recommended!"</p>
                        <h5 style="margin-top: 1rem; color: #2563eb;">Mohammed Hassan</h5>
                        <small style="color: #6b7280;">Sharjah, UAE</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ & Contact Section -->
    <section id="contact" class="section faq-contact">
        <div class="container">
            <h2 class="section-title" style="padding-bottom: 20px;">Frequently Asked Questions & Contact</h2>

            <div class="row">
                <div class="col-6">
                    <h3 style="margin-bottom: 2rem; color: #2563eb;">Frequently Asked Questions</h3>

                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            How much does it cost to start?
                            <span>+</span>
                        </button>
                        <div class="faq-answer">
                            You can start for free! We offer a free plan with basic features. Premium plans start from
                            $29/month with advanced features and higher limits.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            Do you handle shipping and fulfillment?
                            <span>+</span>
                        </button>
                        <div class="faq-answer">
                            Yes! We have partnerships with reliable suppliers who handle all packaging and shipping
                            directly to your customers with your branding.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            What are the profit margins?
                            <span>+</span>
                        </button>
                        <div class="faq-answer">
                            Profit margins typically range from 30-90% depending on the product category and your
                            pricing strategy. Our profit calculator helps optimize margins.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            Is there customer support?
                            <span>+</span>
                        </button>
                        <div class="faq-answer">
                            Absolutely! We provide 24/7 customer support via chat, email, and phone. Our dedicated
                            success team helps you every step of the way.
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="contact-form">
                        <h3 style="margin-bottom: 2rem; color: #2563eb;">Get In Touch</h3>
                        <form id="contactForm" class="contact-form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="4" placeholder="Tell us about your business goals..."></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Send Message</button>

                            <div id="formMessage" style="margin-top: 15px;"></div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="whatsapp-float">
        <button class="whatsapp-btn">
            <i class="fab fa-whatsapp"></i>
        </button>
        <div class="whatsapp-popup">
            <p>Want to chat right now?</p>
            <a href="https://wa.me/447983243965?text=Hello%Team!" target="_blank">Chat on WhatsApp</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="logo" style="color: white;">Arrbaab</div>
                <div class="footer-links">
                    <a href="#hero">Home</a>
                    <a href="#portal">Portal</a>
                    <a href="#features">Features</a>
                    <a href="#contact">Contact</a>
                </div>
            </div>
            <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #374151;">
                <p>© 2025 Arrbaab. All rights reserved. | Developed By <a style="color: white;" class="reference-text"
                        href="https://pitgtech.com/" target="_blank">Prime Information Technology Group (PITG) </a>
                </p>
            </div>
        </div>
    </footer>
    <script>
        // Mobile menu toggle
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');

        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu if open
                navMenu.classList.remove('active');
            });
        });

        // FAQ toggle function
        function toggleFAQ(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('span');

            // Close all other FAQ items
            document.querySelectorAll('.faq-answer').forEach(item => {
                if (item !== answer) {
                    item.classList.remove('active');
                    item.previousElementSibling.querySelector('span').textContent = '+';
                }
            });

            // Toggle current FAQ item
            answer.classList.toggle('active');
            icon.textContent = answer.classList.contains('active') ? '−' : '+';
        }



        // Enhanced scroll effect for header
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = '#2563eb';
                header.style.backdropFilter = 'blur(25px)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.1)';
                header.style.backdropFilter = 'blur(20px)';
            }
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards and sections
        document.querySelectorAll('.portal-card, .step-card, .product-card, .feature-card, .testimonial-card').forEach(
            el => {
                el.style.opacity = '0';
                el.style.transform = 'tranaslateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });

        // Animate stats on load
        setTimeout(() => {
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                stat.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    stat.style.transform = 'scale(1)';
                }, 200);
            });
        }, 3500);

        // Toggle WhatsApp popup
        document.querySelector('.whatsapp-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelector('.whatsapp-popup').classList.toggle('active');
        });

        // Close WhatsApp popup when clicking outside
        document.addEventListener('click', (e) => {
            const popup = document.querySelector('.whatsapp-popup');
            const btn = document.querySelector('.whatsapp-btn');
            if (!popup.contains(e.target) && !btn.contains(e.target)) {
                popup.classList.remove('active');
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("contactForm");
            const messageDiv = document.getElementById("formMessage");
            const submitButton = form.querySelector('button[type="submit"]');

            function showMessage(message, color = 'green', duration = 3000) {
                messageDiv.innerHTML = `<span style="color: ${color};">${message}</span>`;
                messageDiv.style.display = 'block';

                setTimeout(() => {
                    messageDiv.innerHTML = '';
                    messageDiv.style.display = 'none';
                }, duration);
            }

            form.addEventListener("submit", async function(e) {
                e.preventDefault();

                // Save original button text
                const originalText = submitButton.innerHTML;

                // Show loading state on button
                submitButton.innerHTML = 'Loading...';

                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                try {
                    const response = await fetch("{{ route('contact') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: formData
                    });

                    if (response.ok) {
                        showMessage('Thank you! Your message has been sent.', 'green');
                        form.reset();
                    } else if (response.status === 422) {
                        const data = await response.json();
                        let errors = data.errors;
                        let errorHtml = '<ul style="color:red;">';
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorHtml += `<li>${errors[key][0]}</li>`;
                            }
                        }
                        errorHtml += '</ul>';
                        messageDiv.innerHTML = errorHtml;

                        setTimeout(() => {
                            messageDiv.innerHTML = '';
                        }, 3000);
                    } else {
                        showMessage('Something went wrong. Please try again later.', 'red');
                    }

                } catch (error) {
                    console.error("AJAX error:", error);
                    showMessage('Error sending request.', 'red');
                } finally {
                    // Restore original button text
                    submitButton.innerHTML = originalText;
                }
            });
        });
    </script>
</body>

</html>
