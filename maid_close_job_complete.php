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

    <div class="Form">
       
            <?php if(isset($_POST["job_id"])) {

                $cur_job_id = $_POST["job_id"];

                if(isset($_POST["submit"])) {
                    $AzyMaid->maid_close_job($cur_job_id);
                    echo "แจ้งปิดงานเสร็จสิ้นค่ะ กรุณารอทางลูกค้ายืนยันการปิดงานนะคะ " . $cur_job_id;
                }
                
            }
            ?>

    </div>

</body>
</html>
  