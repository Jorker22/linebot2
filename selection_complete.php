<?php
    date_default_timezone_set("Asia/Bangkok");
    require_once("header.php");

    if(isset($_POST["job_id"])) {
        $cur_job_id = $_POST["job_id"];
        $maid_id = $_POST["maid_id"];
    } 
    else {
        $cur_job_id = 0;
    }
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@zy Service Selection</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Titillium+Web:400,700,400italic,700italic|Open+Sans">
    <link rel="stylesheet" href="dist/check-radio.css">
    <link rel="stylesheet" href="dist/azystyle.css">
</head>
<body>

    <div id="Form" class="Form" action="maid_quot_complete.php" method="POST">
        <?php if(isset($_POST["submit"])){
            $AzyCustomer->confirm_selection($cur_job_id,$maid_id);

        ?>
            <div>เลือกแม่บ้านเสร็จสินค่ะ</div>
            <div>งาน <?php echo 'Z'.$cur_job_id;?></div>
            <div>แม่บ้าน : <?php echo $maid_id;?></div>
            
        <?php } ?>

    </div>

</body>
</html>
  