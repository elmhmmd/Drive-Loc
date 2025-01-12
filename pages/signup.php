<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Drive & Loc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Space Grotesk', sans-serif;
        }

        .diagonal-border {
            background: linear-gradient(135deg, #FF0000 0%, #000000 100%);
            transform: skew(-6deg);  /* Reduced skew angle */
            border-radius: 4px;      /* Added subtle border radius */
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

    <!-- Signup Form -->
    <div class="pt-32 pb-24 px-8 md:px-0">  <!-- Added pb-24 for bottom spacing -->
        <div class="max-w-md mx-auto">
            <h2 class="text-4xl font-bold mb-8">Create Account</h2>
            
            <form action="../controllers/process_signup.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Username</label>
                    <input type="text" name="fullname" required 
                           class="w-full px-4 py-4 bg-neutral-800 border-2 border-neutral-700 rounded-lg 
                                  focus:outline-none focus:border-red-500 transition-colors
                                  text-lg placeholder-neutral-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-4 bg-neutral-800 border-2 border-neutral-700 rounded-lg 
                                  focus:outline-none focus:border-red-500 transition-colors
                                  text-lg placeholder-neutral-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-4 bg-neutral-800 border-2 border-neutral-700 rounded-lg 
                                  focus:outline-none focus:border-red-500 transition-colors
                                  text-lg placeholder-neutral-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Confirm Password</label>
                    <input type="password" name="confirm_password" required 
                           class="w-full px-4 py-4 bg-neutral-800 border-2 border-neutral-700 rounded-lg 
                                  focus:outline-none focus:border-red-500 transition-colors
                                  text-lg placeholder-neutral-400">
                </div>

                <button type="submit" 
                        class="w-full diagonal-border px-8 py-4 text-sm tracking-widest hover:opacity-90 
                               transition-opacity mt-6">
                    <span class="inline-block transform skew-x-6">CREATE ACCOUNT</span>
                </button>
            </form>

            <p class="mt-8 text-center text-neutral-400">
                Already have an account? 
                <a href="login.php" class="text-red-500 hover:text-red-400 transition-colors">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
