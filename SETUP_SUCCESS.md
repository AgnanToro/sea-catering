# Test API Endpoints

## 🎉 **SETUP BERHASIL SEMPURNA!**

### **✅ Database & Tables Created:**
- ✅ `users` table - dengan admin & user demo
- ✅ `subscriptions` table - untuk meal plans
- ✅ `contact_messages` table - untuk contact form

### **✅ Server Running:**
**CodeIgniter 4 Server:** http://localhost:8080

### **🔑 Test Login:**

#### **Admin Login:**
```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@seaapps.com\",\"password\":\"Admin123!\"}"
```

#### **User Login:**
```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"user@seaapps.com\",\"password\":\"User123!\"}"
```

### **📱 Update React Frontend:**

Di file React `SEA-app/src/services/api.ts`, ubah:
```typescript
const API_BASE = 'http://localhost:8080/api';
```

### **🚀 All API Endpoints Ready:**

#### **Authentication:**
- `POST /api/auth/login` - Login user
- `POST /api/auth/register` - Register user baru
- `GET /api/auth/profile` - Get user profile (auth required)
- `POST /api/auth/logout` - Logout user

#### **Subscriptions:**
- `GET /api/subscriptions` - Get subscriptions
- `POST /api/subscriptions` - Create subscription
- `GET /api/subscriptions/{id}` - Get subscription detail
- `PUT /api/subscriptions/{id}` - Update subscription
- `DELETE /api/subscriptions/{id}` - Delete subscription
- `GET /api/subscriptions/stats` - Get statistics (admin only)

#### **Contact:**
- `POST /api/contact` - Send contact message (public)
- `GET /api/contact` - Get all messages (admin only)
- `GET /api/contact/{id}` - Get message detail (admin only)
- `GET /api/contact/stats` - Get statistics (admin only)

---

## 🎯 **NEXT STEPS:**

1. **Update React app** API base URL
2. **Test login** dengan user demo
3. **Test React app** dengan new backend
4. **Customize** fitur sesuai kebutuhan

**🎉 MIGRASI 100% BERHASIL! 🎉**
