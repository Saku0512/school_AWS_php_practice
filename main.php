<?php
// This product include vulnerability.(Magic Hash)
// username: hoge
// password: TyNOQHUS
// sha256:0e66298694359207596086558843543959518835691168370379069085300385

$env = parse_ini_file(__DIR__ . './.env');

$dbHost = $env['DB_HOST'];
$dbUser = $env['DB_User'];
$dbPass = $env['DB_PASSWORD'];
$dbName = $env['DB_NAME'];

$link = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

if (!$link) {
    die('DB接続失敗: ' . mysqli_connect_error());
}

$username = $_POST['userhname'] ?? '';
$password = $_POST['password'] ?? '';

$sql = 'SELECT * FROM user WHERE username = ?';
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($stmt);

if (!$user) {
    echo 'ログイン失敗';
    exit;
}

// magic hash
$input_hash = hash('sha256', $password);

// DBのハッシュ値
$db_hash = $user['password_hash'];

if ($input_hash == $db_hash) {
    echo 'ログイン成功';
} else {
    echo 'ログイン失敗';
}

mysqli_stmt_close($stmt);
mysqli_close($link);

?>

<!DOCUTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Magic Hash Login</title>
    </head>
    <body>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username">

            <label>Password</label>
            <input type="password" name="password">

            <button type="submit">Login</button>
        </form>
    </body>
<html>
