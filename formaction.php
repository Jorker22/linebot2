<?php
	date_default_timezone_set("Asia/Bangkok");
	require_once("header.php");
	
	if(isset($_POST["q"]) && isset($_POST["answer"])) {
		$q = (string)$_POST["q"];
		$next_q = (int)$q + 1;
		$answer = (string)$_POST["answer"];

		$q_cnt = (int)$_POST["q_cnt"];

		if(!$AzyCustomer->tempExist)
		{
			$AzyCustomer->insert_temp();
		}
		$AzyCustomer->update_temp("q".$q,$answer);

		if($q_cnt == $q)
		{
			header("location: summary.php"); // summary
		}
		elseif($q_cnt > $q)
		{
			header("location: form.php?q=".(string)$next_q);
		}
	}
	elseif (isset($_POST["summary"])) {
		$job_id = $AzyCustomer->submit_job();
		
		if($job_id) {
			header("location: summary.php?jid=".$job_id);
		}
		else {
			header("location: summary.php?jid=0");
		}
		
	}
	else {
		echo "Error";
	}


	
	
?>