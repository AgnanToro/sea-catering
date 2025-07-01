import type { PlanPrices } from '../types';

export const PLAN_PRICES: PlanPrices = {
  Diet: 75000,
  Protein: 95000,
  Royal: 120000,
};

export const calculateSubscriptionPrice = (
  plan: 'Diet' | 'Protein' | 'Royal',
  mealTypes: string[],
  deliveryDays: string[]
): number => {
  const basePrice = PLAN_PRICES[plan];
  const mealsPerDay = mealTypes.length;
  const daysPerWeek = deliveryDays.length;
  
  // Calculate weekly price
  const weeklyPrice = basePrice * mealsPerDay * daysPerWeek;
  
  return weeklyPrice;
};

export const formatCurrency = (amount: number): string => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
};

export const formatDate = (date: Date): string => {
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(date);
};

export const validateEmail = (email: string): boolean => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

export const validatePhone = (phone: string): boolean => {
  const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,10}$/;
  return phoneRegex.test(phone);
};

export const hashPassword = async (password: string): Promise<string> => {
  // In a real app, use bcrypt or similar
  // This is a simple hash for demo purposes
  const encoder = new TextEncoder();
  const data = encoder.encode(password);
  const hashBuffer = await crypto.subtle.digest('SHA-256', data);
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
};

export const generateId = (): string => {
  return Math.random().toString(36).substr(2, 9);
};

// Mock database functions
export const mockDelay = (ms: number = 1000): Promise<void> => {
  return new Promise(resolve => setTimeout(resolve, ms));
};
