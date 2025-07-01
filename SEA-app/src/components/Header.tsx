import React, { useState } from 'react';

interface HeaderProps {
  activeTab: string;
  setActiveTab: (tab: string) => void;
  isAuthenticated?: boolean;
  userRole?: 'user' | 'admin';
  onLogin?: () => void;
  onLogout?: () => void;
}

const Header: React.FC<HeaderProps> = ({
  activeTab,
  setActiveTab,
  isAuthenticated = false,
  userRole = 'user',
  onLogin,
  onLogout,
}) => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const navItems = [
    { id: 'home', label: 'Beranda' },
    { id: 'menu', label: 'Menu' },
    { id: 'subscription', label: 'Langganan' },
    { id: 'contact', label: 'Hubungi Kami' },
  ];

  const handleNavClick = (tabId: string) => {
    setActiveTab(tabId);
    setMobileMenuOpen(false);
  };

  return (
    <header className="bg-white shadow-sm sticky top-0 z-50">
      <div className="container mx-auto px-4 py-4 flex justify-between items-center relative">
        <div className="flex items-center">
          <button 
            onClick={() => handleNavClick('home')}
            className="text-2xl font-bold text-teal-600 cursor-pointer"
          >
            SEA Catering
          </button>
        </div>

        {/* Desktop Navigation */}
        <nav className="hidden md:flex space-x-8">
          {navItems.map((item) => (
            <button
              key={item.id}
              onClick={() => handleNavClick(item.id)}
              className={`${
                activeTab === item.id ? 'text-teal-600 font-medium' : 'text-gray-600'
              } hover:text-teal-500 transition-colors cursor-pointer whitespace-nowrap`}
            >
              {item.label}
            </button>
          ))}
          {isAuthenticated && (
            <>
              <button
                onClick={() => handleNavClick('dashboard')}
                className={`${
                  activeTab === 'dashboard' ? 'text-teal-600 font-medium' : 'text-gray-600'
                } hover:text-teal-500 transition-colors cursor-pointer whitespace-nowrap`}
              >
                Dashboard
              </button>
              {userRole === 'admin' && (
                <button
                  onClick={() => handleNavClick('admin')}
                  className={`${
                    activeTab === 'admin' ? 'text-teal-600 font-medium' : 'text-gray-600'
                  } hover:text-teal-500 transition-colors cursor-pointer whitespace-nowrap`}
                >
                  Admin
                </button>
              )}
            </>
          )}
        </nav>

        {/* Login/Logout Button */}
        <div className="hidden md:block z-10 relative">
          {isAuthenticated ? (
            <button
              onClick={() => {
                console.log('Logout clicked');
                onLogout?.();
              }}
              className="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors cursor-pointer whitespace-nowrap"
              type="button"
            >
              Keluar
            </button>
          ) : (
            <button
              onClick={() => {
                console.log('Login clicked', onLogin);
                onLogin?.();
              }}
              className="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors cursor-pointer whitespace-nowrap"
              type="button"
            >
              Masuk
            </button>
          )}
        </div>

        {/* Mobile Menu Button */}
        <button
          className="md:hidden text-gray-600"
          onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
        >
          <i className={`fas ${mobileMenuOpen ? 'fa-times' : 'fa-bars'} text-xl`}></i>
        </button>
      </div>

      {/* Mobile Menu */}
      {mobileMenuOpen && (
        <div className="md:hidden bg-white py-4 px-4 shadow-md">
          <div className="flex flex-col space-y-4">
            {navItems.map((item) => (
              <button
                key={item.id}
                onClick={() => handleNavClick(item.id)}
                className={`${
                  activeTab === item.id ? 'text-teal-600 font-medium' : 'text-gray-600'
                } hover:text-teal-500 transition-colors cursor-pointer whitespace-nowrap text-left`}
              >
                {item.label}
              </button>
            ))}
            {isAuthenticated && (
              <>
                <button
                  onClick={() => handleNavClick('dashboard')}
                  className={`${
                    activeTab === 'dashboard' ? 'text-teal-600 font-medium' : 'text-gray-600'
                  } hover:text-teal-500 transition-colors cursor-pointer whitespace-nowrap text-left`}
                >
                  Dashboard
                </button>
                {userRole === 'admin' && (
                  <button
                    onClick={() => handleNavClick('admin')}
                    className={`${
                      activeTab === 'admin' ? 'text-teal-600 font-medium' : 'text-gray-600'
                    } hover:text-teal-500 transition-colors cursor-pointer whitespace-nowrap text-left`}
                  >
                    Admin
                  </button>
                )}
              </>
            )}
            {isAuthenticated ? (
              <button
                onClick={() => {
                  console.log('Mobile logout clicked');
                  onLogout?.();
                  setMobileMenuOpen(false);
                }}
                className="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors cursor-pointer whitespace-nowrap"
              >
                Keluar
              </button>
            ) : (
              <button
                onClick={() => {
                  console.log('Mobile login clicked', onLogin);
                  onLogin?.();
                  setMobileMenuOpen(false);
                }}
                className="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors cursor-pointer whitespace-nowrap"
              >
                Masuk
              </button>
            )}
          </div>
        </div>
      )}
    </header>
  );
};

export default Header;
