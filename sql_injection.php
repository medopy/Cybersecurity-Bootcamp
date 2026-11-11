<?php
try {
    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, password TEXT);");
    $pdo->exec("INSERT INTO users (username, password) VALUES ('admin', 'secret123');");
    $pdo->exec("INSERT INTO users (username, password) VALUES ('ali', 'mypassword');");

} catch (PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}

$searchResult = "";
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'safe';
$usernameInput = isset($_GET['username']) ? $_GET['username'] : '';

if (!empty($usernameInput)) {
    if ($mode === 'unsafe') {
        $sql = "SELECT * FROM users WHERE username = '$usernameInput'";
        try {
            $stmt = $pdo->query($sql);
            $searchResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $searchResult = "خطأ في الاستعلام: " . $e->getMessage();
        }

    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usernameInput]);
        $searchResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>أداة الحماية من ثغرات SQL Injection</title>
    <style>
        body { font-family: Tahoma, sans-serif; background: #f4f4f9; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { padding: 8px; margin: 5px 0; width: 100%; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        pre { background: #eee; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h2>التكليف الحادي عشر: فحص الحماية من SQLi</h2>
    <form method="GET">
        <label>اسم المستخدم للبحث:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($usernameInput); ?>" placeholder="مثال: admin أو ' OR '1'='1">
        
        <label>اختر وضع التشغيل:</label>
        <select name="mode">
            <option value="safe" <?php if($mode=='safe') echo 'selected'; ?>>الوضع الآمن (Prepared Statements)</option>
            <option value="unsafe" <?php if($mode=='unsafe') echo 'selected'; ?>>الوضع غير الآمن (Direct Injection)</option>
        </select>
        
        <button type="submit">بحث واستعلام</button>
    </form>

    <?php if (!empty($usernameInput)): ?>
        <h3>نتيجة البحث:</h3>
        <pre><?php 
            if (is_array($searchResult)) {
                if (count($searchResult) > 0) {
                    print_r($searchResult);
                } else {
                    echo "لم يتم العثور على مستخدم مطابق.";
                }
            } else {
                echo $searchResult;
            }
        ?></pre>
    <?php endif; ?>
</div>
</body>
</html>
