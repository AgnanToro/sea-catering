import React, { useState } from 'react';
import { formatCurrency } from '../utils/helpers';

interface HomeProps {
  setActiveTab: (tab: string) => void;
}

const Home: React.FC<HomeProps> = ({ setActiveTab }) => {
  const [testimonialForm, setTestimonialForm] = useState({
    name: '',
    city: '',
    rating: 0,
    testimonial: '',
  });

  const handleTestimonialSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // In a real app, this would submit to an API
    console.log('Testimonial submitted:', testimonialForm);
    alert('Terima kasih atas testimoni Anda! Testimoni akan ditinjau sebelum ditampilkan.');
    setTestimonialForm({ name: '', city: '', rating: 0, testimonial: '' });
  };

  const handleStarClick = (rating: number) => {
    setTestimonialForm(prev => ({ ...prev, rating }));
  };

  return (
    <main className="min-h-screen">
      {/* Hero Section */}
      <section className="relative overflow-hidden">
        <div className="absolute inset-0 z-0">
          <div className="absolute inset-0 bg-gradient-to-r from-teal-900/90 to-transparent z-10"></div>
          <img
            src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
            alt="Healthy Meals"
            className="w-full h-full object-cover object-center"
          />
        </div>
        <div className="container mx-auto px-4 py-24 md:py-32 relative z-20">
          <div className="max-w-2xl">
            <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
              Healthy Meals, Anytime, Anywhere
            </h1>
            <p className="text-lg md:text-xl text-white/90 mb-8">
              Nikmati makanan sehat dan lezat yang dikirim langsung ke pintu Anda. 
              Kami menyediakan pilihan menu yang dapat disesuaikan dengan kebutuhan nutrisi Anda.
            </p>
            <div className="flex flex-col sm:flex-row gap-4">
              <button
                onClick={() => setActiveTab('menu')}
                className="bg-teal-500 hover:bg-teal-600 text-white px-8 py-3 rounded-lg font-medium transition-colors cursor-pointer whitespace-nowrap"
              >
                Lihat Menu
              </button>
              <button
                onClick={() => setActiveTab('subscription')}
                className="bg-white hover:bg-gray-100 text-teal-700 px-8 py-3 rounded-lg font-medium transition-colors cursor-pointer whitespace-nowrap"
              >
                Berlangganan
              </button>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-800 mb-4">Layanan Kami</h2>
            <p className="text-lg text-gray-600 max-w-3xl mx-auto">
              SEA Catering menyediakan layanan makanan sehat dengan berbagai pilihan menu 
              yang dapat disesuaikan dengan kebutuhan nutrisi Anda.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {/* Feature 1 */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <div className="h-48 overflow-hidden">
                <img
                  src="https://images.unsplash.com/photo-1547496502-affa22d38842?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80"
                  alt="Kustomisasi Makanan"
                  className="w-full h-full object-cover object-center"
                />
              </div>
              <div className="p-6">
                <div className="flex items-center mb-4">
                  <i className="fas fa-utensils text-teal-500 text-xl mr-3"></i>
                  <h3 className="text-xl font-semibold text-gray-800">
                    Kustomisasi Makanan
                  </h3>
                </div>
                <p className="text-gray-600">
                  Sesuaikan menu makanan Anda berdasarkan preferensi, kebutuhan diet, 
                  dan tujuan nutrisi Anda.
                </p>
              </div>
            </div>

            {/* Feature 2 */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <div className="h-48 overflow-hidden">
                <img
                  src="https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                  alt="Pengiriman"
                  className="w-full h-full object-cover object-center"
                />
              </div>
              <div className="p-6">
                <div className="flex items-center mb-4">
                  <i className="fas fa-truck text-teal-500 text-xl mr-3"></i>
                  <h3 className="text-xl font-semibold text-gray-800">
                    Pengiriman ke Kota Besar
                  </h3>
                </div>
                <p className="text-gray-600">
                  Kami mengirimkan makanan segar langsung ke pintu Anda di berbagai 
                  kota besar di Indonesia.
                </p>
              </div>
            </div>

            {/* Feature 3 */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <div className="h-48 overflow-hidden">
                <img
                  src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2053&q=80"
                  alt="Informasi Nutrisi"
                  className="w-full h-full object-cover object-center"
                />
              </div>
              <div className="p-6">
                <div className="flex items-center mb-4">
                  <i className="fas fa-heartbeat text-teal-500 text-xl mr-3"></i>
                  <h3 className="text-xl font-semibold text-gray-800">
                    Informasi Nutrisi
                  </h3>
                </div>
                <p className="text-gray-600">
                  Dapatkan informasi nutrisi lengkap untuk setiap makanan, termasuk 
                  kalori, protein, karbohidrat, dan lemak.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Plans Section */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-800 mb-4">Pilihan Paket</h2>
            <p className="text-lg text-gray-600 max-w-3xl mx-auto">
              Pilih paket yang sesuai dengan kebutuhan dan gaya hidup Anda.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {/* Plan 1 */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <div className="bg-teal-500 py-4">
                <h3 className="text-xl font-bold text-white text-center">Paket Diet</h3>
              </div>
              <div className="p-6">
                <div className="text-center mb-6">
                  <span className="text-4xl font-bold text-gray-800">
                    {formatCurrency(75000)}
                  </span>
                  <span className="text-gray-600 ml-2">/ hari</span>
                </div>
                <ul className="space-y-3 mb-8">
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Makanan rendah kalori</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Porsi terkontrol</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Informasi nutrisi lengkap</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Konsultasi diet gratis</span>
                  </li>
                </ul>
                <button
                  onClick={() => setActiveTab('subscription')}
                  className="w-full bg-teal-500 hover:bg-teal-600 text-white py-3 rounded-lg font-medium transition-colors cursor-pointer whitespace-nowrap"
                >
                  Pilih Paket
                </button>
              </div>
            </div>

            {/* Plan 2 */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow transform scale-105 border-2 border-teal-500">
              <div className="bg-teal-600 py-4 relative">
                <div className="absolute -top-4 right-4 bg-yellow-400 text-teal-800 text-xs font-bold px-3 py-1 rounded-full">
                  TERPOPULER
                </div>
                <h3 className="text-xl font-bold text-white text-center">Paket Protein</h3>
              </div>
              <div className="p-6">
                <div className="text-center mb-6">
                  <span className="text-4xl font-bold text-gray-800">
                    {formatCurrency(95000)}
                  </span>
                  <span className="text-gray-600 ml-2">/ hari</span>
                </div>
                <ul className="space-y-3 mb-8">
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Tinggi protein (30-35g per porsi)</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Ideal untuk pembentukan otot</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Karbohidrat kompleks</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Konsultasi nutrisi gratis</span>
                  </li>
                </ul>
                <button
                  onClick={() => setActiveTab('subscription')}
                  className="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-medium transition-colors cursor-pointer whitespace-nowrap"
                >
                  Pilih Paket
                </button>
              </div>
            </div>

            {/* Plan 3 */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
              <div className="bg-teal-500 py-4">
                <h3 className="text-xl font-bold text-white text-center">Paket Royal</h3>
              </div>
              <div className="p-6">
                <div className="text-center mb-6">
                  <span className="text-4xl font-bold text-gray-800">
                    {formatCurrency(120000)}
                  </span>
                  <span className="text-gray-600 ml-2">/ hari</span>
                </div>
                <ul className="space-y-3 mb-8">
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Menu premium</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Porsi lebih besar</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Termasuk camilan sehat</span>
                  </li>
                  <li className="flex items-start">
                    <i className="fas fa-check text-teal-500 mt-1 mr-3"></i>
                    <span className="text-gray-600">Konsultasi dengan ahli gizi</span>
                  </li>
                </ul>
                <button
                  onClick={() => setActiveTab('subscription')}
                  className="w-full bg-teal-500 hover:bg-teal-600 text-white py-3 rounded-lg font-medium transition-colors cursor-pointer whitespace-nowrap"
                >
                  Pilih Paket
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Testimonials Section */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-800 mb-4">Testimoni Pelanggan</h2>
            <p className="text-lg text-gray-600 max-w-3xl mx-auto">
              Lihat apa kata pelanggan kami tentang layanan SEA Catering.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {/* Testimonial 1 */}
            <div className="bg-gray-50 rounded-xl p-6">
              <div className="flex items-center mb-4">
                <div className="text-yellow-400 flex">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <i key={star} className="fas fa-star"></i>
                  ))}
                </div>
              </div>
              <p className="text-gray-600 italic mb-6">
                "Saya sudah mencoba berbagai layanan katering diet, tapi SEA Catering 
                adalah yang terbaik! Makanannya lezat dan saya berhasil menurunkan 5kg 
                dalam sebulan."
              </p>
              <div className="flex items-center">
                <div className="w-12 h-12 rounded-full bg-teal-200 flex items-center justify-center mr-4">
                  <i className="fas fa-user text-teal-600"></i>
                </div>
                <div>
                  <h4 className="font-medium text-gray-800">Anita Wijaya</h4>
                  <p className="text-sm text-gray-500">Jakarta</p>
                </div>
              </div>
            </div>

            {/* Testimonial 2 */}
            <div className="bg-gray-50 rounded-xl p-6">
              <div className="flex items-center mb-4">
                <div className="text-yellow-400 flex">
                  {[1, 2, 3, 4].map((star) => (
                    <i key={star} className="fas fa-star"></i>
                  ))}
                  <i className="fas fa-star-half-alt"></i>
                </div>
              </div>
              <p className="text-gray-600 italic mb-6">
                "Sebagai atlet, saya membutuhkan asupan protein yang tinggi. Paket Protein 
                dari SEA Catering sangat membantu performa saya. Pengirimannya juga selalu 
                tepat waktu!"
              </p>
              <div className="flex items-center">
                <div className="w-12 h-12 rounded-full bg-teal-200 flex items-center justify-center mr-4">
                  <i className="fas fa-user text-teal-600"></i>
                </div>
                <div>
                  <h4 className="font-medium text-gray-800">Budi Santoso</h4>
                  <p className="text-sm text-gray-500">Surabaya</p>
                </div>
              </div>
            </div>

            {/* Testimonial 3 */}
            <div className="bg-gray-50 rounded-xl p-6">
              <div className="flex items-center mb-4">
                <div className="text-yellow-400 flex">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <i key={star} className="fas fa-star"></i>
                  ))}
                </div>
              </div>
              <p className="text-gray-600 italic mb-6">
                "Paket Royal benar-benar royal! Makanannya enak, porsinya pas, dan 
                camilannya juga sehat. Sangat cocok untuk saya yang sibuk tapi tetap 
                ingin makan sehat."
              </p>
              <div className="flex items-center">
                <div className="w-12 h-12 rounded-full bg-teal-200 flex items-center justify-center mr-4">
                  <i className="fas fa-user text-teal-600"></i>
                </div>
                <div>
                  <h4 className="font-medium text-gray-800">Dewi Lestari</h4>
                  <p className="text-sm text-gray-500">Bandung</p>
                </div>
              </div>
            </div>
          </div>

          {/* Add Testimonial Form */}
          <div className="mt-16 bg-white rounded-xl shadow-md p-8 max-w-2xl mx-auto">
            <h3 className="text-2xl font-bold text-gray-800 mb-6">
              Bagikan Pengalaman Anda
            </h3>
            <form onSubmit={handleTestimonialSubmit}>
              <div className="mb-4">
                <label htmlFor="name" className="block text-gray-700 mb-2">
                  Nama
                </label>
                <input
                  type="text"
                  id="name"
                  value={testimonialForm.name}
                  onChange={(e) => setTestimonialForm(prev => ({ ...prev, name: e.target.value }))}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                  placeholder="Masukkan nama Anda"
                  required
                />
              </div>
              <div className="mb-4">
                <label htmlFor="city" className="block text-gray-700 mb-2">
                  Kota
                </label>
                <input
                  type="text"
                  id="city"
                  value={testimonialForm.city}
                  onChange={(e) => setTestimonialForm(prev => ({ ...prev, city: e.target.value }))}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                  placeholder="Masukkan kota Anda"
                  required
                />
              </div>
              <div className="mb-4">
                <label className="block text-gray-700 mb-2">Rating</label>
                <div className="flex text-2xl">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <i
                      key={star}
                      className={`fas fa-star mr-2 cursor-pointer ${
                        star <= testimonialForm.rating ? 'text-yellow-400' : 'text-gray-400'
                      } hover:text-yellow-400 transition-colors`}
                      onClick={() => handleStarClick(star)}
                    ></i>
                  ))}
                </div>
              </div>
              <div className="mb-6">
                <label htmlFor="testimonial" className="block text-gray-700 mb-2">
                  Testimoni
                </label>
                <textarea
                  id="testimonial"
                  rows={4}
                  value={testimonialForm.testimonial}
                  onChange={(e) => setTestimonialForm(prev => ({ ...prev, testimonial: e.target.value }))}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                  placeholder="Bagikan pengalaman Anda dengan SEA Catering"
                  required
                ></textarea>
              </div>
              <button
                type="submit"
                className="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors cursor-pointer whitespace-nowrap"
              >
                Kirim Testimoni
              </button>
            </form>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-teal-600">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold text-white mb-6">
            Siap untuk Memulai Perjalanan Sehat Anda?
          </h2>
          <p className="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
            Bergabunglah dengan ribuan pelanggan puas kami dan rasakan manfaat makanan 
            sehat yang dikirim langsung ke pintu Anda.
          </p>
          <button
            onClick={() => setActiveTab('subscription')}
            className="bg-white hover:bg-gray-100 text-teal-700 px-8 py-3 rounded-lg font-medium transition-colors cursor-pointer whitespace-nowrap"
          >
            Mulai Berlangganan
          </button>
        </div>
      </section>
    </main>
  );
};

export default Home;
