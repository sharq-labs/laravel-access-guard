<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Verification</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
<div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md transform transition-all hover:shadow-2xl">
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-6">Access Verification</h2>
    <p class="text-sm text-gray-600 text-center mb-4">
        Enter your email below to receive a one-time password (OTP).
    </p>
    <form method="POST" action="{{ route('laravel-access-guard.submit') }}" class="space-y-6">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
                class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm placeholder-gray-400"
                placeholder="you@example.com"
            >
            <!-- Validation Error Message -->
            @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <button
                type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition duration-300 ease-in-out transform hover:scale-105"
            >
                Send OTP
            </button>
        </div>
    </form>
    <div class="mt-4 text-center">
        <p class="text-sm text-gray-500">
            Having trouble? <a href="#" class="text-indigo-600 hover:underline">Contact support</a>.
        </p>
    </div>
</div>
</body>
</html>
