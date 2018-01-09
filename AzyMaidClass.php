<?php

class AzyMaid {

    private $_userId;
    private $_displayName;
    public $maidId;
    public $admin_group;
   

    public function __construct($_userId,$_displayName) {
    	$this->_userId = $_userId;
    	$this->_displayName = $_displayName;
    	$this->admin_group = "Ccc69fd9f5b3b590ff765cb975532812a";
    	$this->initial();
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

    public function initial()
    {
    	$connect = $this->connect_mysql();
		$sql = "SELECT maid_id,maid_line_name FROM maids where maid_line_id = '".$this->_userId."'";
		$result = $connect->query($sql);

		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$this->maidId = $row['maid_id'];

			if($row["maid_line_name"] != $this->_displayName){
				$updatesql = "UPDATE maids SET maid_line_name = '".$this->_displayName."' where maid_line_id = '".$this->_userId."'";
				$connect->query($updatesql);
			}
		
		}else{
			$insertsql = "INSERT INTO `maids`(`maid_id`, `maid_line_id`, `maid_line_name`) VALUES (Default,'".$this->_userId."','".$this->_displayName."')";
			$connect->query($insertsql);
			$this->customerId = $connect->insert_id;
		}

		$connect->close();
    }

    public function get_summary($job_id) {
		$connect = $this->connect_mysql();
		
		$job = $connect->query("SELECT * FROM `jobs` where job_id = '".$job_id."'")->fetch_assoc();
		
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

	public function get_maid_jobs()
	{
		$connect = $this->connect_mysql();
		$sql = "SELECT j.job_id FROM jobs as j JOIN quotations as q ON q.job_id = j.job_id WHERE q.maid_id = '".$this->maidId."' AND j.job_status = 'broadcasted' order by j.job_id desc";

		$result = $connect->query($sql);
		$r = array();
		while ($row = $result->fetch_assoc()) 
	    {
	    	$r[] = $row;
	    }

	    $connect->close();
	    return $r;
	}

	public function get_sum_text() {

		$job_ids = $this->get_maid_jobs();
		$summary = array();
		foreach($job_ids as $job_id){
			$job_sums = $this->get_summary(true,$job_id);
			foreach($job_sums as $sum) {
				$summary[$job_id] .= "<b>".$sum["title"]. "</b> : ". $sum["answer"]."<br>";
			}

        }
        return $summary;
	}

	

    
}
?>