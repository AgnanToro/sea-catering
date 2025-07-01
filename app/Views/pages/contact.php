<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div x-data="contactPage()" class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Hubungi Kami</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Ada pertanyaan atau butuh bantuan? Tim customer service kami siap membantu Anda 24/7.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>
                
                <!-- Success/Error Messages -->
                <div x-show="message" x-cloak :class="messageType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'" class="border rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i :class="messageType === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-triangle text-red-500'" class="text-xl mr-3"></i>
                        <span x-text="message"></span>
                    </div>
                </div>

                <form @submit.prevent="submitForm()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">
                                Nama Lengkap *
                            </label>
                            <input
                                type="text"
                                id="name"
                                x-model="formData.name"
                                required
                                :class="errors.name ? 'border-red-500' : 'border-gray-300'"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                placeholder="Masukkan nama lengkap"
                            />
                            <span x-show="errors.name" x-text="errors.name" class="text-red-500 text-sm mt-1"></span>
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">
                                Email *
                            </label>
                            <input
                                type="email"
                                id="email"
                                x-model="formData.email"
                                required
                                :class="errors.email ? 'border-red-500' : 'border-gray-300'"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                placeholder="email@example.com"
                            />
                            <span x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="phone" class="block text-gray-700 font-medium mb-2">
                                Nomor HP *
                            </label>
                            <input
                                type="tel"
                                id="phone"
                                x-model="formData.phone"
                                required
                                :class="errors.phone ? 'border-red-500' : 'border-gray-300'"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                                placeholder="08xxxxxxxxxx"
                            />
                            <span x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></span>
                        </div>

                        <div>
                            <label for="subject" class="block text-gray-700 font-medium mb-2">
                                Subjek *
                            </label>
                            <select
                                id="subject"
                                x-model="formData.subject"
                                required
                                :class="errors.subject ? 'border-red-500' : 'border-gray-300'"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                            >
                                <option value="">Pilih Subjek</option>
                                <option value="subscription">Pertanyaan Langganan</option>
                                <option value="menu">Pertanyaan Menu</option>
                                <option value="delivery">Pengiriman</option>
                                <option value="billing">Pembayaran</option>
                                <option value="technical">Masalah Teknis</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <span x-show="errors.subject" x-text="errors.subject" class="text-red-500 text-sm mt-1"></span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 font-medium mb-2">
                            Pesan *
                        </label>
                        <textarea
                            id="message"
                            x-model="formData.message"
                            rows="5"
                            required
                            :class="errors.message ? 'border-red-500' : 'border-gray-300'"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="Tulis pesan Anda di sini..."
                        ></textarea>
                        <span x-show="errors.message" x-text="errors.message" class="text-red-500 text-sm mt-1"></span>
                    </div>

                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="w-full bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white py-3 rounded-lg font-medium transition-colors"
                    >
                        <span x-show="!isSubmitting">Kirim Pesan</span>
                        <span x-show="isSubmitting">Mengirim...</span>
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <!-- Customer Service Team -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-headset text-teal-500 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold text-gray-800">Tim Customer Service</h3>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Tim profesional kami siap membantu Anda dengan pertanyaan apapun seputar layanan SEA Catering.
                    </p>
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-teal-200 flex items-center justify-center mr-4">
                            <i class="fas fa-user text-teal-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg text-gray-800">Brian</h4>
                            <p class="text-gray-600">Customer Service Manager</p>
                            <p class="text-teal-600 font-medium">+62 812-3456-7890</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-phone text-teal-500 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-800">Telepon</h4>
                        </div>
                        <div class="space-y-1">
                            <p class="text-gray-600 text-sm">Customer Service:</p>
                            <p class="text-gray-800 font-medium">+62 812-3456-7890</p>
                            <p class="text-gray-600 text-sm">WhatsApp:</p>
                            <p class="text-gray-800 font-medium">+62 813-4567-8901</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-envelope text-teal-500 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-800">Email</h4>
                        </div>
                        <div class="space-y-1">
                            <p class="text-gray-600 text-sm">Customer Service:</p>
                            <p class="text-gray-800 font-medium">info@seacatering.com</p>
                            <p class="text-gray-600 text-sm">Partnership:</p>
                            <p class="text-gray-800 font-medium">partner@seacatering.com</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-map-marker-alt text-teal-500 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-800">Alamat</h4>
                        </div>
                        <div class="space-y-1">
                            <p class="text-gray-600 text-sm">Kantor Pusat:</p>
                            <p class="text-gray-800 font-medium">Jl. Sehat No. 123</p>
                            <p class="text-gray-800 font-medium">Jakarta Selatan 12345</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-clock text-teal-500 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-800">Jam Operasional</h4>
                        </div>
                        <div class="space-y-1">
                            <p class="text-gray-600 text-sm">Senin - Jumat:</p>
                            <p class="text-gray-800 font-medium">08:00 - 18:00 WIB</p>
                            <p class="text-gray-600 text-sm">Sabtu - Minggu:</p>
                            <p class="text-gray-800 font-medium">09:00 - 15:00 WIB</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-question-circle text-teal-500 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold text-gray-800">FAQ</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">
                                Bagaimana cara berlangganan?
                            </h4>
                            <p class="text-gray-600 text-sm">
                                Daftar akun, pilih paket, isi form langganan, dan lakukan pembayaran.
                            </p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">
                                Apakah bisa mengubah menu?
                            </h4>
                            <p class="text-gray-600 text-sm">
                                Ya, Anda bisa mengubah preferensi menu melalui dashboard atau menghubungi customer service.
                            </p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">
                                Bagaimana sistem pembayaran?
                            </h4>
                            <p class="text-gray-600 text-sm">
                                Kami menerima transfer bank, e-wallet, dan kartu kredit. Pembayaran dilakukan di awal periode langganan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function contactPage() {
    return {
        isSubmitting: false,
        message: '',
        messageType: '',
        
        formData: {
            name: '',
            email: '',
            phone: '',
            subject: '',
            message: ''
        },

        errors: {},

        validateForm() {
            this.errors = {};

            // Validate name
            if (!this.formData.name.trim()) {
                this.errors.name = 'Nama harus diisi';
            }

            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!this.formData.email.trim()) {
                this.errors.email = 'Email harus diisi';
            } else if (!emailRegex.test(this.formData.email)) {
                this.errors.email = 'Format email tidak valid';
            }

            // Validate phone
            const phoneRegex = /^(\+62|0)[0-9]{9,13}$/;
            if (!this.formData.phone.trim()) {
                this.errors.phone = 'Nomor HP harus diisi';
            } else if (!phoneRegex.test(this.formData.phone)) {
                this.errors.phone = 'Format nomor HP tidak valid';
            }

            // Validate subject
            if (!this.formData.subject) {
                this.errors.subject = 'Subjek harus dipilih';
            }

            // Validate message
            if (!this.formData.message.trim()) {
                this.errors.message = 'Pesan harus diisi';
            } else if (this.formData.message.trim().length < 10) {
                this.errors.message = 'Pesan minimal 10 karakter';
            }

            return Object.keys(this.errors).length === 0;
        },

        async submitForm() {
            if (!this.validateForm()) {
                return;
            }

            this.isSubmitting = true;
            this.message = '';

            try {
                const response = await fetch('/api/contact', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const result = await response.json();

                if (response.ok) {
                    this.message = 'Pesan berhasil dikirim! Tim kami akan segera menghubungi Anda.';
                    this.messageType = 'success';
                    
                    // Reset form
                    this.formData = {
                        name: '',
                        email: '',
                        phone: '',
                        subject: '',
                        message: ''
                    };
                    this.errors = {};
                } else {
                    this.message = result.error || 'Terjadi kesalahan saat mengirim pesan';
                    this.messageType = 'error';
                }
            } catch (error) {
                console.error('Error:', error);
                this.message = 'Terjadi kesalahan. Silakan coba lagi.';
                this.messageType = 'error';
            } finally {
                this.isSubmitting = false;
                
                // Auto hide message after 5 seconds
                setTimeout(() => {
                    this.message = '';
                }, 5000);
            }
        }
    }
}
</script>

<?= $this->endSection() ?>
