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

    <form class="Form" action="close_job_complete.php" method="POST">
        <div style="text-align: center;width:90%;">
            <?php if(isset($_POST["submit"]) && isset($_POST["job_id"])) {
                $cur_job_id = $_POST["job_id"];
                $AzyCustomer->close_job($cur_job_id);

                echo "ปิดงานเรียบร้อย กรุณาให้คะแนน..." . $cur_job_id;
            ?>
            <div class="choice">
                <label class="Form-label--tick">
                    <input type="radio" value="1" name="rating" id="rate1" class="Form-label-radio">
                    <span class="Form-label-text">1</span>
                </label><br>
                <label class="Form-label--tick">
                    <input type="radio" value="2" name="rating" id="rate2" class="Form-label-radio">
                    <span class="Form-label-text">2</span>
                </label><br>
                <label class="Form-label--tick">
                    <input type="radio" value="3" name="rating" id="rate3" class="Form-label-radio">
                    <span class="Form-label-text">3</span>
                </label><br>
                <label class="Form-label--tick">
                    <input type="radio" value="4" name="rating" id="rate4" class="Form-label-radio">
                    <span class="Form-label-text">4</span>
                </label><br>
                <label class="Form-label--tick">
                    <input type="radio" value="5" name="rating" id="rate5" class="Form-label-radio">
                    <span class="Form-label-text">5</span>
                </label>
            </div>
        </div>
        <input type="hidden" name="job_id" id="job_id" value="<?php echo $cur_job_id;?>"/>
    
        <input name="submit" class="button green" type="submit" value="ส่งข้อมูล"/>
        <?php } ?>
    </form>

</body>
</html>
  