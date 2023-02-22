<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER POSTS</title>
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
    <h1>[MY POSTS]</h1>

    <?php 
        error_reporting(0);
        echo '<a href="Index" id="POST_HYPERLINK">Come back.</a> <br>';
        echo '<a href="posts" id="POST_HYPERLINK">See posts in general.</a>';
        error_reporting(E_ALL);
    ?>

    <form method="post">
        <h3>Turn to Private or Public.</h3>
        <input type="number" name="Private_Id" placeholder="Post_Id">
        <input type='submit' value='Send' name='Button'>

        <h3>Filter the posts.</h3>
        <input type="text" name="Post_Title" placeholder="TITLE">
        <input type='submit' value='Filter' name='Button'>

        <h3>Delete a post.</h3>
        <input type="number" name="Delete_Id" placeholder="Post_Id">
        <input type='submit' value='Delete' name='Button'>

        <h3>Create your own post.</h3>
        <input type="text" name="Title" placeholder="TITLE">
        <br>
        <input type="text" name="Address" placeholder="IMAGE ADDRESS">
        <br>
        <input type="text" name="Description" placeholder="DESCRIPTION">
        <input type='submit' value='Create' name='Button'>
    </form>

    <?php
        error_reporting(0); 
        if ($_COOKIE["Logged"])
        {
            $User = $_COOKIE["Logged"];
            $Button = $_POST["Button"];
            $mysqli = mysqli_connect("localhost","root","Password","Schema");
            $Posts = mysqli_query($mysqli, "SELECT * FROM Schema.posts WHERE username='".$User."';");

            if ($Button == "Create")
            {
                $Description = $_POST["Description"];
                $Image = $_POST["Address"];
                $Title = $_POST["Title"];

                if ($Title && $Image && $Description)
                {
                    $id = 0;
                    $Ids = mysqli_query($mysqli, "SELECT post_id FROM Schema.posts;");
                    while ($All = $Ids->fetch_assoc()) {$id = $All["post_id"];}
                    $id++;

                    mysqli_query($mysqli, "INSERT INTO Schema.posts (post_id, username, title, image_link, description, private) VALUES ('".$id."', '".$User."', '".$Title."', '".$Image."', '".$Description."', 'no');");
                    mysqli_commit($mysqli);
                    header("Refresh:0");
                }
                else {echo "<h2 style='color: red;'>Fill all the fields</h2>";}
            }

            if ($Posts->num_rows)
            {
                echo '<h2>Your Posts...<h2>';
                while ($Response = $Posts->fetch_assoc())
                {
                    $Description = $Response["description"];
                    $Image = $Response["image_link"];
                    $Private = $Response["private"];
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

                    if (str_contains($Title, $_POST["Post_Title"]) || !$_POST["Post_Title"]) 
                    {
                        echo "<h2>[".$Title."] [".$Id."]<h2>";
                        echo '<div style="display: inline_block"> <img alt="'.$Title.'_Image" height="150" width="150"src="'.$Image.'"> </div>';
                        echo "<h3>".$Description."<h3>";
                        echo "<h3 style='color: aquamarine;'>Private: ".$Private."<h3>";
                    }
                }
            }
            else {echo "<h2 style='color: aquamarine;'>No posts here...</h2>";}
        }
    ?>
</body>
</html>