<!doctype html>
<html lang="en">
<body>


<?php

$user_email = 'TO-BE-SET@xxxxxx.com';
echo "<div style='padding:22px;'>Current logged on email (id): $user_email</div>";

if (@$_POST['create_ticket']) {
	/**
	 * Sends form data (ticket_data) to freshdesk.
	 * customer sso security
	 */
	$api_key = "uKPLQP5p2eUv4KV4rJoM";
	$password = "x"; // not needed, keep as x
	$yourdomain = "ashnsugar";

	$ticket_data = json_encode(array(
	"description" => $_POST['description'],
	"subject" => $_POST['subject'],
	"email" => $user_email,
	"priority" => 1,
	"status" => 2,
	"cc_emails" => array("jodybrabec@gmail.com")
	));

	$url = "https://$yourdomain.freshdesk.com/api/v2/tickets";

	$ch = curl_init($url);

	$header[] = "Content-type: application/json";
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $ticket_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);
	$info = curl_getinfo($ch);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$headers = substr($server_output, 0, $header_size);
	$response = substr($server_output, $header_size);

	if($info['http_code'] == 201) {
		echo "Ticket created successfully, the response is given below \n";
		// echo "Response Headers are \n";
		// echo $headers."\n";
		// echo "Response Body \n";
		echo "$response \n";
	} else {
	if($info['http_code'] == 404) {
		echo "Error, Please check the end point \n";
	} else {
		echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
		echo "Headers are ".$headers;
		echo "Response are ".$response;
	}
	}

	curl_close($ch);
}

?>

<div style="font-weight:bold; font-size: 1.3em;">New ticket</div>

We will respond to your ticket at our earliest availability
<form method="POST">
	<input type="hidden" name="create_ticket" value="true" />
	<input type="text" name="subject" value="" placeholder="Subject"  style="width: 50%;" /><br><br>
	Problem Description:<br>
	<textarea name="description" style="width: 50%; height: 300px;"></textarea><br>
	<input type="submit" />
</form>


</body>
</html>
