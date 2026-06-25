<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Kare ONS Herbals | Nature\'s Goodness, Refined by Science')</title>
    <meta content="Premium Ayurvedic manufacturing and herbal healthcare solutions focusing on quality, purity, and effectiveness." name="description"/>
    
    <!-- IBM Plex Sans -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&amp;display=swap" rel="stylesheet"/>
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CDN for Stitch Design System -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed": "#e0e0e0",
                        "on-tertiary-container": "#044317",
                        "inverse-surface": "#393939",
                        "surface-container-high": "#e0e0e0",
                        "on-surface": "#161616",
                        "surface-container": "#f4f4f4",
                        "tertiary-fixed-dim": "#42be65",
                        "surface-container-low": "#f4f4f4",
                        "tertiary": "#198038",
                        "primary-fixed-dim": "#78a9ff",
                        "secondary-fixed-dim": "#c6c6c6",
                        "surface-bright": "#ffffff",
                        "outline": "#8d8d8d",
                        "inverse-primary": "#78a9ff",
                        "on-secondary-fixed-variant": "#525252",
                        "on-error": "#ffffff",
                        "surface-dim": "#e0e0e0",
                        "primary-container": "#d0e2ff",
                        "outline-variant": "#e0e0e0",
                        "on-tertiary-fixed": "#022d0d",
                        "on-secondary-fixed": "#161616",
                        "error-container": "#fff1f1",
                        "surface-tint": "#0f62fe",
                        "on-tertiary": "#ffffff",
                        "on-background": "#161616",
                        "surface-variant": "#f4f4f4",
                        "tertiary-container": "#a7f0ba",
                        "on-primary-fixed": "#001d6c",
                        "error": "#da1e28",
                        "on-secondary": "#ffffff",
                        "on-primary-container": "#161616",
                        "tertiary-fixed": "#a7f0ba",
                        "background": "#ffffff",
                        "on-tertiary-fixed-variant": "#044317",
                        "surface": "#ffffff",
                        "secondary": "#6f6f6f",
                        "on-surface-variant": "#525252",
                        "surface-container-lowest": "#ffffff",
                        "on-error-container": "#750e13",
                        "primary": "#0f62fe",
                        "on-secondary-container": "#525252",
                        "primary-fixed": "#d0e2ff",
                        "on-primary-fixed-variant": "#0043ce",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#f4f4f4",
                        "surface-container-highest": "#c6c6c6",
                        "secondary-container": "#e0e0e0"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["IBM Plex Sans", "sans-serif"],
                        "display": ["IBM Plex Sans", "sans-serif"],
                        "body": ["IBM Plex Sans", "sans-serif"],
                        "label": ["IBM Plex Sans", "sans-serif"]
                    },
                    "spacing": {
                        "gutter": "24px",
                        "margin-desktop": "64px",
                        "container-max": "1280px",
                        "section-gap": "96px",
                        "margin-mobile": "20px"
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .carbon-border { border: 1px solid #e0e0e0; }
        .text-stroke-primary {
            -webkit-text-stroke: 1px #0f62fe;
            color: transparent;
        }
        .nav-link-active {
            border-bottom: 2px solid #0f62fe;
            color: #0f62fe;
        }
    </style>
    
    <!-- AlpineJS for interactive components (if needed by other views) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface overflow-x-hidden selection:bg-primary selection:text-white">

    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-outline-variant transition-all duration-300" id="navbar">
        <div class="flex justify-between items-center max-w-container-max mx-auto px-margin-desktop h-16">
            <a href="{{ route('home') }}" class="font-display text-xl font-bold tracking-tight text-on-surface">
                Kare ONS Herbals
            </a>
            <div class="hidden md:flex items-center gap-8 h-full">
                <a class="nav-link-active flex items-center h-full px-1 text-sm font-medium" href="{{ route('home') }}">Home</a>
                <div class="relative group h-full flex items-center">
                    <button class="flex items-center gap-1 text-on-surface font-medium hover:text-primary transition-colors duration-200 text-sm">
                        Services
                        <span class="material-symbols-outlined text-sm">expand_more</span>
                    </button>
                    <div class="absolute top-full left-0 w-64 bg-white shadow-lg border border-outline-variant opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="flex flex-col py-1">
                            <a class="px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors" href="#">Third Party Manufacturing</a>
                            <a class="px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors" href="#">PCD Pharma Franchise</a>
                            <a class="px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors" href="#">Private Labeling</a>
                            <a class="px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors" href="#">Contract Manufacturing</a>
                        </div>
                    </div>
                </div>
                <a class="text-on-surface font-medium hover:text-primary transition-colors duration-200 text-sm" href="#">About</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors duration-200 text-sm" href="#">Reviews</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors duration-200 text-sm" href="#">Why Us</a>
                <a class="text-on-surface font-medium hover:text-primary transition-colors duration-200 text-sm" href="#">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                <button class="hidden md:block bg-primary text-white px-6 py-2 text-sm font-medium rounded-lg hover:bg-on-primary-fixed-variant transition-all duration-200">
                    GET QUOTE
                </button>
                <button class="md:hidden text-on-surface">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-on-surface text-white pt-20 pb-10 mt-16">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
                <div class="col-span-1">
                    <div class="text-xl font-bold text-white mb-6">Kare ONS Herbals</div>
                    <p class="text-sm text-secondary-fixed font-light mb-8 leading-relaxed">
                        Bringing the wisdom of Ayurveda with carefully crafted herbal formulations focused on holistic wellness and natural care. 
                    </p>
                    <div class="flex gap-3">
                        <a class="w-8 h-8 flex items-center justify-center border border-white/10 rounded hover:bg-primary transition-all" href="#">
                            <span class="material-symbols-outlined text-sm">public</span>
                        </a>
                        <a class="w-8 h-8 flex items-center justify-center border border-white/10 rounded hover:bg-primary transition-all" href="#">
                            <span class="material-symbols-outlined text-sm">mail</span>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-primary-fixed mb-8">Quick Links</h4>
                    <ul class="space-y-4 text-sm font-light text-secondary-fixed">
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">Home</a></li>
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">About Us</a></li>
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">GMP Certification</a></li>
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">Dr. Profile</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-primary-fixed mb-8">Manufacturing</h4>
                    <ul class="space-y-4 text-sm font-light text-secondary-fixed">
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">Third Party Pharma</a></li>
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">PCD Franchise</a></li>
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">Ayurvedic Syrups</a></li>
                        <li><a class="hover:text-primary-fixed transition-colors" href="#">Capsules &amp; Oils</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-primary-fixed mb-8">Contact Unit</h4>
                    <address class="not-italic text-sm text-secondary-fixed font-light leading-relaxed">
                        Kare ONS Herbals Manufacturing Unit,<br/>
                        Industrial Area, Delhi, India<br/><br/>
                        Phone: +91 9315436116<br/>
                        Email: kareonsherbal@gmail.com
                    </address>
                </div>
            </div>
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-6 text-xs text-secondary-fixed/50 font-medium">
                <p>&copy; {{ date('Y') }} Kare ONS Herbals. All rights reserved.</p>
                <div class="flex gap-8">
                    <a class="hover:text-white transition-colors" href="#">Privacy Policy</a>
                    <a class="hover:text-white transition-colors" href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>
