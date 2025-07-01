import React, { useState, useEffect } from 'react';
import { formatCurrency } from '../utils/helpers';
import { apiService } from '../services/api';

interface UserDashboardProps {
  setActiveTab: (tab: string) => void;
}

interface Subscription {
  id: number;
  user_id: number;
  plan_type: string;
  status: string;
  start_date: string;
  next_billing: string;
  price: number;
  meals_per_day: number;
  delivery_days: string; // JSON string
  allergies?: string;
  created_at: string;
  updated_at: string;
}

const UserDashboard: React.FC<UserDashboardProps> = ({ setActiveTab }) => {
  const [subscriptions, setSubscriptions] = useState<Subscription[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string>('');

  useEffect(() => {
    loadSubscriptions();
  }, []);

  const loadSubscriptions = async () => {
    try {
      setIsLoading(true);
      setError('');
      const response = await apiService.getSubscriptions();
      setSubscriptions(response.subscriptions || []);
    } catch (error) {
      console.error('Error loading subscriptions:', error);
      setError(error instanceof Error ? error.message : 'Failed to load subscriptions');
    } finally {
      setIsLoading(false);
    }
  };

  const handlePauseSubscription = async (id: number) => {
    try {
      const subscription = subscriptions.find(sub => sub.id === id);
      if (!subscription) return;

      const newStatus = subscription.status === 'active' ? 'paused' : 'active';
      const response = await apiService.updateSubscriptionStatus(id.toString(), newStatus);
      
      // Update local state
      setSubscriptions(prev => 
        prev.map(sub => 
          sub.id === id 
            ? { ...sub, status: newStatus }
            : sub
        )
      );
      
      // Show success message from backend
      alert(response.message || 'Status subscription berhasil diubah!');
    } catch (error) {
      console.error('Error updating subscription:', error);
      const errorMessage = error instanceof Error ? error.message : 'Gagal mengubah status subscription';
      alert(errorMessage);
    }
  };

  const handleCancelSubscription = async (id: number) => {
    if (!confirm('Apakah Anda yakin ingin membatalkan subscription ini? Tindakan ini tidak dapat dibatalkan.')) {
      return;
    }
    
    try {
      const response = await apiService.updateSubscriptionStatus(id.toString(), 'cancelled');
      
      // Update local state
      setSubscriptions(prev => 
        prev.map(sub => 
          sub.id === id 
            ? { ...sub, status: 'cancelled' }
            : sub
        )
      );
      
      // Show success message from backend
      alert(response.message || 'Subscription berhasil dibatalkan!');
    } catch (error) {
      console.error('Error cancelling subscription:', error);
      const errorMessage = error instanceof Error ? error.message : 'Gagal membatalkan subscription';
      alert(errorMessage);
    }
  };

  const getStatusBadge = (status: string) => {
    const statusStyles = {
      active: 'bg-green-100 text-green-800',
      paused: 'bg-yellow-100 text-yellow-800',
      cancelled: 'bg-red-100 text-red-800'
    };
    
    const statusText = {
      active: 'Aktif',
      paused: 'Dijeda',
      cancelled: 'Dibatalkan'
    };

    return (
      <span className={`px-2 py-1 rounded-full text-xs font-medium ${statusStyles[status as keyof typeof statusStyles]}`}>
        {statusText[status as keyof typeof statusText]}
      </span>
    );
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="container mx-auto px-4">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Dashboard Pengguna</h1>
          <p className="text-gray-600 mt-2">Kelola subscription dan pesanan Anda</p>
        </div>

        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <i className="fas fa-utensils text-teal-500 text-2xl mr-4"></i>
              <div>
                <p className="text-sm font-medium text-gray-600">Total Subscription</p>
                <p className="text-2xl font-bold text-gray-900">
                  {subscriptions.length}
                </p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <i className="fas fa-check-circle text-green-500 text-2xl mr-4"></i>
              <div>
                <p className="text-sm font-medium text-gray-600">Subscription Aktif</p>
                <p className="text-2xl font-bold text-gray-900">
                  {subscriptions.filter(sub => sub.status === 'active').length}
                </p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <i className="fas fa-money-bill-wave text-blue-500 text-2xl mr-4"></i>
              <div>
                <p className="text-sm font-medium text-gray-600">Total Pengeluaran/Bulan</p>
                <p className="text-2xl font-bold text-gray-900">
                  {formatCurrency(
                    subscriptions
                      .filter(sub => sub.status === 'active')
                      .reduce((total, sub) => {
                        const deliveryDays = JSON.parse(sub.delivery_days);
                        return total + (sub.price * deliveryDays.length);
                      }, 0)
                  )}
                </p>
              </div>
            </div>
          </div>
        </div>

        {/* Subscriptions List */}
        <div className="bg-white rounded-lg shadow-md">
          <div className="px-6 py-4 border-b border-gray-200">
            <h2 className="text-xl font-semibold text-gray-900">Daftar Subscription</h2>
          </div>

          {isLoading ? (
            <div className="px-6 py-12 text-center">
              <i className="fas fa-spinner fa-spin text-gray-400 text-4xl mb-4"></i>
              <p className="text-gray-600">Memuat data subscription...</p>
            </div>
          ) : error ? (
            <div className="px-6 py-12 text-center">
              <i className="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
              <h3 className="text-lg font-medium text-gray-900 mb-2">
                Gagal Memuat Data
              </h3>
              <p className="text-gray-600 mb-6">{error}</p>
              <button
                onClick={loadSubscriptions}
                className="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
              >
                Coba Lagi
              </button>
            </div>
          ) : !isLoading && subscriptions.length === 0 ? (
            <div className="px-6 py-12 text-center">
              <i className="fas fa-utensils text-gray-400 text-4xl mb-4"></i>
              <h3 className="text-lg font-medium text-gray-900 mb-2">
                Belum Ada Subscription
              </h3>
              <p className="text-gray-600 mb-6">
                Anda belum memiliki subscription aktif. Mulai berlangganan sekarang untuk menikmati makanan sehat!
              </p>
              <button
                onClick={() => setActiveTab('subscription')}
                className="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
              >
                Mulai Berlangganan
              </button>
            </div>
          ) : (
            <div className="divide-y divide-gray-200">
            {subscriptions.map((subscription) => (
              <div key={subscription.id} className="p-6">
                <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                  <div className="flex-1">
                    <div className="flex items-center mb-2">
                      <h3 className="text-lg font-medium text-gray-900 mr-4">
                        {subscription.plan_type}
                      </h3>
                      {getStatusBadge(subscription.status)}
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-600">
                      <div>
                        <span className="font-medium">ID:</span> {subscription.id}
                      </div>
                      <div>
                        <span className="font-medium">Harga:</span> {formatCurrency(subscription.price)}/hari
                      </div>
                      <div>
                        <span className="font-medium">Makanan:</span> {subscription.meals_per_day} per hari
                      </div>
                      <div>
                        <span className="font-medium">Mulai:</span> {new Date(subscription.start_date).toLocaleDateString('id-ID')}
                      </div>
                    </div>
                    
                    <div className="mt-2 text-sm text-gray-600">
                      <span className="font-medium">Hari Pengiriman:</span> {JSON.parse(subscription.delivery_days || '[]').join(', ')}
                    </div>
                    
                    {subscription.status === 'active' && (
                      <div className="mt-2 text-sm text-gray-600">
                        <span className="font-medium">Tagihan Berikutnya:</span> {new Date(subscription.next_billing).toLocaleDateString('id-ID')}
                      </div>
                    )}
                  </div>

                  <div className="flex flex-col sm:flex-row gap-2 mt-4 lg:mt-0">
                    {subscription.status !== 'cancelled' && (
                      <>
                        <button
                          onClick={() => handlePauseSubscription(subscription.id)}
                          className={`px-4 py-2 text-sm font-medium rounded-lg transition-colors ${
                            subscription.status === 'active'
                              ? 'bg-yellow-500 hover:bg-yellow-600 text-white'
                              : 'bg-green-500 hover:bg-green-600 text-white'
                          }`}
                        >
                          {subscription.status === 'active' ? (
                            <>
                              <i className="fas fa-pause mr-2"></i>
                              Jeda
                            </>
                          ) : (
                            <>
                              <i className="fas fa-play mr-2"></i>
                              Aktifkan
                            </>
                          )}
                        </button>
                        
                        <button
                          onClick={() => handleCancelSubscription(subscription.id)}
                          className="px-4 py-2 text-sm font-medium bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors"
                        >
                          <i className="fas fa-times mr-2"></i>
                          Batalkan
                        </button>
                      </>
                    )}
                    
                    {subscription.status === 'cancelled' && (
                      <span className="text-red-600 text-sm font-medium">
                        Subscription telah dibatalkan
                      </span>
                    )}
                  </div>
                </div>
              </div>
            ))}
            </div>
          )}
        </div>

        {/* Quick Actions */}
        <div className="mt-8 bg-white rounded-lg shadow-md p-6">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
          <div className="flex flex-col sm:flex-row gap-4">
            <button
              onClick={() => setActiveTab('subscription')}
              className="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
              <i className="fas fa-plus mr-2"></i>
              Tambah Subscription Baru
            </button>
            
            <button
              onClick={() => setActiveTab('menu')}
              className="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
              <i className="fas fa-utensils mr-2"></i>
              Lihat Menu
            </button>
            
            <button
              onClick={() => setActiveTab('contact')}
              className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
            >
              <i className="fas fa-phone mr-2"></i>
              Hubungi Support
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default UserDashboard;
