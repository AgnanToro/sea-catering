export interface User {
  id: string;
  email: string;
  name: string;
  phone: string;
  role: 'user' | 'admin';
  createdAt: Date;
}

export interface MenuItem {
  id: string;
  name: string;
  description: string;
  image: string;
  category: 'Diet' | 'Protein' | 'Royal';
  nutritionInfo: {
    calories: number;
    protein: number;
    carbs: number;
    fat: number;
  };
  price: number;
}

export interface Subscription {
  id: string;
  userId: string;
  name: string;
  phone: string;
  plan: 'Diet' | 'Protein' | 'Royal';
  mealTypes: ('breakfast' | 'lunch' | 'dinner')[];
  deliveryDays: string[];
  allergies?: string;
  totalPrice: number;
  status: 'active' | 'paused' | 'cancelled';
  createdAt: Date;
  startDate: Date;
  endDate?: Date;
}

export interface Testimonial {
  id: string;
  name: string;
  city: string;
  rating: number;
  comment: string;
  approved: boolean;
  createdAt: Date;
}

export interface PlanPrices {
  Diet: number;
  Protein: number;
  Royal: number;
}

export interface AuthState {
  isAuthenticated: boolean;
  user: User | null;
  loading: boolean;
}
