<?php

class AzyJob {

    public function __construct() {
    	
    }

    public function connect_mysql() {
	    $connect = null;
		if ($connect == null) {
			$connect = new mysqli("localhost","azyservi_bot","@zys3rvice","azyservi_bot");
		    if ($connect->connect_error) {
				die("Connection failed: " . $connect->connect_error);
				return null;
			}
		    $connect->set_charset("utf8mb4");
		}
		return $connect;
	}

	public function customer_initial($line_id,$display_name) {
		$connect = $this->connect_mysql();
		$sql = "SELECT customer_id,line_name FROM customers where line_user_id = '".$line_id."'";
		$result = $connect->query($sql);

		$array = array();

		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$array["customerId"] = $row['customer_id'];

			if($row["line_name"] != $display_name){
				$updatesql = "UPDATE customers SET line_name = '".$display_name."' where line_user_id = '".$line_id."'";
				$connect->query($updatesql);
			}
		
		}else{
			$insertsql = "INSERT INTO `customers`(`customer_id`, `line_user_id`, `line_name`, `entry_time`, `deleted`) VALUES (Default,'".$line_id."','".$display_name."',Default,Default)";
			$connect->query($insertsql);
			$array["customerId"] = $connect->insert_id;
		}
		$sql = "SELECT * FROM jobs_temp where customer_id = '".$array["customerId"]."'";
		$result = $connect->query($sql);
		
		if($result->num_rows > 0){
			$array["tempExist"] = true;
		}else{
			$array["tempExist"] = false;
		}
		$connect->close();

