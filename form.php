<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("header.php");

	if(isset($_GET["delete"]))
	{
		$AzyCustomer->delete_temp();
		$q = "1";
		$q_info = $AzyCustomer->get_questions($q);
	}
	elseif(isset($_GET["q"]))
	{
		$q = $_GET["q"];
		$q_info = $AzyCustomer->get_questions($q);

		if(!$AzyCustomer->tempExist && $q != '1')
		{
	        header('location: form.php?q=1');
	    }
	}
	else
	{
		header('location: welcome.php');
	}

	$cur_date = new DateTime(date('Y-m-d H:i:s'));
    $cur_date->modify('+1 day');
    $min_date = $cur_date->format('Y-m-d').' '.$cur_date->format('H:i');
    $cur_answer = $AzyCustomer->get_answer($q);
    $q_cnt = $AzyCustomer->get_questions_cnt("cust_maid_service");
    $star = "";
    if($q_info["required"] == "req"){
    	$star = "&#9733;";
    }
    $min = "";
    $max = "";

    if(!empty($q_info['limit_char'])){
        $minmax = explode(",",$q_info['limit_char']);
        $min = $minmax[0];
        $max = $minmax[1];
    }
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
    <!--<script src="https://code.jquery.com/jquery-1.10.2.js"></script>-->
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

</head>
<body>
    <form id="Form" class="Form" action="formaction.php" method="post" onsubmit="return validateForm()">
        <input type="hidden" id="customerId" name="customerId" value="<?php echo $AzyCustomer->customerId;?>"/>
        <input type="hidden" id="userId" name="userId" value="<?php echo $userId;?>"/>
        <input type="hidden" id="displayName" name="displayName" value="<?php echo $displayName;?>"/>
        <input type="hidden" id="q_cnt" name="q_cnt" value="<?php echo $q_cnt;?>"/>
        <input type="hidden" id="q" name="q" value="<?php echo $q_info['q_number']?>"/>
        <input type="hidden" id="required" name="required" value="<?php echo $q_info['required'];?>"/>
        <input type="hidden" id="text_type" name="text_type" value="<?php echo $q_info['text_type'];?>"/>
        <input type="hidden" id="min" name="min" value="<?php echo $min;?>"/>
        <input type="hidden" id="max" name="max" value="<?php echo $max;?>"/>

        <div class="Form-section">
            <div class="Question-number">
                <b><?php echo 'Q'.$q_info["q_number"];?></b><span style="font-size: 80%;color:red"><?php echo $star;?></span>
            </div>
            <span class="Form-title">
            	<label><b><?php echo $q_info["question"];?></b></label>
            </span>

			<?php
			//1. choice
			if($q_info['type'] == 'choice')
			{
				for($x = 1; $x <= $q_info['choice_cnt']; $x++)
				{
                    $value_key = 'value'.$x;
                    $choice_key = 'choice'.$x;

                    if($cur_answer == $q_info[$value_key])
                    {
                        $check = "checked";
                    }
                    else
                    {
                        $check = "";
                    }
            ?>
		            <div class="choice">
		            	<label class="Form-label--tick">
		                    <input type="radio" value="<?php echo $q_info[$value_key]?>" name="answer" id="<?php echo $choice_key;?>" class="Form-label-radio" <?php echo $check;?> >
		                    <span class="Form-label-text"><?php echo $q_info[$choice_key]?></span>
		                </label>
		            </div>
            <?php 
        		} 
        	?>
            <?php 
        	}
        	//2. text
            elseif($q_info['type'] == 'text')
            {
            ?>
                <div class="text">
                    <textarea rows="5" name="answer" class="advancedSearchTextBox focus"><?php echo $cur_answer;?></textarea>
                </div>
            <?php
        	}
        	//3. shorttext
        	elseif($q_info['type'] == 'shorttext')
        	{
        	?>
                <input class="inputtext focus" type="text" id="answer" name="answer" value="<?php echo $cur_answer;?>"/>
            <?php 
        	}
        	//4. datetime
        	elseif($q_info['type'] == 'datetime')
        	{
                if($cur_answer > $min_date)
                {
                    $default_date = $cur_answer;
                }
                else
                {
                    $default_date = $min_date;
                }
            ?>
                <input type="text" id="answer" name="answer" value="<?php echo $default_date?>" readonly class="form_datetime inputtext focus">
            <?php 
        	}
        	//5. file
        	elseif($q_info['type'] == 'file')
        	{
        	?>
                <input type="hidden" id="answer" name="answer"/>
           	<?php 
            }
            //6. yes_no_continue
            elseif($q_info['type'] == 'yesno_cont')
            {
                $yes_checked = "";
                $no_checked = "";
                $yes_and_cont = "";

                if(!is_null($cur_answer))
                {
                    if($cur_answer != 'N')
                    {
                        $yes_checked = "checked";
                        $yes_and_cont = $cur_answer;
                    }
                    else
                    {
                        $no_checked = "checked";
                    } 
                }
            ?>
                <div class="choice">
                    <label class="Form-label--tick">
                        <input type="radio" value="yes" name="radioyesno" id="yes" class="Form-label-radio" <?php echo $yes_checked;?>>
                        <span class="Form-label-text"><?php echo $q_info['choice1']?></span>
                    </label>
                    <br>
                    <textarea disabled rows="3" id="answer" name="answer" class="advancedSearchTextBox" placeholder="<?php echo $q_info['value1'];?>"><?php echo $yes_and_cont;?></textarea>
                    <br>
                    <label class="Form-label--tick">
                        <input type="radio" value="no" name="radioyesno" id="no" class="Form-label-radio" <?php echo $no_checked;?>>
                        <span class="Form-label-text"><?php echo $q_info['choice2']?></span>
                    </label>
                </div>

            <?php 
        	}
        	?>
          
        </div>
        <button href="" id="footer" type="submit">></button>
    </form>
    <div style="color:red;font-size:1em;" id="warning"></div>
