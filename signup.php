<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    $host = 'localhost';  
    $db = 'pf117.sql';      
    $user = 'root';       
    $pass = '';          
    
    try { 
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $nme = $_POST['name'];
        $userName = $_POST['userName'];
        $email = $_POST['email'];
        $password = $_POST['password'];

 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

 
        $stmt = $conn->prepare("INSERT INTO users (name, userName, password, email, dateMade) 
                                VALUES (:name, :userName, :password, :email, NOW())");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
 
        if ($stmt->execute()) {
            echo "Account created successfully!";
            header('Location: index.php');  
            exit();
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
    <title>Sign Up</title>
    <link rel = "stylesheet" href = "start.css">
</head>
<body>
    <nav class="top-nav">vrtx</nav>
    <div class="fulllogin-cont">
        <div class="loginimg-cont">
            <img src="/MUSIC STREAMING WEBSITE/pics/LOGIN-PIC.png" alt="listen to music" class="login-img">
        </div>
        <div class="signupform-cont">
            <form action="signup.php" method="POST">
                <center>
                    <h2 class="loginh2">SIGN UP FOR</h2><h1 class="loginh1"> vrtx</h1><br><br>
                    <label class = "signup-label" >Name</label>
                    <input type = "text" name = "name" placeholder = "Name" required><br>
                    
                    <label class = "signup-label" >Username</label>
                    <input type = "text" name = "userName" placeholder = "Username" required><br>

                    <label class = "signup-label" >Email</label>
                    <input type = "email" name = "email" placeholder = "Email" required><br>

                    <label class = "signup-label" >Password</label> 
                    <input type = "password" name = "password" placeholder = "Password" required><br>

                    <button type = "submit" class = "login-button">SIGN UP</button>
                    <p>Already registered?</p>
                    <a href="index.php"><button type = "button" class = "text-button">Login</button></a>
                </center>
            </form>
        </div>
    </div>
</body>
</html>
