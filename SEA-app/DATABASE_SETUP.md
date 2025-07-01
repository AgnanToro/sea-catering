# Database Implementation Guide for SEA Catering

## Overview
This guide provides multiple options for implementing a real database backend for the SEA Catering application.

## Current Data Structure
The app currently uses these data types:
- Users (authentication, profiles)
- Subscriptions (meal plans, status, billing)
- Testimonials (reviews, ratings)
- Contact Messages (form submissions)

## Implementation Options

### Option 1: SQLite + Express.js Backend (Recommended for Development)

**Pros:**
- Easy to set up and deploy
- No external database server needed
- Perfect for development and small-scale production
- SQL-based with good TypeScript support

**Setup Steps:**

#### 1. Create Backend Server
```bash
# Create backend directory
mkdir sea-catering-backend
cd sea-catering-backend

# Initialize Node.js project
npm init -y

# Install dependencies
npm install express sqlite3 bcryptjs jsonwebtoken cors helmet express-rate-limit
npm install -D @types/node @types/express @types/bcryptjs @types/jsonwebtoken @types/cors typescript nodemon
```

#### 2. Database Schema
```sql
-- Users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Subscriptions table
CREATE TABLE subscriptions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    plan_type VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    start_date DATE NOT NULL,
    next_billing DATE NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    meals_per_day INTEGER NOT NULL,
    delivery_days TEXT NOT NULL, -- JSON array
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Testimonials table
CREATE TABLE testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    message TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'unread',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Meal plans table
CREATE TABLE meal_plans (
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
);
```

#### 3. API Endpoints Needed
```
Authentication:
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
GET  /api/auth/profile

Subscriptions:
GET    /api/subscriptions (user's subscriptions)
POST   /api/subscriptions (create new subscription)
PUT    /api/subscriptions/:id (update subscription)
DELETE /api/subscriptions/:id (cancel subscription)

Testimonials:
GET  /api/testimonials (public testimonials)
POST /api/testimonials (submit testimonial)

Contact:
POST /api/contact (submit contact form)

Admin:
GET /api/admin/dashboard (dashboard metrics)
GET /api/admin/users (user management)
GET /api/admin/subscriptions (subscription management)
```

### Option 2: PostgreSQL + Prisma ORM

**Pros:**
- More scalable for production
- Excellent TypeScript support with Prisma
- Better for complex queries and relationships
- Built-in type safety

**Setup:**
```bash
npm install prisma @prisma/client
npm install -D prisma

# Initialize Prisma
npx prisma init
```

### Option 3: Firebase/Firestore (NoSQL)

**Pros:**
- No backend server needed
- Real-time updates
- Built-in authentication
- Easy to scale

**Cons:**
- Different query patterns (NoSQL)
- Vendor lock-in

### Option 4: Supabase (PostgreSQL + API)

**Pros:**
- PostgreSQL with auto-generated APIs
- Built-in authentication
- Real-time subscriptions
- Easy to set up

## Recommended Implementation Steps

### Phase 1: Backend Setup (Week 1)
1. Set up Express.js server with SQLite
2. Implement authentication endpoints
3. Create user registration/login APIs
4. Add input validation and security measures

### Phase 2: Core Features (Week 2)
1. Implement subscription management APIs
2. Add testimonial submission/display
3. Contact form backend
4. Admin dashboard APIs

### Phase 3: Security & Polish (Week 3)
1. Add rate limiting
2. Implement CSRF protection
3. Add comprehensive input validation
4. Error handling and logging

## Security Considerations

1. **Input Validation**: Use libraries like `joi` or `yup`
2. **SQL Injection Prevention**: Use parameterized queries
3. **Authentication**: JWT tokens with proper expiration
4. **Rate Limiting**: Prevent abuse with express-rate-limit
5. **CORS**: Configure properly for frontend domain
6. **Environment Variables**: Store secrets securely

## Frontend Integration

Update the React app to:
1. Replace demo data with API calls
2. Add loading states
3. Handle API errors properly
4. Implement authentication flow
5. Add offline support (optional)

## Example API Service

```typescript
// src/services/api.ts
class ApiService {
  private baseURL = process.env.REACT_APP_API_URL || 'http://localhost:3001/api';

  async login(email: string, password: string) {
    const response = await fetch(`${this.baseURL}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    return response.json();
  }

  async getSubscriptions() {
    const token = localStorage.getItem('token');
    const response = await fetch(`${this.baseURL}/subscriptions`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    return response.json();
  }
}
```

Would you like me to implement any of these options for your project?
