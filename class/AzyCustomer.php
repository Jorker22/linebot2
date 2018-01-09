<?php
include_once('azysubmitform.php');
include_once('AzyJob.php');
include_once('AzyBot.php');

class AzyCustomer {

    private $_userId;
    private $_displayName;
    public $customerId;
    public $tempExist;
    public $AzyJob;
    public $AzyAdmin;


    public function __construct($_userId,$_displayName,$admin_group) {
    	$this->_userId = $_userId;
    	$this->_displayName = $_displayName;

    	$this->AzyJob = new AzyJob();
 		$this->AzyAdmin = new AzyBot($admin_group);

    	$array = $this->AzyJob->customer_initial($this->_userId,$this->_displayName);

    	$this->tempExist = $array["tempExist"];
    	$this->customerId = $array["customerId"];
    }

	public function get_questions_cnt($q_set) {

		return $this->AzyJob->get_questions_cnt($q_set);
	}

	public function get_questions($q) {
		
	    return $this->AzyJob->get_questions($q);
	}

	public function get_current_q() {	

		return $this->AzyJob->get_current_q($this->customerId);
	}

	public function get_answer($q) {

		return $this->AzyJob->get_answer($this->customerId,$q);
	}

	public function delete_temp() {

		$this->AzyJob->delete_tmp($this->customerId);
	}

	public function insert_temp() {

		$this->AzyJob->insert_temp($this->customerId);
	}

	public function update_temp($question,$answer) {

		$this->AzyJob->update_temp($this->customerId,$question,$answer);
	}

	public function get_summary($submit = false, $job_id = 0) {

		return $this->AzyJob->get_summary($this->customerId,$submit,$job_id);
	}

	public function submit_job() {

		$job_id = $this->AzyJob->submit_job($this->customerId);

		$this->summary_to_image($job_id);
	
		$this->AzyAdmin->notify_submit($job_id,$this->_displayName,$this->AzyJob->get_contact_name($job_id));

		return $job_id;

	}


	public function summary_to_image($job_id)
	{
	    $font = "/home/azyservi/domains/azyservice.com/public_html/service/font/supermarket.ttf";
	    $job = $this->AzyJob->get_summary($this->customerId,true,$job_id);
	    
	    $customer_name = $job[6]['answer'];
	    
	    $cleaning_type = $this->AzyJob->new_wordwrap($job[0]['answer'],40);
	    $more_details = $this->AzyJob->new_wordwrap($job[9]['answer'],40);
	    $accom_type = $this->AzyJob->new_wordwrap(($job[1]['answer'].' '.$job[2]['answer']).'ชั้น',40);
	    
	    $area = $this->AzyJob->new_wordwrap($job[3]['answer'],40);
	    $address = $this->AzyJob->new_wordwrap($job[4]['answer'],40);
	    $datetime = explode(" ",(string)$job[7]["answer"]);
	    $date = date("d/m/Y",strtotime($datetime[0]));
	    $time = $datetime[1];
	    $note = $this->AzyJob->new_wordwrap($job[8]['answer'],40);
	    $tel = $job[5]['answer'];

	    $file_name = $job_id;

	    $g  = new setfont;
	    // Create the image
	    $im = imagecreatefromjpeg('/home/azyservi/domains/azyservice.com/public_html/service/template/azy-01.jpg');
	    // Create some colors
	    
	    $white = imagecolorallocate($im, 255, 255, 255);
	    $grey = imagecolorallocate($im, 128, 128, 128);
	    $black = imagecolorallocate($im, 0, 0, 0);
	    $header = imagecolorallocate($im, 95, 95, 95);

	    $g->imagestring($im, 55, 230, 150, $header, $font, $customer_name);
	    $g->imagestring($im, 55, 400, 255, $header, $font,'Z'. $job_id);
	    $g->imagestring($im, 30, 280, 375, $black, $font, $cleaning_type);
	    $g->imagestring($im, 30, 280, 510, $black, $font, $more_details);
	    $g->imagestring($im, 30, 280, 635, $black, $font, $accom_type);
	    $g->imagestring($im, 30, 280, 765, $black, $font, $area);
	    $g->imagestring($im, 30, 250, 890, $black, $font, $address);
	    $g->imagestring($im, 30, 300, 1060, $black, $font, $date);
	    $g->imagestring($im, 30, 200, 1200, $black, $font, $time);
	    //$g->imagestring($im, 30, 280, 1470, $black, $font, $pic);
	    $g->imagestring($im, 30, 250, 1335, $black, $font, $note);
	    $g->imagestring($im, 30, 280, 1595, $black, $font, $tel);

	    // Output to browser
	    //header('Content-type: image/jpeg');
	    imagejpeg($im,'/home/azyservi/domains/azyservice.com/public_html/service/forms/'.$job_id.'.jpg');
	    //imagejpeg($im);
	    imagedestroy($im);
  	}	

  	public function get_jobs_maid_selection() {

  		return $this->AzyJob->get_jobs_maid_selection($this->customerId);
  	}

  	public function get_close_jobs() {

  		return $this->AzyJob->get_close_jobs();
	}

	public function close_job($job_id) 
	{
		$this->AzyJob->close_job($this->customerId,$job_id);
		$messages = [["type"=>"text","text"=>$job_id." ลูกค้าปิดงาน (ส่งตรงถึงแม่บ้าน??)"]];
        $push = $this->AzyAdmin->push($messages);
	}

	public function rating($job_id,$stars) {

		$this->AzyJob->close_job($this->customerId,$job_id,$stars);
		$messages = [["type"=>"text","text"=>"ลูกค้า rate ".$stars." คะแนน ".$job_id]];
        $push = $this->AzyAdmin->push($messages);
	}

	public function confirm_selection($job_id,$maid_id) {
		$this->AzyJob->confirm_selection($job_id,$maid_id);

		 $messages = [
                        ["type" => "template","altText" => "Chose maid",
                            "template" => ["type" => "buttons","text" =>  "Z".$job_id." ลูกค้าเลือกแม่บ้านแล้ว (id: ".$maid_id.")",
                                "actions" => [["type" => "postback","label" => "ส่งไปบอกแม่บ้าน","data" => $job_id.",announce","text"=>"ส่งไปบอกแม่บ้าน Z".$job_id]]
                            ]
                        ]
                    ];
        $push = $this->AzyAdmin->push($messages);
	}

}
?>