		return $array;
	}

	public function get_questions_cnt($q_set) {
		$connect = $this->connect_mysql();
		$sql = "SELECT count(*) as q_cnt from questions where q_set = '".$q_set."'";
	    $result = $connect->query($sql);
	 
	    $row = $result->fetch_assoc();
	  	$q_cnt = $row['q_cnt'];
	    $connect->close();
	    return $q_cnt;
	}

	public function get_questions($q) {
		$connect = $this->connect_mysql();
		$sql = "SELECT * from questions where q_set = 'cust_maid_service' AND q_number = '".$q."'";
	    $result = $connect->query($sql);
	    $r = array();
	    $r = $result->fetch_assoc();

	    $connect->close();
	    return $r;
	}

	public function get_current_q($customer_id) {	
		$q = false;
		$connect = $this->connect_mysql();
		$sql = "SELECT * from jobs_temp where customer_id = '".$customer_id."'";
	    $result = $connect->query($sql);
	    $answer_all = true;
	    $cnt = 0;
	    while ($row = $result->fetch_assoc()) {
		    foreach($row as $key => $value) {
		    	$cnt++;
		        if(is_null($value)){
		        	$q = $key;
		        	$answer_all = false;
		        	break;
		        }
		     }
		}
		$connect->close();

		if($answer_all){
			$q = $cnt++;
		}
		return $q;
	}

	public function get_answer($customer_id,$q) {
		$connect = $this->connect_mysql();
		$sql = "SELECT q".$q." as answer from jobs_temp where customer_id = '".$customer_id."'";
	    $result = $connect->query($sql);
	    $row = $result->fetch_assoc();
	    $answer = $row['answer'];
	    $connect->close();

	    return $answer; 
	}

	public function get_contact_name($job_id) {
		$connect = $this->connect_mysql();
		$sql = "SELECT q_number from questions where title = 'ชื่อผู้ติดต่อ'";
	    $result = $connect->query($sql);
	    $row = $result->fetch_assoc();
	    $q_number = $row['q_number'];

	    $result = $connect->query("SELECT q".$q_number." FROM jobs WHERE job_id = '".$job_id."'");
	    $row = $result->fetch_assoc();

	    $contact_name = $row["q".$q_number];

	    $connect->close();

	    return $contact_name;
	}

	public function delete_temp($customer_id) {
		$connect = $this->connect_mysql();
		$deletesql = "DELETE FROM jobs_temp where customer_id = '".$customer_id."'";
	    $connect->query($deletesql);
	    $connect->close();

	}

	public function insert_temp($customer_id) {
		$connect = $this->connect_mysql();
		$insertsql = "INSERT INTO `jobs_temp`(`customer_id`) VALUES ('".$customer_id."')";
	    $connect->query($insertsql);
	    $connect->close();
	}

	public function update_temp($customer_id,$question,$answer) {
		$connect = $this->connect_mysql();
		$updatesql = "UPDATE `jobs_temp` SET ".$question." = '".$answer."' where customer_id = '".$customer_id."'";
	    $connect->query($updatesql);
	    $connect->close();
	}

	public function get_summary($customer_id,$submit = false, $job_id = 0) {
		$connect = $this->connect_mysql();
		if(!$submit){
			$job = $connect->query("SELECT * FROM `jobs_temp` where customer_id = '".$customer_id."'")->fetch_assoc();
		}else{
			$job = $connect->query("SELECT * FROM `jobs` where job_id = '".$job_id."'")->fetch_assoc();
		}
		
		$sql = "SELECT * FROM `questions` where q_set = 'cust_maid_service'";
	    $result = $connect->query($sql);
	    $rowcnt = $result->num_rows;

	    if($rowcnt < 1) {
	    	return false;
	    }

	    $sum = array();
	    while ($row = $result->fetch_assoc()) 
	    {
	    	$type = $row["type"];
	    	$ans_value = $job["q".$row["q_number"]];
	    	$row["keep_value"] = $ans_value;
	    	if(empty($ans_value))
	    	{
	    		$row["answer"] = "-";
	    	}
	    	else
	    	{
	    		if($type == "choice")
		    	{
		    		foreach($row as $key=>$value)
		    		{
		    			if($value == $ans_value)
		    			{
		    				$row["answer"] = $row[str_replace("value","choice",$key)];
		    				break;
		    			}
		    		}
		    	}
		    	elseif($type == "text" || $type == "shorttext" || $type == "datetime")
		    	{
		    		$row["answer"] = $ans_value;
		    	}
		    	elseif($type == "yesno_cont")
		    	{
		    		if($ans_value == "N")
		    		{
		    			$row["answer"] = $row["choice2"];
		    		}
		    		else
		    		{
		    			$row["answer"] = $ans_value;
		    		}
		    	}
	    	}

	    	$sum[] = $row;
	    }

	    $connect->close();
	    return $sum;
	}

	public function submit_job($customer_id) {

		$date = date('ymd');
		$max_id = (string)$this->getMaxID();

		if($max_id){
			
			$run_number = (int)substr($max_id,6,2);
			$run_number = (string)($run_number + 1);
			$run_number = str_pad($run_number, 2, '0', STR_PAD_LEFT);

		}else{// not yet
			$run_number = '01';
		}

		$job_id = $date.$run_number.rand(0,9);

		$summary = $this->get_summary($customer_id);
		$field_ar = array();
		$val_ar = array();
		foreach($summary as $sum) 
		{
			$field_ar[] = "q".$sum["q_number"];
			$val_ar[] = "'".$sum["keep_value"]."'";

			if(is_null($sum["keep_value"])) {
				return false;
				break;
			}
		}

		$field = join(",",$field_ar);
		$val = join(",",$val_ar);
		
		$connect = $this->connect_mysql();
		$sql = "INSERT INTO jobs (job_id,customer_id,".$field.") values('".$job_id."','".$customer_id."',".$val.")";
		$connect->query($sql);

		$connect->query("DELETE FROM jobs_temp where customer_id = '".$customer_id."'");

		return $job_id;

	}

	public function getMaxID() {

		$date = date('ymd');

		$connect = $this->connect_mysql();
		$sql = "SELECT MAX(job_id) as maxid FROM jobs where job_id like '".$date."%'";
		$result = $connect->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$maxid = $row['maxid'];
		}else{
			$maxid = false;
		}
		$connect->close();

		return $maxid;
	}

	  public function get_jobs_maid_selection($customer_id) {
  		$connect = $this->connect_mysql();
		$sql = "SELECT * FROM jobs WHERE customer_id = '".$customer_id."' AND (job_status = 'broadcasted' OR job_status = 'closed quot')";

		$result = $connect->query($sql);
		if($result->num_rows > 0) {
			$r = array();
			while ($row = $result->fetch_assoc()) 
		    {
		    	$r[$row["job_id"]] = $row;

		    	$quot_result = $connect->query("SELECT * FROM quotations WHERE job_id ='". $row["job_id"]."' AND quot_status = 'Y'");
		    	$html = "";
		    	if($quot_result->num_rows > 0) {
		    		while($quot_row = $quot_result->fetch_assoc()) {
		    			$r[$row["job_id"]]["quotations"][] = $quot_row;

		    			$html = $html.'<div class="choice">
                        <label class="Form-label--tick">
                            <input type="radio" value="'.$quot_row["maid_id"].'" name="answer" id="'.$quot_row["maid_id"].'" class="Form-label-radio">
                            <span class="Form-label-text">maid '.$quot_row["maid_id"].'</span>
                        </label>
                    </div>';
		    		}
		    	}
		    	else {
		    		$r[$row["job_id"]]["quotations"][0]["job_id"] = 0;
		    	
		    	}

		    	$r[$row["job_id"]]["html_style"] = $html;

		    }
		    $connect->close();
	    	return $r;
		} else {
			$connect->close();
			return false;
		}
  	}

  	public function get_close_jobs($maid = false,$maid_id = 0) {
		$connect = $this->connect_mysql();
		
		$result = $connect->query("SELECT q_number FROM questions WHERE type = 'datetime'");
		$row = $result->fetch_assoc();
		$q = "q".(string)$row["q_number"];

		if(!$maid) {
			$sql = "SELECT * FROM jobs as j
			WHERE j.".$q." < '".date("Y-m-d H:i:s")."' AND (j.job_status = 'chose maid' OR j.job_status = 'maid closed job') order by j.".$q." ASC";

		} 
		else {
			$sql = "SELECT * FROM jobs as j JOIN quotations as q ON q.job_id = j.job_id
			WHERE j.".$q." < '".date("Y-m-d H:i:s")."' AND q.maid_id = '".$maid_id."' AND q.quot_status = 'A'
			AND j.job_status = 'chose maid' order by j.".$q." ASC";
		}
		
		$result = $connect->query($sql);
		$r = array();
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$r[] = $row;
			}
		}
		else {
			$r = false;
		}
		
		$connect->close();
		return $r;
	}

	public function close_job($customer_id,$job_id) {
		$connect = $this->connect_mysql();
		$connect->query("UPDATE jobs SET job_status = 'closed job' WHERE job_id = '".$job_id."' AND customer_id = '".$customer_id."'");
		$connect->close();
	}

	public function maid_close_job($maid_id,$job_id) {
		$connect = $this->connect_mysql();
		$connect->query("UPDATE jobs SET job_status = 'maid closed job' WHERE job_id = '".$job_id."' AND maid_id = '".$maid_id."'");
		$connect->close();

	}

	public function rating($customer_id,$job_id,$stars) {
		$connect = $this->connect_mysql();
		$connect->query("UPDATE jobs SET job_stars = '".$stars."' WHERE job_id = '".$job_id."' AND customer_id = '".$customer_id."'");
		$connect->close();
	}

	public function new_wordwrap($str, $width) {
	    $words = explode(" ",$str);

	    $line_len = 0;
	    $text = '';
	    foreach($words as $word){
	    	$word_len = mb_strlen($word);
	    	$line_len += $word_len;
	    	if($text == ''){
	    		$text .= $word;
	    	}else{
	    		if($line_len <= $width){
	    			$text .= " ".$word;
		    	}else{
		    		$text .= "\n".$word;
		    		$line_len = $word_len;
		    	}
	    	}
	    }
	    return $text;
	}


	public function maid_initial($line_id,$display_name)
    {
    	$connect = $this->connect_mysql();
		$sql = "SELECT maid_id,maid_line_name FROM maids where maid_line_id = '".$line_id."'";
		$result = $connect->query($sql);

		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$maid_id = $row['maid_id'];

			if($row["maid_line_name"] != $display_name){
				$updatesql = "UPDATE maids SET maid_line_name = '".$display_name."' where maid_line_id = '".$line_id."'";
				$connect->query($updatesql);
			}
		
		}else{
			$insertsql = "INSERT INTO `maids`(`maid_id`, `maid_line_id`, `maid_line_name`) VALUES (Default,'".$line_id."','".$display_name."')";
			$connect->query($insertsql);
			$maid_id = $connect->insert_id;
		}

		$connect->close();

		return $maid_id;
    }

    public function get_maid_jobs($maid_id)
	{
		$connect = $this->connect_mysql();
		$sql = "SELECT j.job_id FROM jobs as j JOIN quotations as q ON q.job_id = j.job_id WHERE q.maid_id = '".$maid_id."' AND j.job_status = 'broadcasted' AND q.quot_status = '' order by j.job_id desc";

		$result = $connect->query($sql);
		if( $result->num_rows > 0) {
			$r = array();
			while ($row = $result->fetch_assoc()) 
		    {
		    	$r[] = $row;
		    }
		    $connect->close();
	    	return $r;
		} else {
			$connect->close();
			return false;
		}
		
	}

	public function get_sum_text($maid_id) 
	{

		$job_ids = $this->get_maid_jobs($maid_id);
		$summary = array();
		foreach($job_ids as $job_id){
			$id = $job_id["job_id"];
			$job_sums = $this->get_summary(0,true,$id);
			$text = "<b>เลขที่งาน</b>: Z".$id."<br>";
			foreach($job_sums as $sum) {
				$text .= "<b>".$sum["title"]. "</b> : ". $sum["answer"]."<br>";
			}
			$summary[$id] = $text;
        }
        return $summary;
	} 

