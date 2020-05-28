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
        <div id="navbar">
            <h1 class="logowrapper">fakebook</h1>
        </div>
    </div>
    <div id="contentwrapper">
        <div id="content">
<?php
if (isset($_POST['register-submit'])) {

    $user = "root";
    $password = "";
    $host = "localhost";
    $connect = mysqli_connect($host, $user, $password);

    if ($connect === false) { 
        echo "<p class=\"registerP\">Unable to connect to the database server.</p>";
        echo "<p class=\"registerP\">Error code " . mysqli_errno() . ": " . mysqli_error() . "</p>";
    } else { 
        $database_name = "Fakebook";
        
        if (!mysqli_select_db($connect, $database_name)) { 
            $query = "CREATE DATABASE $database_name";
            $query_result = mysqli_query($connect, $query);
            
            if ($query_result === false) { 
                echo "<p class=\"registerP\">Unable to execute the query.</p>";
                echo "<p class=\"registerP\">Error code " . mysqli_errno($connect) . ": " . mysqli_error($connect) . "</p>";
            }
        }
        
        mysqli_select_db($connect, $database_name);

        $TableName = "users";
        $query = "SHOW TABLES LIKE '$TableName'";
        $query_result = mysqli_query($connect, $query);
        
        if (mysqli_num_rows($query_result) == 0) { 
            $query = "CREATE TABLE $TableName (userID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY, last_name VARCHAR(40), first_name VARCHAR(40), gender VARCHAR(40), birthday VARCHAR(10), PhoneOrEmail VARCHAR(40), pword VARCHAR(255))";
            $query_result = mysqli_query($connect, $query);
            
            if ($query_result === false) {
                echo "<p class=\"registerP\">Unable to create the table.</p>";
                echo "<p class=\"registerP\">Error code " . mysqli_errno($connect) . ": " . mysqli_error($connect) . "</p>";
            }
        }

        $last_name = stripslashes($_POST['last_name']);
        $first_name = stripslashes($_POST['first_name']);
        $gender = stripslashes($_POST['gender']);
        $birthday = stripslashes($_POST['birth']);
        $PhoneOREmail = stripslashes($_POST['phoneORemail']);
        $userpassword = stripslashes($_POST['password']);

        $PhoneOREmail = str_replace("-","", $PhoneOREmail);
        $PhoneOREmail = str_replace("(","", $PhoneOREmail);
        $PhoneOREmail = str_replace(")","", $PhoneOREmail);
        $PhoneOREmail = str_replace(" ","", $PhoneOREmail);

        $hashed_password = password_hash($userpassword, PASSWORD_DEFAULT);

        $last_name = strtoupper($last_name);
        $first_name = strtoupper($first_name);

        $query = "SELECT PhoneOrEmail FROM users WHERE PhoneOrEmail=?";
        $stmt = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($stmt, $query)) {
            header("Location: index.html?error=qlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $PhoneOREmail);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                echo "<h1 id=\"registerH1\">The Email or Phone you putted is already registered!</h1>";
                echo "<form action=\"index.html\"><input type=\"submit\" value=\"Go back to Log In\" id=\"registerbutton\" /></form>";
                exit();
            } else {
                $query = "INSERT INTO $TableName VALUES(NULL, '$last_name', '$first_name', '$gender', '$birthday', '$PhoneOREmail', '$hashed_password')";
                $query_result = mysqli_query($connect, $query);
        
                if ($query_result === false) {
                    echo "<p class=\"registerP\">Unable to execute the query.</p>";
                    echo "<p class=\"registerP\">Error code " . mysqli_errno($connect) . ": " . mysqli_error($connect) . "</p>";
                } else {
                    echo "<h1 id=\"registerH1\">Thank you for being part of Fakebook!</h1>";
                    echo "<form action=\"index.html\"><input type=\"submit\" value=\"Go back to Log In\" id=\"registerbutton\" /></form>";
                }
            }
        }
        mysqli_close($connect);
    }
} else {
    header("Location: ../index.html");
    exit();
}
?>

        </div>
    </div>
    
</body>
</html>