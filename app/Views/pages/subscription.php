<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div x-data="subscriptionPage()" class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Berlangganan SEA Catering</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Pilih paket yang sesuai dengan kebutuhan Anda dan mulai hidup sehat bersama kami.
            </p>
        </div>

        <!-- Login Required Notice -->
        <div x-show="!isAuthenticated" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-yellow-600 text-xl mr-3"></i>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">Login Diperlukan</h3>
                    <p class="text-yellow-700 mb-4">
                        Untuk melakukan pemesanan, Anda perlu login terlebih dahulu. 
                        Jika belum memiliki akun, daftar sekarang dan dapatkan penawaran khusus!
                    </p>
                    <div class="flex gap-4">
                        <a href="/login" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Login
                        </a>
                        <a href="/register" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Daftar Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Plans -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Diet Plan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-teal-500 py-4">
                    <h3 class="text-xl font-bold text-white text-center">Paket Diet</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <span class="text-4xl font-bold text-gray-800">Rp 75.000</span>
                        <span class="text-gray-600 ml-2">/ hari (3 makanan)</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">3 makanan per hari</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Rendah kalori (1200-1500 kal/hari)</span>
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
                    <button
                        @click="selectPlan('diet')"
                        :class="selectedPlan === 'diet' ? 'bg-teal-600 text-white' : 'bg-teal-500 hover:bg-teal-600 text-white'"
                        class="w-full py-3 rounded-lg font-medium transition-colors"
                    >
                        Pilih Paket Diet
                    </button>
                </div>
            </div>

            <!-- Protein Plan -->
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
                        <span class="text-gray-600 ml-2">/ hari (3 makanan)</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">3 makanan per hari</span>
                        </li>
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
                            <span class="text-gray-600">Konsultasi nutrisi gratis</span>
                        </li>
                    </ul>
                    <button
                        @click="selectPlan('protein')"
                        :class="selectedPlan === 'protein' ? 'bg-teal-700 text-white' : 'bg-teal-600 hover:bg-teal-700 text-white'"
                        class="w-full py-3 rounded-lg font-medium transition-colors"
                    >
                        Pilih Paket Protein
                    </button>
                </div>
            </div>

            <!-- Royal Plan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-teal-500 py-4">
                    <h3 class="text-xl font-bold text-white text-center">Paket Royal</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <span class="text-4xl font-bold text-gray-800">Rp 120.000</span>
                        <span class="text-gray-600 ml-2">/ hari (3 makanan)</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-teal-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">3 makanan + 2 camilan</span>
                        </li>
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
                            <span class="text-gray-600">Konsultasi ahli gizi</span>
                        </li>
                    </ul>
                    <button
                        @click="selectPlan('royal')"
                        :class="selectedPlan === 'royal' ? 'bg-teal-600 text-white' : 'bg-teal-500 hover:bg-teal-600 text-white'"
                        class="w-full py-3 rounded-lg font-medium transition-colors"
                    >
                        Pilih Paket Royal
                    </button>
                </div>
            </div>
        </div>

        <!-- Subscription Form -->
        <div x-show="selectedPlan && isAuthenticated" x-cloak class="bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Langganan</h2>
            
            <form @submit.prevent="submitSubscription()">
                <!-- Selected Plan Info -->
                <div class="bg-teal-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-teal-800 mb-2">Paket Terpilih</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-teal-700" x-text="getPlanName(selectedPlan)"></span>
                        <span class="text-xl font-bold text-teal-800" x-text="formatCurrency(getPlanPrice(selectedPlan)) + ' / hari'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-gray-700 font-medium mb-2">
                            Nomor HP Aktif *
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            x-model="formData.phone"
                            placeholder="08xxxxxxxxxx"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                        />
                    </div>

                    <!-- Meal Types Selection -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            Pilih Jenis Makanan * (minimal 1)
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    value="breakfast"
                                    x-model="formData.meal_types"
                                    class="mr-2 text-teal-600 focus:ring-teal-500"
                                />
                                <span>Sarapan (Breakfast)</span>
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    value="lunch"
                                    x-model="formData.meal_types"
                                    class="mr-2 text-teal-600 focus:ring-teal-500"
                                />
                                <span>Makan Siang (Lunch)</span>
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    value="dinner"
                                    x-model="formData.meal_types"
                                    class="mr-2 text-teal-600 focus:ring-teal-500"
                                />
                                <span>Makan Malam (Dinner)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Delivery Days -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            Hari Pengiriman *
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="day in days" :key="day.value">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        :value="day.value"
                                        x-model="formData.delivery_days"
                                        class="mr-2 text-teal-600 focus:ring-teal-500"
                                    />
                                    <span x-text="day.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-6">
                    <label for="address" class="block text-gray-700 font-medium mb-2">
                        Alamat Pengiriman *
                    </label>
                    <textarea
                        id="address"
                        x-model="formData.address"
                        rows="3"
                        required
                        placeholder="Masukkan alamat lengkap untuk pengiriman"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    ></textarea>
                </div>

                <!-- Allergies -->
                <div class="mt-6">
                    <label for="allergies" class="block text-gray-700 font-medium mb-2">
                        Alergi Makanan (Opsional)
                    </label>
                    <textarea
                        id="allergies"
                        x-model="formData.allergies"
                        rows="2"
                        placeholder="Sebutkan alergi makanan yang Anda miliki (jika ada)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    ></textarea>
                </div>

                <!-- Total Calculation -->
                <div x-show="formData.delivery_days.length && formData.meal_types.length" class="bg-gray-50 rounded-lg p-4 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Ringkasan Pembayaran</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Paket terpilih:</span>
                            <span x-text="getPlanName(selectedPlan) + ' (' + formatCurrency(getPlanPrice(selectedPlan)) + '/hari untuk 3 makanan)'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Harga per makanan:</span>
                            <span x-text="formatCurrency(getPricePerMeal(selectedPlan))"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jenis makanan dipilih:</span>
                            <span x-text="formData.meal_types.length + ' jenis (' + formData.meal_types.join(', ') + ')'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Hari pengiriman:</span>
                            <span x-text="formData.delivery_days.length + ' hari per minggu'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Durasi berlangganan:</span>
                            <span>4.3 minggu (1 bulan)</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Perhitungan:</span>
                            <span x-text="formatCurrency(getPricePerMeal(selectedPlan)) + ' × ' + formData.meal_types.length + ' × ' + formData.delivery_days.length + ' × 4.3'"></span>
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span x-text="formatCurrency(calculateTotal())"></span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="w-full bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white py-3 rounded-lg font-medium transition-colors"
                    >
                        <span x-show="!isSubmitting">Konfirmasi Langganan</span>
                        <span x-show="isSubmitting">Memproses...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function subscriptionPage() {
    return {
        isAuthenticated: <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>,
        selectedPlan: '',
        isSubmitting: false,
        
        formData: {
            phone: '',
            meal_types: [],
            delivery_days: [],
            allergies: '',
            address: ''
        },

        days: [
            { value: 'monday', label: 'Senin' },
            { value: 'tuesday', label: 'Selasa' },
            { value: 'wednesday', label: 'Rabu' },
            { value: 'thursday', label: 'Kamis' },
            { value: 'friday', label: 'Jumat' },
            { value: 'saturday', label: 'Sabtu' },
            { value: 'sunday', label: 'Minggu' }
        ],

        selectPlan(plan) {
            this.selectedPlan = plan;
            // Set minimum start date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            this.formData.start_date = tomorrow.toISOString().split('T')[0];
        },

        getPlanName(plan) {
            const names = {
                diet: 'Paket Diet',
                protein: 'Paket Protein',
                royal: 'Paket Royal'
            };
            return names[plan] || '';
        },

        getPlanPrice(plan) {
            // Harga per hari untuk 3 makanan lengkap
            const prices = {
                diet: 75000,
                protein: 95000,
                royal: 120000
            };
            return prices[plan] || 0;
        },

        getPricePerMeal(plan) {
            // Harga per makanan (1/3 dari harga per hari)
            const dailyPrice = this.getPlanPrice(plan);
            return dailyPrice / 3;
        },

        calculateTotal() {
            if (!this.formData.delivery_days.length || !this.formData.meal_types.length) return 0;
            
            const pricePerMeal = this.getPricePerMeal(this.selectedPlan);
            const deliveryDays = this.formData.delivery_days.length; // Number of delivery days selected
            const mealTypes = this.formData.meal_types.length; // Number of meal types selected
            
            // Formula: (Harga per makanan) × (Jumlah jenis makanan) × (Jumlah hari pengiriman) × 4.3 minggu
            return pricePerMeal * mealTypes * deliveryDays * 4.3;
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        },

        async submitSubscription() {
            if (!this.isAuthenticated) {
                alert('Anda harus login terlebih dahulu');
                window.location.href = '/login';
                return;
            }

            // Validasi sesuai requirement
            if (!this.selectedPlan) {
                alert('Pilih paket terlebih dahulu');
                return;
            }

            if (this.formData.meal_types.length === 0) {
                alert('Pilih minimal satu jenis makanan');
                return;
            }

            if (this.formData.delivery_days.length === 0) {
                alert('Pilih minimal satu hari pengiriman');
                return;
            }

            this.isSubmitting = true;

            try {
                const response = await fetch('/subscription', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        plan: this.selectedPlan,
                        phone: this.formData.phone,
                        meal_types: this.formData.meal_types,
                        delivery_days: this.formData.delivery_days,
                        allergies: this.formData.allergies,
                        address: this.formData.address,
                        meals_per_day: this.formData.meal_types.length,
                        price: this.calculateTotal()
                    })
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    alert('Langganan berhasil dibuat! Anda akan diarahkan ke dashboard.');
                    window.location.href = '/dashboard';
                } else {
                    alert(result.message || 'Terjadi kesalahan saat membuat langganan');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>

<?= $this->endSection() ?>
