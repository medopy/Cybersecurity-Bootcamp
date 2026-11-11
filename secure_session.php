<?php
$secure_params = [
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true, 
    'samesite' => 'Strict' 
];

session_set_cookie_params($secure_params);
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    
    if ($username === 'admin') {
        session_regenerate_id(true);
        
        $_SESSION['user'] = $username;
        $_SESSION['logged_in'] = true;
        $message = "Login successful! Secure session established and ID regenerated.";
    } else {
        $message = "Invalid username. Try 'admin'.";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: secure_session.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Session and Cookie Management</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { padding: 8px; margin: 5px 0; width: 100%; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        pre { background: #eee; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Assignment 13: Secure Sessions & Cookies</h2>
    
    <?php if (!empty($message)): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h3>
        <p>Your session is protected with <strong>HttpOnly</strong> and <strong>SameSite=Strict</strong>.</p>
        <p>Current Session ID: <code><?php echo session_id(); ?></code></p>
        <a href="secure_session.php?action=logout"><button style="background: #dc3545;">Logout</button></a>
    <?php else: ?>
        <form method="POST">
            <label>Username (Try: admin):</label>
            <input type="text" name="username" required placeholder="Enter username">
            <button type="submit">Login</button>
        </form>
    <?php endif; ?>

    <hr>
    <h4>XSS Cookie Theft Simulation Test:</h4>
    <p>Run this script in your browser console to test if JavaScript can read the session cookie:</p>
    <pre><code>console.log("Document Cookies:", document.cookie);</code></pre>
    <p><em>Expected result:</em> It should return an empty string ("") because <strong>HttpOnly</strong> blocks JavaScript access completely!</p>
</div>
</body>
</html>