</body>
</html>

    <script type="text/javascript" src="./jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="../js/locales/bootstrap-datetimepicker.th.js" charset="UTF-8"></script>
    <script type="text/javascript">
        $(".form_datetime").datetimepicker({
            language:  'th',
            format: 'yyyy-mm-dd hh:ii',
            autoclose: true,
            startDate: "<?php echo $min_date;?>",
            minuteStep: 10
        });

        if($('#yes').is(':checked')) { 
            $("#answer").prop('disabled', false);
        }
        if($(".focus").val() == ""){
            $(".focus").focus();
        }
        function validateForm() {
            var x = document.forms["Form"]["answer"].value;
            var require = document.getElementById('required').value;
            var radioYesNo = $('input[name=radioyesno]:checked').val();
            var text_type = $('#text_type').val();
            var min = $("#min").val();
            var max = $("#max").val();
        

            if(x == "" && require == "req"){
                if(radioYesNo == "no"){
                    $("#answer").val("N");
                    $("#answer").prop('disabled', false);
                    $("#Form").submit();
                }else{
                    var div = document.getElementById('warning');
                    div.innerHTML = '*กรุณาใส่คำตอบของคุณค่ะ';
                    return false;
                }
               
            }else if(isNaN(x) && text_type == "number"){
                var div = document.getElementById('warning');
                div.innerHTML = '*กรุณาใส่เฉพาะตัวเลขค่ะ';
                return false;
            }else{
                if(min != "" && max != ""){
                    if(x.length >= min && x.length <= max){
                        $("#form").submit();
                    }else{
                        var div = document.getElementById('warning');
                        div.innerHTML = '*ความยาวไม่ตรงตามที่กำหนด';
                        return false;
                    }
                }else{
                    $("#form").submit();
                }
            }
        }

        $('input[name="radioyesno"]').on('change', function(){

            if($('#yes').is(':checked')) { 
                $("#answer").prop('disabled', false);
                $("#answer").focus();

            }else if($('#no').is(':checked')) { 
                $("#answer").prop('disabled', true);
                $("#answer").val("");
            }
            
        });
       
    </script>

