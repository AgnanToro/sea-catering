import express from 'express';
import Joi from 'joi';
import { dbManager } from '../database';

const router = express.Router();

// Validation schema
const testimonialSchema = Joi.object({
  name: Joi.string().min(2).max(100).required(),
  email: Joi.string().email().optional(),
  rating: Joi.number().min(1).max(5).required(),
  message: Joi.string().min(10).max(1000).required()
});

// Get approved testimonials
router.get('/', async (req, res) => {
  try {
    const db = dbManager.getDatabase();
    const testimonials = await db.all(
      'SELECT name, rating, message, created_at FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 20'
    );

    res.json({ testimonials });
  } catch (error) {
    console.error('Get testimonials error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Submit new testimonial
router.post('/', async (req, res) => {
  try {
    const { error, value } = testimonialSchema.validate(req.body);
    if (error) {
      return res.status(400).json({ 
        error: 'Validation error', 
        details: error.details[0].message 
      });
    }

    const { name, email, rating, message } = value;
    const db = dbManager.getDatabase();

    await db.run(`
      INSERT INTO testimonials (name, email, rating, message)
      VALUES (?, ?, ?, ?)
    `, [name, email, rating, message]);

    res.status(201).json({ 
      message: 'Testimonial submitted successfully. It will be reviewed before publication.'
    });
  } catch (error) {
    console.error('Submit testimonial error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

export default router;
