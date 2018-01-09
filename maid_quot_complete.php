<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("maid_header.php");

	if(isset($_POST["job_id"])) {
		$cur_job_id = $_POST["job_id"];
        $price = $_POST["price"];
        $no_of_maids = $_POST["no_of_maids"];
        
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
    <title>@zy Maid Confirm Price</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Titillium+Web:400,700,400italic,700italic|Open+Sans">
    <link rel="stylesheet" href="dist/check-radio.css">
    <link rel="stylesheet" href="dist/azystyle.css">
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  

</head>
<body>
    <div id="Form" class="Form" action="maid_quot_complete.php" method="POST">
        <?php if(isset($_POST["submit"])){
            $AzyMaid->confirm_price($cur_job_id,$price,$no_of_maids);
        ?>
            <div>เสนอราคาเสร็จสิ้นค่ะ</div>
            <div>งาน <?php echo 'Z'.$cur_job_id;?></div>
            <div>ราคา : <?php echo $price;?> บาท</div>
            <div>จำนวนแม่บ้านทั้งหมด : <?php echo $no_of_maids;?> คน</div>
            
        <?php } ?>

    </div>

</body>
</html>

    <script type="text/javascript" src="./jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="../js/locales/bootstrap-datetimepicker.th.js" charset="UTF-8"></script>