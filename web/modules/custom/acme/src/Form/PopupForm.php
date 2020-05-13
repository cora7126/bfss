<?php
namespace Drupal\acme\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\InvokeCommand;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use  \Drupal\user\Entity\User;
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
	
	$vid = 'sports';
$terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
$sports_arr=array();
foreach ($terms as $term) {
 $sports_arr[$term->tid] = $term->name;
}
	
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


     /**
    *ORGANIZATIONS DATA GET
    */
    $athlete_school = $this->Get_Data_From_Tables('athlete_school','ats',$current_user); //FOR ORG-1
    $athlete_club = $this->Get_Data_From_Tables('athlete_club','aclub',$current_user); //FOR ORG-2
    $athlete_uni = $this->Get_Data_From_Tables('athlete_uni','atc',$current_user); //FOR ORG-3

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
	  '#attributes' => array('readonly' => 'readonly'),
	  '#prefix'=>'<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Athletic Information<i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
      );
    $form['lname'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Last name'),
	  '#attributes' => array('readonly' => 'readonly'),
      '#default_value' => $results1['field_last_name_value'],
      );
	  $st=getStates();
    $form['state'] = array(
    '#type' => 'select',
   '#options' => $st,
   //'#default_value' => $state,
      );
    $form['city'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('City'),
      //'#default_value' => $results18['field_city'],
	//  '#suffix' => '</div></div>',
      );
	  
	  $form['sex'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
      '#options' => array(
        t('Gender'),
        t('Male'),
        t('Female'),
        t('Other')),
      //'#default_value' => $results7['athlete_state'],
	  '#required' => TRUE,
      );
	//  print DatePopup::class;die;
    $form['doj'] = array(
      //'#title' => 'Date of Birth',
      '#placeholder' => 'DOB: (MM/DD/YY)',
      '#type' => 'textfield',
      //'#type' => 'date_popup',
     // '#attributes' => ['class' => ['container-inline']],
     //'#attributes' => ['class' => 'date_popup'],
	 //'#attributes' => array('class' => 'date_popup'),
	 '#attributes' => array('id' => array('datepicker')),
      '#required' => true,
      '#default_value' => substr($results3['field_date_value'], 0, 10),
      '#format' => 'm/d/Y',
      '#description' => t('i.e. 09/06/2016'),
	  '#required' => TRUE,
      //'#attributes' => array('disabled' => true),
      );

    $form['gradyear'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Graduation Year'),
     // '#default_value' => $results7['athlete_year'],
	  '#required' => TRUE,
      );
    $form['height'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Height in Inches'),
      //'#default_value' => $results7['field_height'],
	  '#required' => TRUE,
      );
    $form['weight'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Weight in Pounds'),
      //'#default_value' => $results7['field_weight'],
      '#suffix' => '</div></div>',
	  '#required' => TRUE,
      );
	  
	    /*
    *ORGANIZATION - 1
    */
    
    $type_org_1 = isset($athlete_school['athlete_school_type']) ? $athlete_school['athlete_school_type'] : 'school';
    #$orgnames_op1 = $this->Get_Org_Name_For_default($type_org_1);
    $form_state_values = $form_state->getValues();
    $type__1 = isset($type_org_1)?$type_org_1:'school';
    $type_organization_1 = isset($form_state_values['organizationType'])?$form_state_values['organizationType']:$type__1;
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
  '#ajax' => [
          'callback' => '::OrgNamesAjaxCallback_one', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
        ]
 
      );
   
    $form['organizationName'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Orginization Name'),
      '#autocomplete_route_name' => 'edit_form.autocomplete',
      '#autocomplete_route_parameters' => array('field_name' => $type_organization_1, 'count' => 10), 
      '#prefix' => '<div id="edit-output" class="orgtextarea1">',
      '#suffix' => '</div>',
      '#default_value' => $athlete_school['athlete_school_name'] ,
      #'#attributes' => ['id' => 'label_1', 'class' => array['weblabel']],
    );
     // $form['coach_lname'] = array (
     //  '#type' => 'textfield',
     //  //'#title' => ('Height'),
     //  '#placeholder' => t("Coache's Last Name (Optional)"),
     //  '#default_value' => '',
     //  );
	  
    
	  $form['sport'] = array(
    //'#title' => t('az'),
    '#type' => 'select',
    //'#description' => 'Select the desired pizza crust size.',
    '#options' => $sports_arr,
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
		//   '#suffix' => '</div></div></div></div>',
		  );
		  
		$form['stats'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Add all personal stats'),
      //'#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
      '#suffix' => '</div></div></div></div>',
      //'#default_value' => $orgstats_1,
      );

    /*
    *ORGANIZATION - 2
    */
    $type_org_2 =  isset($athlete_club['athlete_school_type']) ? $athlete_club['athlete_school_type'] : 'school';
    #$orgnames_op = $this->Get_Org_Name_For_default($type_org_2);
    $type__2 = isset($type_org_2)?$type_org_2:'school';
    $type_organization_2 = isset($form_state_values['education_1'])?$form_state_values['education_1']:$type__2; 

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
      '#ajax' => [
          'callback' => '::OrgNamesAjaxCallback_two', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output-1', // This element is updated with this AJAX callback.
        ]
      );

      
      $form['schoolname_1'] = [
            '#type' => 'textfield',
            '#placeholder' => t('Orginization Name'),
            '#autocomplete_route_name' => 'edit_form.autocomplete',
            '#autocomplete_route_parameters' => array('field_name' => 'school', 'count' => 10), 
            '#prefix' => '<div id="edit-output-1" class="org-2">',
            '#suffix' => '</div>',
            '#default_value' => $athlete_club['athlete_club_name'],
      ];
     // $form['coach_1'] = array (
     //  '#type' => 'textfield',
     //  //'#title' => ('Height'),
     //  '#placeholder' => t("Coache's Last Name (Optional)"),
     //  '#default_value' => '',
     //  );
     $form['sport_1'] = array (
      '#type' => 'select',
      //'#title' => ('Height'),
      '#options' => $sports_arr,
      );
     $form['position_1'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div_third">',
	  '#suffix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a>',
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
		//  '#suffix' => '</div></div></div>',
		  );
		  
		  $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        //'#prefix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div>',
        '#suffix' => '</div></div></div></div>',
        //'#default_value' => $orgstats_2,
        );
   

		$orgtype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University')
    );
	  $form['education_2'] = array(
    '#type' => 'select',
    '#options' => $orgtype,
	  '#prefix' => '<div class="athlete_school popup-athlete-school-hide last_athlete">
        <div class = "athlete_left">
        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i>
        <div class=items_div>',
         '#ajax' => [
          'callback' => '::OrgNamesAjaxCallback_three', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output-2', // This element is updated with this AJAX callback.
        ]
    );
          $form['schoolname_2'] = [
            '#type' => 'textfield',
            '#placeholder' => t('Orginization Name'),
            '#autocomplete_route_name' => 'edit_form.autocomplete',
            '#autocomplete_route_parameters' => array('field_name' => 'school', 'count' => 10), 
            '#prefix' => '<div id="edit-output-2" class="org-3">',
            '#suffix' => '</div>',
            '#default_value' => $athlete_uni['athlete_uni_name'],
        ];

     // $form['coach_2'] = array (
     //  '#type' => 'textfield',
     //  //'#title' => ('Height'),
     //  '#placeholder' => t("Coache's Last Name (Optional)"),
     //  '#default_value' => '',
     //  );
     $form['sport_2'] = array (
       '#type' => 'select',
      //'#title' => ('Height'),
      '#options' => $sports_arr,
      );
     $form['position_2'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Position'),
      '#default_value' => '',
	  '#prefix' => '<div class="add_pos_div_second">',
	  '#suffix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a>',
	 
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
		//   '#suffix' => '</div></div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div><div class ="right_section">',
		  );
		  $form['stats_2'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
       // '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => $orgstats_3,
        //'#prefix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a></div>',
		 '#suffix' => '</div></div></div></div>
     ',
        );

     $form['instagram'] = array (
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Your Instagram Account(Optional)'),
      '#default_value' => '',
	  '#prefix' => '<a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div><div class ="right_section"><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Social Media<i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
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
    $gender=$field['sex'];
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


	$selstate = $form_state->getValue('state');
	$statevalue = $form['az']['#options'][$selstate];
	
	$conn = Database::getConnection(); 
	/* BASIC INFORMATION UPDATE */
	 $conn->update('user__field_first_name')->condition('entity_id', $current_user, '=')->fields(array('field_first_name_value' => $form_state->getValue('fname'), ))->execute();

    $conn->update('user__field_last_name')->condition('entity_id', $current_user, '=')->fields(array('field_last_name_value' => $form_state->getValue('lname'), ))->execute();
	 $conn->update('user__field_state')->condition('entity_id', $current_user, '=')->fields(array('field_state_value' => $az, ))->execute();
	 
	 $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
    
    $query3->addField('ufln3', 'field_date_value');
    $query3->condition('entity_id', $current_user, '=');
    $results3 = $query3->execute()->fetchAssoc();
	$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if (empty($results3)) {
		
        $conn->insert('user__field_date')->fields(array(
        'entity_id' => $current_user,
        'bundle' => 'user',
        'revision_id' => $current_user,
        'delta' => 0,
        'langcode' => $lang_code,
        'field_date_value' => $form_state->getValue('doj'),
        ))->execute();
    }
    else {
        $conn->update('user__field_date')->condition('entity_id', $current_user, '=')->fields(array(
        'field_date_value' => $form_state->getValue('doj'),
        ))->execute();
    }
	 
	/* BASIC INFORMATION UPDATE END */

	/* ==== SOCIAL ACCOUNT INFO ===*/
	$conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('instagram'),
        'athlete_web_visibility' => $form_state->getValue('youtube'),
        ))->execute();
	/* ==== SOCIAL ACCOUNT INFO END ===*/


	/* ATHLETE INFO DETAIL ====*/
	 $conn->insert('athlete_info')->fields(array(
        'athlete_uid' => $current_user,
         'athlete_state' => $az,
        'athlete_city' => $form_state->getValue('city'),
        'athlete_year' => $form_state->getValue('gradyear'),
        'field_height' => $form_state->getValue('height'),
        'field_weight' => $form_state->getValue('weight'),
        'popup_flag' => $popupFlag,
        ))->execute();
	/* ATHLETE INFO DETAIL ====*/

            $current_user = \Drupal::currentUser()->id();
           
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
	
	
		
	  $query_social = \Drupal::database()->select('athlete_social', 'ascc');
    $query_social->fields('ascc');
    $query_social->condition('athlete_uid', $current_user, '=');
    $results_social = $query_social->execute()->fetchAll();
	 if (empty($results_social)) {
      $conn->insert('athlete_social')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_social_1' => $form_state->getValue('instagram'),
        'athlete_social_2' => $form_state->getValue('youtube'),
        ))->execute();
    } else {
      $conn->update('athlete_social')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_social_1' => $form_state->getValue('instagram'),
        'athlete_social_2' => $form_state->getValue('youtube'),
        ))->execute();
    } 
			
    /**
      *ORGANIZATION DATA SAVE AND UPDATE [START FROM HERE]
    */
    
      //ORGANIZATIONS DATA GET
      
      $athlete_club = $this->Get_Data_From_Tables('athlete_club','aclub',$current_user); //FOR ORG-1
      $athlete_school = $this->Get_Data_From_Tables('athlete_school','ats',$current_user); //FOR ORG-2
      $athlete_uni = $this->Get_Data_From_Tables('athlete_uni','atc',$current_user); //FOR ORG-3
      //ORG - 1
      // print_r($athlete_school);
     //  die;
    if(empty($athlete_school)){
    $FIELDS_athlete_school = [
    'athlete_uid' => $current_user,
    ' athlete_school_name' => !empty($form_state->getValue('organizationName'))?$form_state->getValue('organizationName') : '',
    #'athlete_school_coach' => !empty($form_state->getValue('coach')) ? $form_state->getValue('coach') : '',
    'athlete_school_sport' => !empty($form_state->getValue('sport')) ? $form_state->getValue('sport') : '',
    'athlete_school_pos' => !empty($form_state->getValue('position'))? $form_state->getValue('position') : '',
    'athlete_school_pos2' => !empty($form_state->getValue('position2'))? $form_state->getValue('position2') : '',
    'athlete_school_pos3' => !empty($form_state->getValue('position3'))? $form_state->getValue('position3') : '',
    'athlete_school_stat' => !empty($form_state->getValue('stats'))? $form_state->getValue('stats') : '',
    'athlete_school_type' => !empty($form_state->getValue('organizationType')) ? $form_state->getValue('organizationType') : '',
    ];
    $conn->insert('athlete_school')->fields($FIELDS_athlete_school)->execute(); 
    }else{
      $FIELDS_athlete_school = [
    'athlete_school_name' => !empty($form_state->getValue('organizationName'))?$form_state->getValue('organizationName') : '',
    #'athlete_school_coach' => !empty($form_state->getValue('coach')) ? $form_state->getValue('coach') : '',
    'athlete_school_sport' => !empty($form_state->getValue('sport')) ? $form_state->getValue('sport') : '',
    'athlete_school_pos' => !empty($form_state->getValue('position'))? $form_state->getValue('position') : '',
    'athlete_school_pos2' => !empty($form_state->getValue('position2'))? $form_state->getValue('position2') : '',
    'athlete_school_pos3' => !empty($form_state->getValue('position3'))? $form_state->getValue('position3') : '',
    'athlete_school_stat' => !empty($form_state->getValue('stats'))? $form_state->getValue('stats') : '',
    'athlete_school_type' => !empty($form_state->getValue('organizationType')) ? $form_state->getValue('organizationType') : '',
    ];
    $conn->update('athlete_school')->condition('athlete_uid', $current_user, '=')->fields($FIELDS_athlete_school)->execute();
    }
    
    //ORG - 2

    if(empty($athlete_club)){
    $FIELDS_athlete_club = [
    'athlete_uid' => $current_user,
    'athlete_club_name' => !empty($form_state->getValue('schoolname_1'))?$form_state->getValue('schoolname_1') : '',
    #'athlete_club_coach' => !empty($form_state->getValue('coach_1')) ? $form_state->getValue('coach_1') : '',
    'athlete_club_sport' => !empty($form_state->getValue('sport_1')) ? $form_state->getValue('sport_1') : '',
    'athlete_club_pos' => !empty($form_state->getValue('position_1'))? $form_state->getValue('position_1') : '',
    'athlete_school_pos2' => !empty($form_state->getValue('position_12'))? $form_state->getValue('position_12') : '',
    'athlete_school_pos3' => !empty($form_state->getValue('position_13'))? $form_state->getValue('position_13') : '',
    'athlete_club_stat' => !empty($form_state->getValue('stats_1'))? $form_state->getValue('stats_1') : '',
    'athlete_school_type' => !empty($form_state->getValue('education_1')) ? $form_state->getValue('education_1') : '',
    ];
    $conn->insert('athlete_club')->fields($FIELDS_athlete_club)->execute(); 
    }else{
    $FIELDS_athlete_club = [
    'athlete_club_name' => !empty($form_state->getValue('schoolname_1'))?$form_state->getValue('schoolname_1') : '',
   # 'athlete_club_coach' => !empty($form_state->getValue('coach_1')) ? $form_state->getValue('coach_1') : '',
    'athlete_club_sport' => !empty($form_state->getValue('sport_1')) ? $form_state->getValue('sport_1') : '',
    'athlete_club_pos' => !empty($form_state->getValue('position_1'))? $form_state->getValue('position_1') : '',
    'athlete_school_pos2' => !empty($form_state->getValue('position_12'))? $form_state->getValue('position_12') : '',
    'athlete_school_pos3' => !empty($form_state->getValue('position_13'))? $form_state->getValue('position_13') : '',
    'athlete_club_stat' => !empty($form_state->getValue('stats_1'))? $form_state->getValue('stats_1') : '',
    'athlete_school_type' => !empty($form_state->getValue('education_1')) ? $form_state->getValue('education_1') : '',
    ];
    $conn->update('athlete_club')->condition('athlete_uid', $current_user, '=')->fields($FIELDS_athlete_club)->execute();
    }

       //ORG - 3

    if(empty($athlete_uni)){
    $FIELDS_athlete_uni = [
    'athlete_uid' => $current_user,
    'athlete_uni_name' => !empty($form_state->getValue('schoolname_2'))?$form_state->getValue('schoolname_2') : '',
    #'athlete_uni_coach' => !empty($form_state->getValue('coach_2')) ? $form_state->getValue('coach_2') : '',
    'athlete_uni_sport' => !empty($form_state->getValue('sport_2')) ? $form_state->getValue('sport_2') : '',
    'athlete_uni_pos' => !empty($form_state->getValue('position_2'))? $form_state->getValue('position_2') : '',
    'athlete_uni_pos2' => !empty($form_state->getValue('position_22'))? $form_state->getValue('position_22') : '',
    'athlete_uni_pos3' => !empty($form_state->getValue('position_23'))? $form_state->getValue('position_23') : '',
    'athlete_uni_stat' => !empty($form_state->getValue('stats_2'))? $form_state->getValue('stats_2') : '',
    'athlete_uni_type' => !empty($form_state->getValue('education_2')) ? $form_state->getValue('education_2') : '',
    ];
    $conn->insert('athlete_uni')->fields($FIELDS_athlete_un)->execute();  
    }else{
    $FIELDS_athlete_uni = [
    'athlete_uni_name' => !empty($form_state->getValue('schoolname_2'))?$form_state->getValue('schoolname_2') : '',
   # 'athlete_uni_coach' => !empty($form_state->getValue('coach_2')) ? $form_state->getValue('coach_2') : '',
    'athlete_uni_sport' => !empty($form_state->getValue('sport_2')) ? $form_state->getValue('sport_2') : '',
    'athlete_uni_pos' => !empty($form_state->getValue('position_2'))? $form_state->getValue('position_2') : '',
    'athlete_uni_pos2' => !empty($form_state->getValue('position_22'))? $form_state->getValue('position_22') : '',
    'athlete_uni_pos3' => !empty($form_state->getValue('position_23'))? $form_state->getValue('position_23') : '',
    'athlete_uni_stat' => !empty($form_state->getValue('stats_2'))? $form_state->getValue('stats_2') : '',
    'athlete_uni_type' => !empty($form_state->getValue('education_2')) ? $form_state->getValue('education_2') : '',
    ];
    $conn->update('athlete_uni')->condition('athlete_uid', $current_user, '=')->fields($FIELDS_athlete_uni)->execute();
    }
    /**
      *ORGANIZATION DATA SAVE AND UPDATE [END HERE]
    */	

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
	
  
  }

  public function Get_Data_From_Tables($TableName,$atr,$current_user){
      if($TableName){
        $conn = Database::getConnection();
      $query = $conn->select($TableName, $atr);
        $query->fields($atr);
        $query->condition('athlete_uid', $current_user, '=');
        $results = $query->execute()->fetchAssoc();
      }
      return $results;
  }

  public function Get_Org_Name_For_default($type){
   if($type){
    $query = \Drupal::entityQuery('node');
      $query->condition('type', 'bfss_organizations');
      $query->condition('field_type', $type, 'IN');
      $nids = $query->execute();
      $org_name=[];
      foreach($nids as $nid){
        $node = Node::load($nid);
        $org_name[$node->field_organization_name->value] = $node->field_organization_name->value;
      }
     }
      $empty_val = array('' => 'Organization Name');
      return $empty_val + $org_name;
  }

  /*
  *AJAX FUNCTIONS
  */

  public function OrgNamesAjaxCallback_one(array &$form, FormStateInterface $form_state){
    //ORG-1   
    return  $form['organizationName']; 
  }


  public function OrgNamesAjaxCallback_two(array &$form, FormStateInterface $form_state){
    //ORG-2   
    return  $form['schoolname_1']; 
  }

  public function OrgNamesAjaxCallback_three(array &$form, FormStateInterface $form_state){
    //ORG-3   
    return  $form['schoolname_2']; 
  }

}

