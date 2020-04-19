<?php

/**
 * @file
 * Contains \Drupal\bfss_manager\Form\AddOrganizationForm.
 */

namespace Drupal\bfss_manager\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Drupal\date_popup\DatePopup;
use Drupal\date_popup\DatetimePopup;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * AddOrganization form.
 */
class AddOrganizationForm extends FormBase {
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

    
    $orgtype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
	  $states = getStates();
    $form['organizationState'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
	   //'#required' => TRUE,
      '#options' => $states,
      '#prefix' => '<div class="left_section popup_left_section"><div class="athlete_school"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Add New Organization</h3><div class=items_div><div class="add_pos_div_first"><div class="col-md-3 customselectcss">',
	  '#suffix' =>'</div>',
      //'#default_value' => $orgtype_1,
      );
	  
     $form['organizationType'] = array(
      '#type' => 'select',
	  '#options' => $orgtype,
      //'#description' => 'Select the desired pizza crust size.',
       '#required' => TRUE,
	   '#prefix' =>'<div class="col-md-9 customselectcss">',
	   '#suffix' =>'</div></div>',
      //'#default_value' => array_search($results5['athlete_school_name'], $orgname),
      //'#default_value' => $orgname_1,
      ); 
    
    $form['organizationName'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Orginization Name'),
      //'#description' => 'Select the desired pizza crust size.',
       '#required' => TRUE,
      //'#default_value' => array_search($results5['athlete_school_name'], $orgname),
      //'#default_value' => $orgname_1,
      );
      
      
    $form['address1'] = array(
      '#type' => 'textfield',
      '#placeholder' => t("Address 1"),
      );

    $form['address2'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Address 2'),

     // '#prefix' => '<div class="add_pos_div_first">',
      '#suffix' => '',
      );

    $form['city'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('City'),
      //'#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
	   '#prefix' => '<div class="add_pos_div_first"><div class="col-md-9 customselectcss">',
      '#suffix' => '</div>',
      );
	  
	  $form['zipcode'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Zip code'),
      //'#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
	   '#prefix' => '<div class="col-md-3 customselectcss">',
      '#suffix' => '</div></div></div>',
      );


      $orgtype2 =  array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['organizationState1'] = array( // uni
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $states,
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete" style="display:none;"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Add New Organization</h3><i id="athlete_uni" class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div><div class="col-md-3 customselectcss">',
		'#suffix'=>'</div>',
        );
      $form['organizationType1'] = array(
      '#type' => 'select',
	  '#options' => $orgtype2,
      //'#description' => 'Select the desired pizza crust size.',
       //'#required' => TRUE,
	   '#prefix' =>'<div class="col-md-9 customselectcss">',
	   '#suffix' =>'</div>',
      ); 
      $form['organizationName1'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
		'#placeholder' => t('Orginization Name'),

        );
      $form['address1_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Address 1"),
        );
      $form['address1_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Address 2"),
        );
      
      $form['city1'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('City'),
      //'#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
	   '#prefix' => '<div class="add_pos_div_first"><div class="col-md-9 customselectcss">',
      '#suffix' => '</div>',
      );
	  
	  $form['zipcode1'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Zip code'),
      //'#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
	   '#prefix' => '<div class="col-md-3 customselectcss">',
      '#suffix' => '</div></div></div></div>',
      );

    /*Add another organization 1 END*/
    /*Add another organization 1 start*/
  
      $form['organizationState2'] = array( // club
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $states,
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Add New Organization</h3><i class="fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i><div class=items_div><div class="col-md-3 customselectcss">',
		'#suffix'=>'</div>',
        );

     $form['organizationType2'] = array(
      '#type' => 'select',
	  '#options' => $orgtype2,
      //'#description' => 'Select the desired pizza crust size.',
      // '#required' => TRUE,
	   '#prefix' =>'<div class="col-md-9 customselectcss">',
	   '#suffix' =>'</div>',
      ); 
      $form['organizationName2'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
		'#placeholder' => t('Orginization Name'),

        );
      $form['address2_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Address 1"),
        );
      $form['address2_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Address 2"),
        );
      $form['city2'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('City'),
      //'#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
	   '#prefix' => '<div class="add_pos_div_first"><div class="col-md-9 customselectcss">',
      '#suffix' => '</div>',
      );
	  
