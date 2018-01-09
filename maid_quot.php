<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("maid_header.php");
    
    if(isset($_GET["jid"])){
        $cur_job_id = $_GET["jid"];
        $quot = $AzyMaid->maid_quotations($cur_job_id,"");
        $job_ids = $AzyMaid->get_maid_jobs();
    } else {
        $job_ids = $AzyMaid->get_maid_jobs();
        $cur_job_id = $job_ids[0]["job_id"];
    }

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@zy Maid Quotations</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Titillium+Web:400,700,400italic,700italic|Open+Sans">
    <link rel="stylesheet" href="dist/check-radio.css">
    <link rel="stylesheet" href="dist/azystyle.css">
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

</head>
<body>

    <form class="Form" action="maid_offerprice.php" method="POST">
        <div style="text-align: center;width:90%;">
            <?php if($job_ids) {
                $summary = $AzyMaid->get_sum_text();
            ?>
                <select id="select" name="select">
                <?php 
                foreach($job_ids as $job_id) {
                    if($cur_job_id == $job_id["job_id"]) {
                        $selected = "selected";
                    } else{
                        $selected = "";
                    }
                ?>
                    <option value="<?php echo $job_id["job_id"];?>" <?php echo $selected;?>>Z<?php echo $job_id["job_id"];?></option>";
                <?php 
                }
                ?>
                </select>
        </div>
        <input type="hidden" name="job_id" id="job_id" value="<?php echo $cur_job_id?>"/>
        <div id="summary"></div>
        <input name="submit" class="button green" type="submit" value="เสนอราคา"/>
        <br><br><input name="cancel" type="submit" class="button red" value="ปฏิเสธ"/>
        <?php } 
        else {
                echo "คุณได้ทำรายการหมดแล้วค่ะ";
            }
        ?>
    </form>

</body>
</html>

    <script type="text/javascript" src="./jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="../js/locales/bootstrap-datetimepicker.th.js" charset="UTF-8"></script>
    <script>
        var summary = <?php echo json_encode($summary);?>;
        $(document).ready(function() {
            $("#summary").html(summary[$("#select").val()]);
            $("#job_id").val($("#select").val());
        });
        $("#select").on('change', function() {
            $("#summary").html(summary[$("#select").val()]);
            $("#job_id").val($("#select").val());
        })
       
    </script>
  