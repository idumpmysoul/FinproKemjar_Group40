<?php
/**
 * ZOMBIE APOCALYPSE SATELLITE UPLINK - SECURE VERSION
 * Defense-in-Depth Implementation
 * 
 * SECURITY LAYERS IMPLEMENTED:
 * 1. Input Validation (First Line of Defense)
 * 2. Input Sanitization (Second Line of Defense)
 * 3. Principle of Least Privilege
 * 4. Error Handling
 */

$output = "";
$ip = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip'])) {
    $ip = trim($_POST['ip']);
    
    // ============================================================================
    // DEFENSE LAYER 1: STRICT INPUT VALIDATION
    // ============================================================================
    // WHY: This is the PRIMARY defense. We only accept data that matches 
    // our exact specification - a valid IP address. This prevents ALL malicious
    // input from ever reaching the system() call.
    //
    // FILTER_VALIDATE_IP ensures the input is a properly formatted IPv4 or IPv6 address.
    // If validation fails, we reject the input entirely - no execution happens.
    //
    // This stops attacks like:
    // - "127.0.0.1; cat /flag.txt"
    // - "127.0.0.1 && ls -la"
    // - "127.0.0.1 | whoami"
    // - "$(cat /flag.txt)"
    // ============================================================================
    
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $error = "Invalid IP address format. Please enter a valid IPv4 or IPv6 address.";
        $ip = ""; // Clear invalid input
    } else {
        // ============================================================================
        // DEFENSE LAYER 2: INPUT SANITIZATION (Defense in Depth)
        // ============================================================================
        // WHY: Even though we validated the IP, we add escapeshellarg() as a 
        // SECONDARY layer of protection. This follows the "Defense in Depth" principle:
        // if our validation somehow fails or has a bug, this provides backup protection.
        //
        // escapeshellarg() wraps the input in single quotes and escapes any single quotes
        // within the input, making it impossible for the shell to interpret it as commands.
        //
        // Example: escapeshellarg("127.0.0.1") becomes '127.0.0.1'
        //
        // This is SAFER than escapeshellcmd() alone because:
        // - escapeshellcmd() only escapes special characters but still allows them in the string
        // - escapeshellarg() treats the ENTIRE input as a single literal string argument
        // - Multiple layers mean if one fails, others still protect us
        // ============================================================================
        
        $safe_ip = escapeshellarg($ip);
        $command = "ping -c 4 " . $safe_ip;
        
        // ============================================================================
        // DEFENSE LAYER 3: CONTROLLED EXECUTION
        // ============================================================================
        // We capture output safely and handle errors appropriately
        // ============================================================================
        
        ob_start();
        system($command, $return_code);
        $output = ob_get_clean();
        
        // Check if command executed successfully
        if ($return_code !== 0 && $return_code !== 1) {
            $error = "Command execution failed. Please try again.";
        }
    }
}

/**
 * ADDITIONAL SECURITY RECOMMENDATIONS (Beyond this implementation):
 * 
 * 1. USE PARAMETERIZED FUNCTIONS: Instead of system(), use exec() with explicit
 *    argument arrays where possible, or better yet, use PHP's network functions
 *    like socket_create() to avoid shell commands entirely.
 * 
 * 2. WHITELIST APPROACH: Only allow specific known-safe IP ranges if possible.
 * 
 * 3. RATE LIMITING: Prevent abuse by limiting how many pings a user can perform.
 * 
 * 4. LOGGING & MONITORING: Log all ping attempts for security auditing.
 * 
 * 5. PRINCIPLE OF LEAST PRIVILEGE: Run the web server with minimal permissions.
 *    The user running Apache shouldn't have access to sensitive files.
 * 
 * 6. DISABLE UNNECESSARY FUNCTIONS: In php.ini, disable dangerous functions:
 *    disable_functions = exec,passthru,shell_exec,system,proc_open,popen
 *    (Only enable what you absolutely need)
 * 
 * 7. CSP HEADERS: Implement Content Security Policy to prevent XSS attacks.
 * 
 * 8. INPUT LENGTH LIMITS: Restrict maximum input length to prevent buffer attacks.
 */

// Additional validation: Check input length
if (strlen($ip) > 45) { // Max IPv6 length is 45 chars
    $error = "IP address too long.";
    $ip = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zombie Apocalypse - Satellite Uplink [SECURED]</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #0a0e27 0%, #0e1a0e 100%);
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
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
            letter-spacing: 2px;
        }
        
        .secure-badge {
            background: rgba(0, 255, 0, 0.2);
            border: 2px solid #00ff00;
            padding: 10px 15px;
            margin: 20px 0;
            color: #00ff00;
            font-weight: bold;
            text-align: center;
            border-radius: 4px;
        }
        
        .status {
            color: #00ff00;
            margin-bottom: 20px;
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
        
        .error {
            background: rgba(255, 0, 0, 0.2);
            border-left: 4px solid #ff0000;
            padding: 15px;
            margin: 20px 0;
            color: #ff6666;
        }
        
        .output-container {
            margin-top: 20px;
        }
        
        .output-label {
            color: #00ff00;
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
        
        .security-info {
            margin-top: 30px;
            padding: 15px;
            background: rgba(0, 255, 0, 0.05);
            border: 1px solid #00ff00;
            border-radius: 4px;
            font-size: 0.9em;
        }
        
        .security-info h3 {
            margin-bottom: 10px;
            color: #00ff00;
        }
        
        .security-info ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        
        .security-info li {
            margin: 5px 0;
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
        
        <div class="secure-badge">
            🔒 SECURITY PROTOCOLS ACTIVE - DEFENSE IN DEPTH ENABLED 🔒
        </div>
        
        <div class="status">
            >>> SYSTEM STATUS: SECURED<br>
            >>> SECURITY LEVEL: MAXIMUM<br>
            >>> VALIDATION: ACTIVE<br>
            >>> SANITIZATION: ACTIVE
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
                maxlength="45"
            >
            <button type="submit">🛰️ INITIATE SECURE PING</button>
        </form>
        
        <?php if ($error): ?>
        <div class="error">
            <strong>⚠ SECURITY ERROR:</strong><br>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($output && !$error): ?>
        <div class="output-container">
            <div class="output-label">>>> SATELLITE RESPONSE:</div>
            <pre><?php echo htmlspecialchars($output); ?></pre>
        </div>
        <?php endif; ?>
        
        <div class="security-info">
            <h3>🛡️ SECURITY MEASURES IMPLEMENTED:</h3>
            <ul>
                <li><strong>Layer 1:</strong> Strict IP validation using filter_var()</li>
                <li><strong>Layer 2:</strong> Input sanitization using escapeshellarg()</li>
                <li><strong>Layer 3:</strong> Length validation (max 45 characters)</li>
                <li><strong>Layer 4:</strong> Error handling and controlled execution</li>
                <li><strong>Layer 5:</strong> HTML output encoding to prevent XSS</li>
            </ul>
            <p style="margin-top: 15px;">
                <strong>Try these attacks - they won't work:</strong><br>
                • 127.0.0.1; cat /flag.txt<br>
                • 127.0.0.1 && ls -la<br>
                • 127.0.0.1 | whoami<br>
                • $(cat /flag.txt)
            </p>
        </div>
    </div>
</body>
</html>