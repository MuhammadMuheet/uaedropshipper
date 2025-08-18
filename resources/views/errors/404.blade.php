<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #e3342f;
        }
        .error-message {
            font-size: 1.5rem;
            color: #4a5568;
            margin: 1rem 0;
        }
        .home-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .home-button:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Oops! The page you're looking for doesn't exist.</div>
        <p class="text-gray-600">It seems you may have taken a wrong turn. Don't worry, let's get you back on track!</p>
        <a href="{{ url('/') }}" class="home-button">Return to Home</a>
    </div>
</body>
</html>