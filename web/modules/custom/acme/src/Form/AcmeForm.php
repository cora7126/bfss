<?php
namespace Drupal\acme\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class AcmeForm extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mydata_form';
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
    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('mydata', 'm')
            ->condition('id', $_GET['num'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();
    }
	$form['#attributes'] = array('id' => 'form_id');
    $form['fname'] = array(
      '#type' => 'textfield',
      //'#title' => t('Candidate Name:'),
      '#required' => TRUE,
      '#placeholder' => t('First name'),
       //'#default_values' => array(array('id')),
      '#default_value' => $results1['field_last_name_value'],
      );
    $form['lname'] = array(
      '#type' => 'textfield',
     // '#title' => t('Mobile Number:'),
      '#placeholder' => t('Last name'),
      '#default_value' => $results1['field_last_name_value'],
      );
    $form['state'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
//    '#options' => array(t('--- AZ ---'), t('AL'), t('AK'), t('AZ'), t('AR'), t('CA'), t('CO'), t('CT'), t('DE'), t('DC'), t('FL'), t('GA'), t('HI'), t('ID'), t('IL'), t('IN'), t('IA'), t('KS'), t('KY'), t('LA'), t('ME'), t('MT'), t('NE'), t('NV'), t('NH'), t('NJ'), t('NM'), t('NY'), t('NC'), t('ND'), t('OH'), t('OR'), t('MD'), t('MA'), t('MI'), t('MN'), t('MS'), t('MO'), t('PA'), t('RI'), t('SC'), t('SD'), t('TN'), t('TX'), t('UT'), t('VT'), t('VA'), t('WA'), t('WV'), t('WI'), t('WY')),
    '#default_value' => $results1['field_last_name_value'],
      );
    $form['city'] = array(
      '#type' => 'textfield',
      //'#title' => t('City'),
      '#required' => TRUE,
      '#placeholder' => t('City'),
      '#default_value' => '',
      );

    $form['birth_gender'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Birth Gender ---'), t('Male'), t('Female'), t('Other')),
      );
    $form['field_dob'] = array(
        '#type' => 'date',
        //'#title' => 'Enter Your Date of Birth',
        '#required' => TRUE,
        '#default_value' => array('month' => 9, 'day' => 6, 'year' => 1962),
        '#format' => 'm/d/Y',
        '#placeholder' => t('DOB'),
        '#description' => t('i.e. 09/06/2016'),
        );
    $form['height'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Height in Inches'),
      '#default_value' => '',
      );
     $form['weight'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Weight in Pounds'),
      '#default_value' => '',
      );
     $form['organization_type'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- Organization Type ---'), t('Public'), t('Private'), t('Other')),
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
      );
     $form['instagram'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Your Instagram Account(Optional)'),
      '#default_value' => '',
      );
     $form['youtube'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Your Youtube/Video Channel(Optional)'),
      '#default_value' => '',
      );
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
        //'#value' => t('Submit'),
		'#ajax' => [
        'callback' => [$this, 'submitForm'],
        'wrapper' => 'complete-edit-wrapper'
      ]
    ];
    $message = [
    '#theme' => 'status_messages',
    '#message_list' => drupal_get_messages(), //\Drupal::messenger()->all(),
    '#status_headings' => [
      'status' => t('Status message'),
      'error' => t('Error message'),
      'warning' => t('Warning message'),
    ],
  ];
     $form['messages'] = [
    '#type' => 'item',
    '#markup' => $messages,
    '#weight' => 20,
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
echo "here"; die;
    $field=$form_state->getValues();
    $jodi=$field['jodi'];
    //echo "$name";
    $bloggs=$field['bloggs'];
    $az=$field['az'];
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
    if (isset($_GET['num'])) {
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
          $query = \Drupal::database();
          $query->update('mydata')
              ->fields($field)
              ->condition('id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('mydata.display_table_controller_display');
      }
       else
       {
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
           $query = \Drupal::database();
           $query ->insert('mydata')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");
           $response = new RedirectResponse("/mydata/hello/table");
           $response->send();
       }
     }
}