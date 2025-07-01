import React, { useState, useEffect } from 'react';
import Header from './components/Header';
import Footer from './components/Footer';
import Home from './pages/Home';
import Menu from './pages/Menu';
import Subscription from './pages/Subscription';
import Contact from './pages/Contact';
import UserDashboard from './pages/UserDashboard';
import AdminDashboard from './pages/AdminDashboard';
import { apiService, isAuthenticated, isAdmin, getCurrentUserEmail } from './services/api';

const App: React.FC = () => {
  const [activeTab, setActiveTab] = useState('home');
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [userRole, setUserRole] = useState<'user' | 'admin'>('user');

  // Check authentication status on app load
  useEffect(() => {
    const checkAuthStatus = () => {
      const token = localStorage.getItem('authToken');
      const userRole = localStorage.getItem('userRole');
      
      if (token) {
        setIsAuthenticated(true);
        setUserRole(userRole === 'admin' ? 'admin' : 'user');
      }
    };

    checkAuthStatus();
  }, []);

  const handleLogin = async (e?: React.FormEvent) => {
    console.log('handleLogin called with event:', e);
    if (e) {
      e.preventDefault();
      const form = e.target as HTMLFormElement;
      const formData = new FormData(form);
      const email = formData.get('email') as string;
      const password = formData.get('password') as string;

      // Validate input
      if (!email || !password) {
        alert('Email dan password harus diisi!');
        return;
      }

      // Validate email format
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        alert('Format email tidak valid!');
        return;
      }

      try {
        // Login via API
        const response = await apiService.login(email, password);
        
        // Update authentication state
        setIsAuthenticated(true);
        
        if (response.user.isAdmin) {
          setUserRole('admin');
        } else {
          setUserRole('user');
        }
        
        alert('Login berhasil!');
        setActiveTab('dashboard');
        
      } catch (error) {
        console.error('Login error:', error);
        const errorMessage = error instanceof Error ? error.message : 'Login gagal';
        alert(errorMessage);
      }
    } else {
      // Called from header button - just show login form
      console.log('Showing login form');
      setActiveTab('login');
    }
  };

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    const form = e.target as HTMLFormElement;
    const formData = new FormData(form);
    const name = formData.get('name') as string;
    const email = formData.get('email') as string;
    const phone = formData.get('phone') as string;
    const password = formData.get('password') as string;
    const confirmPassword = formData.get('confirm-password') as string;

    // Basic validation
    if (!name || !email || !phone || !password || !confirmPassword) {
      alert('Semua field harus diisi!');
      return;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('Format email tidak valid!');
      return;
    }

    // Validate phone format (Indonesian phone number)
    const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
    if (!phoneRegex.test(phone)) {
      alert('Format nomor HP tidak valid! Gunakan format 08xxxxxxxxxx');
      return;
    }

    if (password !== confirmPassword) {
      alert('Password dan konfirmasi password tidak sama!');
      return;
    }

    // Enhanced password validation
    if (password.length < 8) {
      alert('Password minimal 8 karakter!');
      return;
    }

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/;
    if (!passwordRegex.test(password)) {
      alert('Password harus mengandung minimal 8 karakter dengan:\n- Huruf kecil (a-z)\n- Huruf besar (A-Z)\n- Angka (0-9)\n- Karakter khusus (!@#$%^&*)');
      return;
    }

    try {
      // Register user via API
      const response = await apiService.register({
        name,
        email,
        password,
        phone
      });

      alert('Registrasi berhasil! Anda sudah otomatis login.');
      
      // Update authentication state
      setIsAuthenticated(true);
      setUserRole('user');
      setActiveTab('dashboard');

    } catch (error) {
      console.error('Registration error:', error);
      const errorMessage = error instanceof Error ? error.message : 'Terjadi kesalahan saat registrasi';
      alert(errorMessage);
    }
  };

  const handleLogout = async () => {
    try {
      await apiService.logout();
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      // Clear state regardless of API response
      setIsAuthenticated(false);
      setUserRole('user');
      setActiveTab('home');
      alert('Logout berhasil!');
    }
  };

  const renderContent = () => {
    switch (activeTab) {
      case 'home':
        return <Home setActiveTab={setActiveTab} />;
      case 'menu':
        return <Menu setActiveTab={setActiveTab} />;
      case 'subscription':
        return <Subscription isAuthenticated={isAuthenticated} setActiveTab={setActiveTab} />;
      case 'contact':
        return <Contact />;
      case 'dashboard':
        if (!isAuthenticated) {
          setActiveTab('login');
          return null;
        }
        return userRole === 'admin' 
          ? <AdminDashboard setActiveTab={setActiveTab} />
          : <UserDashboard setActiveTab={setActiveTab} />;
      case 'login':
        return (
          <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md w-full space-y-8">
              <div>
                <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                  Masuk ke Akun Anda
                </h2>
                <p className="mt-2 text-center text-sm text-gray-600">
                  Atau{' '}
                  <button 
                    onClick={() => setActiveTab('register')}
                    className="font-medium text-teal-600 hover:text-teal-500"
                  >
                    daftar akun baru
                  </button>
                </p>
              </div>
              <div className="bg-white rounded-lg shadow-md p-8">
                <form className="space-y-6" onSubmit={handleLogin}>
                  <div>
                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                      Email
                    </label>
                    <input
                      id="email"
                      name="email"
                      type="email"
                      autoComplete="email"
                      required
                      className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                      placeholder="Masukkan email"
                    />
                  </div>
                  <div>
                    <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                      Password
                    </label>
                    <input
                      id="password"
                      name="password"
                      type="password"
                      autoComplete="current-password"
                      required
                      className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                      placeholder="Masukkan password"
                    />
                  </div>

                  <div className="flex items-center justify-between">
                    <div className="flex items-center">
                      <input
                        id="remember-me"
                        name="remember-me"
                        type="checkbox"
                        className="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                      />
                      <label htmlFor="remember-me" className="ml-2 block text-sm text-gray-900">
                        Ingat saya
                      </label>
                    </div>

                    <div className="text-sm">
                      <button type="button" className="font-medium text-teal-600 hover:text-teal-500">
                        Lupa password?
                      </button>
                    </div>
                  </div>

                  <div>
                    <button
                      type="submit"
                      className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
                    >
                      Masuk
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        );
      case 'register':
        return (
          <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md w-full space-y-8">
              <div>
                <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                  Daftar Akun Baru
                </h2>
                <p className="mt-2 text-center text-sm text-gray-600">
                  Sudah punya akun?{' '}
                  <button 
                    onClick={() => setActiveTab('login')}
                    className="font-medium text-teal-600 hover:text-teal-500"
                  >
                    masuk di sini
                  </button>
                </p>
              </div>
              <div className="bg-white rounded-lg shadow-md p-8">
                <form className="space-y-6" onSubmit={handleRegister}>
                  <div>
                    <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                      Nama Lengkap
                    </label>
                    <input
                      id="name"
                      name="name"
                      type="text"
                      required
                      className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                      placeholder="Masukkan nama lengkap"
                    />
                  </div>
                  <div>
                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                      Email
                    </label>
                    <input
                      id="email"
                      name="email"
                      type="email"
                      autoComplete="email"
                      required
                      className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                      placeholder="Masukkan email"
                    />
                  </div>
                  <div>
                    <label htmlFor="phone" className="block text-sm font-medium text-gray-700">
                      Nomor HP
                    </label>
                    <input
                      id="phone"
                      name="phone"
                      type="tel"
                      required
                      className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                      placeholder="08xxxxxxxxxx"
                    />
                  </div>
                  <div>
                    <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                      Password
                    </label>
                    <input
                      id="password"
                      name="password"
                      type="password"
                      autoComplete="new-password"
                      required
                      className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                      placeholder="Masukkan password"
                    />
                    <p className="mt-1 text-xs text-gray-500">
                      Password minimal 8 karakter dengan huruf kecil, huruf besar, angka, dan karakter khusus (!@#$%^&*)
                    </p>
                  </div>
                  <div>
                    <label htmlFor="confirm-password" className="block text-sm font-medium text-gray-700">
                      Konfirmasi Password
                    </label>
                    <input
                      id="confirm-password"
                      name="confirm-password"
                      type="password"
                      autoComplete="new-password"
                      required
                      className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                      placeholder="Konfirmasi password"
                    />
                  </div>

                  <div>
                    <button
                      type="submit"
                      className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
                    >
                      Daftar
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        );
      default:
        return <Home setActiveTab={setActiveTab} />;
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 font-sans">
      <Header
        activeTab={activeTab}
        setActiveTab={setActiveTab}
        isAuthenticated={isAuthenticated}
        userRole={userRole}
        onLogin={handleLogin}
        onLogout={handleLogout}
      />
      {renderContent()}
      <Footer />
    </div>
  );
};

export default App;
