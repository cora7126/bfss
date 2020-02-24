<?php
namespace Drupal\acme\Form;
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
class PopupForm extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mypopup_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
  $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
    $query2 = \Drupal::database()->select('user__field_state', 'ufs');
    $query2->addField('ufs', 'field_state_value');
    $query2->condition('entity_id', $current_user,'=');
    $results2 = $query2->execute()->fetchAssoc();
    $state = $results2['field_state_value'];
    $query1 = \Drupal::database()->select('user__field_last_name', 'ufln');
    $query1->addField('ufln', 'field_last_name_value');
    $query1->condition('entity_id', $current_user,'=');
    $results1 = $query1->execute()->fetchAssoc();
	$query3 = \Drupal::database()->select('user__field_first_name', 'uffn');
        $query3->addField('uffn', 'field_first_name_value');
        $query3->condition('entity_id', $current_user,'=');
        $results3 = $query3->execute()->fetchAssoc();
	$query4 = \Drupal::database()->select('user__field_state', 'fsv');
        $query4->addField('fsv', 'field_state_value');
        $query4->condition('entity_id', $current_user,'=');
        $results4 = $query4->execute()->fetchAssoc();
	if(!empty($results4)){
		$results4['field_state_value'] = '--AZ--';
	}
    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('mydata', 'm')
            ->condition('id', $_GET['num'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();
    }
	$form['#attributes'] = array('id' => 'popup_form_id');
	$form['welcome'] = array (
      '#type' => 'label',
      '#title' => 'Welcome '.$results3['field_first_name_value'].',to continue you must complete all the required fields below.',
	  '#attributes' => array('id'=>'welcome_label'),
      );
    $form['fname'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('First name'),
      '#default_value' => $results3['field_first_name_value'],
	  '#prefix'=>'<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Athletic Information</h3><div class=items_div>',
      );
    $form['lname'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Last name'),
      '#default_value' => $results1['field_last_name_value'],
      );
    $form['state'] = array(
    '#type' => 'select',
   '#options' => array(t('AL'), t('AK'), t('AZ'), t('AR'), t('CA'), t('CO'), t('CT'), t('DE'), t('DC'), t('FL'), t('GA'), t('HI'), t('ID'), t('IL'), t('IN'), t('IA'), t('KS'), t('KY'), t('LA'), t('ME'), t('MT'), t('NE'), t('NV'), t('NH'), t('NJ'), t('NM'), t('NY'), t('NC'), t('ND'), t('OH'), t('OR'), t('MD'), t('MA'), t('MI'), t('MN'), t('MS'), t('MO'), t('PA'), t('RI'), t('SC'), t('SD'), t('TN'), t('TX'), t('UT'), t('VT'), t('VA'), t('WA'), t('WV'), t('WI'), t('WY')),
      );
    $form['city'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('City'),
      '#default_value' => '',
	  '#suffix' => '</div></div>',
      );

     $form['organization_type'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Organization Type ---'), t('Public'), t('Private'), t('Other')),
	'#prefix' => '<div class="athlete_school"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
      );
     $form['organization_name'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Organization Name ---'), t('A'), t('B'), t('C')),
      );
     $form['coach_lname'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t("Coache's Last Name (Optional)"),
      '#default_value' => '',
      );
     $form['sport'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
     $form['position'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div">',
	  '#suffix' => '<a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos"><i class="fa fa-trash"></i>Remove Position</a></div></div></div></div>',
      );
	  $form['organization_type_club'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Organization Type ---'), t('Public'), t('Private'), t('Other')),
	'#prefix' => '<div class="athlete_school popup-athlete-school-hide previous_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
      );
     $form['organization_name_club'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Organization Name ---'), t('A'), t('B'), t('C')),
      );
     $form['coach_lname_club'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t("Coache's Last Name (Optional)"),
      '#default_value' => '',
      );
     $form['sport_club'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
     $form['position_club'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div">',
	  '#suffix' => '<a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos"><i class="fa fa-trash"></i>Remove Position</a></div></div></div>',
      );
	  $form['organization_type_uni'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Organization Type ---'), t('Public'), t('Private'), t('Other')),
	'#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
      );
     $form['organization_name_uni'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Organization Name ---'), t('A'), t('B'), t('C')),
      );
     $form['coach_lname_uni'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t("Coache's Last Name (Optional)"),
      '#default_value' => '',
      );
     $form['sport_uni'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
     $form['position_uni'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div">',
	  '#suffix' => '<a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos"><i class="fa fa-trash"></i>Remove Position</a></div></div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div><div class ="right_section">',
	 
      );
     $form['instagram'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Your Instagram Account(Optional)'),
      '#default_value' => '',
	  '#prefix' => '<div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Social Media<i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
      );
     $form['youtube'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Your Youtube/Video Channel(Optional)'),
	  '#suffix' => '</div></div></div>',
      '#default_value' => '',
      );
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
		'#prefix' =>'<div id="athlete_submit">',
		'#suffix' => '</div>',
        //'#value' => t('Submit'),
    ];
    
    // $form['#theme'] = 'my_form';
    return $form;
  }
  /**
    * {@inheritdoc}
    */
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
      $popupFlag = 'filled';
    $field=$form_state->getValues();
    $jodi=$field['fname'];
    //echo "$name";
    $bloggs=$field['lname'];
    $az=$field['state'];
    $city=$field['city'];
    $gender=$field['birth_gender'];
    $dob=$field['field_dob'];
    $height = $field['height'];
    $weight = $field['weight'];
    $organizaton_type=$field['organization_type'];
    $organizaton_name=$field['organization_name'];
    $coach_lname=$field['coach_lname'];
    $sport=$field['sport'];
    $position=$field['position'];
    $instagram=$field['instagram'];
    $youtube=$field['youtube'];
          // $field  = array(
              // 'field_jodi'   => $jodi,
              // 'field_bloggs' =>  $bloggs,
              // 'field_az' =>  $az,
              // 'field_city' => $city,
              // 'field_birth_gender' => $gender,
              // 'field_dob' => $dob,
              // 'field_height' => $height,
              // 'field_weight' => $weight,
              // 'field_organization_type' => $organizaton_type,
              // 'field_organization_name' => $organizaton_name,
              // 'field_coach_lname' => $coach_lname,
              // 'field_sport' => $sport,
              // 'field_position' => $position,
              // 'field_instagram' => $instagram,
              // 'field_youtube' => $youtube,
          // );
          // $query = \Drupal::database();
          // $query->update('mydata')
              // ->fields($field)
              // ->condition('id', $_GET['num'])
              // ->execute();
          // drupal_set_message("succesfully updated");
          // $form_state->setRedirect('mydata.display_table_controller_display');
      
           $field  = array(
              'field_jodi'   => $jodi,
              'field_bloggs' =>  $bloggs,
              'field_az' =>  $az,
              'field_city' => $city,
              'field_birth_gender' => $gender,
              'field_dob' => $dob,
              'field_height' => $height,
              'field_weight' => $weight,
              'field_organization_type' => $organizaton_type,
              'field_organization_name' => $organizaton_name,
              'field_coach_lname' => $coach_lname,
              'field_sport' => $sport,
              'field_position' => $position,
              'field_instagram' => $instagram,
              'field_youtube' => $youtube,
          );
           // $query = \Drupal::database();
           // $query ->insert('mydata')
               // ->fields($field)
               // ->execute();
           // drupal_set_message("succesfully saved");
           // $response = new RedirectResponse("/mydata/hello/table");
           // $response->send();
		    $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
	$conn->insert('mydata')->fields(
	array(
              'field_jodi'   => $jodi,
              'field_bloggs' =>  $bloggs,
              'field_az' =>  $az,
              'field_city' => $city,
              'field_birth_gender' => $gender,
              'field_dob' => $dob,
              'field_height' => $height,
              'field_weight' => $weight,
              'field_organization_type' => $organizaton_type,
              'field_organization_name' => $organizaton_name,
              'field_coach_lname' => $coach_lname,
              'field_sport' => $sport,
              'field_position' => $position,
              'field_instagram' => $instagram,
              'field_youtube' => $youtube,
              'popup_flag' => $popupFlag,
              'uid' => $current_user,
          )
	)->execute();
       $form_state->setRedirect('acme_hello');
     }
}