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


			$roomId = $event['source']['roomId'];
			$userId = $event['source']['userId'];
			$messageId = $event['message']['id'];
			$replyToken = $event['replyToken'];


			$url = 'http://m3en.myds.me/om/line/line%20php%20bot%20-%20file%20upload/get_content.php';
			$data = array('roomId' => $roomId, 'messageId' => $messageId);
			$options = array(
			  'http' => array(
			    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			    'method'  => 'POST',
			    'content' => http_build_query($data),
			  ),
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			var_dump($result);


			$url = 'https://api.line.me/v2/bot/message/reply';
			$messages = [
				'type' => 'sticker',
				'packageId' => '2',
				'stickerId' => '41'
			];
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages]
			];
			$json = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
}
echo "OK";
