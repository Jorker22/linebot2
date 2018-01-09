<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("maid_header.php");
    $jobs = $AzyMaid->get_close_jobs();
    $cur_job_id = $jobs[0]["job_id"];
    
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

    <form class="Form" action="maid_close_job_confirm.php" method="POST">
        <div style="text-align: center;width:90%;">
            <?php if($jobs) {
               
            ?>
                <select id="select" name="select">
                <?php 
                foreach($jobs as $job) {
                ?>
                    <option value="<?php echo $job["job_id"];?>">Z<?php echo $job["job_id"];?></option>";
                <?php 
                }
                ?>
                </select>
        </div>
        <input type="hidden" name="job_id" id="job_id" value="<?php echo $cur_job_id?>"/>
        <div id="summary"></div>
        <input name="submit" class="button green" type="submit" value="ปิดงาน"/>
        <?php } 
        else {
                echo "คุณได้ทำรายการหมดแล้วค่ะ";
            }
        ?>
    </form>

</body>
</html>

    <script type="text/javascript" src="./jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script>
        
        $(document).ready(function() {
            
            $("#job_id").val($("#select").val());
        });
        $("#select").on('change', function() {
            
            $("#job_id").val($("#select").val());
        })
       
    </script>
  