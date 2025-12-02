<?php
/**
 * ZOMBIE APOCALYPSE SATELLITE UPLINK - CTF Challenge
 * VULNERABLE VERSION - For Educational Purposes Only
 * Demonstrates Command Injection Vulnerability
 */

$output = "";
$ip = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip'])) {
    $ip = $_POST['ip'];
    
    // VULNERABILITY: Direct user input passed to system() without validation or sanitization
    // This allows command injection attacks like: 127.0.0.1; cat /flag.txt
    $command = "ping -c 4 " . $ip;
    
    ob_start();
    system($command, $return_code);
    $output = ob_get_clean();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zombie Apocalypse - Satellite Uplink</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #0a0e27 0%, #1a0e0e 100%);
            color: #00ff00;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .terminal {
            background: rgba(0, 0, 0, 0.85);
            border: 2px solid #00ff00;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 255, 0, 0.3), inset 0 0 50px rgba(0, 0, 0, 0.5);
            max-width: 800px;
            width: 100%;
            padding: 30px;
            animation: flicker 0.15s infinite;
        }
        
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.98; }
        }
        
        .terminal-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #00ff00;
        }
        
        .terminal-buttons {
            display: flex;
            gap: 8px;
            margin-right: 15px;
        }
        
        .btn-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .btn-red { background: #ff5f56; }
        .btn-yellow { background: #ffbd2e; }
        .btn-green { background: #27c93f; }
        
        h1 {
            font-size: 1.5em;
            color: #ff0000;
            text-shadow: 0 0 10px #ff0000;
            letter-spacing: 2px;
        }
        
        .warning {
            background: rgba(255, 0, 0, 0.1);
            border-left: 4px solid #ff0000;
            padding: 15px;
            margin: 20px 0;
            color: #ff6666;
        }
        
        .status {
            color: #ffff00;
            margin-bottom: 20px;
            text-shadow: 0 0 5px #ffff00;
        }
        
        form {
            margin: 20px 0;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #00ff00;
            font-weight: bold;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px;
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid #00ff00;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        input[type="text"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        button {
            background: #00ff00;
            color: #000;
            border: none;
            padding: 12px 30px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        button:hover {
            background: #00cc00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.6);
        }
        
        .output-container {
            margin-top: 20px;
        }
        
        .output-label {
            color: #ffff00;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        pre {
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid #00ff00;
            padding: 15px;
            color: #00ff00;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            border-radius: 4px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .hint {
            margin-top: 30px;
            padding: 15px;
            background: rgba(0, 100, 255, 0.1);
            border-left: 4px solid #0066ff;
            color: #66aaff;
            font-size: 0.9em;
        }
        
        .skull {
            color: #ff0000;
            font-size: 2em;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="terminal">
        <div class="terminal-header">
            <div class="terminal-buttons">
                <div class="btn-circle btn-red"></div>
                <div class="btn-circle btn-yellow"></div>
                <div class="btn-circle btn-green"></div>
            </div>
            <h1>☣ ZOMBIE APOCALYPSE - SATELLITE UPLINK ☣</h1>
        </div>
        
        <div class="skull">💀 ☠️ 💀</div>
        
        <div class="warning">
            <strong>⚠ EMERGENCY BROADCAST SYSTEM ⚠</strong><br>
            The undead have overrun the city. Our last hope is the military satellite network.
            Use this terminal to ping survivor outposts and coordinate rescue operations.
        </div>
        
        <div class="status">
            >>> SYSTEM STATUS: CRITICAL<br>
            >>> ZOMBIE THREAT LEVEL: MAXIMUM<br>
            >>> SATELLITE LINK: ACTIVE
        </div>
        
        <form method="POST">
            <label for="ip">Enter Target IP Address (Survivor Outpost):</label>
            <input 
                type="text" 
                id="ip" 
                name="ip" 
                placeholder="e.g., 127.0.0.1 or 8.8.8.8"
                value="<?php echo htmlspecialchars($ip); ?>"
                required
            >
            <button type="submit">🛰️ INITIATE PING SEQUENCE</button>
        </form>
        
        <?php if ($output): ?>
        <div class="output-container">
            <div class="output-label">>>> SATELLITE RESPONSE:</div>
            <pre><?php echo htmlspecialchars($output); ?></pre>
        </div>
        <?php endif; ?>
        
        <div class="hint">
            <strong>🎯 CTF HINT:</strong> The satellite system is old and vulnerable. 
            Military protocols were abandoned during the outbreak. 
            Can you find a way to access classified information? 
            Try exploring what else the system might reveal...
        </div>
    </div>
</body>
</html>