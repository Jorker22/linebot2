<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("maid_header.php");
	$AzyMaid = new AzyMaid($userId,$displayName);
	$AzyBot = new AzyBot($userId);

	if(isset($_POST["job_id"])) {
		$cur_job_id = $_POST["job_id"];
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
    <title>@zy Maid Offer Price</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Titillium+Web:400,700,400italic,700italic|Open+Sans">
    <link rel="stylesheet" href="dist/check-radio.css">
    <link rel="stylesheet" href="dist/azystyle.css">
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  

</head>
<body>
    <form id="Form" class="Form" action="aaaa.php" method="POST">
        <?php if(isset($_POST["submit"])){ ?>
        <div>
        	<select id="no_of_maids">
        		<option value="1">1</option>
        		<option value="2">2</option>
        		<option value="3">3</option>
        		<option value="4">4</option>
        		<option value="5">5</option>
        	</select>
        </div>

       	<div>
     		<span>ราคารวมแม่บ้าน <span id="no"></span> คน</span>
     	</div>
     	<div>
       		<input name="price"/>
       	</div>
       	<div style="color:red;font-size:1em;" id="warning"></div>
	    <br><br><input name="submit" class="button green" type="submit" value="เสนอราคา" onclick="return validateForm()"/>
	    <br><br><input name="cancel" type="submit" class="button red" value="ปฏิเสธ" onclick="$('#Form').submit() "/>
	   
	    <?php } elseif(isset($_POST["cancel"])){
	    	$quot = $AzyBot->maid_quotations($cur_job_id,"N");
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
    <script>
    	$(document).ready(function() {
            
            $("#no").text($("#no_of_maids").val());
            
        });
        $("#no_of_maids").on('change', function() {
            $("#no").text($("#no_of_maids").val());
        })

        function validateForm() {
            var x = document.forms["Form"]["price"].value;

            if(x == ""){
               	var div = document.getElementById('warning');
                div.innerHTML = '*กรุณาใส่คำตอบของคุณค่ะ';
                return false;
               
            }else if(isNaN(x)){
                var div = document.getElementById('warning');
                div.innerHTML = '*กรุณาใส่เฉพาะตัวเลขค่ะ';
                return false;
            }else{
                
                $("#Form").submit();
            }
        }
       
    </script>
