<?php 
date_default_timezone_set("Asia/Bangkok");
define("ADMIN_GROUP","Ccc69fd9f5b3b590ff765cb975532812a");
require_once("../class/AzyBot.php");
require_once("../class/AzyMaid.php");
$AzyAdmin = new AzyBot(ADMIN_GROUP);

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

if (!is_null($events['events'])) {
	foreach ($events['events'] as $event) {
		$user = $event['source']['userId'];

		$replyToken = $event['replyToken'];
		$msgid = $event['message']['id'];
		$gettext = $event['message']['text'];
		$source_type = $event['source']['type'];
		$type = $event['type'];

		$AzyBotMaid = new AzyBot($user);
		$maid_profile = (array)json_decode($AzyBotMaid->getProfile());
		$AzyMaid = new AzyMaid($user,$maid_profile["displayName"],ADMIN_GROUP);

		if($type == 'postback') {
			$data = explode(",",$event['postback']['data']);
			$job_id = $data[0];
			$opt = $data[1];

			if($opt == "Broadcast") {
				if($AzyAdmin->broadcast_to_maids($job_id)) {
					$messages = [["type"=>"text","text"=>"Z".$job_id." broadcasted successfully"]];
					$reply = $AzyAdmin->reply($replyToken,$messages);

				} else {
					$messages = [["type"=>"text","text"=>"❌ Z".$job_id." broadcast failed"]];
					$reply = $AzyAdmin->reply($replyToken,$messages);
				}
			} 
			elseif($opt == "close_quot") {
				$cust_name = $AzyAdmin->close_quot($job_id);
				if($cust_name) {
					$messages = [["type"=>"text","text"=>"ปิดการเสนอราคางาน Z".$job_id." (ลูกค้า ".$cust_name.") เรียบร้อยค่ะ"],["type"=>"text","text"=>"แม่บ้านเสนอราคามาค่ะ รบกวน คุณ".$cust_name." เลือกแม่บ้านตามลิ้งค์นี้นะคะ \nhttps://azyservice.com/service/selection.php?jid=".$job_id]];
				}
				else {
					$messages = [["type"=>"text","text"=>"ไม่สามารถทำการปิดงานซ้ำได้"]];
				}
				$reply = $AzyAdmin->reply($replyToken,$messages);
				
			}
			elseif($opt == "reject") {
				if($AzyMaid->maid_quotations($job_id,"N")=="1") {
					$messages = [["type"=>"text","text"=>"ปฏิเสธงาน Z".$job_id." เรียบร้อยค่ะ"]];
					$reply = $AzyBotMaid->reply($replyToken,$messages);
				}
				else {
					$messages = [["type"=>"text","text"=>"ปฏิเสธงาน Z".$job_id." fail"]];
					$reply = $AzyBotMaid->reply($replyToken,$messages);
				}
			}
			elseif($opt == "announce") {
				
				$lists = $AzyAdmin->get_announce_list($job_id);
				if($lists["reject_list"]) {
					$reject_msg = [["type"=>"text","text"=>"คุณถูกปฏิเสธค่ะ ". $job_id]];
					
					$reject = $AzyAdmin->multicast($lists["reject_list"],$reject_msg);
				}

				if($lists["accept_list"]) {
					$accept_msg = [
                        ["type" => "template","altText" => "ตอบรับงาน",
                            "template" => ["type" => "buttons","text" =>  "Z".$job_id." ยินดีด้วยค่ะ ลูกค้าได้เลือกคุณ",
                                "actions" => [["type" => "postback","label" => "รับทราบ","data" => $job_id.",accept","text"=>"รับทราบงาน Z".$job_id]]
                            ]
                        ]
                    ];
					$accept = $AzyAdmin->multicast($lists["accept_list"],$accept_msg);
				}

				$messages = [["type"=>"text","text"=>"Z".$job_id." ส่งไปบอกแม่บ้านเรียบร้อยค่ะ"]];
				$reply = $AzyAdmin->reply($replyToken,$messages);
				
			}
			elseif($opt == "accept") {
				if($AzyMaid->accept_job($job_id)) {
					$messages = [["type"=>"text","text"=>"ขอบคุณค่ะ"]];
					$reply = $AzyBotMaid->reply($replyToken,$messages);
				} 
				else {
					$messages = [["type"=>"text","text"=>"เกิดข้อผิดพลาด"]];
					$reply = $AzyBotMaid->reply($replyToken,$messages);
				}
				
			}
		}

	}
}
echo "OK";
?>