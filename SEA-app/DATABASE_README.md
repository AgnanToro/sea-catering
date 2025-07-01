# 🚀 SEA Catering Backend Setup Guide

## Status Database: ✅ SQLite (Tidak Butuh XAMPP!)

Aplikasi ini menggunakan **SQLite** sebagai database, yang artinya:
- ❌ **TIDAK** butuh XAMPP/phpMyAdmin
- ❌ **TIDAK** butuh install MySQL terpisah
- ✅ Database otomatis dibuat sebagai file `database.sqlite`
- ✅ Mudah di-backup dan di-deploy

## Quick Start

### 1. Jalankan Backend
```bash
cd backend
npm install
npm run dev
```

### 2. Database Otomatis Terbuat
File `database.sqlite` akan otomatis dibuat dengan tables:
- `users` - Data pengguna
- `subscriptions` - Data langganan  
- `testimonials` - Testimoni pengguna
- `contact_messages` - Pesan kontak
- `meal_plans` - Data menu makanan

### 3. API Endpoints Tersedia

#### Authentication
- `POST /api/auth/register` - Daftar user baru
- `POST /api/auth/login` - Login user
- `GET /api/auth/profile` - Profil user

#### Subscriptions  
- `GET /api/subscriptions` - Daftar langganan user
- `POST /api/subscriptions` - Buat langganan baru
- `PUT /api/subscriptions/:id` - Update status langganan

#### Testimonials
- `GET /api/testimonials` - Testimoni yang disetujui
- `POST /api/testimonials` - Submit testimoni baru

#### Contact
- `POST /api/contact` - Submit form kontak (dengan CSRF protection)

#### Admin (butuh login admin)
- `GET /api/admin/dashboard` - Dashboard metrics
- `GET /api/admin/users` - Daftar semua user  
- `GET /api/admin/subscriptions` - Daftar semua langganan
- `GET /api/admin/messages` - Pesan kontak

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Subscriptions Table  
```sql
CREATE TABLE subscriptions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    plan_type VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    start_date DATE NOT NULL,
    next_billing DATE NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    meals_per_day INTEGER NOT NULL,
    delivery_days TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## Testing Database

### 1. Test API dengan Postman/Thunder Client

#### Register User
```json
POST http://localhost:3001/api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com", 
  "password": "SecurePass123!",
  "phone": "081234567890"
}
```

#### Login
```json
POST http://localhost:3001/api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "SecurePass123!"
}
```

#### Create Subscription (butuh token dari login)
```json
POST http://localhost:3001/api/subscriptions
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN

{
  "plan": "Paket Protein",
  "mealsPerDay": 2,
  "deliveryDays": ["Senin", "Rabu", "Jumat"],
  "price": 95000,
  "startDate": "2024-02-01"
}
```

### 2. View Database dengan SQLite Browser
- Download: https://sqlitebrowser.org/
- Buka file `backend/database.sqlite`
- View dan edit data secara visual

## Integrasi Frontend

Update frontend untuk menggunakan API:

```typescript
// src/services/api.ts
const API_BASE = 'http://localhost:3001/api';

export const authAPI = {
  async login(email: string, password: string) {
    const response = await fetch(`${API_BASE}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    return response.json();
  },
  
  async register(userData: any) {
    const response = await fetch(`${API_BASE}/auth/register`, {
      method: 'POST', 
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(userData)
    });
    return response.json();
  }
};
```

## Security Features Implemented

### ✅ Level 4 Security Requirements

1. **Authentication & Authorization**
   - JWT tokens untuk autentikasi
   - Hashed passwords dengan bcrypt
   - Admin-only routes protection

2. **Input Validation & Sanitization** 
   - Joi validation untuk semua input
   - SQL injection prevention (parameterized queries)
   - XSS protection dengan input sanitization
   - CSRF token validation

3. **Security Headers**
   - Helmet.js untuk security headers
   - CORS protection  
   - Rate limiting (100 req/15min per IP)

## Production Deployment

Untuk production, ganti ke PostgreSQL:
1. Setup PostgreSQL database
2. Update connection string di `.env`
3. Migrations akan sama, tinggal ganti driver

## Keuntungan SQLite vs XAMPP

| Feature | SQLite | XAMPP/MySQL |
|---------|--------|-------------|
| Setup | ✅ Zero config | ❌ Complex setup |
| File size | ✅ Single file | ❌ Full server |
| Portability | ✅ Copy file | ❌ Export/import |
| Performance | ✅ Fast reads | ⚠️ Network overhead |
| Scalability | ⚠️ Single writer | ✅ Multiple connections |
| Suitable for | Development, Small apps | Large production apps |

## Kesimpulan

**SQLite sudah cukup untuk:**
- ✅ Development dan testing
- ✅ Small to medium apps (1000+ users)
- ✅ Prototype dan MVP
- ✅ Apps dengan read-heavy workload

**Upgrade ke PostgreSQL jika:**
- ⚠️ Need concurrent writes (1000+ simultaneous users)
- ⚠️ Need advanced features (replication, clustering)
- ⚠️ Team development (shared database)

Untuk aplikasi SEA Catering ini, **SQLite sudah sempurna** untuk Level 4 requirements!
