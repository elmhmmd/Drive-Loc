<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Drive & Loc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Space Grotesk', sans-serif;
        }

        .diagonal-border {
            background: linear-gradient(135deg, #FF0000 0%, #000000 100%);
            transform: skew(-12deg);
        }

        .car-gradient {
            background: linear-gradient(90deg, #FF0000 0%, #000000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen">
    <!-- Navigation (same as homepage) -->
    <nav class="bg-black fixed w-full z-50 top-0">
        <div class="mx-8 md:mx-16 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-12 bg-red-600"></div>
                    <a href="Homepage.php" class="text-2xl font-bold tracking-wider">Drive & Loc</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="pt-32 px-8 md:px-0">
        <div class="max-w-md mx-auto">
            <h2 class="text-4xl font-bold mb-8">Welcome Back</h2>
            
            <form action="process_login.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-3 bg-neutral-900 border border-neutral-800 rounded-lg focus:outline-none focus:border-red-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-3 bg-neutral-900 border border-neutral-800 rounded-lg focus:outline-none focus:border-red-500 transition-colors">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 bg-neutral-900 border-neutral-800 text-red-500 focus:ring-red-500">
                        <span class="ml-2 text-sm text-neutral-400">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-red-500 hover:text-red-400 transition-colors">Forgot Password?</a>
                </div>

                <button type="submit" 
                        class="w-full diagonal-border px-8 py-3 text-sm tracking-widest hover:opacity-90 transition-opacity">
                    LOGIN
                </button>
            </form>

            <p class="mt-8 text-center text-neutral-400">
                Don't have an account? 
                <a href="signup.php" class="text-red-500 hover:text-red-400 transition-colors">Sign up here</a>
            </p>
        </div>
    </div>
</body>
</html>
