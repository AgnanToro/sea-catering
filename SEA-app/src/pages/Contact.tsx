import React, { useState, useEffect } from 'react';
import { validateEmail, validatePhone } from '../utils/helpers';
import { sanitizeInput, generateCSRFToken } from '../utils/security';

const Contact: React.FC = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
  });

  const [errors, setErrors] = useState<Record<string, string>>({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [csrfToken, setCsrfToken] = useState('');

  // Generate CSRF token on component mount
  useEffect(() => {
    setCsrfToken(generateCSRFToken());
  }, []);

  const validateForm = () => {
    const newErrors: Record<string, string> = {};

    // Sanitize and validate name
    const sanitizedName = sanitizeInput(formData.name);
    if (!sanitizedName.trim()) {
      newErrors.name = 'Nama harus diisi';
    } else if (sanitizedName !== formData.name) {
      newErrors.name = 'Nama mengandung karakter tidak valid';
    }

    // Sanitize and validate email
    const sanitizedEmail = sanitizeInput(formData.email);
    if (!sanitizedEmail.trim()) {
      newErrors.email = 'Email harus diisi';
    } else if (!validateEmail(sanitizedEmail)) {
      newErrors.email = 'Format email tidak valid';
    } else if (sanitizedEmail !== formData.email) {
      newErrors.email = 'Email mengandung karakter tidak valid';
    }

    // Sanitize and validate phone
    const sanitizedPhone = sanitizeInput(formData.phone);
    if (!sanitizedPhone.trim()) {
      newErrors.phone = 'Nomor HP harus diisi';
    } else if (!validatePhone(sanitizedPhone)) {
      newErrors.phone = 'Format nomor HP tidak valid';
    } else if (sanitizedPhone !== formData.phone) {
      newErrors.phone = 'Nomor HP mengandung karakter tidak valid';
    }

    // Sanitize and validate subject
    const sanitizedSubject = sanitizeInput(formData.subject);
    if (!sanitizedSubject.trim()) {
      newErrors.subject = 'Subjek harus diisi';
    } else if (sanitizedSubject !== formData.subject) {
      newErrors.subject = 'Subjek mengandung karakter tidak valid';
    }

    // Sanitize and validate message
    const sanitizedMessage = sanitizeInput(formData.message);
    if (!sanitizedMessage.trim()) {
      newErrors.message = 'Pesan harus diisi';
    } else if (sanitizedMessage.trim().length < 10) {
      newErrors.message = 'Pesan minimal 10 karakter';
    } else if (sanitizedMessage !== formData.message) {
      newErrors.message = 'Pesan mengandung karakter tidak valid';
    }

    // Check for potential XSS attacks
    const xssPatterns = [
      /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
      /javascript:/gi,
      /on\w+\s*=/gi
    ];

    const allInputs = [sanitizedName, sanitizedEmail, sanitizedPhone, sanitizedSubject, sanitizedMessage];
    for (const input of allInputs) {
      for (const pattern of xssPatterns) {
        if (pattern.test(input)) {
          newErrors.security = 'Input mengandung kode yang tidak diizinkan';
          break;
        }
      }
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }

    setIsSubmitting(true);
    
    try {
      // Sanitize all inputs before processing
      const sanitizedData = {
        name: sanitizeInput(formData.name),
        email: sanitizeInput(formData.email),
        phone: sanitizeInput(formData.phone),
        subject: sanitizeInput(formData.subject),
        message: sanitizeInput(formData.message),
        csrfToken: csrfToken // Include CSRF token
      };

      // Simulate API call with CSRF protection
      const response = await fetch('/api/contact', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify(sanitizedData)
      });

      if (response.ok) {
        alert('Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
        
        // Reset form and generate new CSRF token
        setFormData({
          name: '',
          email: '',
          phone: '',
          subject: '',
          message: '',
        });
        setCsrfToken(generateCSRFToken());
      } else {
        throw new Error('Failed to send message');
      }
    } catch (error) {
      console.error('Contact form error:', error);
      alert('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleInputChange = (field: string, value: string) => {
    // Basic sanitization on input
    const sanitized = value.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
                           .replace(/javascript:/gi, '')
                           .replace(/on\w+\s*=/gi, '');
    
    setFormData(prev => ({ ...prev, [field]: sanitized }));
    
    // Clear specific field error when user starts typing
    if (errors[field]) {
      setErrors(prev => ({ ...prev, [field]: '' }));
    }
  };

  const contactInfo = [
    {
      icon: 'fa-map-marker-alt',
      title: 'Alamat',
      details: ['Jl. Sehat No. 123', 'Jakarta Selatan, 12345'],
      color: 'text-red-500'
    },
    {
      icon: 'fa-phone-alt',
      title: 'Telepon',
      details: ['+62 812-3456-7890', '+62 21-1234-5678'],
      color: 'text-green-500'
    },
    {
      icon: 'fa-envelope',
      title: 'Email',
      details: ['info@seacatering.com', 'support@seacatering.com'],
      color: 'text-blue-500'
    },
    {
      icon: 'fa-clock',
      title: 'Jam Operasional',
      details: ['Senin - Jumat: 08:00 - 18:00', 'Sabtu: 08:00 - 16:00'],
      color: 'text-purple-500'
    }
  ];

  return (
    <main className="min-h-screen bg-gray-50 py-8">
      <div className="container mx-auto px-4">
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-gray-800 mb-4">Hubungi Kami</h1>
          <p className="text-lg text-gray-600 max-w-3xl mx-auto">
            Punya pertanyaan atau butuh bantuan? Tim customer service kami siap membantu Anda. 
            Hubungi kami melalui form di bawah atau kontak langsung.
          </p>
        </div>

        <div className="max-w-6xl mx-auto">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {/* Contact Form */}
            <div className="bg-white rounded-xl shadow-md p-8">
              <div className="flex items-center mb-6">
                <i className="fas fa-comments text-teal-500 text-2xl mr-3"></i>
                <h2 className="text-2xl font-bold text-gray-800">Kirim Pesan</h2>
              </div>
              
              <form onSubmit={handleSubmit} className="space-y-6">
                {/* Security Notice */}
                {errors.security && (
                  <div className="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div className="flex items-center">
                      <i className="fas fa-shield-alt text-red-500 mr-2"></i>
                      <p className="text-red-700 font-medium">Peringatan Keamanan</p>
                    </div>
                    <p className="text-red-600 text-sm mt-1">{errors.security}</p>
                  </div>
                )}

                {/* Hidden CSRF Token */}
                <input type="hidden" name="csrfToken" value={csrfToken} />

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label htmlFor="name" className="block text-gray-700 font-medium mb-2">
                      Nama Lengkap *
                    </label>
                    <input
                      type="text"
                      id="name"
                      value={formData.name}
                      onChange={(e) => handleInputChange('name', e.target.value)}
                      className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 ${
                        errors.name ? 'border-red-500' : 'border-gray-300'
                      }`}
                      placeholder="Masukkan nama lengkap"
                      disabled={isSubmitting}
                      maxLength={100}
                    />
                    {errors.name && (
                      <p className="text-red-500 text-sm mt-1">{errors.name}</p>
                    )}
                  </div>
                  
                  <div>
                    <label htmlFor="email" className="block text-gray-700 font-medium mb-2">
                      Email *
                    </label>
                    <input
                      type="email"
                      id="email"
                      value={formData.email}
                      onChange={(e) => handleInputChange('email', e.target.value)}
                      className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 ${
                        errors.email ? 'border-red-500' : 'border-gray-300'
                      }`}
                      placeholder="nama@email.com"
                      disabled={isSubmitting}
                      maxLength={255}
                    />
                    {errors.email && (
                      <p className="text-red-500 text-sm mt-1">{errors.email}</p>
                    )}
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label htmlFor="phone" className="block text-gray-700 font-medium mb-2">
                      Nomor HP *
                    </label>
                    <input
                      type="tel"
                      id="phone"
                      value={formData.phone}
                      onChange={(e) => handleInputChange('phone', e.target.value)}
                      className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 ${
                        errors.phone ? 'border-red-500' : 'border-gray-300'
                      }`}
                      placeholder="08xxxxxxxxxx"
                      disabled={isSubmitting}
                      maxLength={20}
                    />
                    {errors.phone && (
                      <p className="text-red-500 text-sm mt-1">{errors.phone}</p>
                    )}
                  </div>

                  <div>
                    <label htmlFor="subject" className="block text-gray-700 font-medium mb-2">
                      Subjek *
                    </label>
                    <select
                      id="subject"
                      value={formData.subject}
                      onChange={(e) => handleInputChange('subject', e.target.value)}
                      className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 ${
                        errors.subject ? 'border-red-500' : 'border-gray-300'
                      }`}
                      disabled={isSubmitting}
                    >
                      <option value="">Pilih subjek</option>
                      <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                      <option value="Langganan">Langganan</option>
                      <option value="Pengiriman">Pengiriman</option>
                      <option value="Keluhan">Keluhan</option>
                      <option value="Saran">Saran</option>
                      <option value="Lainnya">Lainnya</option>
                    </select>
                    {errors.subject && (
                      <p className="text-red-500 text-sm mt-1">{errors.subject}</p>
                    )}
                  </div>
                </div>

                <div>
                  <label htmlFor="message" className="block text-gray-700 font-medium mb-2">
                    Pesan *
                  </label>
                  <textarea
                    id="message"
                    rows={6}
                    value={formData.message}
                    onChange={(e) => setFormData(prev => ({ ...prev, message: e.target.value }))}
                    className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-vertical ${
                      errors.message ? 'border-red-500' : 'border-gray-300'
                    }`}
                    placeholder="Tulis pesan Anda di sini..."
                    disabled={isSubmitting}
                  ></textarea>
                  {errors.message && (
                    <p className="text-red-500 text-sm mt-1">{errors.message}</p>
                  )}
                  <p className="text-gray-500 text-sm mt-1">
                    {formData.message.length}/500 karakter
                  </p>
                </div>

                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="w-full bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white py-3 rounded-lg font-medium transition-colors flex items-center justify-center"
                >
                  {isSubmitting ? (
                    <>
                      <i className="fas fa-spinner fa-spin mr-2"></i>
                      Mengirim...
                    </>
                  ) : (
                    <>
                      <i className="fas fa-paper-plane mr-2"></i>
                      Kirim Pesan
                    </>
                  )}
                </button>
              </form>
            </div>

            {/* Contact Information */}
            <div className="space-y-6">
              {/* Manager Contact */}
              <div className="bg-white rounded-xl shadow-md p-6">
                <div className="flex items-center mb-4">
                  <i className="fas fa-user-tie text-teal-500 text-2xl mr-3"></i>
                  <h3 className="text-xl font-bold text-gray-800">Manajer</h3>
                </div>
                <div className="flex items-center">
                  <div className="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mr-4">
                    <i className="fas fa-user text-teal-600 text-2xl"></i>
                  </div>
                  <div>
                    <h4 className="font-bold text-lg text-gray-800">Brian</h4>
                    <p className="text-gray-600">Manajer Customer Service</p>
                    <p className="text-teal-600 font-medium">+62 812-3456-7890</p>
                  </div>
                </div>
              </div>

              {/* Contact Info Cards */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {contactInfo.map((info, index) => (
                  <div key={index} className="bg-white rounded-xl shadow-md p-6">
                    <div className="flex items-center mb-3">
                      <i className={`fas ${info.icon} ${info.color} text-xl mr-3`}></i>
                      <h4 className="font-bold text-gray-800">{info.title}</h4>
                    </div>
                    <div className="space-y-1">
                      {info.details.map((detail, detailIndex) => (
                        <p key={detailIndex} className="text-gray-600 text-sm">
                          {detail}
                        </p>
                      ))}
                    </div>
                  </div>
                ))}
              </div>

              {/* FAQ Section */}
              <div className="bg-white rounded-xl shadow-md p-6">
                <div className="flex items-center mb-4">
                  <i className="fas fa-question-circle text-teal-500 text-2xl mr-3"></i>
                  <h3 className="text-xl font-bold text-gray-800">FAQ</h3>
                </div>
                <div className="space-y-4">
                  <div>
                    <h4 className="font-medium text-gray-800 mb-1">
                      Bagaimana cara berlangganan?
                    </h4>
                    <p className="text-gray-600 text-sm">
                      Daftar akun, pilih paket, isi form langganan, dan lakukan pembayaran.
                    </p>
                  </div>
                  <div>
                    <h4 className="font-medium text-gray-800 mb-1">
                      Apakah bisa mengubah menu?
                    </h4>
                    <p className="text-gray-600 text-sm">
                      Ya, Anda dapat mengkustomisasi menu sesuai preferensi dan kebutuhan diet.
                    </p>
                  </div>
                  <div>
                    <h4 className="font-medium text-gray-800 mb-1">
                      Bagaimana dengan pengiriman?
                    </h4>
                    <p className="text-gray-600 text-sm">
                      Kami mengirim ke seluruh kota besar di Indonesia dengan jadwal yang fleksibel.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  );
};

export default Contact;
