import React, { useState } from 'react';
import { calculateSubscriptionPrice, formatCurrency, validatePhone } from '../utils/helpers';
import { apiService, type SubscriptionRequest } from '../services/api';
import { sanitizeInput, generateCSRFToken } from '../utils/security';

interface SubscriptionProps {
  isAuthenticated: boolean;
  setActiveTab: (tab: string) => void;
}

const Subscription: React.FC<SubscriptionProps> = ({ isAuthenticated, setActiveTab }) => {
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    plan: 'Protein' as 'Diet' | 'Protein' | 'Royal',
    mealTypes: [] as string[],
    deliveryDays: [] as string[],
    allergies: '',
  });

  const [errors, setErrors] = useState<Record<string, string>>({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitMessage, setSubmitMessage] = useState('');

  const handleMealTypeChange = (mealType: string) => {
    setFormData(prev => ({
      ...prev,
      mealTypes: prev.mealTypes.includes(mealType)
        ? prev.mealTypes.filter(m => m !== mealType)
        : [...prev.mealTypes, mealType]
    }));
  };

  const handleDeliveryDayChange = (day: string) => {
    setFormData(prev => ({
      ...prev,
      deliveryDays: prev.deliveryDays.includes(day)
        ? prev.deliveryDays.filter(d => d !== day)
        : [...prev.deliveryDays, day]
    }));
  };

  const validateForm = () => {
    const newErrors: Record<string, string> = {};

    if (!formData.name.trim()) {
      newErrors.name = 'Nama harus diisi';
    }

    if (!formData.phone.trim()) {
      newErrors.phone = 'Nomor HP harus diisi';
    } else if (!validatePhone(formData.phone)) {
      newErrors.phone = 'Format nomor HP tidak valid';
    }

    if (formData.mealTypes.length === 0) {
      newErrors.mealTypes = 'Pilih minimal satu jenis makanan';
    }

    if (formData.deliveryDays.length === 0) {
      newErrors.deliveryDays = 'Pilih minimal satu hari pengiriman';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!isAuthenticated) {
      setSubmitMessage('Silakan login terlebih dahulu untuk melakukan langganan');
      setActiveTab('login');
      return;
    }

    if (!validateForm()) {
      return;
    }

    setIsSubmitting(true);
    setSubmitMessage('');
    setErrors({});

    try {
      const totalPrice = calculateSubscriptionPrice(
        formData.plan,
        formData.mealTypes,
        formData.deliveryDays
      );

      // Sanitize form inputs
      const startDate = new Date();
      startDate.setDate(startDate.getDate() + 1); // Start tomorrow
      
      const sanitizedData = {
        plan: sanitizeInput(`Paket ${formData.plan}`),
        mealsPerDay: formData.mealTypes.length,
        deliveryDays: formData.deliveryDays.map(day => sanitizeInput(day)),
        price: Math.round(totalPrice / formData.deliveryDays.length), // Price per day
        startDate: startDate.toISOString().split('T')[0], // Format: YYYY-MM-DD
        // Include additional form data for backend processing
        name: sanitizeInput(formData.name),
        phone: sanitizeInput(formData.phone),
        allergies: sanitizeInput(formData.allergies || ''),
        csrfToken: generateCSRFToken()
      };

      // Create subscription via API
      const response = await apiService.createSubscription(sanitizedData);
      
      setSubmitMessage(`Langganan berhasil dibuat! ID: ${response.subscription?.id}. Total harga per hari: ${formatCurrency(sanitizedData.price)}`);
      
      // Redirect to dashboard after 2 seconds
      setTimeout(() => {
        setActiveTab('dashboard');
      }, 2000);
      
      // Reset form
      setFormData({
        name: '',
        phone: '',
        plan: 'Protein',
        mealTypes: [],
        deliveryDays: [],
        allergies: '',
      });

    } catch (error) {
      console.error('Subscription error:', error);
      setSubmitMessage(error instanceof Error ? error.message : 'Terjadi kesalahan saat menyimpan langganan. Silakan coba lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const totalPrice = calculateSubscriptionPrice(
    formData.plan,
    formData.mealTypes,
    formData.deliveryDays
  );

  const mealTypeOptions = [
    { id: 'breakfast', label: 'Sarapan', icon: 'fa-coffee' },
    { id: 'lunch', label: 'Siang', icon: 'fa-sun' },
    { id: 'dinner', label: 'Malam', icon: 'fa-moon' },
  ];

  const deliveryDayOptions = [
    'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
  ];

  return (
    <main className="min-h-screen bg-gray-50 py-8">
      <div className="container mx-auto px-4">
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-gray-800 mb-4">Berlangganan</h1>
          <p className="text-lg text-gray-600 max-w-3xl mx-auto">
            Mulai perjalanan hidup sehat Anda dengan memilih paket langganan 
            yang sesuai dengan kebutuhan dan gaya hidup Anda.
          </p>
        </div>

        <div className="max-w-4xl mx-auto">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Subscription Form */}
            <div className="lg:col-span-2">
              <div className="bg-white rounded-xl shadow-md p-8">
                <h2 className="text-2xl font-bold text-gray-800 mb-6">
                  Form Langganan
                </h2>
                
                <form onSubmit={handleSubmit} className="space-y-6">
                  {/* Personal Information */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label htmlFor="name" className="block text-gray-700 font-medium mb-2">
                        Nama Lengkap *
                      </label>
                      <input
                        type="text"
                        id="name"
                        value={formData.name}
                        onChange={(e) => setFormData(prev => ({ ...prev, name: e.target.value }))}
                        className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 ${
                          errors.name ? 'border-red-500' : 'border-gray-300'
                        }`}
                        placeholder="Masukkan nama lengkap"
                      />
                      {errors.name && (
                        <p className="text-red-500 text-sm mt-1">{errors.name}</p>
                      )}
                    </div>
                    
                    <div>
                      <label htmlFor="phone" className="block text-gray-700 font-medium mb-2">
                        Nomor HP *
                      </label>
                      <input
                        type="tel"
                        id="phone"
                        value={formData.phone}
                        onChange={(e) => setFormData(prev => ({ ...prev, phone: e.target.value }))}
                        className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 ${
                          errors.phone ? 'border-red-500' : 'border-gray-300'
                        }`}
                        placeholder="08xxxxxxxxxx"
                      />
                      {errors.phone && (
                        <p className="text-red-500 text-sm mt-1">{errors.phone}</p>
                      )}
                    </div>
                  </div>

                  {/* Plan Selection */}
                  <div>
                    <label className="block text-gray-700 font-medium mb-3">
                      Pilihan Paket *
                    </label>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                      {[
                        { id: 'Diet', price: 75000, desc: 'Rendah kalori, porsi terkontrol' },
                        { id: 'Protein', price: 95000, desc: 'Tinggi protein, ideal untuk fitness' },
                        { id: 'Royal', price: 120000, desc: 'Menu premium, porsi besar' },
                      ].map((plan) => (
                        <div
                          key={plan.id}
                          className={`border-2 rounded-lg p-4 cursor-pointer transition-colors ${
                            formData.plan === plan.id
                              ? 'border-teal-500 bg-teal-50'
                              : 'border-gray-300 hover:border-teal-300'
                          }`}
                          onClick={() => setFormData(prev => ({ ...prev, plan: plan.id as 'Diet' | 'Protein' | 'Royal' }))}
                        >
                          <div className="text-center">
                            <h3 className="font-bold text-lg text-gray-800">
                              Paket {plan.id}
                            </h3>
                            <p className="text-teal-600 font-bold text-xl">
                              {formatCurrency(plan.price)}/hari
                            </p>
                            <p className="text-sm text-gray-600 mt-2">
                              {plan.desc}
                            </p>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* Meal Types */}
                  <div>
                    <label className="block text-gray-700 font-medium mb-3">
                      Jenis Makanan *
                    </label>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                      {mealTypeOptions.map((meal) => (
                        <label
                          key={meal.id}
                          className={`border-2 rounded-lg p-4 cursor-pointer transition-colors flex items-center ${
                            formData.mealTypes.includes(meal.id)
                              ? 'border-teal-500 bg-teal-50'
                              : 'border-gray-300 hover:border-teal-300'
                          }`}
                        >
                          <input
                            type="checkbox"
                            checked={formData.mealTypes.includes(meal.id)}
                            onChange={() => handleMealTypeChange(meal.id)}
                            className="sr-only"
                          />
                          <i className={`fas ${meal.icon} text-teal-500 text-xl mr-3`}></i>
                          <span className="font-medium">{meal.label}</span>
                        </label>
                      ))}
                    </div>
                    {errors.mealTypes && (
                      <p className="text-red-500 text-sm mt-1">{errors.mealTypes}</p>
                    )}
                  </div>

                  {/* Delivery Days */}
                  <div>
                    <label className="block text-gray-700 font-medium mb-3">
                      Hari Pengiriman *
                    </label>
                    <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-2">
                      {deliveryDayOptions.map((day) => (
                        <label
                          key={day}
                          className={`border-2 rounded-lg p-3 cursor-pointer transition-colors text-center ${
                            formData.deliveryDays.includes(day)
                              ? 'border-teal-500 bg-teal-50'
                              : 'border-gray-300 hover:border-teal-300'
                          }`}
                        >
                          <input
                            type="checkbox"
                            checked={formData.deliveryDays.includes(day)}
                            onChange={() => handleDeliveryDayChange(day)}
                            className="sr-only"
                          />
                          <span className="text-sm font-medium">{day}</span>
                        </label>
                      ))}
                    </div>
                    {errors.deliveryDays && (
                      <p className="text-red-500 text-sm mt-1">{errors.deliveryDays}</p>
                    )}
                  </div>

                  {/* Allergies */}
                  <div>
                    <label htmlFor="allergies" className="block text-gray-700 font-medium mb-2">
                      Alergi Makanan (Opsional)
                    </label>
                    <textarea
                      id="allergies"
                      rows={3}
                      value={formData.allergies}
                      onChange={(e) => setFormData(prev => ({ ...prev, allergies: e.target.value }))}
                      className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                      placeholder="Sebutkan alergi makanan yang Anda miliki..."
                    ></textarea>
                  </div>

                  {/* Submission Message */}
                  {submitMessage && (
                    <div className={`p-4 rounded-lg ${
                      submitMessage.includes('berhasil') 
                        ? 'bg-green-50 border border-green-200 text-green-800' 
                        : 'bg-red-50 border border-red-200 text-red-800'
                    }`}>
                      <div className="flex items-center">
                        <i className={`fas ${
                          submitMessage.includes('berhasil') ? 'fa-check-circle' : 'fa-exclamation-circle'
                        } mr-2`}></i>
                        <span className="text-sm">{submitMessage}</span>
                      </div>
                    </div>
                  )}

                  <button
                    type="submit"
                    disabled={isSubmitting}
                    className={`w-full py-3 rounded-lg font-medium transition-colors ${
                      isSubmitting
                        ? 'bg-gray-400 cursor-not-allowed'
                        : isAuthenticated
                        ? 'bg-teal-600 hover:bg-teal-700'
                        : 'bg-yellow-600 hover:bg-yellow-700'
                    } text-white`}
                  >
                    {isSubmitting ? (
                      <span className="flex items-center justify-center">
                        <i className="fas fa-spinner fa-spin mr-2"></i>
                        Memproses...
                      </span>
                    ) : (
                      isAuthenticated ? 'Berlangganan Sekarang' : 'Login untuk Berlangganan'
                    )}
                  </button>
                </form>
              </div>
            </div>

            {/* Order Summary */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-xl shadow-md p-6 sticky top-4">
                <h3 className="text-xl font-bold text-gray-800 mb-4">
                  Ringkasan Pesanan
                </h3>
                
                <div className="space-y-3 mb-6">
                  <div className="flex justify-between">
                    <span className="text-gray-600">Paket:</span>
                    <span className="font-medium">Paket {formData.plan}</span>
                  </div>
                  
                  <div className="flex justify-between">
                    <span className="text-gray-600">Jenis Makanan:</span>
                    <span className="font-medium">
                      {formData.mealTypes.length > 0 
                        ? `${formData.mealTypes.length} jenis`
                        : '-'
                      }
                    </span>
                  </div>
                  
                  <div className="flex justify-between">
                    <span className="text-gray-600">Hari Pengiriman:</span>
                    <span className="font-medium">
                      {formData.deliveryDays.length > 0 
                        ? `${formData.deliveryDays.length} hari/minggu`
                        : '-'
                      }
                    </span>
                  </div>
                </div>
                
                <div className="border-t pt-4">
                  <div className="flex justify-between items-center">
                    <span className="text-lg font-bold text-gray-800">
                      Total per Minggu:
                    </span>
                    <span className="text-xl font-bold text-teal-600">
                      {totalPrice > 0 ? formatCurrency(totalPrice) : '-'}
                    </span>
                  </div>
                  
                  {totalPrice > 0 && (
                    <div className="mt-2 text-sm text-gray-500">
                      *Harga dapat berubah sesuai dengan pilihan menu
                    </div>
                  )}
                </div>

                {!isAuthenticated && (
                  <div className="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div className="flex items-center">
                      <i className="fas fa-info-circle text-yellow-600 mr-2"></i>
                      <span className="text-sm text-yellow-800">
                        Login diperlukan untuk melakukan langganan
                      </span>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  );
};

export default Subscription;
