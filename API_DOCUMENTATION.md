# SEA Apps - CodeIgniter 4 API Documentation

## 🚀 Migrasi React ke CodeIgniter 4 - SELESAI!

### ✅ **Yang Sudah Berhasil Dimigrasi:**

#### **1. Database Schema**
- ✅ **Users Table** - untuk authentication (admin & user)
- ✅ **Subscriptions Table** - untuk meal subscription plans  
- ✅ **Contact Messages Table** - untuk contact form messages

#### **2. Authentication System**
- ✅ **JWT-based Authentication** dengan Firebase JWT
- ✅ **Login/Register** dengan validasi lengkap
- ✅ **Role-based Access** (Admin & User)
- ✅ **Password Security** dengan bcrypt hashing

#### **3. API Endpoints**

##### **🔐 Authentication Endpoints**
```
POST /api/auth/login          - User login
POST /api/auth/register       - User registration  
POST /api/auth/logout         - User logout
GET  /api/auth/profile        - Get user profile
```

##### **📋 Subscription Endpoints**
```
GET    /api/subscriptions           - Get subscriptions (user: own, admin: all)
GET    /api/subscriptions/{id}      - Get subscription detail
POST   /api/subscriptions          - Create new subscription
PUT    /api/subscriptions/{id}     - Update subscription
DELETE /api/subscriptions/{id}     - Delete subscription
PATCH  /api/subscriptions/{id}/status - Update status (admin only)
GET    /api/subscriptions/stats    - Get statistics (admin only)
```

##### **📧 Contact Endpoints**
```
POST   /api/contact                 - Send contact message (public)
GET    /api/contact                 - Get all messages (admin only)
GET    /api/contact/{id}            - Get message detail (admin only)
PATCH  /api/contact/{id}/status     - Update message status (admin only)
DELETE /api/contact/{id}            - Delete message (admin only)
GET    /api/contact/stats           - Get statistics (admin only)
GET    /api/contact/unread-count    - Get unread count (admin only)
```

#### **4. Security Features**
- ✅ **CORS Filter** - untuk React frontend
- ✅ **Auth Filter** - proteksi endpoint dengan JWT
- ✅ **Admin Filter** - proteksi endpoint admin only
- ✅ **Input Validation** - validasi data komprehensif
- ✅ **SQL Injection Protection** - menggunakan Query Builder CI4

#### **5. Models & Business Logic**
- ✅ **UserModel** - dengan password hashing & validation
- ✅ **SubscriptionModel** - dengan stats & search functionality
- ✅ **ContactMessageModel** - dengan status management
- ✅ **BaseApiModel** - reusable model dengan pagination

---

## 🛠️ **Setup Instructions**

### **1. Database Setup**
```bash
# 1. Buat database MySQL
CREATE DATABASE sea_apps_db;

# 2. Jalankan migrations
cd c:\NGODING\SEA-Apps
php spark migrate

# 3. Jalankan seeder untuk user demo
php spark db:seed UserSeeder
```

### **2. Environment Configuration**
File `.env` sudah dikonfigurasi dengan:
- Database connection
- JWT secret key
- CORS settings
- Session & cache settings

### **3. Start Server**
```bash
cd c:\NGODING\SEA-Apps
php spark serve
```
Server akan berjalan di: `http://localhost:8080`

---

## 📖 **API Usage Examples**

### **Authentication**

#### **Register User**
```javascript
POST http://localhost:8080/api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "081234567890",
  "password": "Password123!"
}
```

#### **Login**
```javascript
POST http://localhost:8080/api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "Password123!"
}
```

#### **Response:**
```json
{
  "status": "success",
  "message": "Login berhasil!",
  "data": {
    "message": "Login berhasil!",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "081234567890",
      "isAdmin": false
    }
  }
}
```

### **Subscriptions**

#### **Create Subscription**
```javascript
POST http://localhost:8080/api/subscriptions
Authorization: Bearer {your-jwt-token}
Content-Type: application/json

{
  "plan": "Premium Plan",
  "mealsPerDay": 3,
  "deliveryDays": ["monday", "wednesday", "friday"],
  "price": 450000,
  "startDate": "2025-07-01",
  "allergies": "Tidak ada",
  "specialNotes": "Vegetarian"
}
```

### **Contact Message**

#### **Send Contact Message**
```javascript
POST http://localhost:8080/api/contact
Content-Type: application/json

{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "phone": "081234567891",
  "subject": "Pertanyaan tentang menu",
  "message": "Saya ingin tahu menu apa saja yang tersedia untuk vegetarian."
}
```

---

## 🔑 **Default Users**

Setelah menjalankan seeder, tersedia 2 user default:

### **Admin User**
- **Email:** admin@seaapps.com
- **Password:** Admin123!
- **Role:** Admin

### **Demo User**  
- **Email:** user@seaapps.com
- **Password:** User123!
- **Role:** User

---

## 🎯 **Fitur yang Sudah Berhasil Dimigrasi dari React**

1. ✅ **Authentication System** - Login/Register/Logout
2. ✅ **User & Admin Dashboard** - Role-based access
3. ✅ **Subscription Management** - CRUD operations
4. ✅ **Contact Form** - Message handling
5. ✅ **Input Validation** - Server-side validation
6. ✅ **Error Handling** - Proper error responses
7. ✅ **CORS Support** - untuk frontend React
8. ✅ **JWT Authentication** - Secure token-based auth

---

## 🔄 **Integrasi dengan React Frontend**

Ubah `API_BASE` di file React `src/services/api.ts`:
```typescript
const API_BASE = 'http://localhost:8080/api';
```

Semua endpoint React sudah compatible dengan API CodeIgniter 4 yang baru!

---

## 📊 **Statistics & Dashboard Data**

API menyediakan endpoint untuk dashboard statistics:
- User & subscription counts
- Revenue tracking  
- Contact message statistics
- Monthly analytics

---

## 🛡️ **Security Features**

1. **JWT Authentication** - Secure token-based auth
2. **Password Hashing** - bcrypt dengan salt
3. **Input Validation** - Comprehensive server-side validation
4. **SQL Injection Protection** - CI4 Query Builder
5. **XSS Protection** - Built-in CI4 security
6. **CORS Configuration** - Proper CORS setup
7. **Role-based Access Control** - Admin/User permissions

---

**🎉 MIGRASI BERHASIL SEMPURNA! 🎉**

Aplikasi React Anda telah berhasil dimigrasi ke CodeIgniter 4 dengan semua fitur yang lengkap dan aman!
