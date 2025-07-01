import express from 'express';
import { dbManager } from '../database';
import { authenticateToken, requireAdmin, AuthenticatedRequest } from '../middleware/auth';

const router = express.Router();

// Get dashboard statistics
router.get('/dashboard', authenticateToken, requireAdmin, async (req: AuthenticatedRequest, res) => {
  try {
    const db = dbManager.getDatabase();

    // Get total users
    const totalUsers = await db.get('SELECT COUNT(*) as count FROM users');
    
    // Get total subscriptions
    const totalSubscriptions = await db.get('SELECT COUNT(*) as count FROM subscriptions');
    
    // Get active subscriptions
    const activeSubscriptions = await db.get(
      'SELECT COUNT(*) as count FROM subscriptions WHERE status = ?', ['active']
    );
    
    // Calculate MRR (Monthly Recurring Revenue)
    const mrrResult = await db.get(
      'SELECT SUM(price) as total FROM subscriptions WHERE status = ?', ['active']
    );
    
    // Get new subscriptions this month
    const thisMonth = new Date();
    const firstDay = new Date(thisMonth.getFullYear(), thisMonth.getMonth(), 1);
    const newSubscriptions = await db.get(
      'SELECT COUNT(*) as count FROM subscriptions WHERE created_at >= ?',
      [firstDay.toISOString()]
    );

    // Get reactivations (paused subscriptions)
    const reactivations = await db.get(
      'SELECT COUNT(*) as count FROM subscriptions WHERE status = ?', ['paused']
    );

    // Get recent activities
    const recentActivities = await db.all(`
      SELECT 
        s.plan_type,
        s.status,
        s.created_at,
        u.name as user_name
      FROM subscriptions s
      JOIN users u ON s.user_id = u.id
      ORDER BY s.created_at DESC
      LIMIT 10
    `);

    const stats = {
      totalUsers: totalUsers.count,
      totalSubscriptions: totalSubscriptions.count,
      activeSubscriptions: activeSubscriptions.count,
      mrr: mrrResult.total || 0,
      newSubscriptions: newSubscriptions.count,
      reactivations: reactivations.count,
      recentActivities
    };

    res.json({ stats });
  } catch (error) {
    console.error('Admin dashboard error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Get all users
router.get('/users', authenticateToken, requireAdmin, async (req: AuthenticatedRequest, res) => {
  try {
    const db = dbManager.getDatabase();
    const users = await db.all(
      'SELECT id, name, email, phone, created_at FROM users ORDER BY created_at DESC'
    );

    res.json({ users });
  } catch (error) {
    console.error('Get users error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Get all subscriptions
router.get('/subscriptions', authenticateToken, requireAdmin, async (req: AuthenticatedRequest, res) => {
  try {
    const db = dbManager.getDatabase();
    const subscriptions = await db.all(`
      SELECT 
        s.*,
        u.name as user_name,
        u.email as user_email
      FROM subscriptions s
      JOIN users u ON s.user_id = u.id
      ORDER BY s.created_at DESC
    `);

    res.json({ subscriptions });
  } catch (error) {
    console.error('Get subscriptions error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Get contact messages
router.get('/messages', authenticateToken, requireAdmin, async (req: AuthenticatedRequest, res) => {
  try {
    const db = dbManager.getDatabase();
    const messages = await db.all(
      'SELECT * FROM contact_messages ORDER BY created_at DESC'
    );

    res.json({ messages });
  } catch (error) {
    console.error('Get messages error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

export default router;
