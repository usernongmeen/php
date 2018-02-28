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
			// Get text sent
			$text = $event['message']['id'];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'http://m3en.myds.me/om/line/line%20php%20bot%20-%20file%20upload/get_content.php';
			$post = [
			    'roomId' => $room,
			    'messageId' => $text
			];
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
}
echo "OK";
