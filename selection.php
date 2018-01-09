<?php
    date_default_timezone_set("Asia/Bangkok");
    require_once("header.php");
    
    if(isset($_GET["jid"])){
        $cur_job_id = $_GET["jid"];
        $job_ids = $AzyCustomer->get_jobs_maid_selection();
    } 
    else {
        $job_ids = $AzyCustomer->get_jobs_maid_selection();
        $cur_job_id = array_keys($job_ids)[0];
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

    <form class="Form" action="selection_confirm.php" method="POST">
        <div style="text-align: center;width:90%;">
            <?php if($job_ids) {

            ?>
                <select id="select" name="select">
                <?php 
                foreach($job_ids as $job) {
                    if($cur_job_id == $job["job_id"]) {
                        $selected = "selected";
                    } else{
                        $selected = "";
                    }
                ?>
                    <option value="<?php echo $job["job_id"];?>" <?php echo $selected;?>>Z<?php echo $job["job_id"];?></option>";
                <?php 
                }
                ?>
                </select>
        </div>
        <input type="hidden" name="job_id" id="job_id" value="<?php echo $cur_job_id?>"/>
        <div id="summary">
            
            <?php 
            if($job_ids[$cur_job_id]["html_style"]) {
                if($job_ids[$cur_job_id]["quotations"]) {
                    foreach($job_ids[$cur_job_id]["quotations"] as $quot) {
            
            ?>
                        <div class="choice">
                            <label class="Form-label--tick">
                                <input type="radio" value="<?php echo $quot['maid_id']?>" name="answer" id="<?php echo $quot['maid_id'];?>" class="Form-label-radio">
                                <span class="Form-label-text"><?php echo "maid ".$quot["maid_id"]?></span>
                            </label>
                        </div>
            <?php
                    }
                }

            }
            else {
                echo "ยังไม่มีแม่บ้านเสนอราคาค่ะ";
            }?>

        </div>
       <button href="" id="footer" type="submit">เลือกแม่บ้าน</button>
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
        
        $("#select").on('change', function() {
            var jobs = <?php echo json_encode($job_ids);?>;
            var quot = jobs[$("#select").val()]["html_style"];
            if(quot != "") {
                $("#summary").html(quot);
            } 
            else {
                $("#summary").html("ยังไม่มีแม่บ้านเสนอราคาค่ะ");
                
            }
            $("#job_id").val($("#select").val());
        })
       
    </script>
  