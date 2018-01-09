<?php

include_once('AzyJob.php');
include_once('AzyBot.php');

class AzyMaid {

    private $_userId;
    private $_displayName;
    public $maidId;
    public $admin_group;
    public $AzyAdmin;
    public $AzyJob;
   

    public function __construct($_userId,$_displayName,$admin_group) {
    	$this->_userId = $_userId;
    	$this->_displayName = $_displayName;

    	$this->AzyAdmin = new AzyBot($admin_group);
    	$this->AzyJob = new AzyJob();

    	$this->maidId = $this->AzyJob->maid_initial($this->_userId,$this->_displayName);
 
    }

    public function get_summary($job_id) 
    {
    	return $this->AzyJob->get_summary(0,true,$job_id);
	}

	public function get_maid_jobs()
	{
		return $this->AzyJob->get_maid_jobs($this->maidId);
	}

	public function get_sum_text() 
	{
		return $this->AzyJob->get_sum_text($this->maidId);
	}    

	public function get_close_jobs() 
	{
		return $this->AzyJob->get_close_jobs(true,$this->maidId);
	}

	public function maid_close_job($job_id) 
	{
		$this->AzyJob->maid_close_job($this->maidId,$job_id);
		$messages = [["type"=>"text","text"=>"แม่บ้านปิดงาน ".$job_id],["type"=>"text","text"=>"แม่บ้านแจ้งว่าทำความสะอาดเรียบร้อย กรุณาแจ้งปิดงานตามลิ้งค์นี้นะคะ\nhttps://azyservice.com/service/close_job.php?jid=".$job_id]];
        $this->AzyAdmin->push($messages);
	}

	public function maid_quotations($job_id,$response) 
	{
		$this->AzyJob->maid_quotations($this->maidId,$job_id,$response);
	}

	public function confirm_price($job_id,$price,$no_of_maids) 
	{
		$this->AzyJob->confirm_price($this->maidId,$job_id,$price,$no_of_maids);
		$quot_count = $this->AzyJob->quot_count($job_id);
		$messages = [
                        ["type" => "template","altText" => "Quotations",
                            "template" => ["type" => "buttons","text" =>  $this->_displayName. " เสนอราคา "."Z".$job_id." ที่ ".$price."บาท (รวม ".$quot_count." คน)",
                                "actions" => [["type" => "postback","label" => "ปิดการเสนอราคา","data" => $job_id.",close_quot","text"=>"ปิดการเสนอราคา Z".$job_id]]
                            ]
                        ]
                    ];

        $push = $this->AzyAdmin->push($messages);

	}

	public function accept_job($job_id)
	{
		return $this->AzyJob->accept_job($this->maidId,$job_id);
		
	}

}
?>