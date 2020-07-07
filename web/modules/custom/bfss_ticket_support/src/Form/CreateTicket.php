<?php
namespace Drupal\bfss_ticket_support\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
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
    $current_user = \Drupal::currentUser()->id();
    //    $conn = Database::getConnection();
    //    $query2 = \Drupal::database()->select('user__field_state', 'ufs');
    //    $query2->addField('ufs', 'field_state_value');
    //    $query2->condition('entity_id', $current_user,'=');
    //    $results2 = $query2->execute()->fetchAssoc();
    //    $state = $results2['field_state_value'];

   $form['header'] = array(
      '#prefix' => '<div class="main_header">
      <h1 style="margin-top: 10px;font-size:15px;margin-left: 20px;">
        <i class="fas fa-home" style="color: #f76907;margin-right: 5px;"></i>
        <i class="fas fa-angle-right" style="font-weight:400;margin-right:5px;"></i>
        <a href="/dashboard" class="edit_dash" style="margin-right:5px;font-weight: bold;">Dashboard</a>
        <i class="fas fa-angle-right" style="font-weight:400;margin-right:5px;"></i>
        <a class="edit_dash" style="font-weight: bold;">Support</a>
        <i class="fas fa-angle-right" style="font-weight:400;margin-right:5px;"></i>
        <a class="edit_dash" style="font-weight: bold;">Ticketing</a></h1>
        <div class="edit_header" style="display:flex; padding:15px;background: #fffcd7;border: 1px solid grey;">
        <i class="fa fa-laptop edit_image" aria-hidden="true"></i>
        <h2 style="margin-top:0px;margin-bottom:0px;">
          <span style="font-size:13px;font-weight:600;">
          SUBMIT A TICKET</span><br>
        </h2>
        <div><br>
          &nbsp; We will respond to your ticket at our earliest availability<br>
        </div>
        </div>',
      '#suffix' => '</div>',
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
      // '#default_value' => $results1['field_last_name_value'],
    );
    $form['create_ticket'] = array(
      '#type' => 'hidden',
      '#value' => '1',
    );
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
  		'#prefix' =>'<div id="create_tct_submit">',
      //'#value' => t('Submit'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
      // $name = $form_state->getValue('jodi');
      // if(preg_match('/[^A-Za-z]/', $name)) {
          // $form_state->setErrorByName('jodi', $this->t('your jodi must in characters without space'));
      // }
    // if (!is_float($form_state->getValue('height'))) {
          // $form_state->setErrorByName('candidate_age', $this->t('Height needs to be a number'));
        // }
    // if (!is_float($form_state->getValue('weight'))) {
          // $form_state->setErrorByName('candidate_age', $this->t('Weight needs to be a number'));
        // }
      /* $number = $form_state->getValue('candidate_age');
      if(!preg_match('/[^A-Za-z]/', $number)) {
          $form_state->setErrorByName('candidate_age', $this->t('your age must in numbers'));
      }*/
      //     if (strlen($form_state->getValue('mobile_number')) < 10 ) {
      //       $form_state->setErrorByName('mobile_number', $this->t('your mobile number must in 10 digits'));
      //      }
      // parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    if (@$_POST['create_ticket']) {
      /**
       * Sends form data (ticket_data) to freshdesk.
       * customer sso security
       */
      $api_key = "6aTnr07ieoIsXLhN1c0";
      $password = "99999"; // not needed, keep as x
      $yourdomain = "digitalrace";

      $ticket_data = json_encode(array(
        "description" => $_POST['description'],
        "subject" => $_POST['subject'],
        "email" => 'ashnsugar@gmail.com',
        "priority" => 1,
        "status" => 2,
        "cc_emails" => array("ashnsugar@gmail.com")
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
        // ksm("Ticket created successfully, the response is given below \n");
        // ksm("$headers\n");
        // ksm("$response \n");
      } else {
        if($info['http_code'] == 404) {
          ksm("Error, Please check the end point \n");
        } else {
          ksm("Error, HTTP Status Code : " . $info['http_code'] . "\n");
          ksm("Headers are ".$headers);
          ksm("Response are ".$response);
        }
      }
      curl_close($ch);
    }

    //    $popupFlag = 'filled';
    //    $field=$form_state->getValues();
    //    $jodi=$field['fname'];
    //    //echo "$name";
    //    $bloggs=$field['lname'];
    //    $az=$field['state'];
    //    $city=$field['city'];
    //    $gender=$field['birth_gender'];
    //    $dob=$field['field_dob'];
    //    $height = $field['height'];
    //    $weight = $field['weight'];
    //    $organizaton_type=$field['organization_type'];
    //    $organizaton_name=$field['organization_name'];
    //    $coach_lname=$field['coach_lname'];
    //    $sport=$field['sport'];
    //    $position=$field['position'];
    //    $instagram=$field['instagram'];
    //    $youtube=$field['youtube'];
    //
    //           $field  = array(
    //              'field_jodi'   => $jodi,
    //              'field_bloggs' =>  $bloggs,
    //              'field_az' =>  $az,
    //              'field_city' => $city,
    //              'field_birth_gender' => $gender,
    //              'field_dob' => $dob,
    //              'field_height' => $height,
    //              'field_weight' => $weight,
    //              'field_organization_type' => $organizaton_type,
    //              'field_organization_name' => $organizaton_name,
    //              'field_coach_lname' => $coach_lname,
    //              'field_sport' => $sport,
    //              'field_position' => $position,
    //              'field_instagram' => $instagram,
    //              'field_youtube' => $youtube,
    //          );
    //		    $current_user = \Drupal::currentUser()->id();
    //    $conn = Database::getConnection();
    //	$conn->insert('mydata')->fields(
    //	array(
    //              'field_jodi'   => $jodi,
    //              'field_bloggs' =>  $bloggs,
    //              'field_az' =>  $az,
    //              'field_city' => $city,
    //              'field_birth_gender' => $gender,
    //              'field_dob' => $dob,
    //              'field_height' => $height,
    //              'field_weight' => $weight,
    //              'field_organization_type' => $organizaton_type,
    //              'field_organization_name' => $organizaton_name,
    //              'field_coach_lname' => $coach_lname,
    //              'field_sport' => $sport,
    //              'field_position' => $position,
    //              'field_instagram' => $instagram,
    //              'field_youtube' => $youtube,
    //              'popup_flag' => $popupFlag,
    //              'uid' => $current_user,
    //          )
    //	)->execute();
    //       $form_state->setRedirect('acme_hello');
  }
}