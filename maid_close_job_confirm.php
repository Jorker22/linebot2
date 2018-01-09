<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("maid_header.php");
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
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

</head>
<body>

    <form class="Form" action="maid_close_job_complete.php" method="POST">
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
  