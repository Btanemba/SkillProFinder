<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animated-gradient {
            background-size: 200% 200%;
            animation: gradient 8s ease infinite;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="h-screen relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-purple-600 via-cyan-400 to-blue-500 animated-gradient"></div>
    
    <!-- Floating Circles -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl float-animation"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-white/10 rounded-full blur-3xl float-animation" style="animation-delay: 4s;"></div>

    <!-- Main Content -->
    <div class="relative h-screen flex items-center justify-center p-3">
        <div class="max-w-md w-full">
            <!-- Logo/Brand -->
            <div class="text-center mb-4">
                <div class="inline-block p-2 bg-white/20 backdrop-blur-sm rounded-xl mb-2">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Welcome Back</h1>
                <p class="text-white/80 text-xs">Sign in to your account</p>
            </div>

            <!-- Card with Glass Effect -->
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden">
                <div class="p-5">
                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500/30 text-red-700 px-4 py-3 rounded-xl mb-6 backdrop-blur-sm">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-3">
                        @csrf

                        <!-- Email Field -->
                        <div class="space-y-1">
                            <label for="email" class="block text-xs font-semibold text-gray-700">Email Address</label>
                            <div class="relative group">
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="w-full px-3 py-2.5 pl-10 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/50 group-hover:bg-white text-sm">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-semibold text-gray-700">Password</label>
                            <div class="relative group">
                                <input id="password" type="password" name="password" required
                                    class="w-full px-3 py-2.5 pl-10 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/50 group-hover:bg-white text-sm">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-xs pt-0.5">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" id="remember" name="remember" class="w-3.5 h-3.5 text-purple-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-purple-500/50 transition-all duration-200">
                                <span class="ml-1.5 text-gray-700 font-medium group-hover:text-purple-600 transition-colors">Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-purple-600 hover:text-purple-700 font-semibold transition-colors duration-200">Forgot password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 via-cyan-400 to-blue-500 hover:from-purple-700 hover:via-cyan-500 hover:to-blue-600 text-white font-bold py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-2xl shadow-lg text-sm">
                            <span class="flex items-center justify-center">
                                Sign In
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-3">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white/50 text-gray-600 font-medium">Don't have an account?</span>
                        </div>
                    </div>

                    <!-- Register Options -->
                    <div class="space-y-2">
                        <p class="text-center text-xs text-gray-700 font-semibold mb-1.5">Register as:</p>
                        <a href="{{ route('register') }}?type=provider" class="flex items-center justify-center w-full py-2.5 px-4 border-2 border-purple-600 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-600 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Skill Provider
                        </a>
                        <a href="{{ route('register') }}?type=client" class="flex items-center justify-center w-full py-2.5 px-4 border-2 border-cyan-500 text-cyan-600 font-semibold rounded-xl hover:bg-cyan-50 transition-all duration-200 text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Client
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <p class="text-white/60 text-xs">© 2025 Skill App. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
