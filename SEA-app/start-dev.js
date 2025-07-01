const { spawn } = require('child_process');
const path = require('path');

// Start backend
console.log('🚀 Starting SEA Catering Backend...');

const backend = spawn('npm', ['run', 'dev'], {
  cwd: path.join(__dirname, 'backend'),
  stdio: 'inherit',
  shell: true
});

// Start frontend
setTimeout(() => {
  console.log('🌐 Starting SEA Catering Frontend...');
  const frontend = spawn('npm', ['run', 'dev'], {
    cwd: __dirname,
    stdio: 'inherit',
    shell: true
  });

  frontend.on('error', (err) => {
    console.error('Frontend error:', err);
  });
}, 3000);

backend.on('error', (err) => {
  console.error('Backend error:', err);
});

backend.on('close', (code) => {
  console.log(`Backend exited with code ${code}`);
});
