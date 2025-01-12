<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Space Grotesk', sans-serif;
        }

        .clip-path-diagonal {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .car-gradient {
            background: linear-gradient(90deg, #FF0000 0%, #000000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .car-gradient-modified {
            background: linear-gradient(90deg, #FF0000 0%, #FF0000 30%, #000000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .diagonal-border {
            background: linear-gradient(135deg, #FF0000 0%, #000000 100%);
            transform: skew(-12deg);
        }

        .hover-grow {
            transition: transform 0.3s ease;
        }

        .hover-grow:hover {
            transform: scale(1.02) translateY(-5px);
        }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Navigation -->
    <nav class="bg-black fixed w-full z-50 top-0">
        <div class="mx-8 md:mx-16 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-12 bg-red-600"></div>
                    <span class="text-2xl font-bold tracking-wider">Drive & Loc</span>
                </div>

                <div class="flex items-center gap-8">
                    <button class="text-sm tracking-widest hover:text-red-500 transition-colors font-medium">
                        <a href="./login.php">LOG IN</a>
                    </button>
                    <button class="diagonal-border px-8 py-3 text-sm tracking-widest hover:opacity-90 transition-opacity">
                        <a href="./signup.php"> REGISTER </a>
                    </button>  
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen pt-32 relative">
        <div class="absolute top-0 right-0 w-3/4 h-screen bg-gradient-to-l from-red-600/10 to-transparent -z-10"></div>
        <div class="mx-8 md:mx-16 grid md:grid-cols-2 gap-16 items-center">
            <div class="space-y-8">
                <h1 class="text-6xl md:text-7xl font-bold leading-tight">
                    DRIVE THE <span class="car-gradient">FUTURE</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-md">
                    Experience automotive excellence through our curated collection of premium vehicles.
                </p>
                <div class="flex gap-8">
                    <div class="space-y-2">
                        <div class="text-4xl font-bold car-gradient">#1</div>
                        <div class="uppercase tracking-wider text-sm">Premium Selection</div>
                    </div>
                    <div class="w-px bg-red-500/20"></div>
                    <div class="space-y-2">
                        <div class="text-4xl font-bold car-gradient-modified">24/7</div>
                        <div class="uppercase tracking-wider text-sm">Hour Support</div>
                    </div>
                </div>
            </div>
            <div class="relative group" id="car-showcase">
                <div class="absolute -inset-4 bg-red-500/20 rounded-full blur-xl group-hover:bg-red-500/30 transition-colors"></div>
                <img src="../assets/images/Dodge.png" alt="Luxury Car" class="w-full relative hover-grow">
            </div>
        </div>
    </section>

    <!-- Features Section with Integrated Stats -->
    <section class="py-32 relative">
        <div class="absolute inset-0 bg-neutral-900 clip-path-diagonal"></div>
        <div class="relative mx-8 md:mx-16">
            <div class="flex justify-between items-end mb-16">
                <h2 class="text-4xl font-bold">EXCEPTIONAL <br>FEATURES</h2>
                <div class="w-32 h-px bg-red-500"></div>
            </div>
            
            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-8 mt-24">

                <div class="hover-grow p-8 bg-black rounded-lg">
                    <div class="w-16 h-16 mb-6 relative">
                        <div class="absolute inset-0 bg-red-500/20 rounded-full blur-md"></div>
                        <img src="../assets/icons/clock-solid.svg" alt="" class="absolute inset-0 w-full h-full object-cover">                    </div>
                    <h3 class="text-xl font-bold mb-4">INSTANT BOOKING</h3>
                    <p class="text-gray-400">Rent cars instantly through our digital platform</p>
                </div>

                <div class="hover-grow p-8 bg-black rounded-lg md:translate-y-8">
                    <div class="w-16 h-16 mb-6 relative">
                        <div class="absolute inset-0 bg-red-500/20 rounded-full blur-md"></div>
                        <img src="../assets/icons/car-solid.svg" alt="" class="absolute inset-0 w-full h-full object-cover">                    </div>
                    <h3 class="text-xl font-bold mb-4">Diverse Collection</h3>
                    <p class="text-gray-400">A wide collection covering every appetite from luxury to sports cars</p>
                </div>

                <div class="hover-grow p-8 bg-black rounded-lg md:translate-y-16">
                    <div class="w-16 h-16 mb-6 relative">
                        <div class="absolute inset-0 bg-red-500/20 rounded-full blur-md"></div>
                        <img src="../assets/icons/globe-solid.svg" alt="" class="absolute inset-0 w-full h-full object-cover">                    </div>
                    <h3 class="text-xl font-bold mb-4">DIGITAL EXPERIENCE</h3>
                    <p class="text-gray-400">Fully digitalized business flow</p>
                </div>
            </div>

            <!-- Stats Section (Integrated) -->
            <div class="mt-32 mb-16">
                <div class="grid grid-cols-3 gap-8">
                    <div class="space-y-4 text-center hover-grow">
                        <div class="text-5xl font-bold car-gradient counter">700+</div>
                        <div class="uppercase tracking-widest text-sm">Satisfied customers</div>
                    </div>
                    <div class="space-y-4 text-center hover-grow">
                        <div class="text-5xl font-bold car-gradient counter">500+</div>
                        <div class="uppercase tracking-widest text-sm">5 star reviews</div>
                    </div>
                    <div class="space-y-4 text-center hover-grow">
                        <div class="text-5xl font-bold car-gradient counter">1200+</div>
                        <div class="uppercase tracking-widest text-sm">Cars rented so far</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Smooth scroll animation for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // GSAP Animations
        gsap.from("#car-showcase", {
            opacity: 0,
            x: 100,
            duration: 1.5,
            ease: "power3.out"
        });

        gsap.from(".counter", {
            scrollTrigger: {
                trigger: ".counter",
                start: "top center"
            },
            textContent: 0,
            duration: 2,
            ease: "power1.out",
            snap: { textContent: 1 },
            stagger: 0.2
        });

        // Parallax effect for car image
        document.addEventListener('mousemove', (e) => {
            const carShowcase = document.querySelector('#car-showcase');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
            carShowcase.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
    </script>
</body>
</html>