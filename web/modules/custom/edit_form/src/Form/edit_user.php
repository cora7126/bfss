<?php
/**
 * @file
 * Contains \Drupal\edit_form\Form\ContributeForm.
 */

namespace Drupal\edit_form\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

/**
 * Contribute form.
 */
class edit_user extends FormBase {	
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
     return 'edit_user';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	$current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
    $query1 = \Drupal::database()->select('user__field_first_name', 'ufln');
        $query1->addField('ufln', 'field_first_name_value');
        $query1->condition('entity_id', $current_user,'=');
        $results1 = $query1->execute()->fetchAssoc(); 
	$query2 = \Drupal::database()->select('user__field_last_name', 'ufln2');
        $query2->addField('ufln2', 'field_last_name_value');
        $query2->condition('entity_id', $current_user,'=');
        $results2 = $query2->execute()->fetchAssoc();
	$query3 = \Drupal::database()->select('user__field_date', 'ufln3');
        $query3->addField('ufln3', 'field_date_value');
        $query3->condition('entity_id', $current_user,'=');
        $results3 = $query3->execute()->fetchAssoc();
	$query4 = \Drupal::database()->select('users_field_data', 'ufln4');
        $query4->addField('ufln4', 'mail');
        $query4->condition('uid', $current_user,'=');
        $results4 = $query4->execute()->fetchAssoc();
	$form['prefix'] = "<div class=athlete_edit_class>";
	$form['suffix'] = "</div>";
    $form['fname'] = array(
      '#type' => 'textfield',
      //'#title' => t('Candidate Name:'),
      '#required' => TRUE,
      '#placeholder' => t('Jodi'),
       //'#default_values' => array(array('id')),
      '#default_value' => $results1['field_first_name_value'],
	  '#prefix'=>'<div class="left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Login Information</h3><div class=items_div>',
      );
    $form['lname'] = array(
      '#type' => 'textfield',
     // '#title' => t('Mobile Number:'),
      '#placeholder' => t('Bloggs'),
      '#default_value' => $results2['field_last_name_value'],
      );
	$form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Email'),
      '#default_value' => $results4['mail'],
      );
    $form['az'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => array(t('--- AZ ---'), t('10"'), t('12"'), t('16"')),
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
    '#options' => array(t('Male'), t('Female'), t('Other')),
      );
    $form['doj'] = array(
        '#type' => 'textfield',
        //'#title' => 'Enter Your Date of Birth',
        '#required' => TRUE,
        '#default_value' => substr($results3['field_date_value'],0,10),
        '#format' => 'm/d/Y',
        '#description' => t('i.e. 09/06/2016'),
        );
	$form['sophmore'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Sophmore'),
      '#default_value' => '',
      );
	  $form['twenty'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('2020'),
      '#default_value' => '',
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
	  '#suffix' => '</div></div>',
      );
	  
	  $form['stats'] = array (
      '#type' => 'textarea',
	  '#suffix' => '</div></div><a class="add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div></div><div class ="right_section"><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website Photo</h3><div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul></div><div class=items_div>',
      '#default_value' => '',
      );
     
	  $form['image_athlete'] = [
                            '#type' => 'managed_file',
                            '#upload_validators' => [
                              'file_validate_extensions' => ['gif png jpg jpeg'],
                              'file_validate_size' => [25600000],
                            ],
                            '#theme' => 'image_widget',
                            '#preview_image_style' => 'medium',
                            '#upload_location' => 'public://',
                            '#required' => FALSE,
							'#prefix' => '</div>',
							'#suffix' =>'<div class="action_bttn"><span>Action</span><ul><li>Remove</li></ul></div></div></div>',
                          ];				  

	  
	
   
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
		'#prefix' =>'<div id="athlete_submit">',
		'#suffix' => '</div>',
        //'#value' => t('Submit'),
    ];
    // $form['#theme'] = 'athlete_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate video URL.
    // if (!UrlHelper::isValid($form_state->getValue('video'), TRUE)) {
      // $form_state->setErrorByName('video', $this->t("The video url '%url' is invalid.", array('%url' => $form_state->getValue('video'))));
    // }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    // foreach ($form_state->getValues() as $key => $value) {
      // drupal_set_message($key . ': ' . $value);
    // }
	 //echo '<pre>';print_r($form_state->getValues()['jodi']);die;
      
	 $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
	$conn->insert('athlete_info')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_email' => $form_state->getValue('email'),
    'athlete_state' => $form_state->getValue('az'),
    'athlete_city' => $form_state->getValue('city'),
    'athlete_coach' => $form_state->getValue('coach'),
    'athlete_year' => $form_state->getValue('doj'),
    'field_height' => $form_state->getValue('height'),
    'field_weight' => $form_state->getValue('weight'),
    'popup_flag' => $popupFlag,
    // 'sender_subject' => $form_state->getValue('recipient'),
    // 'sender_message' => $form_state->getValue('message'),
	)
	)->execute();
	
	$conn->insert('athlete_about')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_about_me' => $form_state->getValue('aboutme'),
	)
	)->execute();
	$conn->insert('athlete_social')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_social_1' => $form_state->getValue('instagram'),
    'athlete_social_2' => $form_state->getValue('youtube'),
	)
	)->execute();
	$conn->insert('athlete_web')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_web_name' => $form_state->getValue('name_web'),
    'athlete_web_visibility' => $form_state->getValue('web_visible_1'),
	)
	)->execute();
	$conn->insert('athlete_addweb')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_addweb_name' => $form_state->getValue('name_web2'),
    'athlete_addweb_visibility' => $form_state->getValue('web_visible_2'),
	)
	)->execute();
	
	// $form_state->setRedirect('acme_hello');
 // return;
  }
}
?>