//bot
	public function maid_quotations($maid_id,$job_id,$response) {

		$connect = $this->connect_mysql();
		$sql = "SELECT * FROM quotations where maid_id = '".$maid_id."' AND job_id = '".$job_id."'";
		$result = $connect->query($sql);
			if($result->num_rows > 0){
				$row = $result->fetch_assoc();
				$current_status = $row["quot_status"];

				if($current_status == "Y") {
					return "Y";
				} 
				elseif($current_status == "N") {
					return "N";
				} 
				elseif($current_status == "") {
					$connect->query("UPDATE quotations SET quot_status = '".$response."' WHERE maid_id = '".$maid_id."' AND job_id = '".$job_id."'");
					return "1";
				}
			}else {
				$insert = "INSERT INTO quotations (job_id,maid_id,price,commission,quot_status) values('".$job_id."','".$maid_id."',Default,Default,'".$response."')";
				$connect->query($insert);
				return "1";
			}
		
		$connect->close();

	}   

	public function confirm_price($maid_id,$job_id,$price,$no_of_maids) {
		if($price > 1000) {
			$commission = $price * 0.05;
		} else {
			$commission = 50;
		}

		$connect = $this->connect_mysql();

		$connect->query("UPDATE quotations 
			SET quot_status = 'Y', 
			price = ".$price.",
			maid_count = ".$no_of_maids.",
			commission = ".$commission."
			WHERE maid_id = '".$maid_id."' AND job_id = '".$job_id."'");
		$connect->close();
	}

	public function quot_count($job_id) {

		$connect = $this->connect_mysql();
		$sql = "SELECT COUNT(quot_id) as cnt FROM quotations WHERE job_id = '".$job_id."' AND quot_status = 'Y'";
		$result = $connect->query($sql);
		$row_cnt = $result->num_rows;
		if($row_cnt > 0) {
			$row = $result->fetch_assoc();
			$cnt = $row["cnt"];
		} else {
			$cnt = 0;
		}
		$connect->close();

		return $cnt;
	}

	public function confirm_selection($job_id,$maid_id) {
		$connect = $this->connect_mysql();

		$connect->query("UPDATE jobs 
			SET job_status = 'chose maid', 
			maid_id = ".$maid_id."
			WHERE job_id = '".$job_id."'");

		$connect->query("UPDATE quotations
			SET quot_status = 'C' WHERE job_id = '".$job_id."' AND maid_id = '".$maid_id."'");

		$connect->close();
	}

	public function get_maid_ids() {
		$connect = $this->connect_mysql();

		$sql = "SELECT maid_line_id FROM maids where deleted = 0";
		$result = $connect->query($sql);
		$maids = array();
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
 				$maids[] = $row["maid_line_id"];
 			}
		}

		$connect->close();
		return $maids;	
	}

	public function get_maid_id() {
		$connect = $this->connect_mysql();

		$sql = "SELECT maid_id FROM maids where maid_line_id = '".$this->_userId."' AND deleted = 0";
		$result = $connect->query($sql);
		if($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$maid_id = $row["maid_id"];	
		} else {
			$maid_id = 0;
		}
		$connect->close();
		return $maid_id;
	}

	public function get_value($table,$field,$where) {
		$connect = $this->connect_mysql();
		$result = $connect->query("SELECT ".$field." FROM ".$table." where ".$where);
		$row = $result->fetch_assoc();
		$connect->close();
		return $row[$field];
	}

	public function change_status($job_id,$status) {
		$connect = $this->connect_mysql();
		$connect->query("UPDATE jobs SET job_status = '".$status."' WHERE job_id = '".$job_id."'");
		$connect->close();
	}

	public function close_quot($job_id) 
	{
		$connect = $this->connect_mysql();

		$sql = "SELECT job_status,line_name from jobs 
		JOIN customers ON customers.customer_id = jobs.customer_id
		where job_id = '".$job_id."'";
		$result = $connect->query($sql);
		$row = $result->fetch_assoc();
		$cur_status = $row["job_status"];
		$cust_name = $row["line_name"];

		if($cur_status == "broadcasted") {
			$sql = "UPDATE jobs SET job_status = 'closed quot' WHERE job_id = '".$job_id."' AND job_status = 'broadcasted'";
			$connect->query($sql);
			return $cust_name;
			
		} 
		else {
			return false;
		}
		
		return false;

		$connect->close();
		
	}

	public function get_announce_list($job_id) {
		
		$connect = $this->connect_mysql();

		$sql = "SELECT maid_line_id FROM maids as m 
		JOIN quotations as q ON m.maid_id = q.maid_id 
		where q.job_id = '".$job_id."' AND q.quot_status = 'Y'";

		$result = $connect->query($sql);
		$array = array();
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
 				$array["reject_list"][] = $row["maid_line_id"];
 			}
		} 
		else {
			$array["reject_list"] = false;
		}

		$sql = "SELECT maid_line_id FROM maids as m 
		JOIN quotations as q ON m.maid_id = q.maid_id 
		where q.job_id = '".$job_id."' AND q.quot_status = 'C'";

		$result = $connect->query($sql);
		if($result->num_rows > 0) {
			$row = $result->fetch_assoc();
 			$array["accept_list"][] = $row["maid_line_id"];
		} 
		else {
			$array["accept_list"] = false;
		}
		$connect->close();
		return $array;
	}

	public function accept_job($maid_id,$job_id) {
		$connect = $this->connect_mysql();
		$sql = "SELECT quot_id,quot_status FROM quotations WHERE job_id = '".$job_id."' AND maid_id = '".$maid_id."'";

		$result = $connect->query($sql);
		if($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			if($row["quot_status"] == "C") {
				$connect->query("UPDATE quotations SET quot_status = 'A' WHERE quot_id = '".$row["quot_id"]."'");
				return true;
			}
			else {
				return false;
			}
		}
		return false;
		$connect->close();
	}




}
?>