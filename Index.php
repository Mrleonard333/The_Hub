<?php 
    function get_all_user_data()
    {

        return 0;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE-HUB</title>
</head>

<style>
    body 
    {
        background-color: rgb(20, 20, 20);
        font-family: Arial;
        color: white;
    }

    #POST_HYPERLINK 
    {
        font-size: x-large;
        color: aquamarine;
    }
</style>


<body>
    <h1>[THE_HUB]</h1>
    <h2>Welcome to The_Hub.</h2>
    <h2>feel free to share your publications.</h2>

    <?php 
        error_reporting(0);
        if ($_COOKIE["Logged"])
        {
            echo '<a href="my_posts" id="POST_HYPERLINK">See my posts.</a> <br>';
            echo '<a href="posts" id="POST_HYPERLINK">See posts in general.</a>';

        }
    ?>

    <form method="post">
    <?php
        error_reporting(0);
        if (!$_COOKIE["Logged"])
        {
            echo "<h2>You are not logged.</h2>";
            echo '<input type="text" name="username" placeholder="Username"> ';
            echo '<input type="password" name="password" placeholder="Password">';
            echo " <input type='submit' value='Login' name='Button'>";
            echo " <input type='submit' value='Sing_In' name='Button'>";
        }
        else
        {
            echo "<h2>Account...</h2>";
            echo "<input type='submit' value='Delete my account' name='Button'> <br><br>";
            echo "<input type='submit' value='Close the account' name='Button'> <br><br>";
            $Button = $_POST["Button"];
            
            if ($Button == "Change my password")
            {
                echo '<input type="password" name="new_password" placeholder="New Password">';
                echo " <input type='submit' value='Send' name='Button'>";
            }
            else {echo "<input type='submit' value='Change my password' name='Button'>";}

            if ($Button == "Delete my account") 
            {
                $mysqli = mysqli_connect("localhost","root","Password","Schema");
                $ACCOUNT_QUERY = mysqli_query($mysqli, "SELECT * FROM Schema.users WHERE username='".$_COOKIE["Logged"]."';");
                $ACCOUNT = $ACCOUNT_QUERY->fetch_assoc();

                $DELETE = mysqli_query($mysqli, "DELETE FROM Schema.users WHERE username='".$ACCOUNT["username"]."' and password='".$ACCOUNT["password"]."';");
                mysqli_query($mysqli, "DELETE FROM Schema.posts WHERE username='".$ACCOUNT["username"]."';");
                mysqli_commit($mysqli);
                    # v Will delete the cookie
                setcookie("Logged", "", time());
                header("Refresh:0");
            }

            if ($Button == "Send")
            {
                $NewPass = $_POST["new_password"];
                if ($NewPass) 
                {
                    $mysqli = mysqli_connect("localhost","root","Password","Schema");
                    $ACCOUNT_QUERY = mysqli_query($mysqli, "SELECT * FROM Schema.users WHERE username='".$_COOKIE["Logged"]."';");
                    $ACCOUNT = $ACCOUNT_QUERY->fetch_assoc();
    
                    mysqli_query($mysqli, "UPDATE Schema.users SET password = '".$NewPass."' WHERE (id=".$ACCOUNT["id"].");");
                    mysqli_commit($mysqli);
                }
                else {echo "<h2 style='color: red;'>Insert the new password</h2>";}
            }

            if ($Button == "Close the account")
            {
                setcookie("Logged", "", time());
                header("Refresh:0");
            }
        }
    ?>
    </form>
</body>
</html>

<?php 
    error_reporting(0);
    if ($_POST["username"] && $_POST["password"])
    {
        $Username = $_POST["username"];
        $Password = $_POST["password"];
        $Button = $_POST["Button"];

        $mysqli = mysqli_connect("localhost","root","Password","Schema");
        
        if ($Button == "Login")
        {
            $Account = mysqli_query($mysqli, "SELECT * FROM Schema.users WHERE username='".$Username."' and password='".$Password."';");
            if ($Account->num_rows) {setcookie("Logged", $Username, time() + (60 * 10)); header("Refresh:0");}
            else {echo "<h2 style='color: red;'>User don't exist</h2>";}
        }

        if ($Button == "Sing_In")
        {
            $Account = mysqli_query($mysqli, "SELECT * FROM Schema.users WHERE username='".$Username."';"); 
            if (!$Account->num_rows) 
            {
                $id = 1;
                $Accounts = mysqli_query($mysqli, "SELECT id FROM Schema.users;");
                while ($Response = $Accounts->fetch_assoc()) {$id += $Response["id"];}

                mysqli_query($mysqli, "INSERT INTO Schema.users (id, username, password) VALUES ('".$id."', '".$Username."', '".$Password."');");
                setcookie("Logged", $Username, time() + (60 * 10));
                mysqli_commit($mysqli);

                header("Refresh:0");
            }
            else {echo "<h2 style='color: red;'>User already exists</h2>";}
        }
    }
    elseif ($_POST["Button"] == "Login" || $_POST["Button"] == "Sing_In")
    {
        echo "<h2 style='color: red;'>You need to fill all the fields.</h2>";
    }
?>
