<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("header.php");
    
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@zy Maid Close Job</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Titillium+Web:400,700,400italic,700italic|Open+Sans">
    <link rel="stylesheet" href="dist/check-radio.css">
    <link rel="stylesheet" href="dist/azystyle.css">


</head>
<body>

    <form class="Form" action="rating.php" method="POST">
        <div style="text-align: center;width:90%;">
            <?php if(isset($_POST["submit"]) && isset($_POST["job_id"])) {
                $cur_job_id = $_POST["job_id"];
                echo "detail..." . $cur_job_id;
            ?>
                
        </div>
        <input type="hidden" name="job_id" id="job_id" value="<?php echo $cur_job_id;?>"/>
        <div id="summary"></div>
        <input name="submit" class="button green" type="submit" value="ยืนยัน"/>
        <?php } 
        ?>
    </form>

</body>
</html>
  