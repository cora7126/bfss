<?php
namespace Drupal\bfss_ticket_support\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use  \Drupal\user\Entity\User;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class CreateTicket extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'create_support_ticket';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    //assessment get by current assessors
    $uid = \Drupal::currentUser();
    $user_id = $uid->id();
    $user = \Drupal\user\Entity\User::load($user_id);
    // $roles = $user->getRoles();
    // $userEmail = $user->getEmail();
    // $username = $user->getUsername();
    $name = $user->getDisplayName();

    // ksm($user);

    $form['header'] = array(
      '#prefix' => '<div class="dash-main-right">
      <h1><i class="fas fa-home"></i> &gt; <a href="/dashboard" class="ticketing-edit_dash">Dashboard</a> &gt; Create a Ticket</h1>
        <div class="dash-sub-main">
          <i class="fas fa-ticket-alt edit_image_solid" aria-hidden="true"></i>
          <h2><span class="ticketing-create-span">CREATE A</span><br>Ticket</h2>
        </div><br><br>',
      '#suffix' => '</div>',
    );

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '
      <div class="ticketing-create">',
    ];

    $arr = ['one'  => t('Priority'), '1'  => t('Low'), '2'  =>t('Medium'), '3'  =>t('High'), '4'  =>t('Urgent')];
    $form['priority'] = array(
      '#type' => 'select',
      '#options' => $arr,
      '#default_value' => 'one',
    );

    $form['subject'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('Subject'),
      // '#default_value' => $results3['field_first_name_value'],
    );
    $form['description'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Questions/Comments'),
      '#required' => TRUE,
      // '#default_value' => $results1['field_last_name_value'],
    );
    $form['create_ticket'] = array(
      '#type' => 'hidden',
      '#value' => '1',
    );
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
      '#prefix' =>'<div id="create_tct_submit" class="ticketing-button">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => '::submitForm', // don't forget :: when calling a class method.
        //'callback' => [$this, 'myAjaxCallback'], //alternative notation
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'click',
        'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ],
      //'#value' => t('Submit'),
    ];

    $form['message2'] = [
      '#type' => 'markup',
      '#markup' => '
      <div class="result_message ticketing-success"></div>
      </div>',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
      // $name = $form_state->getValue('jodi');
      // if(preg_match('/[^A-Za-z]/', $name)) {
          // $form_state->setErrorByName('jodi', $this->t('your jodi must in characters without space'));
      // }
      // parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $uid = \Drupal::currentUser();
    $user_id = $uid->id();
    $user = \Drupal\user\Entity\User::load($user_id);
    $roles = $user->getRoles();
    $userEmail = $user->getEmail();
    $username = $user->getUsername();
    $name = $user->getDisplayName();

    // $roles['athlete']
    // $roles['assessors']
    // $roles['bfss_manager']
    // $roles['coach']
    // $roles['bfss_administrator']

    if (@$_POST['create_ticket']) {
      /**
       * Sends form data (ticket_data) to freshdesk.
       * customer sso security
       */
      $successMessage = 'Thank you, We will respond to your ticket at our earliest availability';

      if ($_POST['create_ticket'] == $successMessage) {
        // Doing this redundant block (which is duplicated below), because this popup form submits twice.
        $message = $successMessage;
        $response = new AjaxResponse();
        $response->addCommand(
          new HtmlCommand(
            '.result_message',
            '<div class="success_message">'.$message.'</div>'
          )
        );
        return $response;
      }
      else {

        $api_key = "6aTnr07ieoIsXLhN1c0";
        $password = "99999"; // not needed, keep as x
        $yourdomain = "digitalrace";

        $ticket_data = json_encode(array(
          "description" => htmlentities($_POST['description']),
          "subject" => htmlentities($_POST['subject']),
          "name" => $name,
          "email" => $userEmail,
          "priority" => ($_POST['priority'] == 'one') ? 1 : (int)$_POST['priority'],
          "status" => 2,
          "cc_emails" => array('jodybrabec@gmail.com')
        ));
        // ksm('$ticket_data', $ticket_data);

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
        $c_response = substr($server_output, $header_size);

        if($info['http_code'] == 201) {
          $_POST['create_ticket'] = $successMessage;
          // ksm("$headers\n");  ksm("$c_response \n");
          $message = $successMessage . '....'; // this should not be displayed, if double-submit is happening.
          $response = new AjaxResponse();
          $response->addCommand(
            new HtmlCommand(
              '.result_message',
              '<div class="success_message">'.$message.'</div>'
            )
          );
          curl_close($ch);
          return $response;

        } else {
          // ksm("Error, HTTP Status Code : " . $info['http_code'] . "\n", "Headers are ".$headers, "Response are ".$c_response);
          if($info['http_code'] == 404) {
            $errMsg = 'Ticketing Error 404';
            // ksm("Error, Please check the end point \n");
          } else {
            if (preg_match('/Validation\s+fail/si', $c_response)) {
              $errMsg = 'Validation failed, user email '.$userEmail.' is probably not registered.';
            }
            else {
              $errMsg = 'Unknown Ticketing Error';
            }
          }
          $response = new AjaxResponse();
          $response->addCommand(
            new HtmlCommand(
              '.result_message',
              '<div class="success_message_delete">'.$errMsg.'</div>'
            )
          );
          curl_close($ch);
          return $response;
        }
      }
    }
  }
}
