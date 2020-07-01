<?php
/**
 * @file
 * Contains \Drupal\bfss_ticket_support\TicketingController.
 */

namespace Drupal\bfss_ticket_support;


use Drupal\Core\Controller\ControllerBase;


class TicketingController extends ControllerBase {
  public function the_vars() {
    $user_email = 'TO-BE-SET@xxxxxx.com';
    $api_key = "uKPLQP5p2eUv4KV4rJoM";
    $password = "x"; // not needed, keep as x
    $yourdomain = "ashnsugar";
    $url = "https://$yourdomain.freshdesk.com/api/v2/tickets"; // ?filter=new_and_my_open";

    return['user_email' => $user_email, 'api_key' => $api_key, 'password' => $password, 'yourdomain' => $yourdomain, 'url' => $url];
  }

  public function fetchAllTickets($url, $api_key, $password) {
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

  public function fetchAllUsers($url, $api_key, $password) {

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

  public function content() {

    $allUsers = $this->fetchAllUsers($this->the_vars()['url'], $this->the_vars()['api_key'], $this->the_vars()['password']);
    $allTickets = $this->fetchAllTickets($this->the_vars()['url'], $this->the_vars()['api_key'], $this->the_vars()['password']);

    $html = '<table style="width: 50%;">';
	  $html .= '<tr>';
		$html .= '<td style="font-weight: bold;">Subject</td>';
		$html .= '<td style="font-weight: bold;">Ticket Creator</td>';
		$html .= '<td style="font-weight: bold;">Assigned To (agent)</td>';
		$html .= '<td style="font-weight: bold;">Status</td>';
		$html .= '</tr>';

    foreach($allTickets as $kt => $vt) {
	    // $customerEmails = $vt->ticket_cc_emails[0]
	    $ticketUrl = 'http://support.5ppdev1.com/a/tickets/' . $vt->id;
	    $contactUrl = 'http://support.5ppdev1.com/a/contacts/' . $vt->requester_id;
	    $agentUrl = 'http://support.5ppdev1.com/a/contacts/' . $vt->responder_id;
	      $html .= '<tr>';
		    $html .= '<td><a href="' . $ticketUrl . '" target="_blank"> ' . $vt->subject . '</a></td>';
		    $html .= '<td><a href="' . $contactUrl . '" target="_blank"> ' . $vt->requester_id . '</a></td>';
		    $html .= '<td><a href="' . $agentUrl . '" target="_blank"> ' . $vt->responder_id . '</a></td>';
		    $html .= '<td>' . $vt->status . '</td>';
		    $html .= '</tr>';
    }

    $html .= '</table>';


    return array(
      '#markup' => '' . $html . '',
    );
  }
}
