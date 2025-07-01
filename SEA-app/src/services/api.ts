// API service untuk integrasi dengan backend database
const API_BASE = 'http://localhost:3003/api';

export interface LoginResponse {
  message: string;
  token: string;
  user: {
    id: string | number;
    name: string;
    email: string;
    phone?: string;
    isAdmin?: boolean;
  };
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  phone?: string;
}

export interface SubscriptionRequest {
  plan: string;
  mealsPerDay: number;
  deliveryDays: string[];
  price: number;
  startDate: string;
  // Additional fields from subscription form
  name?: string;
  phone?: string;
  allergies?: string;
  csrfToken?: string;
}

export interface ContactRequest {
  name: string;
  email: string;
  phone?: string;
  subject: string;
  message: string;
  csrfToken: string;
}

class ApiService {
  private getAuthHeaders(): HeadersInit {
    const token = localStorage.getItem('authToken');
    return {
      'Content-Type': 'application/json',
      ...(token && { 'Authorization': `Bearer ${token}` })
    };
  }

  // Authentication APIs
  async login(email: string, password: string): Promise<LoginResponse> {
    const response = await fetch(`${API_BASE}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error || 'Login failed');
    }

    const data = await response.json();
    
    // Store token in localStorage
    localStorage.setItem('authToken', data.token);
    localStorage.setItem('userEmail', data.user.email);
    localStorage.setItem('userRole', data.user.isAdmin ? 'admin' : 'user');
    
    return data;
  }

  async register(userData: RegisterRequest): Promise<LoginResponse> {
    const response = await fetch(`${API_BASE}/auth/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(userData)
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error || error.details || 'Registration failed');
    }

    const data = await response.json();
    
    // Store token in localStorage
    localStorage.setItem('authToken', data.token);
    localStorage.setItem('userEmail', data.user.email);
    localStorage.setItem('userRole', 'user');
    
    return data;
  }

  async logout(): Promise<void> {
    try {
      await fetch(`${API_BASE}/auth/logout`, {
        method: 'POST',
        headers: this.getAuthHeaders()
      });
    } catch (error) {
      console.error('Logout API error:', error);
    } finally {
      // Clear local storage regardless of API response
      localStorage.removeItem('authToken');
      localStorage.removeItem('userEmail');
      localStorage.removeItem('userRole');
    }
  }

  async getProfile(): Promise<any> {
    const response = await fetch(`${API_BASE}/auth/profile`, {
      headers: this.getAuthHeaders()
    });

    if (!response.ok) {
      throw new Error('Failed to get profile');
    }

    return response.json();
  }

  // Subscription APIs
  async getSubscriptions(): Promise<any> {
    const response = await fetch(`${API_BASE}/subscriptions`, {
      headers: this.getAuthHeaders()
    });

    if (!response.ok) {
      throw new Error('Failed to get subscriptions');
    }

    return response.json();
  }

  async createSubscription(subscription: SubscriptionRequest): Promise<any> {
    const response = await fetch(`${API_BASE}/subscriptions`, {
      method: 'POST',
      headers: this.getAuthHeaders(),
      body: JSON.stringify(subscription)
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error || error.details || 'Failed to create subscription');
    }

    return response.json();
  }

  async updateSubscriptionStatus(id: string, status: string): Promise<any> {
    const response = await fetch(`${API_BASE}/subscriptions/${id}`, {
      method: 'PUT',
      headers: this.getAuthHeaders(),
      body: JSON.stringify({ status })
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error || 'Failed to update subscription');
    }

    return response.json();
  }

  // Testimonial APIs
  async getTestimonials(): Promise<any> {
    const response = await fetch(`${API_BASE}/testimonials`);

    if (!response.ok) {
      throw new Error('Failed to get testimonials');
    }

    return response.json();
  }

  async submitTestimonial(testimonial: {
    name: string;
    email?: string;
    rating: number;
    message: string;
  }): Promise<any> {
    const response = await fetch(`${API_BASE}/testimonials`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(testimonial)
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error || error.details || 'Failed to submit testimonial');
    }

    return response.json();
  }

  // Contact API
  async submitContact(contact: ContactRequest): Promise<any> {
    const response = await fetch(`${API_BASE}/contact`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': contact.csrfToken
      },
      body: JSON.stringify(contact)
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error || error.details || 'Failed to send message');
    }

    return response.json();
  }

  // Admin APIs
  async getAdminDashboard(): Promise<any> {
    const response = await fetch(`${API_BASE}/admin/dashboard`, {
      headers: this.getAuthHeaders()
    });

    if (!response.ok) {
      throw new Error('Failed to get admin dashboard');
    }

    return response.json();
  }

  async getAdminUsers(): Promise<any> {
    const response = await fetch(`${API_BASE}/admin/users`, {
      headers: this.getAuthHeaders()
    });

    if (!response.ok) {
      throw new Error('Failed to get users');
    }

    return response.json();
  }

  async getAdminSubscriptions(): Promise<any> {
    const response = await fetch(`${API_BASE}/admin/subscriptions`, {
      headers: this.getAuthHeaders()
    });

    if (!response.ok) {
      throw new Error('Failed to get subscriptions');
    }

    return response.json();
  }

  async getAdminMessages(): Promise<any> {
    const response = await fetch(`${API_BASE}/admin/messages`, {
      headers: this.getAuthHeaders()
    });

    if (!response.ok) {
      throw new Error('Failed to get messages');
    }

    return response.json();
  }

  // Helper method to check if backend is available
  async checkHealth(): Promise<boolean> {
    try {
      const response = await fetch(`${API_BASE}/health`);
      return response.ok;
    } catch (error) {
      return false;
    }
  }
}

export const apiService = new ApiService();

// Helper functions for authentication state
export const isAuthenticated = (): boolean => {
  return !!localStorage.getItem('authToken');
};

export const isAdmin = (): boolean => {
  return localStorage.getItem('userRole') === 'admin';
};

export const getCurrentUserEmail = (): string | null => {
  return localStorage.getItem('userEmail');
};

// Test login credentials for demo
export const testLogin = async (): Promise<void> => {
  try {
    console.log('🧪 Testing login with demo database...');
    
    // Test with John Doe account from demo database
    const result = await apiService.login('john.doe@email.com', 'User123!');
    console.log('✅ Login successful:', result);
    
    // Test getting subscriptions
    const subscriptions = await apiService.getSubscriptions();
    console.log('✅ Subscriptions:', subscriptions);
    
  } catch (error) {
    console.error('❌ Test login failed:', error);
    
    // Check if backend is running
    const isHealthy = await apiService.checkHealth();
    if (!isHealthy) {
      console.error('🚨 Backend server is not running. Please start with: npm run dev');
    }
  }
};
