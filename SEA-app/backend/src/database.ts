import sqlite3 from 'sqlite3';
import { Database, open } from 'sqlite';

export interface User {
  id: number;
  email: string;
  name: string;
  password_hash: string;
  phone?: string;
  created_at: string;
  updated_at: string;
}

export interface Subscription {
  id: number;
  user_id: number;
  plan_type: string;
  status: 'active' | 'paused' | 'cancelled';
  start_date: string;
  next_billing: string;
  price: number;
  meals_per_day: number;
  delivery_days: string; // JSON string
  created_at: string;
  updated_at: string;
}

export interface Testimonial {
  id: number;
  user_id?: number;
  name: string;
  email?: string;
  rating: number;
  message: string;
  is_approved: boolean;
  created_at: string;
}

export interface ContactMessage {
  id: number;
  name: string;
  email: string;
  phone?: string;
  subject: string;
  message: string;
  status: 'unread' | 'read' | 'replied';
  created_at: string;
}

export interface MealPlan {
  id: number;
  name: string;
  description?: string;
  price_per_meal: number;
  category: string;
  calories?: number;
  protein?: number;
  carbs?: number;
  fat?: number;
  ingredients?: string;
  allergens?: string;
  is_active: boolean;
  created_at: string;
}

class DatabaseManager {
  private db: Database | null = null;

  async initialize(): Promise<void> {
    const dbPath = process.env.DB_PATH || './database.sqlite';
    
    this.db = await open({
      filename: dbPath,
      driver: sqlite3.Database
    });

    await this.createTables();
    await this.seedData();
  }

  private async createTables(): Promise<void> {
    if (!this.db) throw new Error('Database not initialized');

    // Users table
    await this.db.exec(`
      CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email VARCHAR(255) UNIQUE NOT NULL,
        name VARCHAR(255) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )
    `);

    // Subscriptions table
    await this.db.exec(`
      CREATE TABLE IF NOT EXISTS subscriptions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        plan_type VARCHAR(50) NOT NULL,
        status VARCHAR(20) DEFAULT 'active',
        start_date DATE NOT NULL,
        next_billing DATE NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        meals_per_day INTEGER NOT NULL,
        delivery_days TEXT NOT NULL,
        allergies TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
      )
    `);

    // Testimonials table
    await this.db.exec(`
      CREATE TABLE IF NOT EXISTS testimonials (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255),
        rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
        message TEXT NOT NULL,
        is_approved BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
      )
    `);

    // Contact messages table
    await this.db.exec(`
      CREATE TABLE IF NOT EXISTS contact_messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        status VARCHAR(20) DEFAULT 'unread',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )
    `);

    // Meal plans table
    await this.db.exec(`
      CREATE TABLE IF NOT EXISTS meal_plans (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price_per_meal DECIMAL(8,2) NOT NULL,
        category VARCHAR(50) NOT NULL,
        calories INTEGER,
        protein DECIMAL(5,2),
        carbs DECIMAL(5,2),
        fat DECIMAL(5,2),
        ingredients TEXT,
        allergens TEXT,
        is_active BOOLEAN DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )
    `);
  }

  private async seedData(): Promise<void> {
    if (!this.db) throw new Error('Database not initialized');

    // Check if data already exists
    const userCount = await this.db.get('SELECT COUNT(*) as count FROM users');
    if (userCount.count > 0) return; // Data already seeded

    // Seed sample meal plans
    const mealPlans = [
      {
        name: 'Grilled Chicken Salad',
        description: 'Fresh salad with grilled chicken breast',
        price_per_meal: 25000,
        category: 'Diet',
        calories: 350,
        protein: 30,
        carbs: 15,
        fat: 12,
        ingredients: 'Chicken breast, mixed greens, tomatoes, cucumber',
        allergens: null
      },
      {
        name: 'Protein Bowl',
        description: 'High-protein bowl with quinoa and lean meat',
        price_per_meal: 35000,
        category: 'Protein',
        calories: 520,
        protein: 45,
        carbs: 35,
        fat: 18,
        ingredients: 'Quinoa, lean beef, vegetables',
        allergens: null
      },
      {
        name: 'Royal Salmon',
        description: 'Premium salmon with herbs and vegetables',
        price_per_meal: 55000,
        category: 'Royal',
        calories: 650,
        protein: 40,
        carbs: 25,
        fat: 35,
        ingredients: 'Fresh salmon, asparagus, herbs',
        allergens: 'Fish'
      }
    ];

    for (const meal of mealPlans) {
      await this.db.run(`
        INSERT INTO meal_plans (name, description, price_per_meal, category, calories, protein, carbs, fat, ingredients, allergens)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
      `, [meal.name, meal.description, meal.price_per_meal, meal.category, meal.calories, meal.protein, meal.carbs, meal.fat, meal.ingredients, meal.allergens]);
    }

    // Seed sample testimonials
    const testimonials = [
      {
        name: 'Sarah Johnson',
        email: 'sarah@example.com',
        rating: 5,
        message: 'Amazing quality food and excellent service!',
        is_approved: 1
      },
      {
        name: 'Michael Chen',
        email: 'michael@example.com',
        rating: 4,
        message: 'Great variety and always on time delivery.',
        is_approved: 1
      }
    ];

    for (const testimonial of testimonials) {
      await this.db.run(`
        INSERT INTO testimonials (name, email, rating, message, is_approved)
        VALUES (?, ?, ?, ?, ?)
      `, [testimonial.name, testimonial.email, testimonial.rating, testimonial.message, testimonial.is_approved]);
    }
  }

  getDatabase(): Database {
    if (!this.db) throw new Error('Database not initialized');
    return this.db;
  }
}

export const dbManager = new DatabaseManager();
