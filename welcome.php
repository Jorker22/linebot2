<?php
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
    <div class="Form" style="text-align: center">
        
<?php 

    if($AzyCustomer->tempExist){
        $continue = "ดำเนินการต่อ";
        $continue_txt = "คุณมีรายการค้างไว้ ต้องการดำเนินการต่อ หรือเริ่มต้นใหม่?";
        $start = "เริ่มต้นใหม่";
        $q = str_replace("q","",$AzyCustomer->get_current_q());
        $q_cnt = $AzyCustomer->get_questions_cnt('cust_maid_service');
        $customerId = $AzyCustomer->customerId;
        $del_href = 'form.php?delete='.(string)$customerId;

        if((int)$q > (int)$q_cnt){
            $href = 'summary.php';
        }else{
            $href = 'form.php?q='.(string)$q;
        }

        echo $continue_txt
?>
        <br><br><a class="button orange" href="<?php echo $href;?>" role="button"><?php echo $continue; ?></a>
        <br><br><a class="button green" href="<?php echo $del_href;?>" role="button"><?php echo $start; ?></a>
<?php
    }else{
        $start = "เริ่ม";
        $start_txt = "กรุณากรอกข้อมูลเพื่อขอจองบริการแม่บ้าน";
        echo $start_txt;
?>
        <br><br><a class="button green" href="form.php?q=1" role="button"><?php echo $start; ?></a>
<?php } ?>  
    </div>
</body>
</html>


