import express from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import Joi from 'joi';
import { dbManager } from '../database';
import { authenticateToken, AuthenticatedRequest } from '../middleware/auth';

const router = express.Router();

// Validation schemas
const registerSchema = Joi.object({
  name: Joi.string().min(2).max(100).required().messages({
    'string.min': 'Nama minimal 2 karakter',
    'string.max': 'Nama maksimal 100 karakter',
    'any.required': 'Nama harus diisi'
  }),
  email: Joi.string().email().required().messages({
    'string.email': 'Format email tidak valid',
    'any.required': 'Email harus diisi'
  }),
  password: Joi.string().min(8).pattern(new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])'))
    .message('Password harus minimal 8 karakter dengan huruf kecil, huruf besar, angka, dan karakter khusus (!@#$%^&*)')
    .required().messages({
      'any.required': 'Password harus diisi'
    }),
  phone: Joi.string().optional().messages({
    'string.base': 'Nomor HP harus berupa teks'
  })
});

const loginSchema = Joi.object({
  email: Joi.string().email().required().messages({
    'string.email': 'Format email tidak valid',
    'any.required': 'Email harus diisi'
  }),
  password: Joi.string().required().messages({
    'any.required': 'Password harus diisi'
  })
});

// Register new user
router.post('/register', async (req, res) => {
  try {
    // Validate input
    const { error, value } = registerSchema.validate(req.body);
    if (error) {
      return res.status(400).json({ 
        error: 'Validation error', 
        details: error.details[0].message 
      });
    }

    const { name, email, password, phone } = value;
    const db = dbManager.getDatabase();

    // Check if user already exists
    const existingUser = await db.get('SELECT id FROM users WHERE email = ?', [email]);
    if (existingUser) {
      return res.status(409).json({ error: 'Email sudah terdaftar. Silakan gunakan email lain atau login dengan akun yang sudah ada.' });
    }

    // Hash password
    const saltRounds = 12;
    const passwordHash = await bcrypt.hash(password, saltRounds);

    // Insert new user
    const result = await db.run(
      'INSERT INTO users (name, email, password_hash, phone) VALUES (?, ?, ?, ?)',
      [name, email, passwordHash, phone]
    );

    // Generate JWT token
    const token = jwt.sign(
      { userId: result.lastID, email },
      process.env.JWT_SECRET!,
      { expiresIn: '7d' }
    );

    res.status(201).json({
      message: 'Registrasi berhasil! Anda sudah otomatis login.',
      token,
      user: {
        id: result.lastID,
        name,
        email,
        phone
      }
    });
  } catch (error) {
    console.error('Registration error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Login user
router.post('/login', async (req, res) => {
  try {
    // Validate input
    const { error, value } = loginSchema.validate(req.body);
    if (error) {
      return res.status(400).json({ 
        error: 'Validation error', 
        details: error.details[0].message 
      });
    }

    const { email, password } = value;
    const db = dbManager.getDatabase();

    // Check for admin credentials
    if (email === 'admin@seacatering.com') {
      const adminPassword = 'Admin123!'; // In production, store this securely
      
      if (password === adminPassword) {
        const token = jwt.sign(
          { userId: 'admin', email, isAdmin: true },
          process.env.JWT_SECRET!,
          { expiresIn: '7d' }
        );

        return res.json({
          message: 'Login admin berhasil',
          token,
          user: {
            id: 'admin',
            name: 'Administrator',
            email,
            isAdmin: true
          }
        });
      }
    }

    // Get user from database
    const user = await db.get('SELECT * FROM users WHERE email = ?', [email]);
    if (!user) {
      return res.status(401).json({ error: 'Email atau password salah' });
    }

    // Verify password
    const isValidPassword = await bcrypt.compare(password, user.password_hash);
    if (!isValidPassword) {
      return res.status(401).json({ error: 'Email atau password salah' });
    }

    // Generate JWT token
    const token = jwt.sign(
      { userId: user.id, email: user.email },
      process.env.JWT_SECRET!,
      { expiresIn: '7d' }
    );

    res.json({
      message: 'Login berhasil',
      token,
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        phone: user.phone
      }
    });
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Get user profile
router.get('/profile', authenticateToken, async (req: AuthenticatedRequest, res) => {
  try {
    const db = dbManager.getDatabase();
    const user = await db.get('SELECT id, name, email, phone, created_at FROM users WHERE id = ?', [req.user!.userId]);
    
    if (!user) {
      return res.status(404).json({ error: 'User not found' });
    }

    res.json({ user });
  } catch (error) {
    console.error('Profile error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Logout (client-side token removal)
router.post('/logout', (req, res) => {
  res.json({ message: 'Logout berhasil' });
});

export default router;
