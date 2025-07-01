// Demo data untuk testing aplikasi
import { hashPassword, verifyPassword } from './security';

export interface DemoSubscription {
  id: string;
  plan: string;
  status: 'active' | 'paused' | 'cancelled';
  startDate: string;
  nextBilling: string;
  price: number;
  mealsPerDay: number;
  deliveryDays: string[];
}

export interface DemoUser {
  email: string;
  name: string;
  passwordHash: string; // Store hashed password
  subscriptions: DemoSubscription[];
}

// Database user credentials - untuk validasi login (with hashed passwords)
export const registeredUsers: DemoUser[] = [
  {
    email: 'demo@user.com',
    name: 'User Demo',
    passwordHash: hashPassword('Demo123!'), // Strong password requirement
    subscriptions: [
      {
        id: 'SUB-001',
        plan: 'Paket Protein',
        status: 'active',
        startDate: '2024-01-15',
        nextBilling: '2024-02-15',
        price: 95000,
        mealsPerDay: 2,
        deliveryDays: ['Senin', 'Rabu', 'Jumat']
      }
    ]
  },
  {
    email: 'john.doe@email.com',
    name: 'John Doe',
    passwordHash: hashPassword('John123!'), // Strong password requirement
    subscriptions: [
      {
        id: 'SUB-003',
        plan: 'Paket Diet',
        status: 'active',
        startDate: '2024-01-10',
        nextBilling: '2024-02-10',
        price: 75000,
        mealsPerDay: 1,
        deliveryDays: ['Selasa', 'Kamis', 'Sabtu']
      },
      {
        id: 'SUB-004',
        plan: 'Paket Royal',
        status: 'paused',
        startDate: '2023-12-01',
        nextBilling: '2024-01-01',
        price: 120000,
        mealsPerDay: 3,
        deliveryDays: ['Senin', 'Rabu', 'Jumat', 'Sabtu']
      }
    ]
  }
];

// Admin credentials with hashed password
export const adminCredentials = {
  email: 'admin@seacatering.com',
  passwordHash: hashPassword('Admin123!') // Strong password requirement
};

// Function to validate user login with hashed passwords
export const validateUserLogin = (email: string, password: string): { isValid: boolean; user?: DemoUser; isAdmin?: boolean } => {
  // Check admin credentials
  if (email === adminCredentials.email && verifyPassword(password, adminCredentials.passwordHash)) {
    return { isValid: true, isAdmin: true };
  }

  // Check regular user credentials
  const user = registeredUsers.find(u => u.email === email && verifyPassword(password, u.passwordHash));
  if (user) {
    return { isValid: true, user, isAdmin: false };
  }

  return { isValid: false };
};

// Function to register new user with hashed password
export const registerUser = (email: string, password: string, name: string, phone: string): boolean => {
  // Check if user already exists
  const existingUser = registeredUsers.find(u => u.email === email);
  if (existingUser) {
    return false; // User already exists
  }

  // Add new user to database with hashed password
  const newUser: DemoUser = {
    email,
    name: `${name} (${phone})`, // Include phone in name for demo
    passwordHash: hashPassword(password), // Hash the password
    subscriptions: []
  };

  registeredUsers.push(newUser);
  return true; // Registration successful
};

// Function to get user subscriptions
export const getUserSubscriptions = (email: string): DemoSubscription[] => {
  const user = registeredUsers.find(u => u.email === email);
  return user ? user.subscriptions : [];
};

// Function to add subscription to user
export const addUserSubscription = (email: string, subscription: DemoSubscription): boolean => {
  const userIndex = registeredUsers.findIndex(u => u.email === email);
  if (userIndex !== -1) {
    registeredUsers[userIndex].subscriptions.push(subscription);
    return true;
  }
  return false;
};

// Demo data for admin dashboard
export const adminDashboardData = {
  stats: {
    totalSubscriptions: 150,
    activeSubscriptions: 120,
    monthlyRevenue: 12500000,
    newSubscriptionsThisMonth: 25,
    reactivations: 8
  },
  
  recentSubscriptions: [
    {
      id: 'SUB-150',
      customerName: 'Sarah Wilson',
      email: 'sarah@email.com',
      plan: 'Paket Protein',
      status: 'active',
      createdAt: '2024-01-28',
      amount: 95000
    },
    {
      id: 'SUB-149',
      customerName: 'David Chen',
      email: 'david@email.com',
      plan: 'Paket Royal',
      status: 'active',
      createdAt: '2024-01-27',
      amount: 120000
    },
    {
      id: 'SUB-148',
      customerName: 'Maya Putri',
      email: 'maya@email.com',
      plan: 'Paket Diet',
      status: 'active',
      createdAt: '2024-01-26',
      amount: 75000
    }
  ]
};
