<?php
/**
 * @file
 * Contains \Drupal\bfss_ticket_support\TicketingController.
 */

namespace Drupal\bfss_ticket_support;


use Drupal\Core\Controller\ControllerBase;
use  \Drupal\user\Entity\User;


class TicketingController extends ControllerBase {

  protected $allTickets;
  protected $allAgents;
  protected $allCustomers;
  protected $allBfssUsers;

  public function fetchFreshDesk($uriParam) {

    $user_email = 'TO-BE-SET@xxxxxx.com';
    $api_key = "uKPLQP5p2eUv4KV4rJoM";
    $password = "x"; // not needed, keep as x
    $yourdomain = "ashnsugar";

    $url = "https://$yourdomain.freshdesk.com/api/v2/" . $uriParam; // ?filter=new_and_my_open";
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
        echo "BFSS Local FD Error 404: Check the end point \n";
      } else {
        echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
        echo "Headers are ".$headers;
        echo "Response are ".$response;
      }
    }
    curl_close($ch);
    return json_decode($response);
  }


  public function fetchBfssUsers() {
    $userAry = [];
    $userIds = \Drupal::entityQuery('user')->execute();
    foreach ($userIds as $userId) {
      $userAry[$userId] = [];
      $aUser = User::load($userId);
      $email = $aUser->getEmail();
      $userAry[$email]['user_id'] = $userId;
      $userAry[$email]['roles'] = $aUser->getRoles();
      $userAry[$email]['username'] = $aUser->getUsername();
      $userAry[$email]['name'] = $aUser->getDisplayName();
    }
    return $userAry;
  }

  /***
   * Merge BFSS user data via freshdesk ticket email address - Requester's responder's email.
   * INPUT:
   *    $fdId (string) - fresh desk user id
   *    $fdEmail (string) - fresh desk email address- if ticket responder then this will be empty
   * RETURN:
   *    $usrData (array) -
   *        $usrData['fd_id'] = $fdId;
   *        $usrData['name'] = freshDesk full name - if none, then use bfss name
   *        $usrData['username'] = bfss username
   *        $usrData['email'] = freshDesk email - if none, the use bfss email address.
   */
  private function getMergedUser($fdId, $role, $fdEmail = '') {
    $usrData = [];
    if (!$fdId) {
      // ksm('ERROR, no fdId');
    }
    $usrData['fd_id'] = $fdId;
    $usrData['role'] = $role;

    if ($role == 'agent') {
      //---- Match freshDesk ticket-assigned agent with BFSS user via email address
      foreach($this->allAgents as $at => $agent) {
        if ($agent->id == $fdId) {
          $usrData['email'] = $agent->contact->email;
          $usrData['name'] = $agent->contact->name;
        }
      }
    }
    else if ($role == 'requestor') {
      $requester = $this->fetchFreshDesk('contacts/' . $fdId);
      $usrData['email'] = $requester->primary_email;
      $usrData['name'] = $requester->name;
    }
    else if ($fdEmail) {
      //---- Match freshDesk ticket-creating customer with BFSS user via email address
      // if ($fdEmail && $this->allBfssUsers[$fdEmail]) {
      //   $usrData['email'] = $fdEmail;
      //   $usrData['name'] = $this->allBfssUsers[$usrData['email']]['name'];
      // }
    }

    if ($this->allBfssUsers[$usrData['email']]['username']) {
      ksm('eeeee', $this->allBfssUsers[$usrData['email']]);
      $usrData['bfss_username'] = $this->allBfssUsers[$usrData['email']]['username'];
      $usrData['bfss_user_id'] = $this->allBfssUsers[$usrData['email']]['user_id'];
    }

    return $usrData;
  }


  public function content() {

    $this->allTickets = $this->fetchFreshDesk('tickets');
    $this->allAgents = $this->fetchFreshDesk('agents');
    // $this->allCustomers = $this->fetchFreshDesk('contacts');
    $this->allBfssUsers = $this->fetchBfssUsers();

    ksm($this->allTickets);
    ksm($this->allAgents);
    ksm($this->allBfssUsers);

    // Other potential freshDesk vars to use: priority, is_escalated, updated_at

    /***
     * Freshdesk documentation from https://api.freshservice.com/v2/#create_ticket
     * CODES:
        Source Type      Value        Status  Value      Priorities  Value
        Email            1            Open      2        Low          1
        Portal           2            Pending   3        Medium       2
        Phone            3            Resolved  4        High         3
        Chat             4            Closed    5        Urgent       4
        Feedback widget  5
        Yammer           6
        AWS Cloudwatch   7
        Pagerduty        8
        Walkup           9
        Slack           10
     */
    $fdCodeStatus = array(
      2 => 'open',
      3 => 'Pending',
      4 => 'Resolved',
      5 => 'Closed',
    );
    $fdCodePriority = array(
      1 => 'Low',
      2 => 'Medium',
      3 => 'High',
      4 => 'Urgent',
    );

    $html = '<br><div class="success_message_delete">
      Freshdesk Test Account username / password:  ashnsugar@gmail.com / nephilehi
      <br><br>
      Note: BFSS user auto-sync configuration at: <a href="/admin" target="_blank">freshDesk Admin</a>
    </div><br>';

    $html .= '<div class="table-responsive-wrap"><table class="table table-hover table-striped" cellspacing="0" width="100%">
      <tr><thead>
        <th class="th-hd" >Subject</th>
        <th class="th-hd" >Type</th>
        <th class="th-hd" >Create Date</th>
        <th class="th-hd" >Requester</th>
        <th class="th-hd" >Assigned To</th>
        <th class="th-hd" >Status</th>
        <th class="th-hd" >Priority</th>
      </thead></tr>
      <tbody>';

    foreach($this->allTickets as $kt => $ticket) {
      $ticket->type = $ticket->type ? $ticket->type : 'Other';
      $userRequester = $this->getMergedUser($ticket->requester_id, 'requestor', $ticket->ticket_cc_emails[0]);
      if ($ticket->responder_id) {
        $userResponder = $this->getMergedUser($ticket->responder_id, 'agent');
      }
      else {
        $userResponder = [];
      }
      $ticketUrl = 'http://support.5ppdev1.com/a/tickets/' . $ticket->id;
      $agentUrl = 'http://support.5ppdev1.com/a/contacts/' . $ticket->responder_id;
      if (  0  &&  $userRequester['bfss_user_id']) {
        // $requester = '<a href="/user/' . $userRequester['bfss_user_id'] . '" target="_blank"> ' . htmlspecialchars($userRequester['name']) . '</a>';
      }
      else {
        $requester = htmlspecialchars($userRequester['name']);
      }
      $responder = htmlspecialchars(@$userResponder['name']);

      $html .= '<tr>
        <td><a href="' . $ticketUrl . '" target="_blank"> ' . htmlspecialchars($ticket->subject) . '</a></td>
        <td>' . $ticket->type . '</td>
        <td>' . date('Y-m-d H:i:s', strtotime($ticket->created_at)) . '</td>
        <td>' . $requester . '</td>
        <td>' . $responder . '</td>
        <td>' . $fdCodeStatus[$ticket->status] . '</td>
        <td>' . $fdCodePriority[$ticket->priority] . '</td>
      </tr>';
      // EMAIL LINK: <a href="mailto:'.$userResponder['email'].'&subject=Regarding Ticket: '.htmlspecialchars($ticket->subject).'">'
    }

    $html .= '</tbody></table></div>';

    return array(
      '#markup' => '' . $html . '',
    );
  }
}
