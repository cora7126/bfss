<!doctype html>
<html lang="en">
<body>

<?php

$user_email = 'TO-BE-SET@xxxxxx.com';
echo "<div style='padding:22px;'>Current logged on email (id): $user_email</div>";

$api_key = "uKPLQP5p2eUv4KV4rJoM";
$password = "x"; // not needed, keep as x
$yourdomain = "ashnsugar";

// Return the tickets that are new or opend & assigned to you
// If you want to fetch all tickets remove the filter query param
$url = "https://$yourdomain.freshdesk.com/api/v2/tickets"; // ?filter=new_and_my_open";


$allUsers = fetchAllUsers($url, $api_key, $password);
// echo '<pre>';
// var_dump($allUsers);
// echo '</pre>';

$allTickets = fetchAllTickets($url, $api_key, $password);

?>
<table style="width: 50%;">
	<tr>
		<td style="font-weight: bold;">
			Subject
		</td>
		<td style="font-weight: bold;">
			Ticket Creator
		</td>
		<td style="font-weight: bold;">
			Assigned To (agent)
		</td>
		<td style="font-weight: bold;">
			Status
		</td>
	</tr>
<?php
foreach($allTickets as $kt => $vt) {
	// $customerEmails = $vt->ticket_cc_emails[0]
	$ticketUrl = 'http://support.5ppdev1.com/a/tickets/' . $vt->id;
	$contactUrl = 'http://support.5ppdev1.com/a/contacts/' . $vt->requester_id;
	$agentUrl = 'http://support.5ppdev1.com/a/contacts/' . $vt->responder_id;
	?>
	<tr>
		<td>
			<a href="<?php echo $ticketUrl;?>" target="_blank"> <?php echo $vt->subject; ?></a>
		</td>
		<td>
			<a href="<?php echo $contactUrl;?>" target="_blank"> <?php echo $vt->requester_id; ?></a>
		</td>
		<td>
			<a href="<?php echo $agentUrl;?>" target="_blank"> <?php echo $vt->responder_id; ?></a>
		</td>
		<td>
			<?php echo $vt->status; ?>
		</td>
	</tr>
	<?php
}
?>
</table>
<?php



function fetchAllTickets($url, $api_key, $password) {
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);
	$info = curl_getinfo($ch);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$headers = substr($server_output, 0, $header_size);
	$response = substr($server_output, $header_size);

	if($info['http_code'] == 200) {
		// echo $headers."\n";
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
	return json_decode($response);
}



function fetchAllUsers($url, $api_key, $password) {

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);
	$info = curl_getinfo($ch);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$headers = substr($server_output, 0, $header_size);
	$response = substr($server_output, $header_size);

	if($info['http_code'] == 200) {
	//   echo "Contacts fetched successfully, the response is given below \n";
	//   echo "Response Headers are \n";
	//   echo $headers."\n";
	//   echo "Response Body \n";
	//   echo "$response \n";
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
	return json_decode($response);
}

?>


</body>
</html>
