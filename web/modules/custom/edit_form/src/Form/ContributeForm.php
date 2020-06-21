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
use Drupal\date_popup\DatePopup;
use Drupal\date_popup\DatetimePopup;
use Drupal\Core\Ajax\AjaxResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\InvokeCommand;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use  \Drupal\user\Entity\User;
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
    #/preview/profile
    $url = \Drupal\Core\Url::fromRoute('bfss_assessment.preview_atheltic_profile');
    // print_r($url);die;
    $link = \Drupal\Core\Link::fromTextAndUrl($this->t('<span class="icon glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview Changes'), $url);
    $form['#attached']['library'][] = 'edit_form/edit_form_lab';
    $form['#attributes']['class'][] = 'edit_profile_form';

    if ($link) {
      $link = $link->toRenderable();
      $link['#attributes'] = ['target' => '__blank', 'class' => ['button', 'previewButton'], ];
    }
	
	$vid = 'sports';
	$terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
	$sports_arr = array();
	foreach ($terms as $term) {
	 $sports_arr[$term->name] = $term->name;
	}
	
	

    $conn = Database::getConnection();

    $query1 = \Drupal::database()->select('user__field_first_name', 'ufln');
    $query1->addField('ufln', 'field_first_name_value');
    $query1->condition('entity_id', $current_user, '=');
    $results1 = $query1->execute()->fetchAssoc();

    $query2 = \Drupal::database()->select('user__field_last_name', 'ufln2');
    $query2->addField('ufln2', 'field_last_name_value');
    $query2->condition('entity_id', $current_user, '=');
    $results2 = $query2->execute()->fetchAssoc();
    $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
    $query3->addField('ufln3', 'field_date_value');
    $query3->condition('entity_id', $current_user, '=');
    $results3 = $query3->execute()->fetchAssoc();

    $query4 = \Drupal::database()->select('users_field_data', 'ufln4');
    $query4->addField('ufln4', 'mail');
    $query4->condition('uid', $current_user, '=');
    $results4 = $query4->execute()->fetchAssoc();
    

    $query8 = \Drupal::database()->select('user__field_state', 'ufs');
    $query8->fields('ufs');
    $query8->condition('entity_id', $current_user, '=');
    $results8 = $query8->execute()->fetchAssoc();


    $query13 = \Drupal::database()->select('athlete_web', 'aweb');
    $query13->fields('aweb');
    $query13->condition('athlete_uid', $current_user, '=');
    $query13->condition('athlete_web_type', 1, '=');
    $results13 = $query13->execute()->fetchAssoc();
    

    $query_img = \Drupal::database()->select('athlete_prof_image', 'n');
    $query_img->addField('n', 'athlete_target_image_id');
    $query_img->condition('athlete_id', $current_user, '=');
  	$query_img->orderBy('athlete_prof_id', 'DESC');
  	$query_img->range(0, 1);
    $results = $query_img->execute()->fetchAssoc();


    $query18 = \Drupal::database()->select('mydata', 'md');
    $query18->fields('md');
    $query18->condition('uid', $current_user, '=');
    $results18 = $query18->execute()->fetchAssoc();

    //web start
  	$query_web_type_delta0 = \Drupal::database()->select('athlete_web', 'athw');
  	$query_web_type_delta0->fields('athw');
  	$query_web_type_delta0->condition('athlete_uid', $current_user, '=');
  	$query_web_type_delta0->condition('athlete_web_type', 1, '=');
  	$results_web_type_delta0 = $query_web_type_delta0->execute()->fetchAssoc();
  		
  		
  	$query_web_type_delta1 = \Drupal::database()->select('athlete_web', 'athw');
  	$query_web_type_delta1->fields('athw');
  	$query_web_type_delta1->condition('athlete_uid', $current_user, '=');
  	$query_web_type_delta1->condition('athlete_web_type', 2, '=');
  	$results_web_type_delta1 = $query_web_type_delta1->execute()->fetchAssoc();
  		
  	$query_web_type_delta2 = \Drupal::database()->select('athlete_web', 'athw');
  	$query_web_type_delta2->fields('athw');
  	$query_web_type_delta2->condition('athlete_uid', $current_user, '=');
  	$query_web_type_delta2->condition('athlete_web_type', 3, '=');
  	$results_web_type_delta2 = $query_web_type_delta2->execute()->fetchAssoc();
	  //web end

	  $date_of_birth =  \Drupal::database()->select('user__field_date_of_birth', 'ufln4');
    $date_of_birth->addField('ufln4', 'field_date_of_birth_value');
    $date_of_birth->condition('entity_id', $current_user, '=');
    $date_of_birth_val = $date_of_birth->execute()->fetchAssoc();
	

	

	if(empty($results18)){
		$cityquery1 = \Drupal::database()->select('user__field_state', 'ufln');
		$cityquery1->addField('ufln', 'field_state_value');
		$cityquery1->condition('entity_id', $current_user, '=');
		$cityresults1 = $cityquery1->execute()->fetchAssoc();
		$state=$cityresults1['field_state_value'];
	}else{
		$state=$results18['field_az'];
	}

	 /**
    *ORGANIZATIONS DATA GET
    */
    $athlete_school = $this->Get_Data_From_Tables('athlete_school','ats',$current_user); //FOR ORG-1
    $athlete_club = $this->Get_Data_From_Tables('athlete_club','aclub',$current_user); //FOR ORG-2
    $athlete_uni = $this->Get_Data_From_Tables('athlete_uni','atc',$current_user); //FOR ORG-3
   
    /*
    *Athletic info data
    */
    $athlete_info = $this->Get_Data_From_Tables('athlete_info','ai',$current_user); 

    /*
    *social media
    */
    $results10 = $this->Get_Data_From_Tables('athlete_social','asoc',$current_user);

    /*
    *table athlete_orginfo
    */
    $query_org = \Drupal::database()->select('athlete_orginfo', 'n');
    $query_org->fields('n');
    $query_org->condition('athlete_id', $current_user, '=');
    $resultsorg = $query_org->execute()->fetchAssoc();
  

    /*
    *table athlete_orginfo
    */
    $results17 = $this->Get_Data_From_Tables('athlete_clubweb','aclubw',$current_user);

    /*
    *table athlete_addschool
    */
    $results15 = $this->Get_Data_From_Tables('athlete_addschool','aas',$current_user);

    /*
    *table athlete_addweb
    */
    $results14 = $this->Get_Data_From_Tables('athlete_addweb','aaweb',$current_user);

    /*
    *table athlete_uni
    */
    $results12 = $this->Get_Data_From_Tables('athlete_uni','auni',$current_user);

    /*
    *table athlete_about
    */
    $results9 = $this->Get_Data_From_Tables('athlete_about','aa',$current_user);

    $img_id = $results['athlete_target_image_id'];
    $form['prefix'] = "<div class=athlete_edit_class>";
    $form['suffix'] = "</div>";
    $form['#attached']['library'][] = 'edit_form/bfssAthleteProfile_lab';       //here can add
    $form['fname'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Firstname'),
      '#default_value' => $results1['field_first_name_value'],
      '#prefix' => '<div class="left_section popup_left_section">
      <div class="athlete_left">
        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Athletic Information</h3>
        <div class=items_div>  ',
	  '#required' => TRUE,
	  '#attributes' => array('readonly' => 'readonly'),
      );
    $form['lname'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Lastname'),
      '#default_value' => $results2['field_last_name_value'],
	  '#required' => TRUE,
	  '#attributes' => array('readonly' => 'readonly'),
      );
    $form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Preferred Contact Email'),
      '#default_value' => $results4['mail'],
	  '#required' => TRUE,
      );
	     $states = getStates();
       $form_state_values = $form_state->getValues();
       //print_r($form_state_values);
      if(empty($form_state_values)){
        $VNS = $state;
      }else{
        $VNS = $form_state_values['venue_state'];
      }
      //$VNS = !empty($form_state_values['venue_state'])?$form_state_values['venue_state']:$state;

      $form['venue_state'] = array(
        '#type' => 'select',
        '#options' => $states,
        '#default_value' => $state,
        '#ajax' => [
          'callback' => '::VenueLocationAjaxCallback', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output-22', // This element is updated with this AJAX callback.
        ]
        );

        
        $form['venue_loaction'] = [
            '#type' => 'textfield',
            '#placeholder' => t('city'),
             '#default_value' => $results18['field_city'],
            '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
            '#autocomplete_route_parameters' => array('field_name' => $VNS, 'count' => 10), 
            '#prefix' => '<div id="edit-output-22" class="org-3">',
            '#suffix' => '</div>',
        ];

   //  $form['az'] = array(
   //    //'#title' => t('az'),
   //    '#type' => 'select',
   //    //'#description' => 'Select the desired pizza crust size.',
	  // '#options'=>$states,
   //    '#default_value' => $state,
	  // '#required' => TRUE,
   //    '#prefix' => '<div class="full-width-inp">',
   //      '#suffix' => '</div>',
   //   '#ajax' => [
   //        'callback' => '::StateNamesAjaxCallback', // don't forget :: when calling a class method.
   //        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
   //        'event' => 'change',
   //        'wrapper' => 'edit-output-5', // This element is updated with this AJAX callback.
   //      ]
   //    );
	  // //print '<pre>';print_r($results18);die;
   //  $form['city'] = array(
   //    '#type' => 'textfield',
   //    //'#title' => t('City'),
   //     '#required' => TRUE,
   //    '#placeholder' => t('City'),
   //    '#default_value' => $results18['field_city'],
   //    );

	 $arr = [
    '' => t('Gender'),
    'Male'   => t('Male'),
    'Female'  =>t('Female'),
   # 'Other'   => t('Other')
    ];
    $form['sextype'] = array(
      '#type' => 'select',
      '#options' => $arr ,
      '#default_value' => $results18['field_birth_gender'],
	   #'#attributes' => array('disabled' => 'disabled'),
      );
	  
	  $form['sex'] = array(
      //'#title' => t('az'),
      '#type' => 'hidden',
      '#default_value' => $results18['field_birth_gender'],

      );
	  
	
	// $form['test'] = [
	// 	'#type' => 'textfield',
	// 	'#placeholder' => t('test'),
	// 	'#attributes' => array('id' => array('datepicker')),
	// ];
	  

    $form['doj'] = array(
      '#placeholder' => 'Date of Birth',
      '#type' => 'textfield',
	  #'#attributes' => array('readonly' => 'readonly'),
	 //'#attributes' => array('readonly' => 'readonly','id' => array('datepicker')),
      '#required' => true,
      '#default_value' => $date_of_birth_val,
      '#format' => 'm/d/Y',
      '#attributes' => array('id' => array('datepicker')),
      );

	  
    $form['grade'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Grade'),
      '#default_value' => $results18['field_grade'],
	  '#required' => TRUE,
      );
    $form['gradyear'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Graduation Year'),
      '#default_value' => $athlete_info['athlete_year'],
	  '#required' => TRUE,
      );
    $form['height'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Height in Inches'),
      '#default_value' => $athlete_info['field_height'],
	  '#required' => TRUE,
      );
    $form['weight'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Weight in Pounds'),
      '#default_value' => $athlete_info['field_weight'],
      '#suffix' => '</div></div>',
	  '#required' => TRUE,
      );
    $form['aboutme'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Tell us about yourself'),
      '#attributes' => array('maxlength' => 1500),
      '#default_value' => $results9['athlete_about_me'],
      '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>About me</h3><div class=items_div>',
      '#suffix' => '</div></div>',
      );
    $form['instagram'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('You Instagram Handle'),
      '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Social Media<i class="fa fa-info right-icon" aria-hidden="true" data-tooltip="Tooltip Content"></i></h3><div class=items_div>',
      '#default_value' => $results10['athlete_social_1'],
      );
    $form['youtube'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Youtube or vimeo account'),
      '#suffix' => '</div></div>',
      '#default_value' => $results10['athlete_social_2'],
      );

    /*
    *ORGANIZATION - 1
    */
    $type_org_1 = isset($athlete_school['athlete_school_type']) ? $athlete_school['athlete_school_type'] : 'school';
    $orgnames_op1 = $this->Get_Org_Name_For_default($type_org_1);
     //$orgnames_op1 = 'Williams Field High School';
   
    // $state_name = isset($form_state_values['az']) ? $form_state_values['az'] : $state;
    $orgtype = [
      ""=>t('Organization Type'),
      "school"=>t('School'),
      "club"=>t('Club'),
      "university"=>t('University')
  	];
    $form['organizationType'] = [
      '#type' => 'select',
	    '#required' => TRUE,
      '#options' => $orgtype,
      #'#attributes' => array('class' => array('full-width-inp')),
      '#prefix' => '<div class="athlete_school"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><div class=items_div><div class="full-width-inp">',
      '#suffix' => '</div>',
      '#default_value' => isset($athlete_school['athlete_school_type'])?$athlete_school['athlete_school_type']:'school',
      '#ajax' => [
			    'callback' => '::OrgNamesAjaxCallback_one', // don't forget :: when calling a class method.
			    'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
			    'event' => 'change',
			    'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
			  ]
      ];


    $type__1 = isset($type_org_1)?$type_org_1:'school';
    $type_organization_1 = isset($form_state_values['organizationType'])?$form_state_values['organizationType']:$type__1; 
   
    $form['organizationName'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Orginization Name'),
      '#autocomplete_route_name' => 'edit_form.autocomplete',
      '#autocomplete_route_parameters' => array('state_name' => $VNS, 'field_name' => $type_organization_1, 'count' => 10), 
      '#prefix' => '<div id="edit-output" class="orgtextarea1">',
      '#suffix' => '</div>',
      '#default_value' => $athlete_school['athlete_school_name'] ,
      #'#attributes' => ['id' => 'label_1', 'class' => array['weblabel']],
    );



    // $form['organizationName'] = array(
    //   '#type' => 'select',
    //   '#placeholder' => t('Orginization Name'),
    //   #'#required' => TRUE,
    //   '#options' => $orgnames_op1,
    //   '#prefix' => '<div id="edit-output" class="orgtextarea1">',
    //   '#suffix' => '</div>',
    //   '#default_value' => $athlete_school['athlete_school_name'] ,
    //   //'#attributes' => array('disabled' => TRUE),
    //   );
      
      
    // $form['coach'] = array(
    //   '#type' => 'textfield',
    //   '#placeholder' => t("Coach's Last Name"),
    //   '#default_value' => $athlete_school['athlete_school_coach'],
    //   );
	$sports_arr = [''=>'Select Sport'] + $sports_arr;
    $form['sport'] = array(
      '#type' => 'select',
	  '#options' => $sports_arr,
      '#default_value' => $athlete_school['athlete_school_sport'],
      );

    $form['position'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Position'),
      '#default_value' => $athlete_school['athlete_school_pos'],
      '#prefix' => '<div class="add_pos_div_first">',
      '#suffix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove Position</a>',
      );
    if (empty($athlete_school['athlete_school_pos2'])) {
      $form['position2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#attributes' => array('style' => 'display:none'),
        );
    } else {
      $form['position2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $athlete_school['athlete_school_pos2'],
        '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
        );
    }
    if (empty($athlete_school['athlete_school_pos3'])) {
      $form['position3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
        );
    } else {
      $form['position3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $athlete_school['athlete_school_pos3'],
        '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
        );
    }

    $form['stats'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Add all personal stats'),
      '#suffix' => '</div></div>',
      '#default_value' => $athlete_school['athlete_school_stat'],
      );

    /*
    *ORGANIZATION - 2
    */
    $type_org_2 =  isset($athlete_club['athlete_school_type']) ? $athlete_club['athlete_school_type'] : 'school';
    $orgnames_op = $this->Get_Org_Name_For_default($type_org_2);
    // $orgnames_op = "Williams Field High School";
    if (!empty($athlete_club['athlete_club_name']) && !empty($athlete_club['athlete_school_type'])) {

      $form['education_1'] = array( // uni
        '#type' => 'select',
        '#options' => $orgtype,
        #'#attributes' => array('class' => array('full-width-inp')),
        '#prefix' => '</div><div class="org_notempty"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University<i id="athlete_club" class="athlete-del-org fa fa-trash right-icon delete_icon" aria-hidden="true" data-orgname="athlete_club"></i></h3><div class=items_div><div class="full-width-inp">',
        '#suffix' => '</div>',
        '#default_value' => isset($athlete_club['athlete_school_type'])?$athlete_club['athlete_school_type']:'school',
              '#ajax' => [
			    'callback' => '::OrgNamesAjaxCallback_two', // don't forget :: when calling a class method.
			    'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
			    'event' => 'change',
			    'wrapper' => 'edit-output-1', // This element is updated with this AJAX callback.
			  ]
        );



    $type__2 = isset($type_org_2)?$type_org_2:'school';
    $type_organization_2 = isset($form_state_values['education_1'])?$form_state_values['education_1']:$type__2; 
      $form['schoolname_1'] = [
            '#type' => 'textfield',
            '#placeholder' => t('Orginization Name'),
            '#autocomplete_route_name' => 'edit_form.autocomplete',
            '#autocomplete_route_parameters' => array('state_name' => $VNS, 'field_name' => $type_organization_2, 'count' => 10), 
            '#prefix' => '<div id="edit-output-1" class="org-2">',
            '#suffix' => '</div>',
            '#default_value' => $athlete_club['athlete_club_name'],
      ];


      // $form['coach_1'] = array(
      //   '#type' => 'textfield',
      //   '#placeholder' => t("Coache's Last Name"),
      //   '#default_value' => $athlete_club['athlete_club_coach'],
      //   );
      $form['sport_1'] = array(
        '#type' => 'select',	
		    '#options'=> $sports_arr,
        '#default_value' => $athlete_club['athlete_club_sport'],
        );
      $form['position_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $athlete_club['athlete_club_pos'],
        '#prefix' => '<div class="add_pos_div_second">',
        '#suffix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove Position</a></div>',
        );
      if (empty($athlete_club['athlete_school_pos2'])) {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_club['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
          );
      } else {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_club['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          );
      }
      if (empty($athlete_club['athlete_school_pos3'])) {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_club['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          );
      } else {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_club['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
        );
      }

      $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div>',
        '#default_value' => $athlete_club['athlete_club_stat'],
        );
    } else {

 
      $form['education_1'] = array( //uni
        '#type' => 'select',
        '#options' => $orgtype,
       # '#attributes' => array('class' => array('full-width-inp')),
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete" style="display:none;"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University<i id="athlete_club"  data-orgname="athlete_club" class="athlete-del-org fa fa-trash right-icon delete_icon previous_delete" aria-hidden="true"></i></h3><div class=items_div><div class="full-width-inp">',
         '#suffix' => '</div>',
        '#default_value' => isset($athlete_club['athlete_school_type'])?$athlete_club['athlete_school_type']:'school',
              '#ajax' => [
			    'callback' => '::OrgNamesAjaxCallback_2', // don't forget :: when calling a class method.
			    'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
			    'event' => 'change',
			    'wrapper' => 'edit-output-1', // This element is updated with this AJAX callback.
			  ]
        );

     
    $type__2 = isset($type_org_2)?$type_org_2:'school';
    $type_organization_2 = isset($form_state_values['education_1'])?$form_state_values['education_1']:$type__2; 
      $form['schoolname_1'] = [
            '#type' => 'textfield',
            '#placeholder' => t('Orginization Name'),
            '#autocomplete_route_name' => 'edit_form.autocomplete',
            '#autocomplete_route_parameters' => array('state_name' => $VNS, 'field_name' => $type_organization_2, 'count' => 10), 
            '#prefix' => '<div id="edit-output-1" class="org-2">',
            '#suffix' => '</div>',
            '#default_value' => '',
      ];

      // $form['coach_1'] = array(
      //   '#type' => 'textfield',
      //   '#placeholder' => t("Coache's Last Name"),
      //   );
      $form['sport_1'] = array(
        '#type' => 'select',
		'#options'=>$sports_arr,
        '#placeholder' => t('Sport'),
        );
      $form['position_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#prefix' => '<div class="add_pos_div_second">',
        '#suffix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove Position</a></div>',
        );
      if (empty($athlete_uni['athlete_uni_pos2'])) {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
          );
      } else {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          );
      }
      if (empty($athlete_uni['athlete_uni_pos3'])) {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          );
      } else {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          );
      }
      $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div>',
        );
    }
    
    /*
    *ORGANIZATION - 3
    */
	    $type_org_3 =  isset($athlete_uni['athlete_uni_type']) ? $athlete_uni['athlete_uni_type'] : 'school';
      $orgnames_op3 = $this->Get_Org_Name_For_default($type_org_3);
	  //  $orgnames_op3 = "Williams Field High School";
      $type__3 = isset($type_org_3)?$type_org_3:'school';
      $type_organization_3 = isset($form_state_values['education_2'])?$form_state_values['education_2']:$type__3;
     if (!empty($athlete_uni['athlete_uni_name']) && !empty($athlete_uni['athlete_uni_type'])) {
 
      $form['education_2'] = array(
        '#type' => 'select',
        '#options' => $orgtype,
        #'#attributes' => array('class' => array('full-width-inp')),
        '#prefix' => '<div class="athlete_left">
            <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University<i id="athlete_uni" class="athlete-del-org fa fa-trash right-icon delete_icon" aria-hidden="true" data-orgname="athlete_uni"></i></h3>
            <div class=items_div>
            <div class="full-width-inp">',
         '#suffix' => '</div>',
        '#default_value' => isset($athlete_uni['athlete_uni_type'])?$athlete_uni['athlete_uni_type']:'school',
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
            '#autocomplete_route_parameters' => array('state_name' => $VNS, 'field_name' => $type_organization_3, 'count' => 10), 
            '#prefix' => '<div id="edit-output-2" class="org-3">',
            '#suffix' => '</div>',
            '#default_value' => $athlete_uni['athlete_uni_name'],
		    ];

      // $form['coach_2'] = array(
      //   '#type' => 'textfield',
      //   '#placeholder' => t("Coache's Last Name"),
      //   '#default_value' => $athlete_uni['athlete_uni_coach'],
      //   );
      $form['sport_2'] = array(
        '#type' => 'select',
		    '#options'=>$sports_arr,
        '#default_value' => $athlete_uni['athlete_uni_sport'],
        );
      $form['position_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $athlete_uni['athlete_uni_pos'],
        '#prefix' => '<div class="add_pos_div_third">',
        '#suffix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove Position</a></div>',
        );
      if (empty($athlete_uni['athlete_uni_pos2'])) {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_uni['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_hidden_third_1', 'style' => 'display:none'),
          );
      } else {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_uni['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          );
      }
      if (empty($athlete_uni['athlete_uni_pos3'])) {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_uni['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          );
      } else {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $athlete_uni['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          );
      }
      $form['stats_2'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => $athlete_uni['athlete_uni_stat'],
        );
    } else {

      $form['education_2'] = array( // club
        '#type' => 'select',
        '#options' => $orgtype,
        #'#attributes' => array('class' => array('full-width-inp')),
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University<i id="athlete_uni" data-orgname="athlete_uni" class="athlete-del-org fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i></h3><div class=items_div><div class="full-width-inp">',
         '#suffix' => '</div>',
        	'#default_value' => isset($athlete_uni['athlete_uni_type'])?$athlete_uni['athlete_uni_type']:'school',
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
            '#autocomplete_route_parameters' => array('state_name' => $VNS, 'field_name' => $type_organization_3, 'count' => 10), 
            '#prefix' => '<div id="edit-output-2" class="org-3">',
            '#suffix' => '</div>',
            '#default_value' => $athlete_uni['athlete_uni_name'],
        ];
      // $form['coach_2'] = array(
      //   '#type' => 'textfield',
      //   '#placeholder' => t("Coache's Last Name"),
      //   '#default_value' => '',
      //   );
      $form['sport_2'] = array(
        '#type' => 'select',
		'#options'=>$sports_arr,
        '#default_value' => '',
        );
      $form['position_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => '',
        '#prefix' => '<div class="add_pos_div_third">',
        '#suffix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove Position</a></div>',
        );
      if (empty($athlete_uni['athlete_uni_pos2'])) {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_hidden_first_1', 'style' => 'display:none'),
          );
      } else {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          );
      }
      if (empty($athlete_uni['athlete_uni_pos3'])) {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          );
      } else {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          );
      }
      $form['stats_2'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => '',
        );
    }