	  $form['zipcode2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Zip Code'),
        '#suffix' => '</div></div></div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => '',
        '#prefix' => '<div class="col-md-3 customselectcss">',
        );
    
    /*Add another organization 1 END*/
    $form['submit'] = ['#type' => 'submit',
	'#value' => 'save', 
	'#prefix' => '<div id="athlete_submit">',
	'#suffix' => '</div></div><div class ="right_section"><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Organization Search</h3><div class=items_div>',
      //'#value' => t('Submit'),
      ];
	 $form['search_state'] = array(
      '#type' => 'select',
      //'#default_value' => $orgname_1,
      '#options'=>$states,  
	);  
	
	$form['search_text'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Search'),
      '#attributes' => array('disabled' => true),
//      '#default_value' => $orgsport_1,
	  '#suffix' => '</div></div></div>',
      );
	  
    
	 // print '<pre>';print_r($results13);die;
 
    

    // $form['#theme'] = 'athlete_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {

    if (!$form_state->getValue('organizationState') || empty($form_state->getValue('organizationState'))) {
      $form_state->setErrorByName('organizationState', $this->t('State should not be empty.'));
    }
    if (!$form_state->getValue('organizationType') || empty($form_state->getValue('organizationType'))) {
      $form_state->setErrorByName('organizationType', $this->t('Organization Type should not be empty.'));
    }
	if (!$form_state->getValue('organizationName') || empty($form_state->getValue('organizationName'))) {
      $form_state->setErrorByName('organizationName', $this->t('Organization name should not be empty.'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
	// Organization 1
	$organizationState= $form_state->getValue('organizationState');
	$organizationType= $form_state->getValue('organizationType'); 
	$organizationName= $form_state->getValue('organizationName');
	$address1= $form_state->getValue('address1');
	$address2= $form_state->getValue('address2');
	$city= $form_state->getValue('city');
	$zipcode= $form_state->getValue('zipcode');
	
	// Organization 2
	$organizationState1= $form_state->getValue('organizationState1');
	$organizationType1= $form_state->getValue('organizationType1'); 
	$organizationName1= $form_state->getValue('organizationName1');
	$address1_1= $form_state->getValue('address1_1');
	$address1_2= $form_state->getValue('address1_2');
	$city1= $form_state->getValue('city1');
	$zipcode1= $form_state->getValue('zipcode1');
	
	// Organization 3
	$organizationState2= $form_state->getValue('organizationState2');
	$organizationType2= $form_state->getValue('organizationType2'); 
	$organizationName2= $form_state->getValue('organizationName2');
	$address2_1= $form_state->getValue('address2_1');
	$address2_2= $form_state->getValue('address2_2');
	$city2= $form_state->getValue('city2');
	$zipcode2= $form_state->getValue('zipcode2');
	$counter=0;
	
    if($organizationState!='' && $organizationType!='' && $organizationName!=''){
      $conn->insert('bfss_organization')->fields(array(
        'bfss_org_state' => $organizationState,
        'bfss_org_type' => $organizationType,
        'bfss_org_name' => $organizationName,
        'bfss_org_address1' => $address1,
        'bfss_org_address2' => $address2,
        'bfss_org_city' => $city,
        'bfss_org_zip' => $zipcode,
        'bfss_org_user_id' => $current_user,
        'bfss_org_status' => 1,
        'bfss_post_date' => date('Y-m-d H:i:s'),
        ))->execute();
		$counter++;
		//drupal_set_message(t('Organization has been successfully.'));
    }
	if($organizationState1!='' && $organizationType1!='' && $organizationName1!=''){
      $conn->insert('bfss_organization')->fields(array(
        'bfss_org_state' => $organizationState1,
        'bfss_org_type' => $organizationType1,
        'bfss_org_name' => $organizationName1,
        'bfss_org_address1' => $address1_1,
        'bfss_org_address2' => $address1_2,
        'bfss_org_city' => $city1,
        'bfss_org_zip' => $zipcode1,
        'bfss_org_user_id' => $current_user,
        'bfss_org_status' => 1,
        'bfss_post_date' => date('Y-m-d H:i:s'),
        ))->execute();
		$counter++;
		//drupal_set_message(t('Organization has been successfully.'));
    }
	
	if($organizationState2!='' && $organizationType2!='' && $organizationName2!=''){
      $conn->insert('bfss_organization')->fields(array(
        'bfss_org_state' => $organizationState2,
        'bfss_org_type' => $organizationType2,
        'bfss_org_name' => $organizationName2,
        'bfss_org_address1' => $address2_1,
        'bfss_org_address2' => $address2_2,
        'bfss_org_city' => $city2,
        'bfss_org_zip' => $zipcode2,
        'bfss_org_user_id' => $current_user,
        'bfss_org_status' => 1,
        'bfss_post_date' => date('Y-m-d H:i:s'),
        ))->execute();
		$counter++;
		//drupal_set_message(t('Organization has been successfully.'));
    }

    if($counter>0){
		drupal_set_message(t($counter.' Organization has been created successfully.'));
	}
    
  
    
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
