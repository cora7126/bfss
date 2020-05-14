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
use \Drupal\user\Entity\User;
/**
 * Contribute form.
 */
class test extends FormBase {
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
    $userdt = User::load($current_user);
    $field_state =  $userdt->get('field_state')->value;
    $roles_user = \Drupal::currentUser()->getRoles();
    $query18 = \Drupal::database()->select('mydata', 'md');
    $query18->fields('md');
    $query18->condition('uid', $current_user, '=');
    $results18 = $query18->execute()->fetchAssoc();
  


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
        $query4->fields('ufln4');
        $query4->condition('uid', $current_user,'=');
        $results4 = $query4->execute()->fetchAssoc();
	$query5 = \Drupal::database()->select('user__field_mobile', 'ufm');
        $query5->addField('ufm', 'field_mobile_value');
        $query5->condition('entity_id', $current_user,'=');
        $results5 = $query5->execute()->fetchAssoc();
	$query6 = \Drupal::database()->select('user__field_mobile_2', 'ufm2');
        $query6->addField('ufm2', 'field_mobile_2_value');
        $query6->condition('entity_id', $current_user,'=');
        $results6 = $query6->execute()->fetchAssoc();
	$query_img = \Drupal::database()->select('user__user_picture', 'n');
        $query_img->addField('n', 'user_picture_target_id');
        $query_img->condition('entity_id', $current_user,'=');
        $results = $query_img->execute()->fetchAssoc();
	$img_id = $results['user_picture_target_id'];
        $file = File::load($img_id);
		if(!empty($file)){
			$url = $file->url();
		}


