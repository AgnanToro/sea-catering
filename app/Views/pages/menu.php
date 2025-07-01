<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div x-data="menuPage()" class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Menu Sehat Kami</h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Pilih dari berbagai menu sehat yang telah dirancang khusus oleh ahli nutrisi kami. 
                    Setiap hidangan mengandung informasi nutrisi lengkap untuk membantu Anda mencapai tujuan kesehatan.
                </p>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <button
                    @click="selectedCategory = 'All'"
                    :class="selectedCategory === 'All' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-teal-50 hover:text-teal-600'"
                    class="px-6 py-2 rounded-full font-medium transition-colors"
                >
                    Semua
                </button>
                <button
                    @click="selectedCategory = 'Diet'"
                    :class="selectedCategory === 'Diet' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-teal-50 hover:text-teal-600'"
                    class="px-6 py-2 rounded-full font-medium transition-colors"
                >
                    Paket Diet
                </button>
                <button
                    @click="selectedCategory = 'Protein'"
                    :class="selectedCategory === 'Protein' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-teal-50 hover:text-teal-600'"
                    class="px-6 py-2 rounded-full font-medium transition-colors"
                >
                    Paket Protein
                </button>
                <button
                    @click="selectedCategory = 'Royal'"
                    :class="selectedCategory === 'Royal' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-teal-50 hover:text-teal-600'"
                    class="px-6 py-2 rounded-full font-medium transition-colors"
                >
                    Paket Royal
                </button>
            </div>

            <!-- Menu Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="item in filteredItems" :key="item.id">
                    <div 
                        @click="openModal(item)"
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
                    >
                        <div class="h-48 overflow-hidden">
                            <img
                                :src="item.image"
                                :alt="item.name"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span 
                                    :class="getCategoryClass(item.category)"
                                    class="px-3 py-1 rounded-full text-xs font-medium"
                                    x-text="'Paket ' + item.category"
                                ></span>
                                <span class="text-lg font-bold text-teal-600" x-text="formatCurrency(item.price)"></span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2" x-text="item.name"></h3>
                            <p class="text-gray-600 mb-4 line-clamp-2" x-text="item.description"></p>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-fire text-orange-500 mr-1"></i>
                                    <span x-text="item.nutritionInfo.calories + ' kal'"></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-dumbbell text-blue-500 mr-1"></i>
                                    <span x-text="item.nutritionInfo.protein + 'g protein'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- CTA Section -->
            <div class="text-center mt-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    Siap untuk Memulai?
                </h2>
                <p class="text-gray-600 mb-8">
                    Berlangganan sekarang dan dapatkan menu sehat yang dikirim langsung ke rumah Anda.
                </p>
                <a href="/subscription" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                    Berlangganan Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div x-show="selectedMenu" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="relative">
                <img
                    :src="selectedMenu ? selectedMenu.image : ''"
                    :alt="selectedMenu ? selectedMenu.name : ''"
                    class="w-full h-64 object-cover"
                />
                <button
                    @click="closeModal()"
                    class="absolute top-4 right-4 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full w-10 h-10 flex items-center justify-center transition-colors"
                >
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span 
                        :class="selectedMenu ? getCategoryClass(selectedMenu.category) : ''"
                        class="px-3 py-1 rounded-full text-sm font-medium"
                        x-text="selectedMenu ? 'Paket ' + selectedMenu.category : ''"
                    ></span>
                    <span class="text-2xl font-bold text-teal-600" x-text="selectedMenu ? formatCurrency(selectedMenu.price) : ''"></span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-3" x-text="selectedMenu ? selectedMenu.name : ''"></h2>
                <p class="text-gray-600 mb-6" x-text="selectedMenu ? selectedMenu.description : ''"></p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        Informasi Nutrisi (per porsi)
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-500" x-text="selectedMenu ? selectedMenu.nutritionInfo.calories : ''"></div>
                            <div class="text-sm text-gray-600">Kalori</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-500" x-text="selectedMenu ? selectedMenu.nutritionInfo.protein + 'g' : ''"></div>
                            <div class="text-sm text-gray-600">Protein</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-500" x-text="selectedMenu ? selectedMenu.nutritionInfo.carbs + 'g' : ''"></div>
                            <div class="text-sm text-gray-600">Karbohidrat</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-500" x-text="selectedMenu ? selectedMenu.nutritionInfo.fat + 'g' : ''"></div>
                            <div class="text-sm text-gray-600">Lemak</div>
                        </div>
                    </div>
                </div>

                <a href="/subscription" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-medium transition-colors text-center block">
                    Pesan Menu Ini
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function menuPage() {
    return {
        selectedMenu: null,
        selectedCategory: 'All',
        
        menuItems: [
            {
                id: '1',
                name: 'Grilled Chicken Salad',
                description: 'Sayuran segar dengan ayam panggang, cocok untuk diet rendah kalori',
                image: 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                category: 'Diet',
                nutritionInfo: {
                    calories: 350,
                    protein: 25,
                    carbs: 15,
                    fat: 12
                },
                price: 75000
            },
            {
                id: '2',
                name: 'Protein Power Bowl',
                description: 'Bowl berprotein tinggi dengan quinoa, salmon, dan telur',
                image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                category: 'Protein',
                nutritionInfo: {
                    calories: 520,
                    protein: 35,
                    carbs: 30,
                    fat: 18
                },
                price: 95000
            },
            {
                id: '3',
                name: 'Royal Mediterranean',
                description: 'Menu premium dengan daging sapi wagyu dan sayuran organik',
                image: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                category: 'Royal',
                nutritionInfo: {
                    calories: 680,
                    protein: 40,
                    carbs: 25,
                    fat: 22
                },
                price: 120000
            },
            {
                id: '4',
                name: 'Zucchini Noodles',
                description: 'Mie zucchini dengan saus pesto rendah kalori',
                image: 'https://images.unsplash.com/photo-1551782450-17144efb9c50?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                category: 'Diet',
                nutritionInfo: {
                    calories: 280,
                    protein: 18,
                    carbs: 12,
                    fat: 8
                },
                price: 75000
            },
            {
                id: '5',
                name: 'Beef Protein Stack',
                description: 'Daging sapi lean dengan sweet potato dan brokoli',
                image: 'https://images.unsplash.com/photo-1432139509613-5c4255815697?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                category: 'Protein',
                nutritionInfo: {
                    calories: 580,
                    protein: 42,
                    carbs: 35,
                    fat: 20
                },
                price: 95000
            },
            {
                id: '6',
                name: 'Royal Surf & Turf',
                description: 'Kombinasi lobster dan beef tenderloin dengan truffle sauce',
                image: 'https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                category: 'Royal',
                nutritionInfo: {
                    calories: 720,
                    protein: 45,
                    carbs: 20,
                    fat: 28
                },
                price: 120000
            }
        ],

        get filteredItems() {
            if (this.selectedCategory === 'All') {
                return this.menuItems;
            }
            return this.menuItems.filter(item => item.category === this.selectedCategory);
        },

        openModal(item) {
            this.selectedMenu = item;
        },

        closeModal() {
            this.selectedMenu = null;
        },

        getCategoryClass(category) {
            const classes = {
                Diet: 'bg-green-100 text-green-800',
                Protein: 'bg-blue-100 text-blue-800',
                Royal: 'bg-purple-100 text-purple-800'
            };
            return classes[category] || 'bg-gray-100 text-gray-800';
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
