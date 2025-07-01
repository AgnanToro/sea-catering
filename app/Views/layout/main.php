<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'SEA Catering - Healthy Meals Delivery' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    
    <!-- Header -->
    <header x-data="headerComponent()" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center relative">
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold text-teal-600 cursor-pointer">
                    SEA Catering
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="/" :class="isActive('home') ? 'text-teal-600 font-medium' : 'text-gray-600'" class="hover:text-teal-500 transition-colors">
                    Beranda
                </a>
                <a href="/menu" :class="isActive('menu') ? 'text-teal-600 font-medium' : 'text-gray-600'" class="hover:text-teal-500 transition-colors">
                    Menu
                </a>
                <a href="/subscription" :class="isActive('subscription') ? 'text-teal-600 font-medium' : 'text-gray-600'" class="hover:text-teal-500 transition-colors">
                    Langganan
                </a>
                <a href="/contact" :class="isActive('contact') ? 'text-teal-600 font-medium' : 'text-gray-600'" class="hover:text-teal-500 transition-colors">
                    Hubungi Kami
                </a>
                
                <template x-if="isAuthenticated">
                    <div class="flex space-x-8">
                        <a href="/dashboard" :class="isActive('dashboard') ? 'text-teal-600 font-medium' : 'text-gray-600'" class="hover:text-teal-500 transition-colors">
                            Dashboard
                        </a>
                        <template x-if="userRole === 'admin'">
                            <a href="/dashboard/admin" :class="isActive('admin') ? 'text-teal-600 font-medium' : 'text-gray-600'" class="hover:text-teal-500 transition-colors">
                                Admin
                            </a>
                        </template>
                    </div>
                </template>
            </nav>

            <!-- Login/Logout Button -->
            <div class="hidden md:block z-10 relative">
                <template x-if="isAuthenticated">
                    <button
                        @click="handleLogout()"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors"
                    >
                        Keluar
                    </button>
                </template>
                <template x-if="!isAuthenticated">
                    <a href="/login" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        Masuk
                    </a>
                </template>
            </div>

            <!-- Mobile Menu Button -->
            <button
                class="md:hidden text-gray-600"
                @click="mobileMenuOpen = !mobileMenuOpen"
            >
                <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white py-4 px-4 shadow-md">
            <div class="flex flex-col space-y-4">
                <a href="/" class="text-gray-600 hover:text-teal-500 transition-colors text-left">Beranda</a>
                <a href="/menu" class="text-gray-600 hover:text-teal-500 transition-colors text-left">Menu</a>
                <a href="/subscription" class="text-gray-600 hover:text-teal-500 transition-colors text-left">Langganan</a>
                <a href="/contact" class="text-gray-600 hover:text-teal-500 transition-colors text-left">Hubungi Kami</a>
                
                <template x-if="isAuthenticated">
                    <div class="flex flex-col space-y-4">
                        <a href="/dashboard" class="text-gray-600 hover:text-teal-500 transition-colors text-left">Dashboard</a>
                        <template x-if="userRole === 'admin'">
                            <a href="/dashboard/admin" class="text-gray-600 hover:text-teal-500 transition-colors text-left">Admin</a>
                        </template>
                    </div>
                </template>
                
                <template x-if="isAuthenticated">
                    <button
                        @click="handleLogout()"
                        class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors text-left"
                    >
                        Keluar
                    </button>
                </template>
                <template x-if="!isAuthenticated">
                    <a href="/login" class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition-colors text-center block">
                        Masuk
                    </a>
                </template>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">SEA Catering</h3>
                    <p class="text-gray-400 mb-4">
                        Menyediakan makanan sehat dan lezat yang dikirim langsung ke pintu Anda.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-medium mb-4">Layanan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Paket Diet</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Paket Protein</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Paket Royal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Kustomisasi Menu</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-medium mb-4">Perusahaan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Karir</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-medium mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-teal-400"></i>
                            <span class="text-gray-400">Jl. Sehat No. 123, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3 text-teal-400"></i>
                            <span class="text-gray-400">+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3 text-teal-400"></i>
                            <span class="text-gray-400">info@seacatering.com</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-user mt-1 mr-3 text-teal-400"></i>
                            <span class="text-gray-400">24/7 Customer Support</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 SEA Catering. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Global JavaScript -->
    <script>
        function headerComponent() {
            return {
                mobileMenuOpen: false,
                isAuthenticated: <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>,
                userRole: '<?= session()->get('userRole') ?? 'user' ?>',

                isActive(page) {
                    const currentPath = window.location.pathname;
                    if (page === 'home') {
                        return currentPath === '/' || currentPath === '/home';
                    }
                    if (page === 'dashboard') {
                        return currentPath.includes('/dashboard');
                    }
                    if (page === 'admin') {
                        return currentPath.includes('/admin');
                    }
                    return currentPath.includes('/' + page);
                },

                async handleLogout() {
                    if (confirm('Yakin ingin logout?')) {
                        try {
                            // Use GET method since logout route is GET
                            window.location.href = '/logout';
                        } catch (error) {
                            console.error('Logout error:', error);
                            alert('Terjadi kesalahan saat logout');
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>
