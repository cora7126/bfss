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
class ContributeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
     return 'edit_form';
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
    $query5 = \Drupal::database()->select('athlete_school', 'ats');
    $query5->fields('ats');
    $query5->condition('athlete_uid', $current_user,'=');
    $results5 = $query5->execute()->fetchAssoc(); 
	$query6 = \Drupal::database()->select('athlete_club', 'atc');
    $query6->fields('atc');
    $query6->condition('athlete_uid', $current_user,'=');
    $results6 = $query6->execute()->fetchAssoc();
	
	$form['prefix'] = "<div class=athlete_edit_class>";
	$form['suffix'] = "</div>";
//     echo print_r($results1['0']->athlete_school_name);
    // echo print_r($results5);die; 
    $form['fname'] = array(
      '#type' => 'textfield',
      //'#title' => t('Candidate Name:'),
      '#required' => TRUE,
      '#placeholder' => t('Firstname'),
       //'#default_values' => array(array('id')),
      '#default_value' => $results1['field_first_name_value'],
	  '#prefix'=>'<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Athletic Information</h3><div class=items_div>',
      );
    $form['lname'] = array(
      '#type' => 'textfield',
     // '#title' => t('Mobile Number:'),
      '#placeholder' => t('Lastname'),
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
    '#options' => array(t('State'), t('10"'), t('12"'), t('16"')),
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
    '#options' => array(t('Sex'),t('Male'), t('Female'), t('Other')),
      );
    $form['doj'] = array(
        '#type' => 'textfield',
        '#required' => TRUE,
        '#default_value' => substr($results3['field_date_value'],0,10),
        '#format' => 'm/d/Y',
        '#description' => t('i.e. 09/06/2016'),
		'#attributes' => array('disabled' => true),
        );
	$form['sophmore'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Grade'),
      '#default_value' => '',
      );
	  $form['twenty'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Graduation Year'),
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
	  $form['aboutme'] = array (
      '#type' => 'textarea',
      '#placeholder' => t('Tell us about yourself'),
      '#default_value' => '',
	  '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>About me</h3><div class=items_div>',
	  '#suffix' => '</div></div>',
      );
	  $form['instagram'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Your Instagram Account(Optional)'),
	  '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Social Media<i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
      '#default_value' => '',
      );
     $form['youtube'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Your Youtube/Video Channel(Optional)'),
	  '#suffix' => '</div></div>',
      '#default_value' => '',
      );
	  $form['organizationType'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Type'), t('Organization Type 1'), t('Organization Type 2'), t('Organization Type 3')),
	  '#prefix' => '<div class="athlete_school"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
      );
	
	  $form['organizationName'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Name'), t('Organization Name 1'), t('Organization Name 2'), t('Organization Name 3')),
              '#default_value' => '',
      );
		$form['coach'] = array (
      '#type' => 'textfield',
      '#placeholder' => t("Coache's Last Name"),
      '#default_value' => '',
      );
	  $form['sport'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
	  $form['position'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div">',
	  '#suffix' => '<a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos"><i class="fa fa-trash"></i>Remove Position</a></div>',
      );
	  $form['stats'] = array (
      '#type' => 'textarea',
      '#placeholder' => t('Add all personal stats'),
	  '#suffix' => '</div></div>',
      '#default_value' => '',
      );
    
    /*Add another organization 1 start*/
          
       $form['education_1'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Type'), t('Organization Type 1'), t('Organization Type 2'), t('Organization Type 3')),
	  '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
           '#default_value' => '',
      );
	
	  $form['schoolname_1'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Name'), t('Organization Name 1'), t('Organization Name 2'), t('Organization Name 3')),
                '#default_value' => '',
      );
		$form['coach_1'] = array (
      '#type' => 'textfield',
      '#placeholder' => t("Coache's Last Name"),
      '#default_value' => '',
      );
	  $form['sport_1'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
	  $form['position_1'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div">',
	  '#suffix' => '<a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos"><i class="fa fa-trash"></i>Remove Position</a></div>',
      );
	  $form['stats_1'] = array (
      '#type' => 'textarea',
      '#placeholder' => t('Add all personal stats'),
	  '#suffix' => '</div></div>',
      '#default_value' => '',
      );     
     /*Add another organization 1 END*/
          /*Add another organization 1 start*/
          
       $form['education_2'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Type'), t('Organization Type 1'), t('Organization Type 2'), t('Organization Type 3')),
	  '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
      );
	
	  $form['schoolname_2'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Name'), t('Organization Name 1'), t('Organization Name 2'), t('Organization Name 3')),
      );
		$form['coach_2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t("Coache's Last Name"),
      '#default_value' => '',
      );
	  $form['sport_2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
	  $form['position_2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div">',
	  '#suffix' => '<a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos"><i class="fa fa-trash"></i>Remove Position</a></div>',
      );
	  $form['stats_2'] = array (
      '#type' => 'textarea',
      '#placeholder' => t('Add all personal stats'),
	  '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div><div class ="right_section"><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website Photo</h3><div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul></div><div class=items_div>',
      '#default_value' => '',
      );     
     /*Add another organization 1 END*/
     
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
	   $form['school_web'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('School'),
      '#default_value' => $results5['athlete_school_name'],
		'#attributes' => array('disabled' => true),
	  '#prefix' => '</div></div><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website</h3><div class=items_div>',
      );
	   $form['sport_web'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
		'#attributes' => array('disabled' => true),
      '#default_value' => $results5['athlete_school_sport'],
      );
	   $form['name_web'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Pick a Name'),
      '#default_value' => '',
	  '#prefix' => '<div class="container-inline web_name">',
	  '#suffix' => '</div>',
	  '#attributes' => array('id'=>'name_1'),
      );
	  $form['label_1'] = array (
      '#type' => 'label',
      '#title' => '.bfsscience.com',
	  '#attributes' => array('id'=>'label_1'),
      );
	  $form['label_2'] = array (
      '#type' => 'label',
      '#title' => 'Create your unique website profile.<br> eg.jodibloggs.bfsscience.com.<br> Once published , this will become your permanent address and it can not be changed.<br>',
      );
	  $form['preview_1'] = array (
      '#type' => 'button',
      '#default_value' => 'Preview Changes',
      );
	  $form['web_visible_1'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Website Visibility'), t('on'), t('off')),
		'#suffix' => '</div>',
      );
   $form['school_web2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('school'),
      '#default_value' => $results6['athlete_club_name'],
	  '#attributes' => array('disabled' => true),
	  '#prefix' => '</div><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
      );
	   $form['sport_web2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('sport'),
      '#default_value' => $results6['athlete_club_sport'],
	  '#attributes' => array('disabled' => true),
      );
	   $form['name_web2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Pick a Name'),
      '#default_value' => '',
	  '#prefix' => '<div class="container-inline web_name">',
'#suffix' => '</div>',
	  '#attributes' => array('id'=>'name_2'),
      );
	  $form['label_12'] = array (
      '#type' => 'label',
      '#title' => '.bfsscience.com',
	  '#attributes' => array('id'=>'label_2'),
      );
	  $form['label_22'] = array (
      '#type' => 'label',
      '#title' => 'Create your unique website profile.<br> eg.jodibloggs.bfsscience.com.<br> Once published , this will become your permanent address and it can not be changed.<br>',
      );
	  $form['preview_12'] = array (
      '#type' => 'button',
      '#default_value' => 'Preview Changes',
      );
	  $form['web_visible_2'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Website Visibility'), t('on'), t('off')),
		'#suffix' => '</div></div></div></div>',
      );
   
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
//      $key = $form_state->getValue('organizationName');
//     $val = $form['organizationName']['#options'][$key];
//      
//	 echo '<pre>';print_r($form_state->getValues()['organizationName']);die;
     $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
//   $query = $conn->select('athlete_school', 'ats')
//    ->fields('ats', array('athlete_school_name', 'athlete_school_coach',))
//    ->condition('athlete_uid', $current_user,'=');;
        
    $query = \Drupal::database()->select('athlete_school', 'ats');
		$query->fields('ats');
		$query->condition('athlete_uid', $current_user,'=');
		$results = $query->execute()->fetchAll();
	$query_uni = \Drupal::database()->select('athlete_uni', 'ats');
		$query_uni->fields('ats');
		$query_uni->condition('athlete_uid', $current_user,'=');
		$results_uni = $query_uni->execute()->fetchAll();
	$query_club = \Drupal::database()->select('athlete_club', 'ats');
		$query_club->fields('ats');
		$query_club->condition('athlete_uid', $current_user,'=');
		$results_club = $query_club->execute()->fetchAll();
    // print_r($results);die;
	if(empty($results)){
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
        $conn->insert('athlete_school')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_school_name' => $form_state->getValue('organizationName'),
    'athlete_school_coach' => $form_state->getValue('coach'),
    'athlete_school_sport' => $form_state->getValue('sport'),
    'athlete_school_pos' => $form_state->getValue('position'),
    'athlete_school_stat' => $form_state->getValue('stats'),
    'athlete_school_type' => $form_state->getValue('organizationType'),
	)
	)->execute(); 
	$conn->insert('athlete_uni')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_uni_name' => $form_state->getValue('education_1'),
    'athlete_uni_coach' => $form_state->getValue('coach_1'),
    'athlete_uni_sport' => $form_state->getValue('sport_1'),
    'athlete_uni_pos' => $form_state->getValue('position_1'),
    'athlete_uni_stat' => $form_state->getValue('stats_1'),
	)
	)->execute(); 
	$conn->insert('athlete_club')->fields(
	array(
    'athlete_uid' => $current_user,
    'athlete_club_name' => $form_state->getValue('education_2'),
    'athlete_club_coach' => $form_state->getValue('coach_2'),
    'athlete_club_sport' => $form_state->getValue('sport_2'),
    'athlete_club_pos' => $form_state->getValue('position_2'),
    'athlete_club_stat' => $form_state->getValue('stats_2'),
	)
	)->execute();
	
	}	
   
	// $form_state->setRedirect('acme_hello');
 // return;
  }
}
?>