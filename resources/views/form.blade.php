<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Verification</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex items-center justify-center py-6">
    <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-sm">
        <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">Access Verification</h2>
        <form method="POST" action="{{ route('laravel-access-guard.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Enter your email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="your-email@example.com"
                >
            </div>
            <div>
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Send OTP
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
