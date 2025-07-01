<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-900/90 to-transparent z-10"></div>
        <img
            src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
            alt="Healthy Meals"
            class="w-full h-full object-cover object-center"
        />
    </div>
    <div class="container mx-auto px-4 py-24 md:py-32 relative z-20">
        <div class="max-w-2xl">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Healthy Meals, Anytime, Anywhere
            </h1>
            <p class="text-lg md:text-xl text-white/90 mb-8">
                Nikmati makanan sehat dan lezat yang dikirim langsung ke pintu Anda. 
                Kami menyediakan pilihan menu yang dapat disesuaikan dengan kebutuhan nutrisi Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/menu" class="bg-teal-500 hover:bg-teal-600 text-white px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Lihat Menu
                </a>
                <a href="/subscription" class="bg-white hover:bg-gray-100 text-teal-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Berlangganan
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Layanan Kami</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                SEA Catering menyediakan layanan makanan sehat dengan berbagai pilihan menu 
                yang dapat disesuaikan dengan kebutuhan nutrisi Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1547496502-affa22d38842?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80"
                        alt="Kustomisasi Makanan"
                        class="w-full h-full object-cover object-center"
                    />
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-utensils text-teal-500 text-xl mr-3"></i>
                        <h3 class="text-xl font-semibold text-gray-800">
                            Kustomisasi Makanan
                        </h3>
                    </div>
                    <p class="text-gray-600">
                        Sesuaikan menu makanan Anda berdasarkan preferensi, kebutuhan diet, 
                        dan tujuan nutrisi Anda.
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                        alt="Pengiriman"
                        class="w-full h-full object-cover object-center"
                    />
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-truck text-teal-500 text-xl mr-3"></i>
                        <h3 class="text-xl font-semibold text-gray-800">
                            Pengiriman ke Kota Besar
                        </h3>
                    </div>
                    <p class="text-gray-600">
                        Kami mengirimkan makanan segar langsung ke pintu Anda di berbagai 
                        kota besar di Indonesia.
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2053&q=80"
                        alt="Informasi Nutrisi"
                        class="w-full h-full object-cover object-center"
                    />
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-heartbeat text-teal-500 text-xl mr-3"></i>
                        <h3 class="text-xl font-semibold text-gray-800">
                            Informasi Nutrisi
                        </h3>
                    </div>
                    <p class="text-gray-600">
                        Dapatkan informasi nutrisi lengkap untuk setiap makanan, termasuk 
                        kalori, protein, karbohidrat, dan lemak.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Plans Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Pilihan Paket</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Pilih paket yang sesuai dengan kebutuhan dan gaya hidup Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Plan 1 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-teal-500 py-4">
                    <h3 class="text-xl font-bold text-white text-center">Paket Diet</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <span class="text-4xl font-bold text-gray-800">Rp 75.000</span>
                        <span class="text-gray-600 ml-2">/ hari</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Makanan rendah kalori</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Porsi terkontrol</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Informasi nutrisi lengkap</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Konsultasi diet gratis</span>
                        </li>
                    </ul>
                    <a href="/subscription" class="w-full bg-teal-500 hover:bg-teal-600 text-white py-3 rounded-lg font-medium transition-colors text-center block">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- Plan 2 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow transform scale-105 border-2 border-teal-500">
                <div class="bg-teal-600 py-4 relative">
                    <div class="absolute -top-4 right-4 bg-yellow-400 text-teal-800 text-xs font-bold px-3 py-1 rounded-full">
                        TERPOPULER
                    </div>
                    <h3 class="text-xl font-bold text-white text-center">Paket Protein</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <span class="text-4xl font-bold text-gray-800">Rp 95.000</span>
                        <span class="text-gray-600 ml-2">/ hari</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Tinggi protein (30-35g per porsi)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Ideal untuk pembentukan otot</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Karbohidrat kompleks</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Konsultasi nutrisi gratis</span>
                        </li>
                    </ul>
                    <a href="/subscription" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-medium transition-colors text-center block">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- Plan 3 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-teal-500 py-4">
                    <h3 class="text-xl font-bold text-white text-center">Paket Royal</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <span class="text-4xl font-bold text-gray-800">Rp 120.000</span>
                        <span class="text-gray-600 ml-2">/ hari</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Menu premium</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Porsi lebih besar</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Termasuk camilan sehat</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Konsultasi dengan ahli gizi</span>
                        </li>
                    </ul>
                    <a href="/subscription" class="w-full bg-teal-500 hover:bg-teal-600 text-white py-3 rounded-lg font-medium transition-colors text-center block">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Testimoni Pelanggan</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Lihat apa kata pelanggan kami tentang layanan SEA Catering.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400 flex">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-600 italic mb-6">
                    "Saya sudah mencoba berbagai layanan katering diet, tapi SEA Catering 
                    adalah yang terbaik! Makanannya lezat dan saya berhasil menurunkan 5kg 
                    dalam sebulan."
                </p>
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-teal-200 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-teal-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Anita Wijaya</h4>
                        <p class="text-sm text-gray-500">Jakarta</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400 flex">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <p class="text-gray-600 italic mb-6">
                    "Sebagai atlet, saya membutuhkan asupan protein yang tinggi. Paket Protein 
                    dari SEA Catering sangat membantu performa saya. Pengirimannya juga selalu 
                    tepat waktu!"
                </p>
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-teal-200 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-teal-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Budi Santoso</h4>
                        <p class="text-sm text-gray-500">Surabaya</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400 flex">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-600 italic mb-6">
                    "Paket Royal benar-benar royal! Makanannya enak, porsinya pas, dan 
                    camilannya juga sehat. Sangat cocok untuk saya yang sibuk tapi tetap 
                    ingin makan sehat."
                </p>
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-teal-200 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-teal-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Citra Dewi</h4>
                        <p class="text-sm text-gray-500">Bandung</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-teal-700">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
            Siap untuk Memulai Hidup Sehat?
        </h2>
        <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
            Bergabunglah dengan ribuan pelanggan yang sudah merasakan manfaat makanan sehat dari SEA Catering.
        </p>
        <a href="/subscription" class="bg-white hover:bg-gray-100 text-teal-700 px-8 py-3 rounded-lg font-medium transition-colors">
            Mulai Berlangganan
        </a>
    </div>
</section>

<?= $this->endSection() ?>
