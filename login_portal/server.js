const express = require('express');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static React page
app.use(express.static(path.join(__dirname, 'public')));

// Simulated vulnerable login endpoint
app.post('/login', (req, res) => {
  const { username = '', password = '' } = req.body;

  // Simple, intentionally insecure detection of SQL injection patterns
  const lowered = String(username).toLowerCase();
  const looksLikeSqli = lowered.includes(" or ") || lowered.includes("' or ") || lowered.includes(' or\'1\'=\'1');

  // Normal admin credentials (for convenience)
  const okPlain = username === 'admin' && password === 'admin';

  if (okPlain || looksLikeSqli) {
    return res.json({ success: true, message: 'Login success (simulated). Redirecting...' });
  }

  return res.json({ success: false, message: 'Invalid credentials' });
});

// Fallback for SPA
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(PORT, () => {
  console.log(`Login portal running on port ${PORT}`);
});
