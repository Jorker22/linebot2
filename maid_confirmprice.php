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
    <form id="Form" class="Form" action="maid_quot_complete.php" method="POST">
        <?php if(isset($_POST["submit"])){ 
        ?>
        <input type="hidden" name="job_id" id="job_id" value="<?php echo $cur_job_id?>"/>
        <input type="hidden" name="no_of_maids" id="no_of_maids" value="<?php echo $no_of_maids?>"/>
        <input type="hidden" name="price" id="price" value="<?php echo $price?>"/>
       
        <div>กรุณายืนยันการเสนอราคา <?php echo "Z".$cur_job_id;?></div>

       	<div>จำนวนแม่บ้าน <?php echo $no_of_maids;?> คน</div>
     	<div>ราคาที่เสนอ <?php echo $price; ?> บาท</div>
       	
	    <br><br><input name="submit" class="button green" type="submit" value="ยืนยัน"/>
	   
	    <?php } elseif(isset($_POST["cancel"])){
	    	$quot = $AzyMaid->maid_quotations($cur_job_id,"N");
	    ?>
	    ปฎิเสธ

	    <?php }?>
    </form>

</body>
</html>

    <script type="text/javascript" src="./jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="../js/locales/bootstrap-datetimepicker.th.js" charset="UTF-8"></script>