    $form['username'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $results4['name'],
	  '#prefix'=>'<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Login Information</h3><div class=items_div>',
	  '#attributes' => array('disabled'=>true),
      );
    if(in_array('assessors', $roles_user)){
      $hd_title = "ASSESSORS&#39; Information";
    }elseif(in_array('coach', $roles_user)){
      $hd_title = "COACHES&#39;s Information";
    }elseif(in_array('athlete', $roles_user)){
      $hd_title = "ATHLETE&#39;s Information"; 
    }elseif(in_array('bfss_administrator', $roles_user)){
      $hd_title = "ADMIN&#39;s Information"; 
    }else{
      $hd_title = "USER&#39;s Information"; 
    }
	$form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Email'),
      '#required' => TRUE,
      '#default_value' => $results4['mail'],
      '#attributes' => array('disabled'=>true),
	  '#prefix' => '',
	  '#suffix' => '<a class="change_pass" id="change_id" href="javascript:void(0)">Change Password</a></div></div><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>'.$hd_title.'</h3><div class=items_div>',
      );

      $form['fname'] = array(
      '#type' => 'textfield',
      '#default_value' => $results1['field_first_name_value'],
      '#attributes' => array('disabled'=>true),
	  
      );
	 if(in_array('assessors', $roles_user)){

      $form['city_state'] = array(
      '#type' => 'textfield',
      '#default_value' => 'Gilbert, '.$field_state,
      '#attributes' => array('disabled'=>true),
      );


     // $form['submit'] = [
     //    '#type' => 'submit',
     //    '#value' => 'save',
     //    '#prefix' =>'<div id="athlete_submit" class="assessor-btn user-submit-button">',
     //    '#suffix' => '</div>',
     //        //'#value' => t('Submit'),
     //    ];
    
    }
    if(!in_array('assessors', $roles_user)){

       $form['lname'] = array(
      '#type' => 'textfield',
     // '#title' => t('Mobile Number:'),
      // '#placeholder' => t('Bloggs'),
      '#default_value' => $results2['field_last_name_value'],
    '#attributes' => array('disabled'=>true),
      );

        $form['numberone'] = array(
            '#type' => 'textfield',
            '#placeholder' => 'Phone Number',
            #'#required' => TRUE,
             '#default_value' => $results5['field_mobile_value'],
          ); 
        if(!in_array('coach', $roles_user)){
          $form['date_joined'] = array (
            '#type' => 'textfield',
            '#placeholder' => t('Date joined'),
            '#suffix' => '</div></div>',
            '#default_value' => substr($results3['field_date_value'],0,10),
            // '#attributes' => array('disabled'=>true),
            '#attributes' => array('id' => array('datepicker')),
          );
        }
    }
    // for coach
    if(in_array('coach', $roles_user)){

      $states = $this->getStates();
      $form['az'] = array(
      '#type' => 'select',
      '#options'=>$states,
      '#default_value' => $results18['field_az'],
      '#required' => TRUE,
        );
      $form['city'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('City'),
        '#required' => TRUE,
        '#default_value' => $results18['field_city'],
      );
      $gender_arr =  array('' => 'Select Gender','male' => 'Male','female' => 'Female','other' => 'Other');
      $form['sextype'] = array(
      '#type' => 'select',
      '#suffix' => '</div></div>',
      '#options' => $gender_arr,
      '#required' => TRUE,
      '#default_value' => $results18['field_birth_gender'],
      );
    
     /* Organization START */
	 	$query_bfss_coach = \Drupal::database()->select('bfss_coach', 'bc');
	    $query_bfss_coach->fields('bc');
	    $query_bfss_coach->condition('coach_uid',$current_user, '=');
	    $results_bfss_coach = $query_bfss_coach->execute()->fetchAll();


     //organizationType1
        $orgtype1 = array(
          ""=>t('Organization Type'),
          "school"=>t('School'),
          "club"=>t('Club'),
          "university"=>t('University'));
        $form['organizationType1'] = array(
          '#type' => 'select',
         '#required' => TRUE,
          '#options' => $orgtype1,
          '#prefix' => '<div class="athlete_school bfssAthleteProfile"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><div class=items_div>',
          '#default_value' => $results_bfss_coach[0]->field_organization_type_one,
          );
          
        
        $form['organizationName1'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Orginization Name'),
          '#required' => TRUE,
          '#default_value' => $results_bfss_coach[0]->field_organization_name_one,
          );
          
          
        $form['coachtitle1'] = array(
          '#type' => 'textfield',
          '#required' => TRUE,
          '#placeholder' => t("Coach Title"),
          '#default_value' => $results_bfss_coach[0]->field_coach_title_one,
          );

        
       
        $form['sport1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#required' => TRUE,
        #'#options'=>$sports_arr,
        '#default_value' => $results_bfss_coach[0]->field_sport_one,
          );

        $form['year1'] = array(
          '#type' => 'textfield',
          '#placeholder' => t("Year"),
          '#required' => TRUE,
          '#default_value' => $results_bfss_coach[0]->field_year_one,
          '#suffix' => '</div>',
          );
    //organizationType2
        $orgtype2 = array(
          ""=>t('Organization Type'),
          "school"=>t('School'),
          "club"=>t('Club'),
          "university"=>t('University')
      	);


      $form['organizationType2'] = array( //uni
        '#type' => 'select',
        '#options' => $orgtype2,
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete" style="display:none;"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon previous_delete" aria-hidden="true"></i><div class=items_div>',
        '#default_value' => isset($results_bfss_coach[0]->field_organization_type_two)?$results_bfss_coach[0]->field_organization_type_two:'',
        );

          $form['organizationName2'] = array(
            '#type' => 'textfield',
            '#placeholder' => t('Orginization Name'),
            '#default_value' => isset($results_bfss_coach[0]->field_organization_name_two)?$results_bfss_coach[0]->field_organization_name_two:'',
            );

          $form['coachtitle2'] = array(
            '#type' => 'textfield',
            '#placeholder' => t("Coache Title"),
            '#default_value' => isset($results_bfss_coach[0]->field_coach_title_two)?$results_bfss_coach[0]->field_coach_title_two:'',
            );

           $form['sport2'] = array(
            '#type' => 'textfield',
            '#placeholder' => t('Sport'),
           
            '#default_value' => isset($results_bfss_coach[0]->field_sport_two)?$results_bfss_coach[0]->field_sport_two:'',
            );
          $form['year2'] = array(
            '#type' => 'textfield',
            '#placeholder' => t("Year"),
            '#default_value' => isset($results_bfss_coach[0]->field_year_two)?$results_bfss_coach[0]->field_year_two:'',
            '#prefix' => '<div class="add_pos_div_second"></div>',
            '#suffix' => '</div></div>',
            );



    //organizationType3
         $orgtype3 = array(
          ""=>t('Organization Type'),
          "school"=>t('School'),
          "club"=>t('Club'),
          "university"=>t('University')
      	);


          $form['organizationType3'] = array( // club
            '#type' => 'select',
            '#options' => $orgtype3,
            '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i><div class=items_div>',
            '#default_value' => isset($results_bfss_coach[0]->field_organization_type_three)?$results_bfss_coach[0]->field_organization_type_three:'',
            );

          $form['organizationName3'] = array(
            '#type' => 'textfield',
            '#placeholder' => t('Orginization Name'),
            '#default_value' =>  isset($results_bfss_coach[0]->field_organization_name_three)?$results_bfss_coach[0]->field_organization_name_three:'',
            );

          $form['coachtitle3'] = array(
            '#type' => 'textfield',
            '#placeholder' => t("Coache Title"),
            '#default_value' => isset($results_bfss_coach[0]->field_coach_title_three)?$results_bfss_coach[0]->field_coach_title_three:'',
            );

           $form['sport3'] = array(
            '#type' => 'textfield',
            '#placeholder' => t('Sport'),
            #'#options'=> $results_bfss_coach[0]->field_sport_three,
            '#default_value' =>isset($results_bfss_coach[0]->field_sport_three)?$results_bfss_coach[0]->field_sport_three:'',
             
          );
          $form['year3'] = array(
            '#type' => 'textfield',
            '#placeholder' => t("Year"),
            '#default_value' => isset($results_bfss_coach[0]->field_year_three)?$results_bfss_coach[0]->field_year_three:'',
            '#prefix' => '<div class="add_pos_div_third">',
            '#suffix' => '</div></div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
          );

         
    }
    /* Organization END */
