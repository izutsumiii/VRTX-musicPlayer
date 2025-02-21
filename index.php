<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: homepage.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost'; 
    $db = 'pf117.sql';  
    $user = 'root';  
    $pass = '';  
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE userName = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $user['id'];
            $hashed_password = $user['password'];

            if (password_verify($password, $hashed_password)) { 
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;

                header('Location: homepage.php');
                exit();
            } else {
                $error_message = "Invalid username or password!";
            }
        } else {
            $error_message = "Invalid username or password!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel = "stylesheet" href = "start.css">
</head>
<body>
    <nav class="top-nav">vrtx</nav>
    <div class="fulllogin-cont">
        <div class="loginimg-cont">
            <img src="/MUSIC STREAMING WEBSITE/pics/LOGIN-PIC.png" alt="listen to music" class="login-img">
        </div>
        <div class="loginform-cont">
            <form action="index.php" method="POST">
                    <h2 class="loginh2">LOGIN TO</h2><h1 class="loginh1"> vrtx</h1><br><br>
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Username" required><br>

                    <label>Password</label>
                    <input type = "password" name = "password" placeholder="Password" required><br>

                    <?php if (isset($error_message)) { echo '<p style="color:red;">' . $error_message . '</p>'; } ?>
                    <button type="submit" class="login-button">LOGIN</button>

                    <p>Not yet registered?</p>
                    <a href="signup.php"><button type="button" class="text-button">Sign Up</button></a>
            </form>
        </div>
    </div>
</body>
</html>
