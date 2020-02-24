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
      '#prefix' => '<div class="main_header"><h1 style="margin-top: 10px;font-size:15px;margin-left: 20px;"><i class="fas fa-home" style="color: #f76907;margin-right: 5px;"></i><i class="fas fa-angle-right" style="font-weight:400;margin-right:5px;"></i><a href="/dashboard" class="edit_dash" style="margin-right:5px;font-weight: bold;">Dashboard</a><i class="fas fa-angle-right" style="font-weight:400;margin-right:5px;"></i><a class="edit_dash" style="font-weight: bold;">Support</a><i class="fas fa-angle-right" style="font-weight:400;margin-right:5px;"></i><a class="edit_dash" style="font-weight: bold;">Ticketing</a></h1><div class="edit_header" style="display:flex; padding:15px;background: #fffcd7;border: 1px solid grey;"><i class="fa fa-laptop edit_image" aria-hidden="true"></i><h2 style="margin-top:0px;margin-bottom:0px;"><span style="font-size:13px;font-weight:600;">SUBMIT A</span><br>Ticket</h2></div>',
      '#suffix' => '</div>',
    );
  $form[sub_header] = array( 
    '#prefix' => '<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class= "        fa fa-plus hide"></i></div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
         <a class="nav-link active" data-toggle="tab" href="#home">Open Ticket</a>
        </li>
        <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#menu1">Resolved Ticket</a>
        </li>
    </ul>
    </h3><div class=items_div>',
     '#suffix' => '</div>',
      );
   
   
    $form['subject'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('Subject'),
      '#prefix' => '<div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div><i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
//      '#default_value' => $results3['field_first_name_value'],
      );
    $form['comment'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Questions/Comments'),
//      '#default_value' => $results1['field_last_name_value'],
      );
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
		'#prefix' =>'<div id="create_tct_submit">',
		'#suffix' => '</div></div>',
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
//          if (strlen($form_state->getValue('mobile_number')) < 10 ) {
//            $form_state->setErrorByName('mobile_number', $this->t('your mobile number must in 10 digits'));
//           }
    // parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//      $popupFlag = 'filled';
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