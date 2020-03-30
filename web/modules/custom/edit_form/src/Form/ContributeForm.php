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

use Symfony\Component\HttpFoundation\RedirectResponse;

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

    $form['#attributes']['class'][] = 'edit_profile_form';

    if ($link) {
      $link = $link->toRenderable();
      $link['#attributes'] = ['target' => '__blank', 'class' => ['button', 'previewButton'], ];
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
    $query5 = \Drupal::database()->select('athlete_school', 'ats');
    $query5->fields('ats');
    $query5->condition('athlete_uid', $current_user, '=');
    $results5 = $query5->execute()->fetchAssoc();
    $query6 = \Drupal::database()->select('athlete_uni', 'atc');
    $query6->fields('atc');
    $query6->condition('athlete_uid', $current_user, '=');
    $results6 = $query6->execute()->fetchAssoc();
    $query7 = \Drupal::database()->select('athlete_info', 'ai');
    $query7->fields('ai');
    $query7->condition('athlete_uid', $current_user, '=');
    $results7 = $query7->execute()->fetchAssoc();
    $query8 = \Drupal::database()->select('user__field_state', 'ufs');
    $query8->fields('ufs');
    $query8->condition('entity_id', $current_user, '=');
    $results8 = $query8->execute()->fetchAssoc();
    $query9 = \Drupal::database()->select('athlete_about', 'aa');
    $query9->fields('aa');
    $query9->condition('athlete_uid', $current_user, '=');
    $results9 = $query9->execute()->fetchAssoc();
    $query10 = \Drupal::database()->select('athlete_social', 'asoc');
    $query10->fields('asoc');
    $query10->condition('athlete_uid', $current_user, '=');
    $results10 = $query10->execute()->fetchAssoc();
    $query12 = \Drupal::database()->select('athlete_uni', 'auni');
    $query12->fields('auni');
    $query12->condition('athlete_uid', $current_user, '=');
    $results12 = $query12->execute()->fetchAssoc();
    $query13 = \Drupal::database()->select('athlete_web', 'aweb');
    $query13->fields('aweb');
    $query13->condition('athlete_uid', $current_user, '=');
    $query13->condition('athlete_web_type', 1, '=');
    $results13 = $query13->execute()->fetchAssoc();
    $query14 = \Drupal::database()->select('athlete_addweb', 'aaweb');
    $query14->fields('aaweb');
    $query14->condition('athlete_uid', $current_user, '=');
    $results14 = $query14->execute()->fetchAssoc();
    $query_img = \Drupal::database()->select('athlete_prof_image', 'n');
    $query_img->addField('n', 'athlete_target_image_id');
    $query_img->condition('athlete_id', $current_user, '=');
	$query_img->orderBy('athlete_prof_id', 'DESC');
	$query_img->range(0, 1);
    $results = $query_img->execute()->fetchAssoc();
    $query15 = \Drupal::database()->select('athlete_addschool', 'aas');
    $query15->fields('aas');
    $query15->condition('athlete_uid', $current_user, '=');
    $results15 = $query15->execute()->fetchAssoc();
	
    $query16 = \Drupal::database()->select('athlete_club', 'aclub');
    $query16->fields('aclub');
    $query16->condition('athlete_uid', $current_user, '=');
    $results16 = $query16->execute()->fetchAssoc();
	
    $query17 = \Drupal::database()->select('athlete_clubweb', 'aclubw');
    $query17->fields('aclubw');
    $query17->condition('athlete_uid', $current_user, '=');
    $results17 = $query17->execute()->fetchAssoc();
    $query18 = \Drupal::database()->select('mydata', 'md');
    $query18->fields('md');
    $query18->condition('uid', $current_user, '=');
    $results18 = $query18->execute()->fetchAssoc();
	
	$delta0 = \Drupal::database()->select('athlete_web', 'md');
	$delta0->fields('md');
	$delta0->condition('athlete_uid', $current_user, '=');
	$delta0->condition('delta', 0, '=');
	$delta0->condition('athlete_web_type', 1, '=');
	$results_delta0 = $delta0->execute()->fetchAssoc();
	$deltaweb0=$results_delta0['athlete_web_name'];
	$deltaweb0_visibility=$results_delta0['athlete_web_visibility'];
	
	$delta1 = \Drupal::database()->select('athlete_web', 'md');
	$delta1->fields('md');
	$delta1->condition('athlete_uid', $current_user, '=');
	$delta1->condition('delta', 1, '=');
	$delta1->condition('athlete_web_type', 1, '=');
	$results_delta1 = $delta1->execute()->fetchAssoc();
	$deltaweb1=$results_delta1['athlete_web_name'];
	$deltaweb1_visibility=$results_delta1['athlete_web_visibility'];
	
	$delta2 = \Drupal::database()->select('athlete_web', 'md');
	$delta2->fields('md');
	$delta2->condition('athlete_uid', $current_user, '=');
	$delta2->condition('delta', 2, '=');
	$delta2->condition('athlete_web_type', 1, '=');
	$results_delta2 = $delta2->execute()->fetchAssoc();
	$deltaweb2=$results_delta2['athlete_web_name'];
	$deltaweb2_visibility=$results_delta2['athlete_web_visibility'];
	
	
	
	$query_org = \Drupal::database()->select('athlete_orginfo', 'n');
    $query_org->fields('n');
    $query_org->condition('athlete_id', $current_user, '=');
	$resultsorg = $query_org->execute()->fetchAssoc();
	
	$orgtype_1='';
	$orgname_1='';
	$orgcoach_1='';
	$orgsport_1='';
	$orgpos_1='';
	$orgpos2_1='';
	$orgpos3_1='';
	$orgstats_1='';
	
	$orgtype_2='';
	$orgname_2='';
	$orgcoach_2='';
	$orgsport_2='';
	$orgpos_2='';
	$orgpos2_2='';
	$orgpos3_2='';
	$orgstats_2='';
	
	$orgtype_3='';
	$orgname_3='';
	$orgcoach_3='';
	$orgsport_3='';
	$orgpos_3='';
	$orgpos2_3='';
	$orgpos3_3='';
	$orgstats_3='';
	
	if(empty($results18)){
		$type1=1;
		$type2=2;
		$type3=3;
	}else{
		$resultorginfo=json_decode($resultsorg['orgtype_text']);
		//print '<pre>';print_r(($resultorginfo->type1));die;
		$type1=$resultorginfo->type1->type1;
		$id1=$resultorginfo->type1->id;
		
		$type2=$resultorginfo->type2->type1;
		$id2=$resultorginfo->type2->id;
		$type3=$resultorginfo->type3->type1;
		$id3=$resultorginfo->type3->id;
		
		if($type1!=""){
			if($type1==1){
				$resulttype = \Drupal::database()->select('athlete_school', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype->condition('id', $id1, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_1=$resulttype1['athlete_school_type'];
				$orgname_1=$resulttype1['athlete_school_name'];
				$orgcoach_1=$resulttype1['athlete_school_coach'];
				$orgsport_1=$resulttype1['athlete_school_sport'];
				$orgpos_1=$resulttype1['athlete_school_pos'];
				$orgpos2_1=$resulttype1['athlete_school_pos2'];
				$orgpos3_1=$resulttype1['athlete_school_pos3'];
				$orgstats_1=$resulttype1['athlete_school_stat'];
			}elseif($type1==2){
				$resulttype = \Drupal::database()->select('athlete_club', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('id', $id1, '=');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_1=$resulttype1['athlete_school_type'];
				$orgname_1=$resulttype1['athlete_club_name'];
				$orgcoach_1=$resulttype1['athlete_club_coach'];
				$orgsport_1=$resulttype1['athlete_club_sport'];
				$orgpos_1=$resulttype1['athlete_club_pos'];
				$orgpos2_1=$resulttype1['athlete_school_pos2'];
				$orgpos3_1=$resulttype1['athlete_school_pos3'];
				$orgstats_1=$resulttype1['athlete_club_stat'];
			}elseif($type1==3){
				$resulttype = \Drupal::database()->select('athlete_uni', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('id', $id1, '=');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_1=$resulttype1['athlete_uni_type'];
				$orgname_1=$resulttype1['athlete_uni_name'];
				$orgcoach_1=$resulttype1['athlete_uni_coach'];
				$orgsport_1=$resulttype1['athlete_uni_sport'];
				$orgpos_1=$resulttype1['athlete_uni_pos'];
				$orgpos2_1=$resulttype1['athlete_uni_pos2'];
				$orgpos3_1=$resulttype1['athlete_uni_pos3'];
				$orgstats_1=$resulttype1['athlete_uni_stat'];
			}
		}
		
		if($type2!=""){
			if($type2==1){
				$resulttype = \Drupal::database()->select('athlete_school', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype->condition('id', $id2, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_2=$resulttype1['athlete_school_type'];
				$orgname_2=$resulttype1['athlete_school_name'];
				$orgcoach_2=$resulttype1['athlete_school_coach'];
				$orgsport_2=$resulttype1['athlete_school_sport'];
				$orgpos_2=$resulttype1['athlete_school_pos'];
				$orgpos2_2=$resulttype1['athlete_school_pos2'];
				$orgpos3_2=$resulttype1['athlete_school_pos3'];
				$orgstats_2=$resulttype1['athlete_school_stat'];
			}elseif($type2==2){
				$resulttype = \Drupal::database()->select('athlete_club', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype->condition('id', $id2, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_2=$resulttype1['athlete_school_type'];
				$orgname_2=$resulttype1['athlete_club_name'];
				$orgcoach_2=$resulttype1['athlete_club_coach'];
				$orgsport_2=$resulttype1['athlete_club_sport'];
				$orgpos_2=$resulttype1['athlete_club_pos'];
				$orgpos2_2=$resulttype1['athlete_school_pos2'];
				$orgpos3_2=$resulttype1['athlete_school_pos3'];
				$orgstats_2=$resulttype1['athlete_club_stat'];
			}elseif($type2==3){
				$resulttype = \Drupal::database()->select('athlete_uni', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype->condition('id', $id2, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_2=$resulttype1['athlete_uni_type'];
				$orgname_2=$resulttype1['athlete_uni_name'];
				$orgcoach_2=$resulttype1['athlete_uni_coach'];
				$orgsport_2=$resulttype1['athlete_uni_sport'];
				$orgpos_2=$resulttype1['athlete_uni_pos'];
				$orgpos2_2=$resulttype1['athlete_uni_pos2'];
				$orgpos3_2=$resulttype1['athlete_uni_pos3'];
				$orgstats_2=$resulttype1['athlete_uni_stat'];
			}
		}
		
		if($type3!=""){
			if($type3==1){
				$resulttype = \Drupal::database()->select('athlete_school', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype->condition('id', $id3, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_3=$resulttype1['athlete_school_type'];
				$orgname_3=$resulttype1['athlete_school_name'];
				$orgcoach_3=$resulttype1['athlete_school_coach'];
				$orgsport_3=$resulttype1['athlete_school_sport'];
				$orgpos_3=$resulttype1['athlete_school_pos'];
				$orgpos2_3=$resulttype1['athlete_school_pos2'];
				$orgpos3_3=$resulttype1['athlete_school_pos3'];
				$orgstats_3=$resulttype1['athlete_school_stat'];
			}elseif($type3==2){
				$resulttype = \Drupal::database()->select('athlete_club', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype->condition('id', $id3, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_3=$resulttype1['athlete_school_type'];
				$orgname_3=$resulttype1['athlete_club_name'];
				$orgcoach_3=$resulttype1['athlete_club_coach'];
				$orgsport_3=$resulttype1['athlete_club_sport'];
				$orgpos_3=$resulttype1['athlete_club_pos'];
				$orgpos2_3=$resulttype1['athlete_school_pos2'];
				$orgpos3_3=$resulttype1['athlete_school_pos3'];
				$orgstats_3=$resulttype1['athlete_club_stat'];
			}elseif($type3==3){
				$resulttype = \Drupal::database()->select('athlete_uni', 'ats');
				$resulttype->fields('ats');
				$resulttype->condition('athlete_uid', $current_user, '=');
				$resulttype->condition('id', $id3, '=');
				$resulttype1 = $resulttype->execute()->fetchAssoc();
				$orgtype_3=$resulttype1['athlete_uni_type'];
				$orgname_3=$resulttype1['athlete_uni_name'];
				$orgcoach_3=$resulttype1['athlete_uni_coach'];
				$orgsport_3=$resulttype1['athlete_uni_sport'];
				$orgpos_3=$resulttype1['athlete_uni_pos'];
				$orgpos2_3=$resulttype1['athlete_uni_pos2'];
				$orgpos3_3=$resulttype1['athlete_uni_pos3'];
				$orgstats_3=$resulttype1['athlete_uni_stat'];
			}
		}
	}


	if(empty($results18)){
		$cityquery1 = \Drupal::database()->select('user__field_state', 'ufln');
		$cityquery1->addField('ufln', 'field_state_value');
		$cityquery1->condition('entity_id', $current_user, '=');
		$cityresults1 = $cityquery1->execute()->fetchAssoc();
		$city=$cityresults1['field_state_value'];
	}else{
		$city=$results18['field_az'];
	}
	//print $city;die;
	/* 
	if($count_data_num_results>0){
		
	}else{
		
	} */
	//print $count_data_num_results;die;
   // $img_id = 357;
    $img_id = $results['athlete_target_image_id'];
    // echo "<pre>"; print_r($results8['field_state_value']);die;
    // $file = File::load($img_id);

    $form['prefix'] = "<div class=athlete_edit_class>";
    $form['suffix'] = "</div>";
    //     echo print_r($results1['0']->athlete_school_name);
    // echo print_r($results5);die;
    $form['fname'] = array(
      '#type' => 'textfield',
      //'#title' => t('Candidate Name:'),
      //      '#required' => TRUE,
      '#placeholder' => t('Firstname'),
      //'#default_values' => array(array('id')),
      '#default_value' => $results1['field_first_name_value'],
      '#prefix' => '<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Athletic Information</h3><div class=items_div>',
	  '#required' => TRUE,
      );
    $form['lname'] = array(
      '#type' => 'textfield',
      // '#title' => t('Mobile Number:'),
      '#placeholder' => t('Lastname'),
      '#default_value' => $results2['field_last_name_value'],
	  '#required' => TRUE,
      );
    $form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Preferred Contact Email'),
      '#default_value' => $results4['mail'],
	  '#required' => TRUE,
      );
	     $states = getStates();
    $form['az'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
	  '#options'=>$states,
      '#default_value' => $city,
	  '#required' => TRUE,
      );
    $form['city'] = array(
      '#type' => 'textfield',
      //'#title' => t('City'),
       '#required' => TRUE,
      '#placeholder' => t('City'),
      '#default_value' => $results18['field_city'],
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
      '#default_value' => $results7['athlete_state'],
	  '#required' => TRUE,
      );
	//  print DatePopup::class;die;
    $form['doj'] = array(
      //'#title' => 'Date of Birth',
      '#placeholder' => 'Date of Birth',
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
      //'#attributes' => array('disabled' => true),
      );

	  
    $form['grade'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Grade'),
      '#default_value' => $results7['athlete_city'],
	  '#required' => TRUE,
      );
    $form['gradyear'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Graduation Year'),
      '#default_value' => $results7['athlete_year'],
	  '#required' => TRUE,
      );
    $form['height'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Height in Inches'),
      '#default_value' => $results7['field_height'],
	  '#required' => TRUE,
      );
    $form['weight'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Weight in Pounds'),
      '#default_value' => $results7['field_weight'],
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
      '#placeholder' => t('Your Instagram Account(Optional)'),
      '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Social Media<i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
      '#default_value' => $results10['athlete_social_1'],
      );
    $form['youtube'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Your Youtube/Video Channel(Optional)'),
      '#suffix' => '</div></div>',
      '#default_value' => $results10['athlete_social_2'],
      );
    $orgtype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
    $form['organizationType'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
	   '#required' => TRUE,
      '#options' => $orgtype,
      '#prefix' => '<div class="athlete_school"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><div class=items_div>',
      '#default_value' => $orgtype_1,
      );
      
    
    $form['organizationName'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Orginization Name'),
      //'#description' => 'Select the desired pizza crust size.',
       '#required' => TRUE,
      //'#default_value' => array_search($results5['athlete_school_name'], $orgname),
      '#default_value' => $orgname_1,
      );
      
      
    $form['coach'] = array(
      '#type' => 'textfield',
      '#placeholder' => t("Coach's Last Name"),
      '#default_value' => $orgcoach_1,
      );
    $form['sport'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#default_value' => $orgsport_1,
      );
    $form['position'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Position'),
      '#default_value' => $orgpos_1,
      '#prefix' => '<div class="add_pos_div_first">',
      '#suffix' => '',
      );
    if ($orgpos2_1=='') {
      $form['position2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#attributes' => array('style' => 'display:none'),
        // '#prefix' => '<div class ="pos_first_1"',
        // '#suffix' => '</div>',
        );
    } else {
      $form['position2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $orgpos2_1,
        '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
        // '#prefix' => '<div class =pos_first_1',
        // '#suffix' => '</div>',
        );
    }
    if ($orgpos3_1=='') {
      $form['position3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
        // '#prefix' => '<div class ="pos_first_2"',
        // '#suffix' => '</div>',
        );
    } else {
      $form['position3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $orgpos3_1,
        '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
        // '#prefix' => '<div class =pos_first_2',
        // '#suffix' => '</div>',
        );
    }

    $form['stats'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Add all personal stats'),
      '#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
      '#suffix' => '</div></div>',
      '#default_value' => $orgstats_1,
      );

    /*Add another organization 1 start*/
	//print '<pre>';print_r($results12);die;
	//print $results12['athlete_uni_type'];die;
      // for type 2
	  
	  if ($orgtype_2!='') { //uni
	//print '<pre>';print_r($results12);die;
	//print $results12['athlete_uni_type'];die;
      $orgtype2 =  array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_1'] = array( // uni
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $orgtype2,
        '#prefix' => '</div><div class="org_notempty"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i id="athlete_uni" class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
        '#default_value' => $orgtype_2,
        );
      /*$orgname2 = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));*/
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
        '#default_value' => $orgname_2,
        );
      $form['coach_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        '#default_value' => $orgcoach_2,
        );
      $form['sport_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $orgsport_2,
        );
      $form['position_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $orgpos_2,
        '#prefix' => '<div class="add_pos_div_second">',
        '#suffix' => '',
        );
      if ($orgpos2_2=='') {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos2_2,
          '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos2_2,
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1',
          // '#suffix' => '</div>',
          );
      }
      if ($orgpos3_2=='') {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos3_2,
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos3_2,
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#prefix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div>',
        '#suffix' => '</div></div>',
        '#default_value' => $orgstats_2,
        );
    } else {

      $orgtype2 = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_1'] = array( //uni
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $orgtype2,
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete" style="display:none;"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon previous_delete" aria-hidden="true"></i><div class=items_div>',
        // '#default_value' => array_search($results12['athlete_uni_type'],$orgtype2),

        );
      /*$orgname2 = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));*/
      $form['schoolname_1'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
		'#placeholder' => t('Orginization Name'),
        //'#description' => 'Select the desired pizza crust size.',
        /*'#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        // '#default_value' => array_search($results12['athlete_uni_name'],$orgname2),
        '#default_value' => $results12['athlete_uni_name'],

        );
      $form['coach_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        // '#default_value' => $results12['athlete_uni_coach'],
        );
      $form['sport_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        // '#default_value' => $results12['athlete_uni_sport'],
        );
      $form['position_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        // '#default_value' => $results12['athlete_uni_pos'],
        '#prefix' => '<div class="add_pos_div_second">',
        '#suffix' => '',
        );
      if (empty($results6['athlete_uni_pos2'])) {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1',
          // '#suffix' => '</div>',
          );
      }
      if (empty($results6['athlete_uni_pos3'])) {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#prefix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div>',
        '#suffix' => '</div></div>',
        // '#default_value' => $results12['athlete_uni_stat'],
        );
    }
    

    /*Add another organization 1 END*/
    /*Add another organization 1 start*/
	
      if ($orgtype_3!='') {
      $unitype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_2'] = array(
        '#type' => 'select',
        '#options' => $unitype,
        '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i id="athlete_club" class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
        '#default_value' => $orgtype_3,
        );
      /*$uniname = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));*/
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
      $form['coach_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        '#default_value' => $orgcoach_3,
        );
      $form['sport_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $orgsport_3,
        );
      $form['position_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $orgpos_3,
        '#prefix' => '<div class="add_pos_div_third">',
        '#suffix' => '',
        );
      if ($orgpos2_3=='') {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos2_3,
          '#attributes' => array('class' => 'pos_hidden_third_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos2_3,
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1',
          // '#suffix' => '</div>',
          );
      }
      if ($orgpos3_3=='') {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos3_3,
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $orgpos3_3,
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_2'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => $orgstats_3,
        '#prefix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a></div>',
        );
    } else {
		$unitype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_2'] = array( // club
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $unitype,
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i><div class=items_div>',
        );

      $form['schoolname_2'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
		'#placeholder' => t('Orginization Name'),
        //'#description' => 'Select the desired pizza crust size.',
        /*'#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        );
      $form['coach_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        '#default_value' => '',
        );
      $form['sport_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => '',
        );
      $form['position_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => '',
        '#prefix' => '<div class="add_pos_div_third">',
        '#suffix' => '',
        );
      if (empty($results16['athlete_school_pos2'])) {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_hidden_first_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1 ',
          // '#suffix' => '</div>',
          );
      }
      if (empty($results16['athlete_school_pos3'])) {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_2'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => '',
        '#prefix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a></div>',
        );
    }
    /*Add another organization 1 END*/
    $form['submit'] = ['#type' => 'submit', '#value' => 'save', '#prefix' => '<div id="athlete_submit">', '#suffix' => '</div></div><div class ="right_section"><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website Photo</h3><div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul></div><div class=items_div>',
      //'#value' => t('Submit'),
      ];
    $form['image_athlete'] = ['#type' => 'managed_file', '#upload_validators' => ['file_validate_extensions' => ['gif png jpg jpeg'], 'file_validate_size' => [25600000], ], '#theme' => 'image_widget', '#preview_image_style' => 'medium', '#upload_location' => 'public://', '#required' => false, '#default_value' => array($img_id), '#prefix' => '</div>', '#suffix' => '<div class="action_bttn"><span>Action</span><ul><li>Remove</li></ul></div></div></div>', ];

    $form['school_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('School'),
      '#default_value' => $orgname_1,
      '#attributes' => array('disabled' => true),
      '#prefix' => '</div></div><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website</h3><div class=items_div>',
      );
    $form['sport_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#attributes' => array('disabled' => true),
      '#default_value' => $orgsport_1,
      );
	 // print '<pre>';print_r($results13);die;
    $form['name_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Pick a Name'),
      '#default_value' => $deltaweb0,
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
      '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br> Once published , this will become your permanent address and it can not be changed.<br>',
      );
    $form['preview_1'] = array(
      '#type' => 'markup',
      '#markup' => render($link),
      '#prefix' => "<div class='previewdiv' data-id='1'>",
      '#suffix' => "</div>",
      // '#type' => 'button',
      // '#default_value' => 'Preview Changes',
      );
    $form['web_visible_1'] = array(
      '#type' => 'select',
      '#options' => array(
        t('Website Visibility'),
        t('On'),
        t('Off')),
      '#default_value' => $deltaweb0_visibility,
      '#suffix' => '',
      );
    if ($orgtype_2!='') {
      $form['school_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $orgname_2,
        '#attributes' => array('disabled' => true),
        '#prefix' => '</div></div><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
        );
      $form['sport_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $orgsport_2,
        '#attributes' => array('disabled' => true),
        );
      $form['name_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $deltaweb1,
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
        '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br> Once published , this will become your permanent address and it can not be changed.<br>',
        );
      $form['preview_12'] = array(
        '#type' => 'markup',
        '#markup' => render($link),
        '#prefix' => "<div class='previewdiv' data-id='2'>",
        '#suffix' => "</div>",
        // '#type' => 'button',
        // '#default_value' => 'Preview Changes',
        );
      $form['web_visible_2'] = array(
        '#type' => 'select',
        '#options' => array(
          t('Website Visibility'),
          t('on'),
          t('off')),
        '#default_value' => $deltaweb1_visibility,
        '#suffix' => '</div></div>',
        );
    }
    if ($orgtype_3!='') {
      $form['school_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $orgname_3,
        '#attributes' => array('disabled' => true),
        '#prefix' => '<div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
        );
      $form['sport_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $orgsport_3,
        '#attributes' => array('disabled' => true),
        );
      $form['name_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $deltaweb2,
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
        '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br> Once published , this will become your permanent address and it can not be changed.<br>',
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
        '#default_value' => $deltaweb2_visibility,
        '#suffix' => '</div></div></div>',
        );
    }

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
    if (!$form_state->getValue('sex') || empty($form_state->getValue('sex'))) {
      $form_state->setErrorByName('sex', $this->t('Gender should not be empty.'));
    }
    if (!$form_state->getValue('city') || empty($form_state->getValue('city'))) {
      $form_state->setErrorByName('city', $this->t('City should not be empty.'));
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
    if (!$form_state->getValue('coach') || empty($form_state->getValue('coach'))) {
      $form_state->setErrorByName('coach', $this->t("Coach's last name should not be empty."));
    }
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
	
	$org_type1= $form_state->getValue('organizationType'); // school
	$org_type2= $form_state->getValue('education_1'); // club
	$org_type3= $form_state->getValue('education_2'); //uni
	
	
	
    $seltype1 = $form_state->getValue('organizationType');
    $selname1 = $form_state->getValue('organizationName');
    $seltype2 = $form_state->getValue('education_1');
    $selnameval2 = $form_state->getValue('schoolname_1');
    $seltypeval3 = $form_state->getValue('education_2');
    $selnameval3 = $form_state->getValue('schoolname_2');
    $selstate = $form_state->getValue('az');
    $seltypeval1 = $form['organizationType']['#options'][$seltype1];
    $selnameval1 = $form['organizationName']['#options'][$selname1];
    $seltypeval2 = $form['education_1']['#options'][$seltype2];


    $statevalue = $form['az']['#options'][$selstate];
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
	
    $query_web = \Drupal::database()->select('athlete_web', 'aw');
    $query_web->fields('aw');
    $query_web->condition('athlete_uid', $current_user, '=');
    $results_web = $query_web->execute()->fetchAll();
	
    $query_addweb = \Drupal::database()->select('athlete_addweb', 'aaw');
    $query_addweb->fields('aaw');
    $query_addweb->condition('athlete_uid', $current_user, '=');
    $results_addweb = $query_addweb->execute()->fetchAll();
	
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
	
    $query_web = \Drupal::database()->select('athlete_web', 'athw');
    $query_web->fields('athw');
    $query_web->condition('athlete_uid', $current_user, '=');
    $results_web = $query_web->execute()->fetchAll();
	
	
	$query_web_type_delta0 = \Drupal::database()->select('athlete_web', 'athw');
    $query_web_type_delta0->fields('athw');
    $query_web_type_delta0->condition('athlete_uid', $current_user, '=');
    $query_web_type_delta0->condition('athlete_web_type', 1, '=');
    $query_web_type_delta0->condition('delta', 0, '=');
    $results_web_type_delta0 = $query_web_type_delta0->execute()->fetchAll();
	
	
	$query_web_type_delta1 = \Drupal::database()->select('athlete_web', 'athw');
    $query_web_type_delta1->fields('athw');
    $query_web_type_delta1->condition('athlete_uid', $current_user, '=');
    $query_web_type_delta1->condition('athlete_web_type', 1, '=');
    $query_web_type_delta1->condition('delta', 1, '=');
    $results_web_type_delta1 = $query_web_type_delta1->execute()->fetchAll();
	
	$query_web_type_delta2 = \Drupal::database()->select('athlete_web', 'athw');
    $query_web_type_delta2->fields('athw');
    $query_web_type_delta2->condition('athlete_uid', $current_user, '=');
    $query_web_type_delta2->condition('athlete_web_type', 1, '=');
    $query_web_type_delta2->condition('delta', 2, '=');
    $results_web_type_delta2 = $query_web_type_delta2->execute()->fetchAll();
	
	
    $query_addweb = \Drupal::database()->select('athlete_addweb', 'athaw');
    $query_addweb->fields('athaw');
    $query_addweb->condition('athlete_uid', $current_user, '=');
    $results_addweb = $query_addweb->execute()->fetchAll();
	
    $query_clubweb = \Drupal::database()->select('athlete_clubweb', 'athawa');
    $query_clubweb->fields('athawa');
    $query_clubweb->condition('athlete_uid', $current_user, '=');
    $results_clubweb = $query_clubweb->execute()->fetchAll();
	
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
    $conn->update('user__field_state')->condition('entity_id', $current_user, '=')->fields(array('field_state_value' => $statevalue, ))->execute();


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

    if (empty($results_web)) {
      $conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('instagram'),
        'athlete_web_visibility' => $form_state->getValue('youtube'),
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('instagram'),
        'athlete_web_visibility' => $form_state->getValue('youtube'),
        ))->execute();
    }
	
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
        'athlete_web_type' => 1,
        'delta' => 1,
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->condition('athlete_web_type', 1, '=')->condition('delta', 1, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('name_web2'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_2'),
        ))->execute();
    }
	
	if (empty($results_web_type_delta2)) {
      $conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('name_web3'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_3'),
        'athlete_web_type' => 1,
        'delta' => 2,
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->condition('athlete_web_type', 1, '=')->condition('delta', 2, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('name_web3'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_3'),
        ))->execute();
    }
	
    if (empty($results_mydata)) {
      $conn->insert('mydata')->fields(array(
        'uid' => $current_user,
        'field_az' => $form_state->getValue('az'),
        'field_city' => $form_state->getValue('city'),
        ))->execute();
    } else {
      $conn->update('mydata')->condition('uid', $current_user, '=')->fields(array(
        'field_az' => $form_state->getValue('az'),
        'field_city' => $form_state->getValue('city'),
        ))->execute();
    }

    if (empty($results_info)) {
      $conn->insert('athlete_info')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_email' => $form_state->getValue('email'),
        'athlete_state' => $form_state->getValue('sex'),
        'athlete_city' => $form_state->getValue('grade'),
        'athlete_coach' => $form_state->getValue('coach'),
        'athlete_year' => $form_state->getValue('gradyear'),
        'field_height' => $form_state->getValue('height'),
        'field_weight' => $form_state->getValue('weight'),
        'popup_flag' => $popupFlag,
        ))->execute();
    } else {
      $conn->update('athlete_info')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_email' => $form_state->getValue('email'),
        'athlete_state' => $form_state->getValue('sex'),
        'athlete_city' => $form_state->getValue('grade'),
        'athlete_coach' => $form_state->getValue('coach'),
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
	
	/* for selection in Type 1 starts here ==== */
	if($org_type1==1){

		$conn->insert('athlete_school')->fields(array(
		'athlete_uid' => $current_user,
		'athlete_school_name' => $form_state->getValue('organizationName'),
		'athlete_school_coach' => $form_state->getValue('coach'),
		'athlete_school_sport' => $form_state->getValue('sport'),
		'athlete_school_pos' => $form_state->getValue('position'),
		'athlete_school_pos2' => $form_state->getValue('position2'),
		'athlete_school_pos3' => $form_state->getValue('position3'),
		'athlete_school_stat' => $form_state->getValue('stats'),
		'athlete_school_type' => $org_type1,
		))->execute();
		
		$query_sch = \Drupal::database()->select('athlete_school', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id1 = $results['id'];
		
	}elseif($org_type1==2){
        $conn->insert('athlete_club')->fields(array(
          'athlete_uid' => $current_user,
          'athlete_club_name' => $form_state->getValue('organizationName'),
          'athlete_club_coach' => $form_state->getValue('coach'),
          'athlete_club_sport' => $form_state->getValue('sport'),
          'athlete_club_pos' => $form_state->getValue('position'),
          'athlete_school_pos2' => $form_state->getValue('position2'),
          'athlete_school_pos3' => $form_state->getValue('position3'),
          'athlete_club_stat' => $form_state->getValue('stats'),
          'athlete_school_type' => $org_type1,
          ))->execute();
      
	  
		$query_sch = \Drupal::database()->select('athlete_club', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id1 = $results['id'];
    
	}elseif($org_type1==3){
      $conn->insert('athlete_uni')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_uni_name' => $form_state->getValue('organizationName'),
        'athlete_uni_coach' => $form_state->getValue('coach'),
        'athlete_uni_sport' => $form_state->getValue('sport'),
        'athlete_uni_pos' => $form_state->getValue('position'),
        'athlete_uni_pos2' => $form_state->getValue('position2'),
        'athlete_uni_pos3' => $form_state->getValue('position3'),
        'athlete_uni_stat' => $form_state->getValue('stats'),
        'athlete_uni_type' => $org_type1,
        ))->execute();
		
		$query_sch = \Drupal::database()->select('athlete_uni', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id1 = $results['id'];
    
	}
	/* for selection in Type 1 ends here ==== */
	
	/* for selection in Type 2 starts here ==== */
	if($org_type2==1){
		$conn->insert('athlete_school')->fields(array(
		'athlete_uid' => $current_user,
		'athlete_school_name' => $form_state->getValue('schoolname_1'),
		'athlete_school_coach' => $form_state->getValue('coach_1'),
		'athlete_school_sport' => $form_state->getValue('sport_1'),
		'athlete_school_pos' => $form_state->getValue('position_1'),
		'athlete_school_pos2' => $form_state->getValue('position_12'),
		'athlete_school_pos3' => $form_state->getValue('position_13'),
		'athlete_school_stat' => $form_state->getValue('stats_1'),
		'athlete_school_type' => $org_type2,
		))->execute();
		
		$query_sch = \Drupal::database()->select('athlete_school', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id2 = $results['id'];
	}elseif($org_type2==2){
        $conn->insert('athlete_club')->fields(array(
          'athlete_uid' => $current_user,
          'athlete_club_name' => $form_state->getValue('schoolname_1'),
          'athlete_club_coach' => $form_state->getValue('coach_1'),
          'athlete_club_sport' => $form_state->getValue('sport_1'),
          'athlete_club_pos' => $form_state->getValue('position_1'),
          'athlete_school_pos2' => $form_state->getValue('position_12'),
          'athlete_school_pos3' => $form_state->getValue('position_13'),
          'athlete_club_stat' => $form_state->getValue('stats_1'),
          'athlete_school_type' => $org_type2,
          ))->execute();
		$query_sch = \Drupal::database()->select('athlete_club', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id2 = $results['id'];
    
	}elseif($org_type2==3){
      $conn->insert('athlete_uni')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_uni_name' => $form_state->getValue('schoolname_1'),
        'athlete_uni_coach' => $form_state->getValue('coach_1'),
        'athlete_uni_sport' => $form_state->getValue('sport_1'),
        'athlete_uni_pos' => $form_state->getValue('position_1'),
        'athlete_uni_pos2' => $form_state->getValue('position_12'),
        'athlete_uni_pos3' => $form_state->getValue('position_13'),
        'athlete_uni_stat' => $form_state->getValue('stats_1'),
        'athlete_uni_type' => $org_type2,
        ))->execute();
		$query_sch = \Drupal::database()->select('athlete_uni', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id2 = $results['id'];
	}
	/* for selection in Type 2 ends here ==== */
	
	/* for selection in Type 3 starts here ==== */
	if($org_type3==1){
		
		$conn->insert('athlete_school')->fields(array(
		'athlete_uid' => $current_user,
		'athlete_school_name' => $form_state->getValue('schoolname_2'),
		'athlete_school_coach' => $form_state->getValue('coach_2'),
		'athlete_school_sport' => $form_state->getValue('sport_2'),
		'athlete_school_pos' => $form_state->getValue('position_2'),
		'athlete_school_pos2' => $form_state->getValue('position_22'),
		'athlete_school_pos3' => $form_state->getValue('position_23'),
		'athlete_school_stat' => $form_state->getValue('stats_2'),
		'athlete_school_type' => $org_type3,
		))->execute();
		$query_sch = \Drupal::database()->select('athlete_school', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id3 = $results['id'];
		
	}elseif($org_type3==2){
        $conn->insert('athlete_club')->fields(array(
          'athlete_uid' => $current_user,
          'athlete_club_name' => $form_state->getValue('schoolname_2'),
          'athlete_club_coach' => $form_state->getValue('coach_2'),
          'athlete_club_sport' => $form_state->getValue('sport_2'),
          'athlete_club_pos' => $form_state->getValue('position_2'),
          'athlete_school_pos2' => $form_state->getValue('position_22'),
          'athlete_school_pos3' => $form_state->getValue('position_23'),
          'athlete_club_stat' => $form_state->getValue('stats_2'),
          'athlete_school_type' => $org_type3,
          ))->execute();
		
		$query_sch = \Drupal::database()->select('athlete_club', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id3 = $results['id'];
	}elseif($org_type3==3){
      $conn->insert('athlete_uni')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_uni_name' => $form_state->getValue('schoolname_2'),
        'athlete_uni_coach' => $form_state->getValue('coach_2'),
        'athlete_uni_sport' => $form_state->getValue('sport_2'),
        'athlete_uni_pos' => $form_state->getValue('position_2'),
        'athlete_uni_pos2' => $form_state->getValue('position_22'),
        'athlete_uni_pos3' => $form_state->getValue('position_23'),
        'athlete_uni_stat' => $form_state->getValue('stats_2'),
        'athlete_uni_type' => $org_type3,
        ))->execute();
		$query_sch = \Drupal::database()->select('athlete_uni', 'n');
		$query_sch->addField('n', 'id');
		$query_sch->condition('athlete_uid', $current_user, '=');
		$query_sch->orderBy('id', 'DESC');
		$query_sch->range(0, 1);
		$results = $query_sch->execute()->fetchAssoc();
		$id3 = $results['id'];
	}
	/* for selection in Type 3 ends here ==== */
	 
	 
	
	$query_orginfo= \Drupal::database()->select('athlete_orginfo', 'orginfo');
    $query_orginfo->fields('orginfo');
    $query_orginfo->condition('athlete_id', $current_user, '=');
    $results_orginfo = $query_orginfo->execute()->fetchAll();
	$count_school_num_results = count($results_orginfo);
	
	
	
	
	$type1_dt=array('type1'=>$org_type1,'id'=>$id1);
	$type2_dt=array('type1'=>$org_type2,'id'=>$id2);
	$type3_dt=array('type1'=>$org_type3,'id'=>$id3);
	$textdata=array('type1'=>$type1_dt,'type2'=>$type2_dt,'type3'=>$type3_dt);
	if($count_school_num_results==0){
		
		$conn->insert('athlete_orginfo')->fields(array(
        'athlete_id' => $current_user,
        'orgtype_text' => json_encode($textdata),
        ))->execute();
	}else{
		$conn->update('athlete_orginfo')->condition('athlete_id', $current_user, '=')->fields(array('orgtype_text' => json_encode($textdata), ))->execute();
	}
    
    drupal_set_message(t('An error occurred and processing did not complete.'), 'error');
    // $form_state->setRedirect('acme_hello');
    // return;
  }
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

?>
