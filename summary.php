<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("header.php");
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@zy Service Form</title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Titillium+Web:400,700,400italic,700italic|Open+Sans">
    <link rel="stylesheet" href="dist/check-radio.css">
    <link rel="stylesheet" href="dist/azystyle.css">
</head>
<body>
	
		<?php if(isset($_GET['jid'])) {
			//show summary for each jid
			$job_id = $_GET['jid'];
			if($job_id == "0") {
				echo 'no data';
			}
		?>
			<img width="90%" src="forms/<?php echo $job_id.'.jpg';?>">
		<?php
		}
		else {
		?>
			<form id="Form" class="Form" action="formaction.php" method="post">
				<input type="hidden" id="summary" name="summary" value="1"/>
				<?php
					$summary = $AzyCustomer->get_summary();
					if($summary)
					{
						foreach($summary as $sum)
						{
							echo "<b>".$sum["title"]. "</b> : ". $sum["answer"]."<br>";
						}
					}
					
				?>
				<button href="" id="footer" type="submit">ส่งข้อมูล</button>
			</form>
		<?php	
		}
		?>
	
</body>
</html>
