const express = require('express');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static React page
app.use(express.static(path.join(__dirname, 'public')));

const fs = require('fs');
const sqlite3 = require('sqlite3').verbose();

// Ensure data directory exists
const dataDir = path.join(__dirname, 'data');
if (!fs.existsSync(dataDir)) fs.mkdirSync(dataDir, { recursive: true });

// Open (or create) sqlite DB
const dbFile = path.join(dataDir, 'users.db');
const db = new sqlite3.Database(dbFile);

// Initialize users table and insert demo admin if not present
db.serialize(() => {
  db.run("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT, password TEXT)");
  db.get("SELECT COUNT(1) as c FROM users WHERE username = 'admin'", (err, row) => {
    if (!err && row && row.c === 0) {
      db.run("INSERT INTO users (username, password) VALUES ('admin','admin')");
    }
  });
});

// Vulnerable login endpoint (INTENTIONAL: concatenates user input into SQL)
app.post('/login', (req, res) => {
  const { username = '', password = '' } = req.body;

  // WARNING: the following line is intentionally vulnerable to SQL injection
  const sql = `SELECT * FROM users WHERE username = '${username}' AND password = '${password}';`;

  db.get(sql, (err, row) => {
    if (err) {
      console.error('DB error', err);
      return res.status(500).json({ success: false, message: 'Internal error' });
    }

    if (row) {
      // login success
      return res.json({ success: true, message: 'Login success (vulnerable demo). Redirecting...' });
    }

    return res.json({ success: false, message: 'Invalid credentials' });
  });
});

// Fallback for SPA
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(PORT, () => {
  console.log(`Login portal running on port ${PORT}`);
});
