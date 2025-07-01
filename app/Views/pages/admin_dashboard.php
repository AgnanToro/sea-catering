<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div x-data="adminDashboard()" class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-600 mt-2">Kelola dan pantau seluruh operasi SEA Catering</p>
        </div>

        <!-- Date Range Selector -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input
                        type="date"
                        x-model="dateRange.start"
                        @change="updateStats()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input
                        type="date"
                        x-model="dateRange.end"
                        @change="updateStats()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                    />
                </div>
                <div class="flex items-end">
                    <button
                        @click="resetDateRange()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors"
                    >
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-users text-blue-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['totalUsers'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-utensils text-green-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Subscription</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['totalSubscriptions'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-teal-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Subscription Aktif</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['activeSubscriptions'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-money-bill-wave text-purple-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">MRR</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(<?= $stats['mrr'] ?? 0 ?>)"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-envelope text-yellow-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Contacts</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['totalContacts'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-bell text-red-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Unread Contacts</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['unreadContacts'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <!-- Subscription Growth Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <i class="fas fa-chart-line text-green-500 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pertumbuhan Bulan Ini</p>
                        <div class="flex items-center">
                            <p class="text-2xl font-bold text-gray-900 mr-2"><?= $stats['currentMonthSubscriptions'] ?? 0 ?></p>
                            <?php if (($stats['subscriptionGrowthPercent'] ?? 0) >= 0): ?>
                                <span class="text-green-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    +<?= $stats['subscriptionGrowthPercent'] ?? 0 ?>%
                                </span>
                            <?php else: ?>
                                <span class="text-red-600 text-sm font-medium flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    <?= $stats['subscriptionGrowthPercent'] ?? 0 ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">vs bulan lalu: <?= $stats['lastMonthSubscriptions'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Growth Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pertumbuhan Subscription</h3>
                <canvas id="growthChart" x-ref="growthChart"></canvas>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Bulanan (MRR)</h3>
                <canvas id="revenueChart" x-ref="revenueChart"></canvas>
            </div>
        </div>

        <!-- Recent Subscriptions -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Subscription Terbaru</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
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
                                Harga
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($recentSubscriptions)): ?>
                            <?php foreach ($recentSubscriptions as $subscription): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?= esc($subscription['user_name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= esc($subscription['user_email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Paket <?= ucfirst(esc($subscription['plan'])) ?></div>
                                        <div class="text-sm text-gray-500"><?= esc($subscription['meals_per_day']) ?> makan/hari</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                                            <?= $subscription['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                                ($subscription['status'] === 'paused' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst(esc($subscription['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('d M Y', strtotime($subscription['start_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp <?= number_format($subscription['price'], 0, ',', '.') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button 
                                            @click="viewSubscription(<?= $subscription['id'] ?>)"
                                            class="text-teal-600 hover:text-teal-900 mr-3"
                                        >
                                            View
                                        </button>
                                        <button 
                                            @click="updateSubscriptionStatus(<?= $subscription['id'] ?>, '<?= $subscription['status'] === 'active' ? 'paused' : 'active' ?>')"
                                            class="text-blue-600 hover:text-blue-900 mr-3"
                                        >
                                            <?= $subscription['status'] === 'active' ? 'Pause' : 'Activate' ?>
                                        </button>
                                        <button 
                                            @click="deleteSubscription(<?= $subscription['id'] ?>)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada subscription
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button
                    @click="loadAllSubscriptions()"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-list mr-2"></i>
                    View All Subscriptions
                </button>
                
                <button
                    @click="loadContacts()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-envelope mr-2"></i>
                    Manage Contacts
                </button>
                
                <button
                    @click="exportData()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-download mr-2"></i>
                    Export Data
                </button>
                
                <button
                    @click="generateReport()"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-chart-bar mr-2"></i>
                    Generate Report
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for subscription details -->
    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Subscription Details</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div x-show="selectedSubscription" class="space-y-4">
                    <!-- Subscription details will be loaded here -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">User</label>
                            <p class="text-gray-900" x-text="selectedSubscription ? selectedSubscription.user_name : ''"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Plan</label>
                            <p class="text-gray-900" x-text="selectedSubscription ? 'Paket ' + selectedSubscription.plan : ''"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <p class="text-gray-900" x-text="selectedSubscription ? selectedSubscription.status : ''"></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Price</label>
                            <p class="text-gray-900" x-text="selectedSubscription ? formatCurrency(selectedSubscription.price) : ''"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for All Subscriptions -->
    <div x-show="showSubscriptionsModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl max-w-6xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Semua Subscriptions</h3>
                    <button @click="showSubscriptionsModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="subscription in allSubscriptions" :key="subscription.id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" x-text="subscription.user_name"></div>
                                        <div class="text-sm text-gray-500" x-text="subscription.user_email"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="'Paket ' + subscription.plan"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                              :class="subscription.status === 'active' ? 'bg-green-100 text-green-800' : 
                                                     subscription.status === 'paused' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'"
                                              x-text="subscription.status">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatCurrency(subscription.price)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="new Date(subscription.start_date).toLocaleDateString('id-ID')"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Contacts -->
    <div x-show="showContactsModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Contact Messages</h3>
                    <button @click="showContactsModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="contact in allContacts" :key="contact.id">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-medium text-gray-900" x-text="contact.name"></h4>
                                    <p class="text-sm text-gray-500" x-text="contact.email"></p>
                                </div>
                                <span class="text-xs text-gray-400" x-text="new Date(contact.created_at).toLocaleDateString('id-ID')"></span>
                            </div>
                            <p class="text-sm text-gray-700" x-text="contact.message"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function adminDashboard() {
    return {
        showModal: false,
        selectedSubscription: null,
        showSubscriptionsModal: false,
        showContactsModal: false,
        allSubscriptions: [],
        allContacts: [],
        growthChart: null,
        revenueChart: null,
        
        // Date range for filtering
        dateRange: {
            start: '',
            end: ''
        },
        
        // Stats data
        stats: {
            newSubscriptions: <?= $stats['totalSubscriptions'] ?? 0 ?>,
            mrr: <?= $stats['mrr'] ?? 0 ?>,
            reactivations: 0,
            activeSubscriptions: <?= $stats['activeSubscriptions'] ?? 0 ?>,
            totalUsers: <?= $stats['totalUsers'] ?? 0 ?>
        },

        init() {
            // Set default date range (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            
            this.dateRange.start = thirtyDaysAgo.toISOString().split('T')[0];
            this.dateRange.end = today.toISOString().split('T')[0];
            
            this.$nextTick(() => {
                this.initCharts();
                this.updateStats();
            });
        },
        
        async updateStats() {
            try {
                const token = localStorage.getItem('authToken');
                const response = await fetch(`/api/subscriptions/stats?start=${this.dateRange.start}&end=${this.dateRange.end}`, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    this.stats = {
                        newSubscriptions: result.newSubscriptions || 0,
                        mrr: result.mrr || 0,
                        reactivations: result.reactivations || 0,
                        activeSubscriptions: result.activeSubscriptions || 0,
                        totalUsers: result.totalUsers || 0
                    };
                }
            } catch (error) {
                console.error('Error updating stats:', error);
            }
        },
        
        resetDateRange() {
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            
            this.dateRange.start = thirtyDaysAgo.toISOString().split('T')[0];
            this.dateRange.end = today.toISOString().split('T')[0];
            
            this.updateStats();
        },

        initCharts() {
            // Growth Chart with real data
            const growthCtx = this.$refs.growthChart.getContext('2d');
            this.growthChart = new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($chartData['labels']) ?>,
                    datasets: [{
                        label: 'Subscription Baru',
                        data: <?= json_encode($chartData['growthData']) ?>,
                        borderColor: 'rgb(20, 184, 166)',
                        backgroundColor: 'rgba(20, 184, 166, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Subscription Baru: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Revenue Chart with real data
            const revenueCtx = this.$refs.revenueChart.getContext('2d');
            this.revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($chartData['labels']) ?>,
                    datasets: [{
                        label: 'Revenue (Juta Rupiah)',
                        data: <?= json_encode($chartData['revenueData']) ?>,
                        backgroundColor: 'rgba(20, 184, 166, 0.8)',
                        borderColor: 'rgb(20, 184, 166)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: Rp ' + context.parsed.y.toFixed(2) + ' Juta';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value + 'M';
                                }
                            }
                        }
                    }
                }
            });
        },

        async viewSubscription(id) {
            try {
                console.log('Fetching subscription ID:', id);
                
                const response = await fetch(`/api/subscriptions/${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Response status:', response.status);
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('API response:', result);
                    
                    // Handle different response formats
                    if (result.data) {
                        this.selectedSubscription = result.data;
                    } else if (result.subscription) {
                        this.selectedSubscription = result.subscription;
                    } else {
                        this.selectedSubscription = result;
                    }
                    
                    this.showModal = true;
                } else {
                    const error = await response.json();
                    console.error('API error:', error);
                    alert(error.error || error.message || 'Gagal memuat detail subscription');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        },

        async updateSubscriptionStatus(id, newStatus) {
            if (!confirm(`Yakin ingin mengubah status subscription menjadi ${newStatus}?`)) return;

            try {
                console.log('Updating status for ID:', id, 'to:', newStatus);
                
                // Use POST instead of PATCH for better browser compatibility
                const response = await fetch(`/api/subscriptions/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PATCH' // Method override header
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                console.log('Update response status:', response.status);
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('Update result:', result);
                    alert('Status subscription berhasil diubah');
                    this.showModal = false; // Close modal
                    location.reload(); // Reload to update the table
                } else {
                    const result = await response.json();
                    console.error('Update error:', result);
                    alert(result.error || result.message || 'Gagal mengubah status');
                }
            } catch (error) {
                console.error('Update fetch error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        },

        async deleteSubscription(id) {
            if (!confirm('Yakin ingin menghapus subscription ini? Data yang dihapus tidak dapat dikembalikan.')) return;

            try {
                console.log('Deleting subscription ID:', id);
                
                const response = await fetch(`/api/subscriptions/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Delete response status:', response.status);
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('Delete result:', result);
                    alert('Subscription berhasil dihapus');
                    location.reload(); // Reload to update the table
                } else {
                    const result = await response.json();
                    console.error('Delete error:', result);
                    alert(result.error || result.message || 'Gagal menghapus subscription');
                }
            } catch (error) {
                console.error('Delete fetch error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        },

        loadAllSubscriptions() {
            console.log('loadAllSubscriptions() called');
            // Show all subscriptions in a modal or redirect to dedicated page
            // For now, let's create a simple modal with all subscriptions
            this.loadSubscriptionsData();
        },

        async loadSubscriptionsData() {
            console.log('loadSubscriptionsData() called');
            try {
                const response = await fetch('/api/subscriptions');
                console.log('API response status:', response.status);
                const result = await response.json();
                console.log('API response data:', result);
                
                if (response.ok) {
                    this.allSubscriptions = result.data || result;
                    this.showSubscriptionsModal = true;
                    console.log('Subscriptions loaded:', this.allSubscriptions.length, 'items');
                } else {
                    console.error('API error:', result);
                    alert('Gagal memuat data subscriptions: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error loading subscriptions:', error);
                alert('Terjadi kesalahan saat memuat data: ' + error.message);
            }
        },

        loadContacts() {
            console.log('loadContacts() called');
            // Load contacts data and show in modal
            this.loadContactsData();
        },

        async loadContactsData() {
            console.log('loadContactsData() called');
            try {
                const response = await fetch('/api/contact');
                console.log('Contact API response status:', response.status);
                const result = await response.json();
                console.log('Contact API response data:', result);
                
                if (response.ok) {
                    this.allContacts = result.data || result;
                    this.showContactsModal = true;
                    console.log('Contacts loaded:', this.allContacts.length, 'items');
                } else {
                    console.error('Contact API error:', result);
                    alert('Gagal memuat data contacts: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error loading contacts:', error);
                alert('Terjadi kesalahan saat memuat data: ' + error.message);
            }
        },

        async exportData() {
            console.log('exportData() called');
            try {
                // Use session-based authentication instead of token
                const response = await fetch('/admin/export', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Export response status:', response.status);

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `sea_catering_data_${new Date().toISOString().split('T')[0]}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    console.log('Export successful');
                    alert('Data berhasil diexport!');
                } else {
                    const errorText = await response.text();
                    console.error('Export error:', errorText);
                    alert('Gagal mengexport data: ' + response.status);
                }
            } catch (error) {
                console.error('Export error:', error);
                alert('Terjadi kesalahan saat export data: ' + error.message);
            }
        },

        async generateReport() {
            console.log('generateReport() called');
            console.log('Date range:', this.dateRange);
            try {
                const response = await fetch('/admin/report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        start_date: this.dateRange.start,
                        end_date: this.dateRange.end,
                        type: 'comprehensive'
                    })
                });

                console.log('Report response status:', response.status);
                const result = await response.json();
                console.log('Report response data:', result);

                if (response.ok) {
                    if (result.success) {
                        // Download the generated report
                        const downloadUrl = result.download_url;
                        const a = document.createElement('a');
                        a.href = downloadUrl;
                        a.download = result.filename;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        console.log('Report generated successfully');
                        alert('Report berhasil dibuat dan didownload!');
                    } else {
                        console.error('Report generation failed:', result.message);
                        alert('Gagal membuat report: ' + (result.message || 'Unknown error'));
                    }
                } else {
                    console.error('Report API error:', result);
                    alert('Gagal generate report: ' + response.status);
                }
            } catch (error) {
                console.error('Report error:', error);
                alert('Terjadi kesalahan saat generate report: ' + error.message);
            }
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }
    }
}
</script>

<?= $this->endSection() ?>
