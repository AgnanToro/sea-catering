import express from 'express';
import Joi from 'joi';
import { dbManager } from '../database';

const router = express.Router();

// Validation schema
const contactSchema = Joi.object({
  name: Joi.string().min(2).max(100).required(),
  email: Joi.string().email().required(),
  phone: Joi.string().min(10).max(20).optional(),
  subject: Joi.string().min(5).max(255).required(),
  message: Joi.string().min(10).max(2000).required(),
  csrfToken: Joi.string().required()
});

// Submit contact form
router.post('/', async (req, res) => {
  try {
    const { error, value } = contactSchema.validate(req.body);
    if (error) {
      return res.status(400).json({ 
        error: 'Validation error', 
        details: error.details[0].message 
      });
    }

    const { name, email, phone, subject, message, csrfToken } = value;
    
    // CSRF token validation (basic implementation)
    const csrfHeader = req.headers['x-csrf-token'];
    if (!csrfHeader || csrfHeader !== csrfToken) {
      return res.status(403).json({ error: 'Invalid CSRF token' });
    }

    const db = dbManager.getDatabase();

    await db.run(`
      INSERT INTO contact_messages (name, email, phone, subject, message)
      VALUES (?, ?, ?, ?, ?)
    `, [name, email, phone, subject, message]);

    res.status(201).json({ 
      message: 'Message sent successfully. We will contact you soon.'
    });
  } catch (error) {
    console.error('Contact form error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

export default router;
