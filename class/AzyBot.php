<?php
include_once('AzyJob.php');

class AzyBot {

    public function __construct($userId) 
    {
    	$this->_userId = $userId;
    	$this->_accessToken = "egQa5x0UdLUqsow7a/d53vRdVsB0q48+tYde+a46llCx6qY4dm2LMs3AfY5zIOXiqJaFpezfa79yM2DqWOQ86D1sUoDBLMjsqDPsbXHiumQYAYiM6lVlM/oiUNaYHuSZWUdPH0iqywbYQOncNkHG+gdB04t89/1O/w1cDnyilFU=		";
    	$this->AzyJob = new AzyJob();
    }
 
	public function reply($replyToken,$messages)
	{
		//T Request to Messaging API to reply to sender
		$url = 'https://api.line.me/v2/bot/message/reply';
		$data = [
			'replyToken' => $replyToken,
			'messages' => $messages,
		];
		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $this->_accessToken);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		echo $result . "\r\n";
		return $result;

	}

	public function push($messages,$return = true) 
	{
		$url = 'https://api.line.me/v2/bot/message/push';
		$data = [
			'to' => $this->_userId,
			'messages' => $messages,
		];

		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $this->_accessToken);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		//echo $result . "\r\n";
		return $result;

	}
	public function multicast($userIds,$messages) 
	{
		$url = 'https://api.line.me/v2/bot/message/multicast';
		$data = [
			'to' => $userIds,
			'messages' => $messages
		];

		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $this->_accessToken);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		echo $result . "\r\n";
		return $result;
	}
	public function getMessageContent($messageId) 
	{
		//T Request to Messaging API to reply to sender
		$url = 'https://api.line.me/v2/bot/message/'.$messageId.'/content';

		$headers = array('Authorization: Bearer ' . $this->_accessToken);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	public function getProfile() 
	{
		//T Request to Messaging API to reply to sender
		$url = 'https://api.line.me/v2/bot/profile/'.$this->_userId;

		$headers = array('Authorization: Bearer ' . $this->_accessToken);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	public function notify_submit($job_id,$displayName,$contact_name) 
	{
		$path = "https://azyservice.com/service/forms/".$job_id.".jpg";
		$messages = [
						['type' => 'image','originalContentUrl' => $path, 'previewImageUrl' => $path],
						["type" => "template","altText" => "Broadcast",
							"template" => ["type" => "buttons","text" => $contact_name." (".$displayName. ") submit job Z ".$job_id,
								"actions" => [["type" => "postback","label" => "broadcast","data" => $job_id.",Broadcast","text"=>"Broadcast คุณ ".$contact_name." (".$displayName. ")"]]
							]
						]
					];
		$this->push($messages);
	}

	public function broadcast_to_maids($job_id)
	{
		$job_status = $this->AzyJob->get_value("jobs","job_status","job_id = '".$job_id."'");
		$contact_name = $this->AzyJob->get_contact_name($job_id);

		if($job_status == "submitted") {
			$path = "https://azyservice.com/service/forms/".$job_id.".jpg";
			$messages = [
							['type' => 'image','originalContentUrl' => $path, 'previewImageUrl' => $path],
							["type" => "template","altText" => "มีงานมาให้เสนอราคาค่ะ",
								"template" => ["type" => "buttons","text" => "ต้องการเสนอราคา คุณ".$contact_name."หรือไม่คะ? (". "Z".$job_id.")",
									"actions" => [
										["type" => "uri","label"=>"รายละเอียดเพิ่มเติม","uri"=>"https://azyservice.com/service/maid_quot.php?jid=".$job_id],
										["type" => "postback","label" => "ปฏิเสธ","data" => $job_id.",reject","text"=>"ปฏิเสธงาน Z".$job_id]
									]
								]
							]
						];

			$userIds = $this->AzyJob->get_maid_ids();
			$this->AzyJob->change_status($job_id,"broadcasted");
			$response = json_decode($this->multicast($userIds,$messages),true);
			if(empty($response["message"])) {
				$this->AzyJob->change_status($job_id,"broadcasted");
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	public function close_quot($job_id) 
	{
		return $this->AzyJob->close_quot($job_id);
	}

	public function get_announce_list($job_id)
	{
		return $this->AzyJob->get_announce_list($job_id);
	}

}
?>