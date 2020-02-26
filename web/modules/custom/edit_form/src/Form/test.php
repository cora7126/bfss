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
	$form['prefix'] = "<div class=athlete_edit_class>";
	$form['suffix'] = "</div>";
    $form['username'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $results4['name'],
	  '#prefix'=>'<div class="left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Login Information</h3><div class=items_div>',
	  '#attributes' => array('disabled'=>true),
      );
    
	$form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Email'),
      '#required' => TRUE,
      '#default_value' => $results4['mail'],
	  '#prefix' => '',
	  '#suffix' => '<a class="change_pass" id=change_id>Change Password</a></div></div><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>ATHLETE\'s Information</h3><div class=items_div>',
      );
        $form['fname'] = array(
      '#type' => 'textfield',
      '#default_value' => $results1['field_first_name_value'],
      '#attributes' => array('disabled'=>true),
	  
      );
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
        '#required' => TRUE,
         '#default_value' => $results5['field_mobile_value'],
      ); 
         
       $form['date_joined'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Date joined'),
	  '#suffix' => '</div></div>',
      '#default_value' => substr($results3['field_date_value'],0,10),
      '#attributes' => array('disabled'=>true),
      );
	  $form['organizationType'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Type'), t('Organization Type 1'), t('Organization Type 2'), t('Organization Type 3')),
	  '#prefix' => '<div class="athlete_school"><div class = "athlete_left edit-user-display"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
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
      '#placeholder' => t("Coach's Last Name"),
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
     
    
    /*Add another organization 1 start*/
          
       $form['education_1'] = array(
		//'#title' => t('az'),
		'#type' => 'select',
		//'#description' => 'Select the desired pizza crust size.',
		'#options' => array(t('Organization Type'), t('Organization Type 1'), t('Organization Type 2'), t('Organization Type 3')),
	  '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete"><div class = "athlete_left edit-user-display"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
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
	  '#suffix' => "</div></div></div><a class='add_org popup_add_org edit-user-display'><i class='fa fa-plus'></i>Add Another Organization</a></div><div class ='right_section'><div class = 'athlete_right '><h3><div class='toggle_icon'><i class='fa fa-minus'></i><i class='fa fa-plus hide'></i></div>My Website Photo</h3><div class='edit_dropdown'><a class='drop' >Action<span class='down-arrow fa fa-angle-down'></span></a><ul class='dropdown-menu' style='padding:0'></ul></div><img src= $url class='edit-profile-image' ><div class='items_div'><div class='popupimage' id='imagepopup'>",
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
                            '#default_value' => array($img_id),
                            
                            '#prefix' => '</div></div>',
                            '#suffix' =>'<div class="action_bttn"><span>Action</span><ul><li>Remove</li></ul></div></div></div>',
                          ];
        
	   $form['school_web'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('School'),
      '#default_value' => $results5['athlete_school_name'],
		'#attributes' => array('disabled' => true),
	  '#prefix' => '</div></div><div class = "athlete_right edit-user-display"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website</h3><div class=items_div>',
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
		'#options' => array(t('Website Visibility'), t('On'), t('Off')),
		'#suffix' => '</div>',
      );
   $form['school_web2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('School'),
      '#default_value' => $results6['athlete_uni_name'],
	  '#attributes' => array('disabled' => true),
	  '#prefix' => '</div><div class = "athlete_right edit-user-display"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
      );
	   $form['sport_web2'] = array (
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#default_value' => $results6['athlete_uni_sport'],
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
     $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
     $query = \Drupal::database()->select('user__field_mobile', 'ufm');
		$query->fields('ufm');
		$query->condition('entity_id', $current_user,'=');
		$results = $query->execute()->fetchAll(); 
	$query_pic = \Drupal::database()->select('user__user_picture', 'uup');
		$query_pic->fields('uup');
		$query_pic->condition('entity_id', $current_user,'=');
		$results_pic = $query_pic->execute()->fetchAll();	
                $imgid = $form_state->getValue('image_athlete');
				if(empty($results_pic)){
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
				}else{
                $conn->update('user__user_picture')
					->condition('entity_id',$current_user,'=')
					->fields(
						array(
						'user_picture_target_id' => $imgid[0],
						)
					)
				->execute();
				}
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
    }
    $conn->update('user__field_first_name')
	->condition('entity_id',$current_user,'=')
	->fields([
		'field_first_name_value' => $form_state->getValue('fname'),
	])
	->execute();
    
    $conn->update('users_field_data')
	->condition('uid',$current_user,'=')
	->fields([
		'mail' => $form_state->getValue('email'),
	])
	->execute();
    if(!empty($results)){
     $conn->update('user__field_mobile')
	->condition('entity_id',$current_user,'=')
	->fields([
		'field_mobile_value' => $form_state->getValue('numberone'),
	])
	->execute();
    }
	$form_state->setRedirect('acme_hello');
  }
}
?>