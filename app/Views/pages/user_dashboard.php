<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div x-data="userDashboard()" class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Pengguna</h1>
            <p class="text-gray-600 mt-2">Kelola subscription dan pesanan Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-utensils text-teal-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Subscription</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="subscriptions.length"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Subscription Aktif</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="activeSubscriptions"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-money-bill-wave text-blue-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(totalSpent)"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Subscription Anda</h2>
            </div>

            <div x-show="isLoading" class="px-6 py-12 text-center">
                <i class="fas fa-spinner fa-spin text-gray-400 text-3xl mb-4"></i>
                <p class="text-gray-600">Memuat data subscription...</p>
            </div>

            <div x-show="error && !isLoading" class="px-6 py-12 text-center">
                <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Gagal Memuat Data</h3>
                <p class="text-gray-600 mb-6" x-text="error"></p>
                <button
                    @click="loadSubscriptions()"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                >
                    Coba Lagi
                </button>
            </div>

            <div x-show="!isLoading && !error && subscriptions.length === 0" class="px-6 py-12 text-center">
                <i class="fas fa-utensils text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Subscription</h3>
                <p class="text-gray-600 mb-6">
                    Anda belum memiliki subscription aktif. Mulai hidup sehat dengan berlangganan sekarang!
                </p>
                <a href="/subscription" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Berlangganan Sekarang
                </a>
            </div>

            <div x-show="!isLoading && !error && subscriptions.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Paket
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Mulai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Billing Berikutnya
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="subscription in subscriptions" :key="subscription.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900" x-text="getPlanName(subscription.plan)"></div>
                                        <div class="text-sm text-gray-500 ml-2">
                                            (<span x-text="subscription.meals_per_day"></span> makan/hari)
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="getStatusClass(subscription.status)"
                                        class="px-2 py-1 rounded-full text-xs font-medium"
                                        x-text="getStatusText(subscription.status)"
                                    ></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-text="formatDate(subscription.start_date)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-text="formatDate(subscription.next_billing)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-text="formatCurrency(subscription.price)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            x-show="subscription.status === 'active'"
                                            @click="pauseSubscription(subscription.id)"
                                            class="text-yellow-600 hover:text-yellow-900"
                                        >
                                            <i class="fas fa-pause mr-1"></i>
                                            Pause
                                        </button>
                                        <button
                                            x-show="subscription.status === 'paused'"
                                            @click="resumeSubscription(subscription.id)"
                                            class="text-green-600 hover:text-green-900"
                                        >
                                            <i class="fas fa-play mr-1"></i>
                                            Resume
                                        </button>
                                        <button
                                            @click="cancelSubscription(subscription.id)"
                                            class="text-red-600 hover:text-red-900 ml-2"
                                        >
                                            <i class="fas fa-times mr-1"></i>
                                            Cancel
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="/subscription" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Subscription Baru
            </a>
            
            <a href="/menu" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-utensils mr-2"></i>
                Lihat Menu
            </a>
            
            <a href="/contact" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-phone mr-2"></i>
                Hubungi Support
            </a>
        </div>
    </div>
</div>

<script>
function userDashboard() {
    return {
        subscriptions: <?= json_encode($subscriptions ?? []) ?>,
        isLoading: false,
        error: '',

        get activeSubscriptions() {
            return this.subscriptions.filter(sub => sub.status === 'active').length;
        },

        get totalSpent() {
            return this.subscriptions.reduce((total, sub) => total + parseInt(sub.price), 0);
        },

        async loadSubscriptions() {
            this.isLoading = true;
            this.error = '';

            try {
                const token = localStorage.getItem('authToken');
                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                const response = await fetch('/api/subscriptions', {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    this.subscriptions = result.subscriptions || [];
                } else {
                    this.error = result.error || 'Gagal memuat data subscription';
                }
            } catch (error) {
                console.error('Error:', error);
                this.error = 'Terjadi kesalahan. Silakan coba lagi.';
            } finally {
                this.isLoading = false;
            }
        },

        async pauseSubscription(id) {
            if (!confirm('Yakin ingin mem-pause subscription ini?')) return;

            try {
                const token = localStorage.getItem('authToken');
                const response = await fetch(`/api/subscriptions/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify({ status: 'paused' })
                });

                if (response.ok) {
                    await this.loadSubscriptions();
                    alert('Subscription berhasil di-pause');
                } else {
                    const result = await response.json();
                    alert(result.error || 'Gagal mem-pause subscription');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        },

        async resumeSubscription(id) {
            if (!confirm('Yakin ingin melanjutkan subscription ini?')) return;

            try {
                const token = localStorage.getItem('authToken');
                const response = await fetch(`/api/subscriptions/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify({ status: 'active' })
                });

                if (response.ok) {
                    await this.loadSubscriptions();
                    alert('Subscription berhasil dilanjutkan');
                } else {
                    const result = await response.json();
                    alert(result.error || 'Gagal melanjutkan subscription');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        },

        async cancelSubscription(id) {
            if (!confirm('Yakin ingin membatalkan subscription ini? Tindakan ini tidak dapat dibatalkan.')) return;

            try {
                const token = localStorage.getItem('authToken');
                const response = await fetch(`/api/subscriptions/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });

                if (response.ok) {
                    await this.loadSubscriptions();
                    alert('Subscription berhasil dibatalkan');
                } else {
                    const result = await response.json();
                    alert(result.error || 'Gagal membatalkan subscription');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        },

        getPlanName(planType) {
            const plans = {
                diet: 'Paket Diet',
                protein: 'Paket Protein',
                royal: 'Paket Royal'
            };
            return plans[planType] || planType;
        },

        getStatusClass(status) {
            const classes = {
                active: 'bg-green-100 text-green-800',
                paused: 'bg-yellow-100 text-yellow-800',
                cancelled: 'bg-red-100 text-red-800',
                expired: 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },

        getStatusText(status) {
            const texts = {
                active: 'Aktif',
                paused: 'Ditunda',
                cancelled: 'Dibatalkan',
                expired: 'Kedaluwarsa'
            };
            return texts[status] || status;
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
}
</script>

<?= $this->endSection() ?>
