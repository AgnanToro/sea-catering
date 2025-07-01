import express from 'express';
import Joi from 'joi';
import { dbManager } from '../database';
import { authenticateToken, AuthenticatedRequest } from '../middleware/auth';

const router = express.Router();

// Validation schema
const subscriptionSchema = Joi.object({
  plan: Joi.string().valid('Paket Diet', 'Paket Protein', 'Paket Royal').required(),
  mealsPerDay: Joi.number().min(1).max(5).required(),
  deliveryDays: Joi.array().items(Joi.string()).min(1).max(7).required(),
  price: Joi.number().min(0).required(),
  startDate: Joi.date().min('now').required(),
  // Additional optional fields
  name: Joi.string().max(255).optional(),
  phone: Joi.string().max(20).optional(),
  allergies: Joi.string().max(1000).allow('', null).optional(),
  csrfToken: Joi.string().optional()
});

// Get user's subscriptions
router.get('/', authenticateToken, async (req: AuthenticatedRequest, res) => {
  try {
    const db = dbManager.getDatabase();
    const subscriptions = await db.all(
      'SELECT * FROM subscriptions WHERE user_id = ? ORDER BY created_at DESC',
      [req.user!.userId]
    );

    res.json({ subscriptions });
  } catch (error) {
    console.error('Get subscriptions error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Create new subscription
router.post('/', authenticateToken, async (req: AuthenticatedRequest, res) => {
  try {
    const { error, value } = subscriptionSchema.validate(req.body);
    if (error) {
      console.log('Validation error details:', error.details);
      console.log('Request body:', req.body);
      return res.status(400).json({ 
        error: 'Data tidak valid', 
        details: error.details[0].message.replace(/"/g, ''),
        field: error.details[0].path[0]
      });
    }

    const { plan, mealsPerDay, deliveryDays, price, startDate, allergies } = value;
    const db = dbManager.getDatabase();

    // Validate startDate is not in the past
    const today = new Date().toISOString().split('T')[0];
    if (startDate < today) {
      return res.status(400).json({ 
        error: 'Tanggal mulai tidak boleh di masa lalu' 
      });
    }

    // Calculate next billing date (30 days from start)
    const nextBilling = new Date(startDate);
    nextBilling.setDate(nextBilling.getDate() + 30);

    const result = await db.run(`
      INSERT INTO subscriptions (user_id, plan_type, meals_per_day, delivery_days, price, start_date, next_billing, allergies)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    `, [
      req.user!.userId,
      plan,
      mealsPerDay,
      JSON.stringify(deliveryDays),
      price,
      startDate,
      nextBilling.toISOString().split('T')[0],
      allergies || null
    ]);

    const newSubscription = await db.get(
      'SELECT * FROM subscriptions WHERE id = ?',
      [result.lastID]
    );

    res.status(201).json({ 
      message: 'Subscription berhasil dibuat',
      subscription: newSubscription
    });
  } catch (error) {
    console.error('Create subscription error:', error);
    res.status(500).json({ 
      error: 'Terjadi kesalahan server',
      details: process.env.NODE_ENV === 'development' ? (error instanceof Error ? error.message : 'Unknown error') : 'Silakan coba lagi'
    });
  }
});

// Update subscription status
router.put('/:id', authenticateToken, async (req: AuthenticatedRequest, res) => {
  try {
    const { id } = req.params;
    const { status } = req.body;

    if (!['active', 'paused', 'cancelled'].includes(status)) {
      return res.status(400).json({ error: 'Status tidak valid. Gunakan: active, paused, atau cancelled' });
    }

    const db = dbManager.getDatabase();

    // Check if subscription belongs to user
    const subscription = await db.get(
      'SELECT * FROM subscriptions WHERE id = ? AND user_id = ?',
      [id, req.user!.userId]
    );

    if (!subscription) {
      return res.status(404).json({ error: 'Subscription tidak ditemukan atau tidak memiliki akses' });
    }

    // Prevent changing already cancelled subscriptions
    if (subscription.status === 'cancelled' && status !== 'cancelled') {
      return res.status(400).json({ error: 'Subscription yang sudah dibatalkan tidak dapat diubah' });
    }

    await db.run(
      'UPDATE subscriptions SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?',
      [status, id]
    );

    const updatedSubscription = await db.get(
      'SELECT * FROM subscriptions WHERE id = ?',
      [id]
    );

    let message = 'Status subscription berhasil diubah';
    switch (status) {
      case 'active':
        message = 'Subscription berhasil diaktifkan';
        break;
      case 'paused':
        message = 'Subscription berhasil dijeda';
        break;
      case 'cancelled':
        message = 'Subscription berhasil dibatalkan';
        break;
    }

    res.json({ 
      message,
      subscription: updatedSubscription
    });
  } catch (error) {
    console.error('Update subscription error:', error);
    res.status(500).json({ 
      error: 'Terjadi kesalahan server',
      details: process.env.NODE_ENV === 'development' ? (error instanceof Error ? error.message : 'Unknown error') : 'Silakan coba lagi'
    });
  }
});

export default router;
