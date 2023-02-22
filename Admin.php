<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
</head>

<style>
    body 
    {
        background-color: rgb(20, 20, 20);
        font-family: Arial;
        color: white;
    }
</style>

<body>
    <h1>[ADMIN_PLACE]</h1>
    
    <form method="post">
        <h3>Filter the posts.</h3>
        <input type="text" name="Post_Title" placeholder="TITLE">
        <input type='submit' value='Filter' name='Button'>

        <h3>Turn to Private or Public.</h3>
        <input type="number" name="Private_Id" placeholder="Post_Id">
        <input type='submit' value='Send' name='Button'>

        <h3>Delete a post.</h3>
        <input type="number" name="Delete_Id" placeholder="Post_Id">
        <input type='submit' value='Delete' name='Button'>

        <h3>Censure a post image.</h3>
        <input type="number" name="Post_Id" placeholder="Post_Id">
        <input type='submit' value='Censor' name='Button'>
    </form>

    <?php
        error_reporting(0); # < Will hide the errors warnings
        $Button = $_POST["Button"];
        $mysqli = mysqli_connect("localhost","root","Password","Schema"); # < Will connect with the database
        $Posts = mysqli_query($mysqli, "SELECT * FROM Schema.posts"); # < Will execute a MySQL command

        if ($Posts->num_rows)
        {
            echo "<h2>User's posts<h2>"; # v Will get an array with the DataBase data
            while ($Response = $Posts->fetch_assoc())
            {
                $Description = $Response["description"];
                $Username = $Response["username"];
                $Image = $Response["image_link"];
                $Private = $Response["private"];
                $Filter_Title = $_POST["Title"]; # < Will get the form data
                $Title = $Response["title"];
                $Id = $Response["post_id"];

                if ($Button == "Send" && $Id == $_POST["Private_Id"])
                {
                    if ($Private == "yes") {$Private = "no";} else {$Private = "yes";}
                    mysqli_query($mysqli, "UPDATE Schema.posts SET private = '".$Private."' WHERE (post_id = ".$Id.");");
                    mysqli_commit($mysqli);
                }
                if ($Button == "Delete" && $Id == $_POST["Delete_Id"])
                {
                    mysqli_query($mysqli, "DELETE FROM Schema.posts WHERE (post_id = ".$Id.");");
                    mysqli_commit($mysqli);
                    continue;
                }
                if ($Button == "Censor" && $Id == $_POST["Post_Id"])
                {
                    $Image = "https://img.freepik.com/premium-vector/pixel-censored-sign-black-censor-bar-concept-icon-isolated-white-background_705714-561.jpg?w=2000";
                    mysqli_query($mysqli, "UPDATE Schema.posts SET image_link = '".$Image."' WHERE (post_id = ".$Id.");");
                    mysqli_commit($mysqli);
                }

                if (str_contains($Title, $_POST["Post_Title"]) || !$_POST["Post_Title"]) 
                {
                    echo "<h2>[".$Username."]-[".$Title."] [".$Id."]<h2>";
                    echo '<div style="display: inline_block"> <img alt="'.$Title.'_Image" height="150" width="150"src="'.$Image.'"> </div>';
                    echo "<h3>".$Description."<h3>";
                    echo "<h3 style='color: aquamarine;'>Private: ".$Private."<h3>";
                }
            }
        }
        else {echo "<h2 style='color: aquamarine;'>No posts here...</h2>";}
    ?>
</body>
</html>