<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - I.M System</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md text-center">
        <h1 class="text-6xl font-bold text-gray-900 mb-8">Hello World</h1>
        <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-8 rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:scale-105">
            Go to Login Page
        </a>
        </div>
    </body>
</html>