<?php
$access_token = 'Lrocod53H3w9ysM+qaTSVWYWP/13z2JM2YOim7PqsRj6hkI7/KrMBmp44oEgM58N8OeFfoy2gESIw4eNCJGuUCEKUqi8oViv/b3sFTnKl0yN1Bd2LKnFyv0Tyk52+ZBxm6hnoTFUuom/5PFXPJr3HgdB04t89/1O/w1cDnyilFU=';
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
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => 'roomId' . $room . '
				userId: ' . $profile . '
				ImageId: ' . $text
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			$data = json_decode(file_get_contents('https://api.line.me/v2/bot/profile/' . $profile), true);
			header('Authorization: Bearer ' . $access_token);

			echo $data;

			echo $result . "\r\n";
		}
	}
}
echo "OK";
