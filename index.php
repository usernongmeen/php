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
			// Get chat room
			$room = $event['source']['roomId'];
			// Get user profile
			$profile = $event['source']['userId'];
			// Get text sent
			$text = $event['message']['id'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Make a POST Request to Messaging API to reply to sender
			$urlUpload = 'http://m3en.myds.me/om/line/line%20php%20bot%20-%20file%20upload/get_content.php';
			$urlReply = 'https://api.line.me/v2/bot/message/reply';
			$dataUpload = [
				'roomId' => $room,
				'messageId' => $text,
			];
			$messages = [
				'type' => 'text',
				'text' => 'roomId: ' . $room . '
				userId: ' . $profile . '
				messageId: ' . $text,
			];
			$dataReply = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$postUpload = json_encode($dataUpload);
			$postReply = json_encode($dataReply);
			
			$headersUpload = array('Content-Type: application/json');
			$headersReply = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			
			$chUpload = curl_init($urlUpload);
			curl_setopt($chUpload, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($chUpload, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($chUpload, CURLOPT_POSTFIELDS, $postUpload);
			curl_setopt($chUpload, CURLOPT_HTTPHEADER, $headersUpload);
			$resultUpload = curl_exec($chUpload);
			curl_close($chUpload);
			
			$chReply = curl_init($urlReply);
			curl_setopt($chReply, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($chReply, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($chReply, CURLOPT_POSTFIELDS, $postReply);
			curl_setopt($chReply, CURLOPT_HTTPHEADER, $headersReply);
			$resultReply = curl_exec($chReply);
			curl_close($chReply);
			
			echo $resultUpload;
			echo $resultReply;
		}
	}
}
echo "OK";
