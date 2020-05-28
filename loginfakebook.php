<?php
if (isset($_POST['Submit'])) {
    $user = "root";
    $password = "";
    $host = "localhost";
    $database_name = "Fakebook";

    $connect = mysqli_connect($host, $user, $password, $database_name);

    $PhoneOrEmail = $_POST['phoneORemail'];
    $PhoneOrEmail = str_replace("-","", $PhoneOrEmail);
    $PhoneOrEmail = str_replace("(","", $PhoneOrEmail);
    $PhoneOrEmail = str_replace(")","", $PhoneOrEmail);
    $PhoneOrEmail = str_replace(" ","", $PhoneOrEmail);

    $userPassword = $_POST['password'];

    if (empty($PhoneOrEmail) || empty($userPassword)) {
        header("Location: index.html?error=emptyfields");
        exit();
    } else {
        $query = "SELECT * FROM users WHERE PhoneOrEmail=?";
        $stmt = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt, $query)) {
            header("Location: index.html?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt,"s", $PhoneOrEmail);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($userPassword, $row['pword']);
                if ($pwdCheck == FALSE) {
                    header("Location: index.html?error=wrongpwd");
                    exit(); 
                } else if ($pwdCheck == true) {
                    session_start();
                    $_SESSION['userID'] = $row['userID'];
                    $_SESSION['first_name'] = $row['first_name'];
                } else {
                    header("Location: index.html?error=wrongpwd");
                    exit();
                }
            } else {
                header("Location: index.html?error=nouser");
                exit();
            }
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeBook</title>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="img/favicon.png" />
</head>
<body>
    <div id="navwrapper">
        <form method="POST" action="logout.php">
            <button type="submit" name="logout-submit" id="logout">Log Out</button>
        </form>
        <div id="navbar">
            <h1 class="logowrapper">fakebook</h1>
        </div>
    </div>
    <div id="contentwrapper">
        <div id="content">
            <?php 
            if (isset($_SESSION['userID'])) {
                echo "<h1 id=\"loginH1\"> Welcome, " . $_SESSION['first_name'] . "</h1>";
            }
            ?>
        </div>
    </div>
    
</body>
</html>