if(!in_array('assessors', $roles_user)){



$form['html_image_athlete'] = [
  '#type' => 'markup',
  '#markup' => '</div>
  <div class ="right_section">
    <div class = "athlete_right">
      <h3><div class="toggle_icon">
          <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
        </div>My Website Photo
      </h3>
      <div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul>
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
    '#suffix' => '<div class="action_bttn">
            <span>Action</span><ul><li>Remove</li></ul>
    </div></div></div>',
    ];
 
}

if(in_array('coach', $roles_user)){
	$form['instagram_account'] = array(
	'#type' => 'textfield',
	'#placeholder' => t('TEAM Instagram Account(Optional)'),
	'#default_value' => isset($results_bfss_coach[0]->field_instagram)?$results_bfss_coach[0]->field_instagram:'',

	'#prefix' => '
	</div></div>
	<div class = "athlete_right">
	<h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SCHOOL/TEAM SCOCIAL MEDIA</h3>
	<div class=items_div>',
	);


	$form['youtube_account'] = array(
	  '#type' => 'textfield',
	  '#placeholder' => t('TEAM Youtube/Video Channel(Optional)'),
	  '#default_value' => isset($results_bfss_coach[0]->field_youtube)?$results_bfss_coach[0]->field_youtube:'',
	);
	 
}
  

    if ($orgtype_2!='') {
      $form['school_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $orgname_2,
        '#attributes' => array('disabled' => true),
        '#prefix' => '</div></div><div class = "athlete_right edit-user-display"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
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


 //CHANGE PASSWORD FIELDS
     $form['pass_label'] = array(
      '#type' => 'label',
      '#value' => t('Your password must be at least 8 characters long and contain at least one number and one character'),
    '#prefix' => '</div><div id="changepassdiv" class="changePassword_popup"><div class="popup_header change_password_header"><h3>Change Password <i class="fa fa-times right-icon changepassdiv-modal-close spb_close" aria-hidden="true"></i></h3></div>',
      );
    $form['current_pass'] = array(
      '#type' => 'password',
      '#placeholder' => t('Old Password'),
      );
    $form['newpass'] = array(
      '#type' => 'password',
      '#placeholder' => t('New Password'),
      );
    $form['newpassconfirm'] = array(
      '#type' => 'password',
      '#placeholder' => t('Confirm New Password'),
      );
    
    $form['pass_error'] = array(
      '#type' => 'label',
      '#value' => t('Incorrect entry,please try again.'),
    '#suffix' => '<span class="passerror"> Need more help? Click here </span>',
      );
    $form['changebutton'] = [
        '#type' => 'label',
        '#title' => 'update',
    '#prefix' =>'',
    '#suffix' => '</div>',
    '#attributes' => array('id'=>'save_pass','style'=>'cursor:pointer; background:green;padding: 5px;
    border-radius: 3px;'),
    ];
 $form['label_text'] = array(
      '#type' => 'label',
      '#title' => 'No longer need your Parent / Guardian on your account and want to remove them? <br> You can request Parent / Guardian removal from your account via our ticketing system.',
      '#prefix' => '
      <div class ="right_section box-pre"><div class="athlete_right">',
      '#suffix' => '</div></div>',
      '#attributes' => array('id => parent_label'),
    );
    //end change password
     if(!in_array('assessors', $roles_user)){
   $form['submit'] = ['#type' => 'submit', 
      '#value' => 'SAVE', 
      '#prefix' => '</div></div><div id="athlete_submit" class="athlete_submit">',
      '#suffix' => '</div>',
      //'#value' => t('Submit'),
    ];
  }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
   if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
        $form_state->setErrorByName('email', $this->t('Please enter a valid email.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser()->id();
    $roles_user = \Drupal::currentUser()->getRoles();
    $conn = Database::getConnection();

    //user profile 
	  $query_pic = \Drupal::database()->select('user__user_picture', 'uup');
		$query_pic->fields('uup');
		$query_pic->condition('entity_id', $current_user,'=');
		$results_pic = $query_pic->execute()->fetchAll();	
    $imgid = $form_state->getValue('image_athlete');
				if(empty($results_pic)){
          if(isset($imgid[0])){
					 $conn->insert('user__user_picture')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => '0',
							'user_picture_target_id' => $imgid[0],
							)
					)->execute();
          }
				}else {
					if(!empty($imgid[0])){
						$conn->update('user__user_picture')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'user_picture_target_id' => $imgid[0],
							)
						)
						->execute();
					}else{
						$conn->update('user__user_picture')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'user_picture_target_id' => '240',
							)
						)
						->execute();
					}
                
			}

		//joining date	

       if(in_array('coach', $roles_user) || in_array('assessors', $roles_user)){
         // for assessors or coach here
        }else{
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
                'field_date_value' => $form_state->getValue('date_joined'),
                ))->execute();
            }
            else {
                $conn->update('user__field_date')->condition('entity_id', $current_user, '=')->fields(array(
                'field_date_value' => $form_state->getValue('date_joined'),
                ))->execute();
            }
        }

    if(!in_array('coach', $roles_user) || !in_array('assessors', $roles_user)){

  	  
      }


	  //mydata
    $query_mydata = \Drupal::database()->select('mydata', 'md');
    $query_mydata->fields('md');
    $query_mydata->condition('uid', $current_user, '=');
    $results_mydata = $query_mydata->execute()->fetchAll();

    if (empty($results_mydata)) {
        $conn->insert('mydata')->fields(array(
          'uid' => $current_user,
          'field_az' => $form_state->getValue('az'),
          'field_city' => $form_state->getValue('city'),
          'field_birth_gender' => $form_state->getValue('sextype'),
          ))->execute();
      } else {
        $conn->update('mydata')->condition('uid', $current_user, '=')->fields(array(
          'field_az' => $form_state->getValue('az'),
          'field_city' => $form_state->getValue('city'),
          'field_birth_gender' => $form_state->getValue('sextype'),
          ))->execute();
      } 
      if(in_array('coach', $roles_user)){
        //org
		$query_bfss_coach = \Drupal::database()->select('bfss_coach', 'bc');
	    $query_bfss_coach->fields('bc');
	    $query_bfss_coach->condition('coach_uid',$current_user, '=');
	    $results_bfss_coach = $query_bfss_coach->execute()->fetchAll();
	    $arrfields = array(
	            //one
	            'coach_uid' => $current_user,
	            'field_organization_type_one' => !empty($form_state->getValue('organizationType1'))?$form_state->getValue('organizationType1'):'',
	            'field_organization_name_one' => !empty($form_state->getValue('organizationName1'))?$form_state->getValue('organizationName1'):'',
	            'field_coach_title_one' => !empty($form_state->getValue('coachtitle1'))?$form_state->getValue('coachtitle1'):'',
	            'field_sport_one' => !empty($form_state->getValue('sport1'))?$form_state->getValue('sport1'):'',
	            'field_year_one' => !empty($form_state->getValue('year1'))?$form_state->getValue('year1'):'',
	            //two
	            #'coach_uid' => $current_user,
	            'field_organization_type_two' => !empty($form_state->getValue('organizationType2'))?$form_state->getValue('organizationType2'):'',
	            'field_organization_name_two' => !empty($form_state->getValue('organizationName2'))?$form_state->getValue('organizationName2'):'',
	            'field_coach_title_two' => !empty($form_state->getValue('coachtitle2'))?$form_state->getValue('coachtitle2'):'',
	            'field_sport_two' => !empty($form_state->getValue('sport2'))?$form_state->getValue('sport2'):'',
	            'field_year_two' => !empty($form_state->getValue('year2'))?$form_state->getValue('year2'):'',
	            //three
	            #'coach_uid' => $current_user,
	            'field_organization_type_three' => !empty($form_state->getValue('organizationType3'))?$form_state->getValue('organizationType3'):'',
	            'field_organization_name_three' => !empty($form_state->getValue('organizationName3'))?$form_state->getValue('organizationName3'):'',
	            'field_coach_title_three' => !empty($form_state->getValue('coachtitle3'))?$form_state->getValue('coachtitle3'):'',
	            'field_sport_three' => !empty($form_state->getValue('sport3'))?$form_state->getValue('sport3'):'',
	            'field_year_three' => !empty($form_state->getValue('year3'))?$form_state->getValue('year3'):'',
	            //social media
	            'field_instagram' => !empty($form_state->getValue('instagram_account'))?$form_state->getValue('instagram_account'):'',
	            'field_youtube' => !empty($form_state->getValue('youtube_account'))?$form_state->getValue('youtube_account'):'',
	        );
	    if(empty($results_bfss_coach)){
	        $conn->insert('bfss_coach')
	        ->fields($arrfields)
	        ->execute();
	    }else{
	    	$conn->update('bfss_coach')
	        ->condition('coach_uid',$current_user,'=')
	        ->fields($arrfields)
	        ->execute();
	    }
      }
		
    //mobile field

    $query = \Drupal::database()->select('user__field_mobile', 'ufm');
    $query->fields('ufm');
    $query->condition('entity_id', $current_user,'=');
    $results = $query->execute()->fetchAll(); 	


   if(in_array('coach', $roles_user) || in_array('assessors', $roles_user)){
     // for assessors or coach here
    }else{	
    if(empty($results)){
    $conn->insert('user__field_mobile')->fields(
            array(
            'entity_id' => $current_user,
            'field_mobile_value' => $form_state->getValue('numberone'),
            'bundle' => 'user',
            'deleted' => '0',
            'revision_id' => $current_user,
            'langcode' => 'en',
            'delta' => '0',
            )
    )->execute();
    }else{
        $conn->update('user__field_mobile')
        ->condition('entity_id',$current_user,'=')
        ->fields([
          'field_mobile_value' => $form_state->getValue('numberone'),
        ])
        ->execute();
    }

    //first name 
    $conn->update('user__field_first_name')
  	->condition('entity_id',$current_user,'=')
  	->fields([
  		'field_first_name_value' => $form_state->getValue('fname'),
  	])
  	->execute();
  }

    //email
    $conn->update('users_field_data')
  	->condition('uid',$current_user,'=')
  	->fields([
  		'mail' => $form_state->getValue('email'),
  	])
  	->execute();

    
	   $form_state->setRedirect('acme_hello');
  }


  public function getStates() {
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
}
?>
