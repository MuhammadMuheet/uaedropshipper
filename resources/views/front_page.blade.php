<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>UAE Dropshipper - Professional Dropshipping Platform</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 95%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 100px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 0px 16px;
            transition: all 0.3s ease;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            flex-wrap: wrap;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #388707;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2.5rem;
            align-items: center;
            flex-grow: 1;
            justify-content: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #388707;
        }

        .mobile-menu {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
        }

        .get-started {
            position: relative;
            display: inline-block;
        }

        .get-started-btn {
            background: #388707;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .get-started-btn:hover {
            background: #2d6b05;
            transform: translateY(-2px);
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 140px;
            z-index: 1000;
        }

        .dropdown.active {
            display: block;
        }

        .dropdown a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: background 0.3s ease, color 0.3s ease;
            border-bottom: 0.1px solid #e0e0e0;
        }

        .dropdown a:last-child {
            border-bottom: none;
        }

        .dropdown a:hover {
            background: #f5f5f5;
            color: #388707;
        }

        .mobile-only {
            display: none;
        }

        @media (max-width: 768px) {
            header {
                padding: 1rem 2rem;
            }

            nav {
                flex-direction: column;
                align-items: flex-start;
                padding: 0.5rem 1rem;
            }

            .logo {
                width: 60%;
                font-size: 1.5rem;
                display: flex;
                align-items: center;
            }

            .mobile-menu {
                display: block;
                width: 40%;
                text-align: right;
                font-size: 1.5rem;
            }

            .nav-header {
                display: flex;
                width: 100%;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
                position: absolute;
                top: 100%;
                left: 0;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(12px);
                padding: 1rem;
                border-radius: 0 0 15px 15px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                z-index: 999;
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links a {
                padding: 0.5rem 0;
                width: 100%;
                text-align: left;
            }

            .desktop-only {
                display: none;
            }

            .mobile-only {
                display: block;
                width: 100%;
                margin: 0.5rem 0;
            }

            .get-started-btn {
                width: 100%;
                padding: 12px;
                font-size: 1.1rem;
                border-radius: 10px;
            }

            .dropdown {
                position: static;
                width: 100%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(12px);
                border-radius: 8px;
                margin-top: 0.5rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                padding: 0.5rem 0;
            }

            .dropdown a {
                padding: 12px 20px;
                width: 100%;
                text-align: left;
                border-bottom: 0.1px solid #e0e0e0;
            }

            .dropdown a:last-child {
                border-bottom: none;
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

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #388707 0%, #2d6b05 50%, #1f4d03 100%);
            padding: 200px 0 140px;
            color: white;
            position: relative;
            overflow: hidden;
            animation: gradientShift 15s ease infinite;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,0 1000,300 1000,1000 0,700"/></svg>');
            background-size: cover;
            opacity: 0.3;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: float 20s ease infinite;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(50px, 50px);
            }

            100% {
                transform: translate(0, 0);
            }
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-block;
            background: white;
            color: #388707;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-dashboard {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .dashboard-header {
            display: flex;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .dashboard-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot-red {
            background: #ff5f57;
        }

        .dot-yellow {
            background: #ffbd2e;
        }

        .dot-green {
            background: #28ca42;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .dashboard-chart {
            height: 80px;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }

        .chart-line {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(45deg, #4CAF50, #8BC34A);
            clip-path: polygon(0 80%, 20% 60%, 40% 70%, 60% 40%, 80% 30%, 100% 20%, 100% 100%, 0 100%);
        }

        /* Section Styles */
        section {
            padding: 80px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Portal Gallery */
        .portal-gallery {
            background: #f8f9fa;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .gallery-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-10px);
        }

        .gallery-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #388707, #4CAF50);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .gallery-content {
            padding: 1.5rem;
        }

        .gallery-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .gallery-description {
            color: #666;
            font-size: 0.95rem;
        }

        /* Steps Section */
        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .step-item {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 80px;
            height: 80px;
            background: #388707;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
            position: relative;
            z-index: 2;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 40px;
            left: calc(50% + 40px);
            width: calc(100% - 80px);
            height: 2px;
            background: #388707;
            opacity: 0.3;
        }

        .step-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .step-description {
            color: #666;
        }

        /* Popular Products */
        .products {
            background: #f8f9fa;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #388707, #4CAF50);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #388707;
            margin-bottom: 0.5rem;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
        }

        /* Countries Section */
        /* General Reset and Container */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Section Styling */
        #countries {
            padding: 4rem 0;
            background: #fff;
            overflow: hidden;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: clamp(1.8rem, 5vw, 2.5rem);
            color: #1a1a1a;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: clamp(1rem, 3vw, 1.2rem);
            color: #4a4a4a;
            max-width: 600px;
            margin: 0 auto;
        }

        .section-text {
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            color: #4a4a4a;
            max-width: 700px;
            margin: 1.5rem auto;
            text-align: center;
        }

        /* Countries Map */
        .countries-map {
            position: relative;
            min-height: clamp(300px, 80vw, 600px);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
        }

        .connectors {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .connector {
            fill: none;
            stroke: #388707;
            stroke-width: 2;
            stroke-dasharray: 5;
            stroke-linecap: round;
            animation: drawPath 2s ease-in-out infinite;
        }

        /* UAE Center */
        .uae-center {
            position: relative;
            z-index: 10;
        }

        .uae-flag {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        /* Country Pointers */
        .country-pointer {
            position: absolute;
            width: clamp(70px, 10vw, 90px);
            height: clamp(70px, 10vw, 90px);
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            border: 2px solid #388707;
            font-size: clamp(0.8rem, 2vw, 0.8rem);
            font-weight: 600;
            color: #1a1a1a;
            text-align: center;
            transition: transform 0.3s ease, background 0.3s ease;
            z-index: 5;
        }

        .country-pointer:hover,
        .country-pointer:focus {
            transform: scale(1.1);
            background: #e6f3e6;
            outline: none;
        }

        .country-pointer:focus {
            box-shadow: 0 0 0 3px rgba(56, 135, 7, 0.3);
        }

        /* Country Positions (Desktop - Radial Layout) */
        .country-1 {
            top: 10%;
            left: 10%;
        }

        .country-2 {
            top: 10%;
            right: 10%;
        }

        .country-3 {
            bottom: 10%;
            left: 10%;
        }

        .country-4 {
            bottom: 10%;
            right: 10%;
        }

        .country-5 {
            left: 5%;
            top: 50%;
            transform: translateY(-50%);
        }

        .country-6 {
            right: 5%;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Animations */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes drawPath {
            0% {
                stroke-dashoffset: 10;
            }

            50% {
                stroke-dashoffset: 5;
            }

            100% {
                stroke-dashoffset: 10;
            }
        }

        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease-in forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .countries-map {
                min-height: 400px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .connectors {
                z-index: 1;
            }

            .country-pointer {
                width: 60px;
                height: 60px;
                font-size: 0.8rem;
                position: relative;
                margin: 0;
            }

            .uae-flag {
                width: 90px;
                height: 90px;
                border-width: 3px;
            }

            .uae-flag span {
                font-size: 0.9rem;
            }

            .country-1,
            .country-2,
            .country-3,
            .country-4,
            .country-5,
            .country-6 {
                position: relative;
                top: auto;
                left: auto;
                right: auto;
                bottom: auto;
                transform: none;
            }

            /* Top Row */
            .country-1,
            .country-2,
            .country-3 {
                display: inline-flex;
            }

            .countries-map::before {
                content: '';
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
                order: 1;
            }

            .country-1,
            .country-2,
            .country-3 {
                order: 1;
            }

            .uae-center {
                order: 2;
            }

            /* Bottom Row */
            .country-4,
            .country-5,
            .country-6 {
                display: inline-flex;
                order: 3;
            }

            .countries-map::after {
                content: '';
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
                order: 3;
            }

            .connector {
                stroke-width: 1.5;
            }

            .connector[data-from="country-1"] {
                d: "M33% 10% Q 50% 30%, 50% 50%";
            }

            .connector[data-from="country-2"] {
                d: "M50% 10% Q 50% 30%, 50% 50%";
            }

            .connector[data-from="country-3"] {
                d: "M67% 10% Q 50% 30%, 50% 50%";
            }

            .connector[data-from="country-4"] {
                d: "M33% 90% Q 50% 70%, 50% 50%";
            }

            .connector[data-from="country-5"] {
                d: "M50% 90% Q 50% 70%, 50% 50%";
            }

            .connector[data-from="country-6"] {
                d: "M67% 90% Q 50% 70%, 50% 50%";
            }
        }

        @media (max-width: 480px) {
            #countries {
                padding: 2rem 0;
            }

            .countries-map {
                min-height: 350px;
            }

            .uae-flag {
                width: 80px;
                height: 80px;
            }

            .uae-flag span {
                font-size: 0.8rem;
            }

            .country-pointer {
                width: 50px;
                height: 50px;
                font-size: 0.7rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .section-subtitle,
            .section-text {
                font-size: 0.9rem;
            }

            .connector {
                stroke-width: 1;
            }
        }

        /* Features Section */
        .features {
            background: #f8f9fa;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            color: #388707;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-description {
            color: #666;
        }

        /* Testimonials */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .testimonial-quote {
            font-size: 3rem;
            color: #388707;
            opacity: 0.3;
            position: absolute;
            top: 10px;
            left: 20px;
        }

        .testimonial-text {
            margin-bottom: 1.5rem;
            font-style: italic;
            position: relative;
            z-index: 2;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            background: #388707;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .author-info h4 {
            margin-bottom: 0.25rem;
            color: #333;
        }

        .author-info p {
            color: #666;
            font-size: 0.9rem;
        }

        /* FAQ & Contact */
        .faq-contact {
            background: #f8f9fa;
        }

        .faq-contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
        }

        .faq-item {
            margin-bottom: 1.5rem;
        }

        .faq-question {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: #388707;
            color: white;
        }

        .faq-answer {
            background: white;
            padding: 0 1.5rem;
            border-radius: 0 0 10px 10px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-answer.active {
            padding: 1rem 1.5rem;
            max-height: 200px;
        }

        .contact-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #388707;
        }

        .submit-btn {
            background: #388707;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            background: #2d6b05;
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #388707;
        }

        .footer-section p,
        .footer-section a {
            color: #ccc;
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: block;
        }

        .footer-section a:hover {
            color: #388707;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #555;
            color: #999;
        }

        .footer-bottom a {
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                width: calc(100% - 20px);
                margin: 0 10px;
                top: 10px;
            }

            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .step-item:not(:last-child)::after {
                display: none;
            }

            .faq-contact-grid {
                grid-template-columns: 1fr;
            }

            .countries-map {
                min-height: 300px;
            }

            .country-pointer {
                width: 60px;
                height: 60px;
                font-size: 0.7rem;
            }

            .country-1,
            .country-2 {
                top: -0px;
            }

            .country-3,
            .country-4 {
                bottom: -0px;
            }

            .country-5 {
                left: -0px;
            }

            .country-6 {
                right: -0px;
            }

            .country-1::before {
                height: 60px;
                bottom: -60px;
            }

            .country-2::before {
                height: 60px;
                bottom: -60px;
            }

            .country-3::before {
                height: 60px;
                top: -60px;
            }

            .country-4::before {
                height: 60px;
                top: -60px;
            }

            .country-5::before {
                width: 60px;
            }

            .country-6::before {
                width: 60px;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Animation classes */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reference-text {
            color: #fff;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="nav-header">
                <div class="logo mob-order-1">
                    <img src="logo.png" alt="uae dropshipper" width="120" height="auto">
                </div>
                <button class="mobile-menu mob-order-2">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#portal">Portal</a></li>
                <li><a href="#steps">How to Start</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#faq">Contact</a></li>
                <li class="mobile-only get-started">
                    <button class="get-started-btn">Get Started</button>
                    <div class="dropdown">
                        <a href="/login">Login</a>
                        <a href="/user-register">Register</a>
                    </div>
                </li>
            </ul>
            <div class="get-started desktop-only">
                <button class="get-started-btn">Get Started</button>
                <div class="dropdown">
                    <a href="/login">Login</a>
                    <a href="/user-register">Register</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text fade-in">
                    <h1>Start Your Dropshipping Empire Today</h1>
                    <p>Join thousands of successful entrepreneurs who trust UAE Dropshipper for their dropshipping
                        business. Fast, reliable, and profitable solutions for your e-commerce success.</p>
                    <a href="/user-register" class="cta-button">Get Started Now</a>
                </div>
                <div class="hero-visual fade-in">
                    <div class="hero-dashboard">
                        <div class="dashboard-header">
                            <div class="dashboard-dot dot-red"></div>
                            <div class="dashboard-dot dot-yellow"></div>
                            <div class="dashboard-dot dot-green"></div>
                        </div>
                        <div class="dashboard-stats">
                            <div class="stat-card">
                                <div class="stat-number">$45K</div>
                                <div class="stat-label">Monthly Revenue</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">1,247</div>
                                <div class="stat-label">Orders</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">89%</div>
                                <div class="stat-label">Success Rate</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Support</div>
                            </div>
                        </div>
                        <div class="dashboard-chart">
                            <div class="chart-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal Gallery Section -->
    <section id="portal" class="portal-gallery">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Our Dropshipping Portal</h2>
                <p class="section-subtitle">Explore our comprehensive platform designed to streamline your dropshipping
                    operations</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item fade-in">
                    <div class="gallery-image">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="gallery-content">
                        <h3 class="gallery-title">Analytics Dashboard</h3>
                        <p class="gallery-description">Real-time insights into your sales, profits, and performance
                            metrics with detailed analytics and reporting tools.</p>
                    </div>
                </div>
                <div class="gallery-item fade-in">
                    <div class="gallery-image">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="gallery-content">
                        <h3 class="gallery-title">Product Management</h3>
                        <p class="gallery-description">Easily manage your product catalog, pricing, and inventory with
                            our intuitive product management system.</p>
                    </div>
                </div>
                <div class="gallery-item fade-in">
                    <div class="gallery-image">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="gallery-content">
                        <h3 class="gallery-title">Order Processing</h3>
                        <p class="gallery-description">Streamlined order fulfillment process with automated tracking
                            and
                            customer notification systems.</p>
                    </div>
                </div>
                <div class="gallery-item fade-in">
                    <div class="gallery-image">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="gallery-content">
                        <h3 class="gallery-title">Customer Management</h3>
                        <p class="gallery-description">Comprehensive customer relationship management tools to help you
                            build lasting relationships.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How to Start Section -->
    <section id="steps">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Start Working in 3 Simple Steps</h2>
                <p class="section-subtitle">Get your dropshipping business up and running in minutes</p>
            </div>
            <div class="steps-container">
                <div class="step-item fade-in">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Register Your Account</h3>
                    <p class="step-description">Sign up for free and create your personalized dropshipping account with
                        all necessary business details.</p>
                </div>
                <div class="step-item fade-in">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Choose Your Products</h3>
                    <p class="step-description">Browse our extensive catalog and select the products you want to sell
                        in your online store.</p>
                </div>
                <div class="step-item fade-in">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Start Selling</h3>
                    <p class="step-description">Launch your store and start making sales while we handle fulfillment
                        and shipping for you.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Products Section -->
    <section id="products" class="products">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Popular Products</h2>
                <p class="section-subtitle">Discover our best-selling items that are driving success for our partners
                </p>
            </div>
            <div class="products-grid">
                <div class="product-card fade-in">
                    <div class="product-image">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Smartphone Accessories</h3>
                        <div class="product-price">$15 - $89</div>
                        <p class="product-description">Premium phone cases, chargers, and accessories with high profit
                            margins.</p>
                    </div>
                </div>
                <div class="product-card fade-in">
                    <div class="product-image">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Fashion Apparel</h3>
                        <div class="product-price">$25 - $120</div>
                        <p class="product-description">Trendy clothing and fashion items that appeal to a wide
                            audience.</p>
                    </div>
                </div>
                <div class="product-card fade-in">
                    <div class="product-image">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Home Decor</h3>
                        <div class="product-price">$20 - $150</div>
                        <p class="product-description">Beautiful home decoration items and furniture accessories.</p>
                    </div>
                </div>
                <div class="product-card fade-in">
                    <div class="product-image">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Fitness Equipment</h3>
                        <div class="product-price">$30 - $200</div>
                        <p class="product-description">Health and fitness products for the growing wellness market.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Countries Available Section -->
    <section id="countries">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Launch Your UAE Business Online</h2>
                <p class="section-subtitle">Entrepreneurs from these countries can start their dropshipping business in
                    the UAE with UAE Dropshipper's seamless online support.</p>
            </div>
            <div class="countries-map">
                <svg class="connectors" width="100%" height="100%" preserveAspectRatio="xMidYMid meet">
                    <path class="connector" d="M15% 15% Q 50% 50%, 50% 50%" data-from="country-1" />
                    <path class="connector" d="M50% 15% Q 50% 50%, 50% 50%" data-from="country-2" />
                    <path class="connector" d="M85% 15% Q 50% 50%, 50% 50%" data-from="country-3" />
                    <path class="connector" d="M15% 85% Q 50% 50%, 50% 50%" data-from="country-4" />
                    <path class="connector" d="M50% 85% Q 50% 50%, 50% 50%" data-from="country-5" />
                    <path class="connector" d="M85% 85% Q 50% 50%, 50% 50%" data-from="country-6" />
                </svg>
                <div data-country="Bangladesh">
                    <img class="country-pointer country-1" src="countries/bangladesh.png" width="110"
                        style="object-fit: cover; object-position: -22px center;">
                </div>
                <div data-country="Pakistan">
                    <img class="country-pointer country-2" src="countries/pakistan.png" width="110"
                        style="object-fit: cover; object-position: -25px center;">
                </div>
                <div data-country="Sri Lanka">
                    <img class="country-pointer country-3" src="countries/Sri-Lanka.png" width="111000"
                        style="object-fit: cover; object-position: -70px center;">
                </div>
                <div data-country="India">
                    <img class="country-pointer country-4" src="countries/india.png" width="110"
                        style="object-fit:cover">
                </div>
                <div data-country="Afghanistan">
                    <img class="country-pointer country-5" src="countries/Afghanistan.png" width="110"
                        style="object-fit:cover">
                </div>
                <div data-country="Iran">
                    <img class="country-pointer country-6" src="countries/iran.png" width="110"
                        style="object-fit:cover">
                </div>
                <div class="uae-center">
                    <div class="uae-flag">
                        <img src="countries/uae-flag.png" width="130">
                    </div>
                </div>
            </div>
            <div class="text-center fade-in">
                <p class="section-text">
                    Connect with UAE Dropshipper to launch your dropshipping business in the UAE from anywhere in the
                    world, with full online support.
                </p>
            </div>
        </div>
    </section>
    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Platform Features</h2>
                <p class="section-subtitle">Everything you need to succeed in dropshipping</p>
            </div>
            <div class="features-grid">
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title">Lightning Fast</h3>
                    <p class="feature-description">Quick order processing and fast shipping to ensure customer
                        satisfaction and repeat business.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="feature-title">Easy Payments</h3>
                    <p class="feature-description">Multiple payment options and secure transactions for both you and
                        your customers.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="feature-title">Profit Calculator</h3>
                    <p class="feature-description">Real-time profit calculations to help you optimize pricing and
                        maximize your earnings.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3 class="feature-title">Advanced Reposting</h3>
                    <p class="feature-description">Automated product reposting and inventory management across multiple
                        platforms.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Client Reviews</h2>
                <p class="section-subtitle">See what our successful partners have to say about UAE Dropshipper</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card fade-in">
                    <div class="testimonial-quote">"</div>
                    <p class="testimonial-text">UAE Dropshipper transformed my business completely. I went from
                        struggling with inventory to making consistent profits every month. The platform is incredibly
                        user-friendly and the support team is amazing.</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">AM</div>
                        <div class="author-info">
                            <h4>Ahmed Mohammed</h4>
                            <p>Dubai, UAE</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card fade-in">
                    <div class="testimonial-quote">"</div>
                    <p class="testimonial-text">The profit calculator feature alone saved me countless hours. Now I can
                        quickly determine which products will be most profitable and focus my marketing efforts
                        accordingly.</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">SA</div>
                        <div class="author-info">
                            <h4>Sarah Al-Rashid</h4>
                            <p>Abu Dhabi, UAE</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card fade-in">
                    <div class="testimonial-quote">"</div>
                    <p class="testimonial-text">Starting with UAE Dropshipper was the best decision I made for my
                        e-commerce journey. The three-step process made it so easy to get started, and I was making
                        sales within a week!</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">KA</div>
                        <div class="author-info">
                            <h4>Khalid Al-Mansouri</h4>
                            <p>Sharjah, UAE</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ & Contact Section -->
    <section id="faq" class="faq-contact">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">FAQ & Contact</h2>
                <p class="section-subtitle">Get answers to common questions or reach out to us directly</p>
            </div>
            <div class="faq-contact-grid">
                <div class="faq-section fade-in">
                    <h3 style="margin-bottom: 2rem; color: #333; font-size: 1.5rem;">Frequently Asked Questions</h3>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>How do I get started with dropshipping?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Simply register for an account, choose your products from our catalog, and start selling.
                                We handle all the fulfillment and shipping for you.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>What are the costs involved?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Registration is free. You only pay for the products when you make a sale, plus a small
                                platform fee for our services.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>How long does shipping take?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Standard shipping within UAE takes 1-3 business days. Express shipping is available for
                                next-day delivery in major cities.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Do you provide customer support?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, we provide 24/7 customer support through chat, email, and phone to help you succeed
                                in your dropshipping business.</p>
                        </div>
                    </div>
                </div>
                <div class="contact-section fade-in">
                    <h3 style="margin-bottom: 2rem; color: #333; font-size: 1.5rem;">Contact Us</h3>
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
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>

                        <!-- Success/Error Message Container -->
                        <div id="formMessage" style="margin-top: 15px;"></div>
                    </form>
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
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>UAE Dropshipper</h3>
                    <p>Your trusted partner for dropshipping success in the UAE and beyond. We provide comprehensive
                        solutions for modern e-commerce entrepreneurs.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="#home">Home</a>
                    <a href="#portal">Portal</a>
                    <a href="#steps">How to Start</a>
                    <a href="#products">Products</a>
                    <a href="#features">Features</a>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <a href="#faq">FAQ</a>
                    <a href="#testimonials">Reviews</a>
                    <a href="#faq">Contact Us</a>
                    <a href="#">Help Center</a>
                    <a href="#">Documentation</a>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-envelope"></i> info@uaedropshipper.com</p>
                    <p><i class="fas fa-phone"></i> +44 7983 243965</p>
                    <p><i class="fas fa-map-marker-alt"></i> Ras Al Khor WareHouse#32 UAE</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 UAE Dropshipper. All rights reserved. | Developed By <a class="reference-text"
                        href="https://pitgtech.com/" target="_blank">Prime Information Technology Group (PITG) </a>
                </p>
            </div>
        </div>
    </footer>

    <script>
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
            });
        });

        // FAQ toggle functionality
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('i');

            // Close all other FAQ items
            document.querySelectorAll('.faq-answer').forEach(item => {
                if (item !== answer) {
                    item.classList.remove('active');
                }
            });

            document.querySelectorAll('.faq-question i').forEach(item => {
                if (item !== icon) {
                    item.classList.remove('fa-chevron-up');
                    item.classList.add('fa-chevron-down');
                }
            });

            // Toggle current FAQ item
            answer.classList.toggle('active');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        }

        // FAQ toggle functionality
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('i');
            document.querySelectorAll('.faq-answer').forEach(item => {
                if (item !== answer) {
                    item.classList.remove('active');
                }
            });
            document.querySelectorAll('.faq-question i').forEach(item => {
                if (item !== icon) {
                    item.classList.remove('fa-chevron-up');
                    item.classList.add('fa-chevron-down');
                }
            });
            answer.classList.toggle('active');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        }
        // Fade-in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
        // Toggle mobile menu
        document.querySelector('.mobile-menu').addEventListener('click', (e) => {
            e.stopPropagation();
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
            // Close dropdown when toggling mobile menu
            document.querySelector('.dropdown').classList.remove('active');
        });

        // Toggle dropdown on Get Started button click for both mobile and desktop
        document.querySelectorAll('.get-started-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent event from bubbling up
                const dropdown = btn.nextElementSibling; // Get the dropdown within the same parent
                dropdown.classList.toggle('active');

                // Close other dropdowns to avoid multiple open dropdowns
                document.querySelectorAll('.dropdown').forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
            });
        });

        // Close all dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                const getStartedBtn = dropdown.previousElementSibling; // Get the associated button
                if (!getStartedBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });
        // Toggle WhatsApp popup
        document.querySelector('.whatsapp-btn').addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent event from closing dropdown
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
        //Country section
        document.addEventListener('DOMContentLoaded', () => {

            const updateConnectors = () => {
                const map = document.querySelector('.countries-map');
                const svg = document.querySelector('.connectors');
                const uae = document.querySelector('.uae-center').getBoundingClientRect();
                const mapRect = map.getBoundingClientRect();

                document.querySelectorAll('.country-pointer').forEach(country => {
                    const rect = country.getBoundingClientRect();
                    const path = svg.querySelector(`.connector[data-from="${country.classList[1]}"]`);
                    const startX = ((rect.left + rect.width / 2) - mapRect.left) / mapRect.width * 100;
                    const startY = ((rect.top + rect.height / 2) - mapRect.top) / mapRect.height * 100;
                    const endX = ((uae.left + uae.width / 2) - mapRect.left) / mapRect.width * 100;
                    const endY = ((uae.top + uae.height / 2) - mapRect.top) / mapRect.height * 100;
                    const controlX = (startX + endX) / 2;
                    const controlY = startY < endY ? Math.min(startY + 20, endY - 20) : Math.max(
                        startY - 20, endY + 20);
                    path.setAttribute('d',
                        `M${startX}% ${startY}% Q${controlX}% ${controlY}%, ${endX}% ${endY}%`);
                });
            };

            window.addEventListener('resize', updateConnectors);
            window.addEventListener('load', updateConnectors);
            updateConnectors();
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
