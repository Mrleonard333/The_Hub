<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POSTS</title>
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
    <h1>[POSTS]</h1>

    <?php 
        error_reporting(0);
        echo '<a href="Index" id="POST_HYPERLINK">Come back.</a> <br>';
        if ($_COOKIE["Logged"]) {echo '<a href="my_posts" id="POST_HYPERLINK">See my posts.</a>';}
    ?>

    <form method="post">
        <h3>Filter the posts.</h3>
        <input type="text" name="Post_Title" placeholder="TITLE">
        <input type='submit' value='Filter' name='Button'>
    </form>

    <?php 
        error_reporting(0);
        if ($_COOKIE["Logged"])
        {            
            $mysqli = mysqli_connect("localhost","root","Password","Schema");
            $Posts = mysqli_query($mysqli, "SELECT * FROM Schema.posts WHERE not username='".$_COOKIE["Logged"]."';");

            if ($Posts->num_rows)
            {
                echo '<h2>General Posts...<h2>';
                while ($Response = $Posts->fetch_assoc())
                {
                    $Description = $Response["description"];
                    $Username = $Response["username"];
                    $Image = $Response["image_link"];
                    $Private = $Response["private"];
                    $Title = $Response["title"];

                    if (str_contains($Title, $_POST["Post_Title"]) || !$_POST["Post_Title"]) 
                    {
                        if ($Private == "no")
                        {
                            echo "<h2>[".$Username."]-[".$Title."]<h2>";
                            echo '<div style="display: inline_block"> <img alt="'.$Title.'_Image" height="150" width="150"src="'.$Image.'"> </div>';
                            echo "<h3>".$Description."<h3>";
                        }
                    }
                }
            }
            else
            {
                echo "<h2 style='color: aquamarine;'>No posts here...</h2>";
            }
        }
    ?>

</body>
</html>

