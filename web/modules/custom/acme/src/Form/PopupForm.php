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
    /*$query2 = \Drupal::database()->select('user__field_state', 'ufs');
    $query2->addField('ufs', 'field_state_value');
    $query2->condition('entity_id', $current_user,'=');
    $results2 = $query2->execute()->fetchAssoc();
    $state = $results2['field_state_value'];*/
	
	$query18 = \Drupal::database()->select('mydata', 'md');
    $query18->fields('md');
    $query18->condition('uid', $current_user, '=');
    $results18 = $query18->execute()->fetchAssoc();
	

	if(empty($results18)){
		$cityquery1 = \Drupal::database()->select('user__field_state', 'ufln');
		$cityquery1->addField('ufln', 'field_state_value');
		$cityquery1->condition('entity_id', $current_user, '=');
		$cityresults1 = $cityquery1->execute()->fetchAssoc();
		$state=$cityresults1['field_state_value'];
	}else{
		$state=$results18['field_az'];
	}
	
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
	  '#prefix'=>'<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Athletic Information<i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
      );
    $form['lname'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Last name'),
      '#default_value' => $results1['field_last_name_value'],
      );
	  $st=getStates();
    $form['state'] = array(
    '#type' => 'select',
   '#options' => $st,
   '#default_value' => $state,
      );
    $form['city'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('City'),
      '#default_value' => $results18['field_city'],
	  '#suffix' => '</div></div>',
      );
	 $orgtype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
     $form['organization_type'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => $orgtype,
	'#prefix' => '<div class="athlete_school"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University<i class="far fa-trash-alt right-icon delete_icon" aria-hidden="true"></i></h3><div class=items_div>',
      );
    $form['organizationName'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Orginization Name'),
      //'#description' => 'Select the desired pizza crust size.',
       '#required' => TRUE,
      //'#default_value' => array_search($results5['athlete_school_name'], $orgname),
      '#default_value' => $orgname_1,
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
	   '#required' => TRUE,
      );
     $form['position'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div">',
	  '#suffix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
	   '#required' => TRUE,
      );
	  $form['position2'] = array (
		  '#type' => 'textfield',
		  '#placeholder' => t('Position'),
		  '#default_value' => '',
		  '#attributes' => array('style'=>'display:none'),
		  // '#prefix' => '<div class ="pos_first_1"',
		  // '#suffix' => '</div>',
	 );

    //here1111

	  $form['position3'] = array (
		  '#type' => 'textfield',
		  '#placeholder' => t('Position'),
		  '#default_value' => '',
		  '#attributes' => array('class' =>'pos_hidden_first_2','style'=>'display:none'),
		  // '#prefix' => '<div class ="pos_first_2"',
		   '#suffix' => '</div></div></div></div>',
		  );
	  $orgtype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
	  $form['education_1'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => $orgtype,
	'#prefix' => '<div class="athlete_school popup-athlete-school-hide previous_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i  class="fa fa-trash right-icon delete_icon previous_delete" aria-hidden="true"></i><div class=items_div>',
      );
      $form['schoolname_1'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
		'#placeholder' => t('Orginization Name'),
        //'#description' => 'Select the desired pizza crust size.',
      /*  '#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        //'#default_value' => array_search($results12['athlete_uni_name'], $orgname2),
        );
     $form['coach_1'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t("Coache's Last Name (Optional)"),
      '#default_value' => '',
      );
     $form['sport_1'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
     $form['position_1'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div_third">',
	  '#suffix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div></div></div>',
      );
	   $form['position_12'] = array (
		  '#type' => 'textfield',
		  '#placeholder' => t('Position'),
		  '#default_value' => $results6['athlete_uni_pos2'],
		  '#attributes' => array('class' =>'pos_hidden_second_1','style'=>'display:none'),
		  // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
		  // '#suffix' => '</div>',
		  );
		  $form['position_13'] = array (
		  '#type' => 'textfield',
		  '#placeholder' => t('Position'),
		  '#default_value' => $results6['athlete_uni_pos3'],
		  '#attributes' => array('class' =>'pos_hidden_first_2','style'=>'display:none'),
		  // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
		  // '#suffix' => '</div>',
		  );
		  $orgtype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
	  $form['education_2'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => $orgtype,
	'#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i><div class=items_div>',
      );
     $form['schoolname_2'] = array(
        '#type' => 'textfield',
		'#placeholder' => t('Orginization Name'),
        /*'#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        '#default_value' => $orgname_3,
        );
     $form['coach_2'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t("Coache's Last Name (Optional)"),
      '#default_value' => '',
      );
     $form['sport_2'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Sport'),
      '#default_value' => '',
      );
     $form['position_2'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div_second">',
	  '#suffix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a></div></div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div><div class ="right_section">',
	 
      );
	  
	  $form['position_22'] = array (
		  '#type' => 'textfield',
		  '#placeholder' => t('Position'),
		  '#default_value' => $results16['athlete_school_pos2'],
		  '#attributes' => array('class' =>'pos_hidden_third_1','style'=>'display:none'),
		  // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
		  // '#suffix' => '</div>',
		  );
		  
		  $form['position_23'] = array (
		  '#type' => 'textfield',
		  '#placeholder' => t('Position'),
		  '#default_value' => $results16['athlete_school_pos3'],
		  '#attributes' => array('class' =>'pos_hidden_first_2','style'=>'display:none'),
		  // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
		  // '#suffix' => '</div>',
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
    '#value' => 'FINISH',
		'#prefix' =>'<div class="left_section popup_left_section finish-btn"><div class="athlete_submit">',
		'#suffix' => '</div></div>',
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
	
	$seltype1 = $form_state->getValue('organization_type');
	$selname1 = $form_state->getValue('organization_name'); 
	$seltype2 = $form_state->getValue('education_1');
	$selname2 = $form_state->getValue('schoolname_1'); 
	$seltype3 = $form_state->getValue('education_2');
	$selname3 = $form_state->getValue('schoolname_2');
	$selstate = $form_state->getValue('az');
	$seltypeval1 = $form['organization_type']['#options'][$seltype1];
	$selnameval1 = $form['organization_name']['#options'][$selname1];
	$seltypeval2 = $form['education_1']['#options'][$seltype2];
	$selnameval2 = $form['schoolname_1']['#options'][$selname2];
	$seltypeval3 = $form['education_2']['#options'][$seltype3];
	$selnameval3 = $form['schoolname_2']['#options'][$selname3];
	// echo $selnameval1; echo $setypeval1;die;
        $current_user = \Drupal::currentUser()->id();
	$query_mydata = \Drupal::database()->select('mydata', 'msd');
		$query_mydata->fields('msd');
		$query_mydata->condition('uid', $current_user,'=');
		$results_mydata = $query_mydata->execute()->fetchAll();
//                echo '<pre>'; print_r($results_mydata);die;
                
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
           // $query ->insert('mydata')
               // ->fields($field)
               // ->execute();
           // drupal_set_message("succesfully saved");
           // $response = new RedirectResponse("/mydata/hello/table");
           // $response->send();
            $current_user = \Drupal::currentUser()->id();
            $conn = Database::getConnection(); 
            if(empty($results_mydata)){
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
	}else{
		$conn->update('mydata')
						->condition('uid',$current_user,'=')
						->fields(
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
							)
						->execute();
	}
	
	if($seltype1 != 0 && $selname1 != 0){
		$conn->insert('athlete_school')->fields(
			array(
			'athlete_uid' => $current_user,
			'athlete_school_name' => $selnameval1,
			'athlete_school_coach' => $form_state->getValue('coach_lname'),
			'athlete_school_sport' => $form_state->getValue('sport'),
			'athlete_school_pos' => $form_state->getValue('position'),
			'athlete_school_pos2' => $form_state->getValue('position2'),
			'athlete_school_pos3' => $form_state->getValue('position3'),
			'athlete_school_stat' => $form_state->getValue('stats'),
			'athlete_school_type' => $seltypeval1,
			)
		)->execute();
	}
	if($seltype3 != 0 && $selname3 != 0){
		if(!empty($selnameval3) && !empty($seltypeval3)){
				$conn->insert('athlete_club')->fields(
					array(
					'athlete_uid' => $current_user,
					'athlete_club_name' => $selnameval3,
					'athlete_club_coach' => $form_state->getValue('coach_2'),
					'athlete_club_sport' => $form_state->getValue('sport_2'),
					'athlete_club_pos' => $form_state->getValue('position_2'),
					'athlete_school_pos2' => $form_state->getValue('position_22'),
					'athlete_school_pos3' => $form_state->getValue('position_23'),
					'athlete_club_stat' => $form_state->getValue('stats_2'),
					'athlete_school_type' => $seltypeval3,
					)
				)->execute();
		}
	}	
	if($seltype2 != 0 && $selname2 != 0){
				$conn->insert('athlete_uni')->fields(
					array(
					'athlete_uid' => $current_user,
					'athlete_uni_name' => $selnameval2,
					'athlete_uni_coach' => $form_state->getValue('coach_1'),
					'athlete_uni_sport' => $form_state->getValue('sport_1'),
					'athlete_uni_pos' => $form_state->getValue('position_1'),
					'athlete_uni_pos2' => $form_state->getValue('position_12'),
					'athlete_uni_pos3' => $form_state->getValue('position_13'),
					'athlete_uni_stat' => $form_state->getValue('stats_1'),
					'athlete_uni_type' => $seltypeval2,
					)
				)->execute(); 
	}
	$conn->insert('athlete_social')->fields(
					array(
					'athlete_uid' => $current_user,
					'athlete_social_1' => $instagram ,
					'athlete_social_2' => $youtube,
					)
				)->execute(); 
	
       $form_state->setRedirect('acme_hello');
     }
	 function getStates() {
	return $st=array(
      'AL'=>  t('AL'),
      'AK'=>  t('AK'),
      'AZ'=>  t('AZ'),
       'AR'=> t('AR'),
      'CA'=>  t('CA'),
      'CO'=>   t('CO'),
      'CT'=>    t('CT'),
       'DE'=>    t('DE'),
     'DC'=>      t('DC'),
       'FL'=>    t('FL'),
        'GA'=>     t('GA'),
   'HI'=>     t('HI'),
     'ID'=>    t('ID'),
       'IL'=>   t('IL'),
       'IN'=> t('IN'),
       'IA'=> t('IA'),
      'KS'=>  t('KS'),
       'KY'=> t('KY'),
       'LA'=> t('LA'),
       'ME'=> t('ME'),
       'MT'=> t('MT'),
       'NE'=> t('NE'),
       'NV'=> t('NV'),
       'NH'=> t('NH'),
       'NJ'=> t('NJ'),
       'NM'=> t('NM'),
       'NY'=> t('NY'),
       'NC'=> t('NC'),
        'ND'=>t('ND'),
       'OH'=> t('OH'),
        'OR'=>t('OR'),
       'MD'=> t('MD'),
       'MA'=> t('MA'),
       'MI'=> t('MI'),
        'MN'=>t('MN'),
        'MS'=>t('MS'),
       'MO'=> t('MO'),
       'PA'=> t('PA'),
       'RI'=> t('RI'),
       'SC'=> t('SC'),
        'SD'=>t('SD'),
       'TN'=> t('TN'),
        'TX'=>  t('TX'),
         'UT'=> t('UT'),
        'VT'=>  t('VT'),
        'VA'=>  t('VA'),
         'WA'=> t('WA'),
         'WV'=> t('WV'),
        'WI'=>  t('WI'),
        'WY'=>  t('WY'));
	
  /*return [
    'AL' => 'Alabama',
    'AK' => 'Alaska',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'DC' => 'District of Columbia',
    'FL' => 'Florida',
    'GA' => 'Georgia',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
	'MD'=>'Maryland',
	'MA'=>'Massachusetts',
	'MI'=>'Michigan',
	'MN'=>'Minnesota',
	'MS'=>'Mississippi',
	'MO'=>'Missouri',
	'PA'=>'Pennsylvania',
	'RI'=>'Rhode Island',
	'SC'=>'South Carolina',
	'SD'=>'South Dakota',
	'TN'=>'Tennessee',
	'TX'=>'Texas',
	'UT'=>'Utah',
	'VT'=>'Vermont',
	'VA'=>'Virginia',
	'WA'=>'Washington',
	'WV'=>'West Virginia',
	'WI'=>'Wisconsin',
	'WY'=>'Wyoming',
  ];*/
}
}