//END ORG

    $form['html_image_athlete'] = [
  		'#type' => 'markup',
  		'#markup' => '</div><div class ="right_section">
      <div class = "athlete_right">
      <h3>
        <div class="toggle_icon">
            <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website Photo</h3>
              <div class="edit_dropdown">
                <a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a>
                <ul class="dropdown-menu" style="padding:0"></ul>
              </div>
        <div class=items_div>',
		];
    $form['image_athlete'] = [
    '#type' => 'managed_file',
    '#upload_validators' => [
    		'file_validate_extensions' => ['gif png jpg jpeg'],
    		//'file_validate_size' => [25600000], 
		],
    '#theme' => 'image_widget', 
    '#preview_image_style' => 'medium', 
    '#upload_location' => 'public://',
    '#required' => false,
    '#default_value' => array($img_id),
    '#prefix' => '</div>',
    '#suffix' => '<div class="action_bttn"><span>Action</span><ul><li>Remove</li></ul></div></div></div>',
    ];

    $form['school_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Organization Name'),
      '#default_value' => $athlete_school['athlete_school_name'],
      '#attributes' => array('disabled' => true),
      '#prefix' => '</div></div><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website</h3><div class=items_div>',
      );
    $form['sport_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#attributes' => array('disabled' => true),
      '#default_value' => $athlete_school['athlete_school_sport'],
      );

    $form['name_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Pick a Name'),
      '#default_value' => $results_web_type_delta0['athlete_web_name'],
      '#prefix' => '<div class="container-inline web_name webfield">',
      '#suffix' => '</div>',
      '#attributes' => array('id' => 'name_1'),
      );
    $form['label_1'] = array(
      '#type' => 'label',
      '#title' => ' http://bfsscience.com/users/',
      '#attributes' => array('id' => 'label_1', 'class' => array('weblabel')),
      );
    $form['label_2'] = array(
      '#type' => 'label',
      '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br>Once published, this will become your permanent address and it can not be changed.<br>',
      );
    $form['preview_1'] = array(
      '#type' => 'markup',
      '#markup' => render($link),
      '#prefix' => "<div class='previewdiv' data-id='1'>",
      '#suffix' => "</div>",
      );
    $form['web_visible_1'] = array(
      '#type' => 'select',
      '#options' => array(
        t('Website Visibility'),
        t('On'),
        t('Off')),
      '#default_value' => $results_web_type_delta0['athlete_web_visibility'],
      '#suffix' => '</div></div>',
      );

    if (!empty($athlete_club['athlete_club_name'])) {
      $form['school_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $athlete_club['athlete_club_name'],
        '#attributes' => array('disabled' => true),
        '#prefix' => '<div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
        );
      $form['sport_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $athlete_club['athlete_club_sport'],
        '#attributes' => array('disabled' => true),
        );
      $form['name_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $results_web_type_delta1['athlete_web_name'],
        '#prefix' => '<div class="container-inline web_name webfield">',
        '#suffix' => '</div>',
        '#attributes' => array('id' => 'name_2'),
        );
      $form['label_12'] = array(
        '#type' => 'label',
        '#title' => ' http://bfsscience.com/users/',
        '#attributes' => array('id' => 'label_2', 'class' => array('weblabel')),
        );
      $form['label_22'] = array(
        '#type' => 'label',
        '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br>Once published, this will become your permanent address and it can not be changed.<br>',
        );
      $form['preview_12'] = array(
        '#type' => 'markup',
        '#markup' => render($link),
        '#prefix' => "<div class='previewdiv' data-id='2'>",
        '#suffix' => "</div>",
        );
      $form['web_visible_2'] = array(
        '#type' => 'select',
        '#options' => array(
          t('Website Visibility'),
          t('on'),
          t('off')),
        '#default_value' => $results_web_type_delta1['athlete_web_visibility'],
        '#suffix' => '</div></div>',
        );
    }
    if (!empty($athlete_uni['athlete_uni_name'])) {
      $form['school_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $athlete_uni['athlete_uni_name'],
        '#attributes' => array('disabled' => true),
        '#prefix' => '<div class = "athlete_right">
                 <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3>
              <div class=items_div>',
        );
      $form['sport_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $athlete_uni['athlete_uni_sport'],
        '#attributes' => array('disabled' => true),
        );
      $form['name_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $results_web_type_delta2['athlete_web_name'],
        '#prefix' => '<div class="container-inline web_name webfield">',
        '#suffix' => '</div>',
        '#attributes' => array('id' => 'name_2'),
        );
      $form['label_13'] = array(
        '#type' => 'label',
        '#title' => 'http://bfsscience.com/users/',
        '#attributes' => array('id' => 'label_2', 'class' => array('weblabel')),
        );
      $form['label_23'] = array(
        '#type' => 'label',
        '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br>Once published, this will become your permanent address and it can not be changed.<br>',
        );
      $form['preview_13'] = array(
        '#type' => 'markup',
        '#markup' => render($link),
        '#prefix' => "<div class='previewdiv' data-id='3'>",
        '#suffix' => "</div>",
        // '#type' => 'button',
        // '#default_value' => 'Preview Changes',
        );
      $form['web_visible_3'] = array(
        '#type' => 'select',
        '#options' => array(
          t('Website Visibility'),
          t('on'),
          t('off')),
        '#default_value' => $results_web_type_delta2['athlete_web_visibility'],
        '#suffix' => '</div>
        </div>
       ',
        );


    }
   // $valsave = Markup::create('<em class="desktop">SAVE ALL CHANGES</em><em class="mobile">SAVE</em>');
    $form['submit'] = ['#type' => 'submit', '#value' => 'SAVE ALL CHANGES', '#prefix' => ' </div><div class="bfss_save_all save_all_changes">', '#suffix' => '</div>',
      //'#value' => t('Submit'),
      ];
    // $form['#theme'] = 'athlete_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {

    if (!$form_state->getValue('fname') || empty($form_state->getValue('fname'))) {
      $form_state->setErrorByName('fname', $this->t('First name should not be empty.'));
    }
    if (!$form_state->getValue('lname') || empty($form_state->getValue('lname'))) {
      $form_state->setErrorByName('lname', $this->t('Last name should not be empty.'));
    }
    if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Please enter a valid email.'));
    }
    /* if (!$form_state->getValue('sex') || empty($form_state->getValue('sex'))) {
      $form_state->setErrorByName('sex', $this->t('Gender should not be empty.'));
    } */
    if (!$form_state->getValue('venue_loaction') || empty($form_state->getValue('venue_loaction'))) {
      $form_state->setErrorByName('venue_loaction', $this->t('City should not be empty.'));
    }
    if (!$form_state->getValue('grade') || empty($form_state->getValue('grade'))) {
      $form_state->setErrorByName('grade', $this->t('Grade should not be empty.'));
    }
    if (!$form_state->getValue('gradyear') || empty($form_state->getValue('gradyear'))) {
      $form_state->setErrorByName('gradyear', $this->t('Graduation year should not be empty.'));
    }
    if (!$form_state->getValue('height') || !is_numeric($form_state->getValue('height'))) {
      $form_state->setErrorByName('height', $this->t('Height should be a number.'));
    }
    if (empty($form_state->getValue('height'))) {
      $form_state->setErrorByName('height', $this->t('Height should not be empty.'));
    }
    if (!$form_state->getValue('weight') || !is_numeric($form_state->getValue('weight'))) {
      $form_state->setErrorByName('weight', $this->t('Weight should be a number.'));
    }
    if (empty($form_state->getValue('weight'))) {
      $form_state->setErrorByName('weight', $this->t('Weight should not be empty.'));
    }
    //    if (!$form_state->getValue('aboutme') || strlen($form_state->getValue('aboutme')) <= 15000 ) {
    //        $form_state->setErrorByName('aboutme', $this->t('Should be a less than 1000 character.'));
    //    }
    /* if (!$form_state->getValue('coach') || empty($form_state->getValue('coach'))) {
      $form_state->setErrorByName('coach', $this->t("Coach's last name should not be empty."));
    } */
    if (!$form_state->getValue('sport') || empty($form_state->getValue('sport'))) {
      $form_state->setErrorByName('sport', $this->t("Sport should not be empty."));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {

    $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();

	// $user = User::load($current_user);
	// $user->save();

    $seltype1 = $form_state->getValue('organizationType');
    $selname1 = $form_state->getValue('organizationName');
    $seltype2 = $form_state->getValue('education_1');
    $selnameval2 = $form_state->getValue('schoolname_1');
    $seltypeval3 = $form_state->getValue('education_2');
    $selnameval3 = $form_state->getValue('schoolname_2');
 
    $seltypeval1 = $form['organizationType']['#options'][$seltype1];
    $selnameval1 = $form['organizationName']['#options'][$selname1];
    $seltypeval2 = $form['education_1']['#options'][$seltype2];


    $query_info = \Drupal::database()->select('athlete_info', 'ai');
    $query_info->fields('ai');
    $query_info->condition('athlete_uid', $current_user, '=');
    $results_info = $query_info->execute()->fetchAll();
	
    $query_about = \Drupal::database()->select('athlete_about', 'aa');
    $query_about->fields('aa');
    $query_about->condition('athlete_uid', $current_user, '=');
    $results_about = $query_about->execute()->fetchAll();
	
    $query_social = \Drupal::database()->select('athlete_social', 'ascc');
    $query_social->fields('ascc');
    $query_social->condition('athlete_uid', $current_user, '=');
    $results_social = $query_social->execute()->fetchAll();
	


	
    $query_school = \Drupal::database()->select('athlete_school', 'ats');
    $query_school->fields('ats');
    $query_school->condition('athlete_uid', $current_user, '=');
    $results_school = $query_school->execute()->fetchAll();
	
	$count_school_num_results = count($results_school);
    $query_uni = \Drupal::database()->select('athlete_uni', 'au');
    $query_uni->fields('au');
    $query_uni->condition('athlete_uid', $current_user, '=');
    $results_uni = $query_uni->execute()->fetchAll();
	$count_uni_num_results = count($results_uni);
	
    $query_fname = \Drupal::database()->select('user__field_first_name', 'uffn');
    $query_fname->fields('uffn');
    $query_fname->condition('entity_id', $current_user, '=');
    $results_fname = $query_fname->execute()->fetchAll();
	
    $query_lname = \Drupal::database()->select('user__field_last_name', 'ufln2');
    $query_lname->addField('ufln2', 'field_last_name_value');
    $query_lname->condition('entity_id', $current_user, '=');
    $results_lname = $query_lname->execute()->fetchAssoc();
	
    $query_mail = \Drupal::database()->select('users_field_data', 'ufln4');
    $query_mail->addField('ufln4', 'mail');
    $query_mail->condition('uid', $current_user, '=');
    $results_mail = $query_mail->execute()->fetchAssoc();
	

	

	
   
	

	
    $query_club = \Drupal::database()->select('athlete_club', 'athawac');
    $query_club->fields('athawac');
    $query_club->condition('athlete_uid', $current_user, '=');
    $results_club = $query_club->execute()->fetchAll();
	
	$count_club_num_results = count($results_club);
	
    $query_mydata = \Drupal::database()->select('mydata', 'md');
    $query_mydata->fields('md');
    $query_mydata->condition('uid', $current_user, '=');
    $results_mydata = $query_mydata->execute()->fetchAll();

    $imgid = $form_state->getValue('image_athlete');

	
	
	
	$query_pic = \Drupal::database()->select('athlete_prof_image', 'uup');
    $query_pic->fields('uup');
    $query_pic->condition('athlete_id', $current_user, '=');
    $results_pic = $query_pic->execute()->fetchAll();
	$count_pic = count($results_pic);
	if ($count_pic==0) {
		$conn->insert('athlete_prof_image')->fields(array(
        'athlete_id' => $current_user,
        'athlete_target_image_id' => $imgid[0],
        ))->execute();
	}else{
		if (!empty($imgid[0])) {
        $conn->update('athlete_prof_image')->condition('athlete_id', $current_user, '=')->fields(array('athlete_target_image_id' => $imgid[0], ))->execute();
      } else {
        $conn->update('athlete_prof_image')->condition('athlete_id', $current_user, '=')->fields(array('athlete_target_image_id' => '240', ))->execute();
      }
	}	
	

    $conn->update('user__field_first_name')->condition('entity_id', $current_user, '=')->fields(array('field_first_name_value' => $form_state->getValue('fname'), ))->execute();

    $conn->update('user__field_last_name')->condition('entity_id', $current_user, '=')->fields(array('field_last_name_value' => $form_state->getValue('lname'), ))->execute();
    $conn->update('users_field_data')->condition('uid', $current_user, '=')->fields(array('mail' => $form_state->getValue('email'), ))->execute();

    if(!empty($form_state->getValue('venue_state'))){
    $conn->update('user__field_state')->condition('entity_id', $current_user, '=')->fields(array('field_state_value' => $form_state->getValue('venue_state') ))->execute();
	}

    $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
    $query3->addField('ufln3', 'field_date_value');
    $query3->condition('entity_id', $current_user, '=');
    $results3 = $query3->execute()->fetchAssoc();

    $date_of_birth =  \Drupal::database()->select('user__field_date_of_birth', 'ufln4');
    $date_of_birth->addField('ufln4', 'field_date_of_birth_value');
    $date_of_birth->condition('entity_id', $current_user, '=');
    $date_of_birth_val = $date_of_birth->execute()->fetchAssoc();


	  $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if (empty($date_of_birth_val)) {
		
        $conn->insert('user__field_date_of_birth')->fields(array(
        'entity_id' => $current_user,
        'bundle' => 'user',
        'revision_id' => $current_user,
        'delta' => 0,
        'langcode' => $lang_code,
        'field_date_of_birth_value' => $form_state->getValue('doj'),
        ))->execute();
    }
    else {
        $conn->update('user__field_date_of_birth')->condition('entity_id', $current_user, '=')->fields(array(
        'field_date_of_birth_value' => $form_state->getValue('doj'),
        ))->execute();
    }


	
	
	
	
	
	
    if (empty($results_mydata)) {
      $conn->insert('mydata')->fields(array(
        'uid' => $current_user,
        'field_az' => $form_state->getValue('venue_state'),
        'field_city' => $form_state->getValue('venue_loaction'),
        'field_birth_gender' => $form_state->getValue('sextype'),
        'field_grade' => $form_state->getValue('grade'),
        ))->execute();
    } else {
		
      $conn->update('mydata')->condition('uid', $current_user, '=')->fields(array(
        'field_az' => $form_state->getValue('venue_state'),
        'field_city' => $form_state->getValue('venue_loaction'),
        'field_birth_gender' => $form_state->getValue('sextype'),
        'field_grade' => $form_state->getValue('grade'),
        ))->execute();
    }

    if (empty($results_info)) {
      $conn->insert('athlete_info')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_email' => $form_state->getValue('email'),
        #'athlete_coach' => $form_state->getValue('coach'),
        'athlete_year' => $form_state->getValue('gradyear'),
        'field_height' => $form_state->getValue('height'),
        'field_weight' => $form_state->getValue('weight'),
        'popup_flag' => $popupFlag,
        ))->execute();
    } else {
      $conn->update('athlete_info')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_email' => $form_state->getValue('email'),
        #'athlete_coach' => $form_state->getValue('coach'),
        'athlete_year' => $form_state->getValue('gradyear'),
        'field_height' => $form_state->getValue('height'),
        'field_weight' => $form_state->getValue('weight'),
        'popup_flag' => $popupFlag,
        ))->execute();
    }
    
    
    
    
    if (empty($results_about)) {
      $conn->insert('athlete_about')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_about_me' => $form_state->getValue('aboutme'),
        ))->execute();
    } else {
      $conn->update('athlete_about')->condition('athlete_uid', $current_user, '=')->fields(array('athlete_about_me' => $form_state->getValue('aboutme'), ))->execute();
    }

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
		$conn->insert('athlete_uni')->fields($FIELDS_athlete_uni)->execute();	
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
      

	/**
	*WEB PAGE START HERE
	*/
	$query_web_type_delta0 = \Drupal::database()->select('athlete_web', 'athw');
	$query_web_type_delta0->fields('athw');
	$query_web_type_delta0->condition('athlete_uid', $current_user, '=');
	$query_web_type_delta0->condition('athlete_web_type', 1, '=');
	$results_web_type_delta0 = $query_web_type_delta0->execute()->fetchAll();
		
		
	$query_web_type_delta1 = \Drupal::database()->select('athlete_web', 'athw');
	$query_web_type_delta1->fields('athw');
	$query_web_type_delta1->condition('athlete_uid', $current_user, '=');
	$query_web_type_delta1->condition('athlete_web_type', 2, '=');
	$results_web_type_delta1 = $query_web_type_delta1->execute()->fetchAll();
		
	$query_web_type_delta2 = \Drupal::database()->select('athlete_web', 'athw');
	$query_web_type_delta2->fields('athw');
	$query_web_type_delta2->condition('athlete_uid', $current_user, '=');
	$query_web_type_delta2->condition('athlete_web_type', 3, '=');
	$results_web_type_delta2 = $query_web_type_delta2->execute()->fetchAll();

	if (empty($results_web_type_delta0)) {
      $conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('name_web'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_1'),
        'athlete_web_type' => 1,
        'delta' => 0,
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->condition('athlete_web_type', 1, '=')->condition('delta', 0, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('name_web'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_1'),
        ))->execute();
    }

    if (empty($results_web_type_delta1)) {
      $conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('name_web2'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_2'),
        'athlete_web_type' =>  2,
        'delta' => 1,
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->condition('athlete_web_type', 2, '=')->condition('delta', 1, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('name_web2'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_2'),
        ))->execute();
    }


	if (empty($results_web_type_delta2)) {
      $conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('name_web3'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_3'),
        'athlete_web_type' =>  3,
        'delta' => 2,
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->condition('athlete_web_type', 3, '=')->condition('delta', 2, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('name_web3'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_3'),
        ))->execute();
    }

    //	for getUserNameValiditity
    $query_addweb = \Drupal::database()->select('athlete_addweb', 'athaw');
    $query_addweb->fields('athaw');
    $query_addweb->condition('athlete_uid', $current_user, '=');
    $results_addweb = $query_addweb->execute()->fetchAll();
    
    if (empty($results_addweb)) {
      $conn->insert('athlete_addweb')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_addweb_name' => $form_state->getValue('name_web2'),
        'athlete_addweb_visibility' => $form_state->getValue('web_visible_2'),
        ))->execute();
    } else {
      $conn->update('athlete_addweb')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_addweb_name' => $form_state->getValue('name_web2'),
        'athlete_addweb_visibility' => $form_state->getValue('web_visible_2'),
        ))->execute();
    }

    $query_clubweb = \Drupal::database()->select('athlete_clubweb', 'athawa');
    $query_clubweb->fields('athawa');
    $query_clubweb->condition('athlete_uid', $current_user, '=');
    $results_clubweb = $query_clubweb->execute()->fetchAll();
    if (empty($results_clubweb)) {
      $conn->insert('athlete_clubweb')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_clubweb_name' => $form_state->getValue('name_web3'),
        'athlete_clubweb_visibility' => $form_state->getValue('web_visible_3'),
        ))->execute();
    } else {
      $conn->update('athlete_clubweb')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_clubweb_name' => $form_state->getValue('name_web3'),
        'athlete_clubweb_visibility' => $form_state->getValue('web_visible_3'),
        ))->execute();
    }
	/**
	*WEB PAGE END HERE
	*/


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

 	public function OrgNamesAjaxCallback_1(array &$form, FormStateInterface $form_state) {
	 	$op = [''=>'Organizations Name'];	
	 	//ORG-1		
		if ($selectedValue = $form_state->getValue('organizationType')) {
			$selectedText = $form['organizationType']['#options'][$selectedValue];
			$orgNames = $this->Get_Org_Name($selectedText);
			$arrName = explode(',',$orgNames);
			$selArr = [];
		    foreach ($arrName as $key => $value) {
		      $selArr[$value] = $value;
		    }
		    $selArr = ['' => 'Organization Name'] + $selArr;
					if(!empty($arrName)){
			        	$form['organizationName'] = [
			                '#placeholder' => t('Organization Name'),
			                '#type' => 'select', 
			                '#options' => $selArr,
			                '#prefix' => '<div id="edit-output" class="org-1">',
			                '#suffix' => '</div>',
			                '#attributes' => array('disabled' => FALSE),
			            ];
		            }else{
		           		$form['organizationName'] = [
			                '#placeholder' => t('Organization Name'),
			                '#type' => 'select', 
			                '#options' => ['' => 'Organization Name'],
			                '#prefix' => '<div id="edit-output" class="org-1">',
			                '#suffix' => '</div>',
			                '#attributes' => array('disabled' => FALSE),
			            ];
			                
		           }
		  
        }
     	return  $form['organizationName'];    		
	}
	//ORG-2
	public function OrgNamesAjaxCallback_2(array &$form, FormStateInterface $form_state) {
		if ($selectedValue = $form_state->getValue('education_1')) {
				$selectedText = $form['education_1']['#options'][$selectedValue];
				$orgNames = $this->Get_Org_Name($selectedText);
				$arrName = explode(',',$orgNames);
				$selArr = [];
			    foreach ($arrName as $key => $value) {
			      $selArr[$value] = $value;
			    }
			    $selArr = ['' => 'Organization Name'] + $selArr;
						if(!empty($arrName)){
				        	$form['schoolname_1'] = [
				                '#placeholder' => t('Organization Name'),
				                '#type' => 'select', 
				                '#options' => $selArr,
				                '#prefix' => '<div id="edit-output-1" class="org-2">',
				                '#suffix' => '</div>',
				                '#attributes' => array('disabled' => FALSE),
				            ];
			            }else{
			           		$form['schoolname_1'] = [
				                '#placeholder' => t('Organization Name'),
				                '#type' => 'select', 
				                '#options' => ['' => 'Organization Name'],
				                '#prefix' => '<div id="edit-output-1" class="org-2">',
				                '#suffix' => '</div>',
				                '#attributes' => array('disabled' => FALSE),
				            ];
				                
			           }
			    
	    }
	    return  $form['schoolname_1'];
	}


	public function Get_Org_Name($type){
	    if(isset($type)){
	      $query = \Drupal::entityQuery('node');
	      $query->condition('type', 'bfss_organizations');
	      $query->condition('field_type', $type, 'IN');
	      $nids = $query->execute();
	      $org_name=[];
	      foreach($nids as $nid){
	        $node = Node::load($nid);
	        $org_name[]= $node->field_organization_name->value;
	      }
	      $result = implode(",",$org_name);
	    }
	    return $result;
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
      $query->range(0, 10);
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

    public function VenueLocationAjaxCallback(array &$form, FormStateInterface $form_state){

      return  $form['venue_loaction']; 
    }

}




