import React, { useState } from 'react';
import type { MenuItem } from '../types';
import { formatCurrency } from '../utils/helpers';

interface MenuProps {
  setActiveTab: (tab: string) => void;
}

const Menu: React.FC<MenuProps> = ({ setActiveTab }) => {
  const [selectedMenu, setSelectedMenu] = useState<MenuItem | null>(null);
  const [selectedCategory, setSelectedCategory] = useState<'All' | 'Diet' | 'Protein' | 'Royal'>('All');

  const menuItems: MenuItem[] = [
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
        calories: 750,
        protein: 45,
        carbs: 20,
        fat: 28
      },
      price: 120000
    }
  ];

  const filteredItems = selectedCategory === 'All' 
    ? menuItems 
    : menuItems.filter(item => item.category === selectedCategory);

  const openModal = (item: MenuItem) => {
    setSelectedMenu(item);
  };

  const closeModal = () => {
    setSelectedMenu(null);
  };

  return (
    <main className="min-h-screen bg-gray-50 py-8">
      <div className="container mx-auto px-4">
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-gray-800 mb-4">Menu Kami</h1>
          <p className="text-lg text-gray-600 max-w-3xl mx-auto">
            Pilih dari berbagai menu sehat yang telah dirancang khusus oleh chef dan 
            ahli nutrisi untuk mendukung gaya hidup sehat Anda.
          </p>
        </div>

        {/* Category Filter */}
        <div className="flex flex-wrap justify-center gap-4 mb-8">
          {['All', 'Diet', 'Protein', 'Royal'].map((category) => (
            <button
              key={category}
              onClick={() => setSelectedCategory(category as 'All' | 'Diet' | 'Protein' | 'Royal')}
              className={`px-6 py-2 rounded-full font-medium transition-colors ${
                selectedCategory === category
                  ? 'bg-teal-600 text-white'
                  : 'bg-white text-gray-600 hover:bg-teal-50 hover:text-teal-600'
              }`}
            >
              {category === 'All' ? 'Semua' : `Paket ${category}`}
            </button>
          ))}
        </div>

        {/* Menu Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {filteredItems.map((item) => (
            <div
              key={item.id}
              className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
              onClick={() => openModal(item)}
            >
              <div className="h-48 overflow-hidden">
                <img
                  src={item.image}
                  alt={item.name}
                  className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                />
              </div>
              <div className="p-6">
                <div className="flex items-center justify-between mb-2">
                  <span className={`px-3 py-1 rounded-full text-xs font-medium ${
                    item.category === 'Diet' ? 'bg-green-100 text-green-800' :
                    item.category === 'Protein' ? 'bg-blue-100 text-blue-800' :
                    'bg-purple-100 text-purple-800'
                  }`}>
                    Paket {item.category}
                  </span>
                  <span className="text-lg font-bold text-teal-600">
                    {formatCurrency(item.price)}
                  </span>
                </div>
                <h3 className="text-xl font-semibold text-gray-800 mb-2">
                  {item.name}
                </h3>
                <p className="text-gray-600 mb-4 line-clamp-2">
                  {item.description}
                </p>
                <div className="flex items-center justify-between text-sm text-gray-500">
                  <div className="flex items-center">
                    <i className="fas fa-fire text-orange-500 mr-1"></i>
                    <span>{item.nutritionInfo.calories} kal</span>
                  </div>
                  <div className="flex items-center">
                    <i className="fas fa-dumbbell text-blue-500 mr-1"></i>
                    <span>{item.nutritionInfo.protein}g protein</span>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* CTA Section */}
        <div className="text-center mt-16">
          <h2 className="text-2xl font-bold text-gray-800 mb-4">
            Siap untuk Memulai?
          </h2>
          <p className="text-gray-600 mb-6">
            Pilih paket langganan yang sesuai dengan kebutuhan Anda
          </p>
          <button
            onClick={() => setActiveTab('subscription')}
            className="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-medium transition-colors"
          >
            Berlangganan Sekarang
          </button>
        </div>
      </div>

      {/* Modal */}
      {selectedMenu && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div className="relative">
              <img
                src={selectedMenu.image}
                alt={selectedMenu.name}
                className="w-full h-64 object-cover"
              />
              <button
                onClick={closeModal}
                className="absolute top-4 right-4 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full w-10 h-10 flex items-center justify-center transition-colors"
              >
                <i className="fas fa-times text-gray-600"></i>
              </button>
            </div>
            <div className="p-6">
              <div className="flex items-center justify-between mb-4">
                <span className={`px-3 py-1 rounded-full text-sm font-medium ${
                  selectedMenu.category === 'Diet' ? 'bg-green-100 text-green-800' :
                  selectedMenu.category === 'Protein' ? 'bg-blue-100 text-blue-800' :
                  'bg-purple-100 text-purple-800'
                }`}>
                  Paket {selectedMenu.category}
                </span>
                <span className="text-2xl font-bold text-teal-600">
                  {formatCurrency(selectedMenu.price)}
                </span>
              </div>
              <h2 className="text-2xl font-bold text-gray-800 mb-3">
                {selectedMenu.name}
              </h2>
              <p className="text-gray-600 mb-6">
                {selectedMenu.description}
              </p>
              
              <div className="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 className="text-lg font-semibold text-gray-800 mb-3">
                  Informasi Nutrisi (per porsi)
                </h3>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div className="text-center">
                    <div className="text-2xl font-bold text-orange-500">
                      {selectedMenu.nutritionInfo.calories}
                    </div>
                    <div className="text-sm text-gray-600">Kalori</div>
                  </div>
                  <div className="text-center">
                    <div className="text-2xl font-bold text-blue-500">
                      {selectedMenu.nutritionInfo.protein}g
                    </div>
                    <div className="text-sm text-gray-600">Protein</div>
                  </div>
                  <div className="text-center">
                    <div className="text-2xl font-bold text-green-500">
                      {selectedMenu.nutritionInfo.carbs}g
                    </div>
                    <div className="text-sm text-gray-600">Karbohidrat</div>
                  </div>
                  <div className="text-center">
                    <div className="text-2xl font-bold text-yellow-500">
                      {selectedMenu.nutritionInfo.fat}g
                    </div>
                    <div className="text-sm text-gray-600">Lemak</div>
                  </div>
                </div>
              </div>

              <button
                onClick={() => {
                  closeModal();
                  setActiveTab('subscription');
                }}
                className="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-medium transition-colors"
              >
                Pesan Menu Ini
              </button>
            </div>
          </div>
        </div>
      )}
    </main>
  );
};

export default Menu;
