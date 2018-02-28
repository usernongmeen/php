<?php
$access_token = 'Cc62NCw9whOAnXC84ptuVBVHJpE25iKSAVK+96+eHvlmYd9SMvfnLSDImAsAFN47XLqq2YkAvuEPAdNGjmH/vNgPUu3Ej5rMRdr7js6VACSzvj7GWFNIBaFkxTQg8vaqGZ/tFP48mGy1KueQEd4qpgdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {


			// Upload
			// -----
			// Get chat room
			$room = $event['source']['roomId'];
			// Get user profile
			$profile = $event['source']['userId'];
			// Get text sent
			$text = $event['message']['id'];
			// Make a POST Request to Upload to Server
			$url = 'http://m3en.myds.me/om/line/line%20php%20bot%20-%20file%20upload/get_content.php';

			$data = array(
    			'roomId' => $room,
    			'messageId' => $text
    		);
    		$json = json_encode($data);
    		$client = new Zend_Http_Client($url);
    		$client->setRawData($json, 'application/json')->request('POST');


			// Reply
			// -----
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$messages = [
				'type' => 'text',
				'text' => 'roomId: ' . $room . '
				userId: ' . $profile . '
				messageId: ' . $text
			];
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages]
			];
			$post = json_encode($data);
			$headersReply = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($urlReply);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
}
echo "OK";
