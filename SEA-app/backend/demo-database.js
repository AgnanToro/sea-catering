import sqlite3 from 'sqlite3';
import { open } from 'sqlite';
import bcrypt from 'bcryptjs';

async function createDemoDatabase() {
  console.log('🎯 Creating SEA Catering Database Demo...\n');

  // Open database
  const db = await open({
    filename: './sea-catering-demo.sqlite',
    driver: sqlite3.Database
  });

  // Create tables
  console.log('📋 Creating tables...');
  
  await db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      email VARCHAR(255) UNIQUE NOT NULL,
      name VARCHAR(255) NOT NULL,
      password_hash VARCHAR(255) NOT NULL,
      phone VARCHAR(20),
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
  `);

  await db.exec(`
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
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES users(id)
    )
  `);

  await db.exec(`
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

  await db.exec(`
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

  console.log('✅ Tables created successfully!\n');

  // Insert demo data
  console.log('📝 Inserting demo data...');

  // Demo users with hashed passwords
  const adminPassword = await bcrypt.hash('Admin123!', 12);
  const userPassword = await bcrypt.hash('User123!', 12);

  await db.run(`
    INSERT OR IGNORE INTO users (email, name, password_hash, phone) 
    VALUES (?, ?, ?, ?)
  `, ['admin@seacatering.com', 'Administrator', adminPassword, '+62812345678']);

  await db.run(`
    INSERT OR IGNORE INTO users (email, name, password_hash, phone) 
    VALUES (?, ?, ?, ?)
  `, ['john.doe@email.com', 'John Doe', userPassword, '+62856243165']);

  await db.run(`
    INSERT OR IGNORE INTO users (email, name, password_hash, phone) 
    VALUES (?, ?, ?, ?)
  `, ['sarah@email.com', 'Sarah Wilson', userPassword, '+62812987654']);

  // Demo subscriptions
  await db.run(`
    INSERT OR IGNORE INTO subscriptions (user_id, plan_type, status, start_date, next_billing, price, meals_per_day, delivery_days)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  `, [2, 'Paket Protein', 'active', '2024-01-15', '2024-02-15', 95000, 2, '["Senin","Rabu","Jumat"]']);

  await db.run(`
    INSERT OR IGNORE INTO subscriptions (user_id, plan_type, status, start_date, next_billing, price, meals_per_day, delivery_days)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  `, [3, 'Paket Diet', 'active', '2024-01-10', '2024-02-10', 75000, 1, '["Selasa","Kamis","Sabtu"]']);

  await db.run(`
    INSERT OR IGNORE INTO subscriptions (user_id, plan_type, status, start_date, next_billing, price, meals_per_day, delivery_days)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  `, [2, 'Paket Royal', 'paused', '2023-12-01', '2024-01-01', 120000, 3, '["Senin","Rabu","Jumat","Sabtu"]']);

  // Demo testimonials
  await db.run(`
    INSERT OR IGNORE INTO testimonials (name, email, rating, message, is_approved)
    VALUES (?, ?, ?, ?, ?)
  `, ['Sarah Johnson', 'sarah@example.com', 5, 'Amazing quality food and excellent service!', 1]);

  await db.run(`
    INSERT OR IGNORE INTO testimonials (name, email, rating, message, is_approved)
    VALUES (?, ?, ?, ?, ?)
  `, ['Michael Chen', 'michael@example.com', 4, 'Great variety and always on time delivery.', 1]);

  // Demo contact messages
  await db.run(`
    INSERT OR IGNORE INTO contact_messages (name, email, phone, subject, message)
    VALUES (?, ?, ?, ?, ?)
  `, ['Alice Brown', 'alice@email.com', '+62812111222', 'Pertanyaan Menu', 'Apakah ada menu vegetarian?']);

  console.log('✅ Demo data inserted successfully!\n');

  // Show statistics
  console.log('📊 Database Statistics:');
  const userCount = await db.get('SELECT COUNT(*) as count FROM users');
  const subCount = await db.get('SELECT COUNT(*) as count FROM subscriptions');
  const testCount = await db.get('SELECT COUNT(*) as count FROM testimonials');
  const msgCount = await db.get('SELECT COUNT(*) as count FROM contact_messages');

  console.log(`👥 Users: ${userCount.count}`);
  console.log(`📋 Subscriptions: ${subCount.count}`);
  console.log(`⭐ Testimonials: ${testCount.count}`);
  console.log(`📧 Messages: ${msgCount.count}\n`);

  // Show sample data
  console.log('👀 Sample Data Preview:');
  console.log('\n📋 Subscriptions:');
  const subscriptions = await db.all(`
    SELECT 
      s.id,
      s.plan_type,
      s.status,
      s.price,
      u.name as user_name
    FROM subscriptions s
    JOIN users u ON s.user_id = u.id
  `);
  
  subscriptions.forEach(sub => {
    console.log(`- ${sub.user_name}: ${sub.plan_type} (${sub.status}) - Rp ${sub.price.toLocaleString()}`);
  });

  console.log('\n⭐ Testimonials:');
  const testimonials = await db.all('SELECT name, rating, message FROM testimonials WHERE is_approved = 1');
  testimonials.forEach(test => {
    console.log(`- ${test.name} (${test.rating}⭐): "${test.message}"`);
  });

  await db.close();
  console.log('\n🎉 Database demo completed successfully!');
  console.log('📁 Database file created: sea-catering-demo.sqlite');
  console.log('\n💡 You can open this file with SQLite Browser to view the data visually.');
  console.log('💡 Download SQLite Browser: https://sqlitebrowser.org/');
}

// Run the demo
createDemoDatabase().catch(console.error);