function getStates() {
	return $st=array(
    'AL'=> t('AL'),
    'AK'=> t('AK'),
    'AZ'=> t('AZ'),
    'AR'=> t('AR'),
    'CA'=> t('CA'),
    'CO'=> t('CO'),
    'CT'=> t('CT'),
    'DE'=> t('DE'),
 	  'DC'=> t('DC'),
    'FL'=> t('FL'),
    'GA'=> t('GA'),
	  'HI'=> t('HI'),
    'ID'=> t('ID'),
    'IL'=> t('IL'),
    'IN'=> t('IN'),
    'IA'=> t('IA'),
    'KS'=> t('KS'),
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
    'ND'=> t('ND'),
    'OH'=> t('OH'),
    'OR'=> t('OR'),
    'MD'=> t('MD'),
    'MA'=> t('MA'),
    'MI'=> t('MI'),
    'MN'=> t('MN'),
    'MS'=> t('MS'),
    'MO'=> t('MO'),
    'PA'=> t('PA'),
    'RI'=> t('RI'),
    'SC'=> t('SC'),
    'SD'=> t('SD'),
    'TN'=> t('TN'),
    'TX'=> t('TX'),
    'UT'=> t('UT'),
    'VT'=> t('VT'),
    'VA'=> t('VA'),
    'WA'=> t('WA'),
    'WV'=> t('WV'),
    'WI'=> t('WI'),
    'WY'=> t('WY'));
}

?>
