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
      '#default_value' => $results5['field_mobile_value'],
      ); 
	  // $form['numbertwo'] = array(
      // '#type' => 'textfield',
	  // '#placeholder' => '(888)123-4567',
      // '#default_value' => $results6['field_mobile_2_value'],
      // );
	  $form['somedate'] = array(
      '#type' => 'textfield',
      '#default_value' => substr($results3['field_date_value'],0,10),
	  '#attributes' => array('disabled'=>true),
      );
    
	  
	  $form['stats'] = array (
      '#type' => 'textarea',
	  '#suffix' => '</div></div></div></div><div class ="right_section"><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website Photo</h3><div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul></div><div class="popupimage" id="imagepopup">',
//	  '#attributes' => array('style' => 'display:none'),
      );
     
	  $form['image_edit_user'] = [
                            '#type' => 'managed_file',
//                            '#upload_validators' => [
//                              'file_validate_extensions' => ['gif png jpg jpeg'],
//                              'file_validate_size' => '',
//                            ],
                            '#theme' => 'image_widget',
                            '#preview_image_style' => 'medium',
                            '#upload_location' => 'public://',
                            '#required' => FALSE,
                            '#prefix' => '</div>',
                          ];	
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
		'#prefix' =>'	</div></div><div id="athlete_submit">',
		'#suffix' => '</div>',
        //'#value' => t('Submit'),
    ];
	//CHANGE PASSWORD FIELDS
	   $form['pass_label'] = array(
      '#type' => 'label',
      '#value' => t('Your password must be at least 8 characters long and contain at least one number and one character'),
	  '#prefix' => '<div id=changepassdiv>',
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
      '#value' => t('Incorrect enrty,please try again.'),
	  '#suffix' => '<span class=passerror> Need more help? Click here </span>',
      );
    $form['changebutton'] = [
        '#type' => 'button',
        '#value' => 'save',
		'#prefix' =>'',
		'#suffix' => '</div>',
		'#attributes' => array('id'=>'save_pass'),
    ];
	  //end change password
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
	
	// $conn->update('users_field_data')->fields(
						// array(
						// 'name' => $form_state->getValue('username'),
						// )
				// )->execute();
	$conn->update('users_field_data')->fields(
array(
'mail' => $form_state->getValue('email'),
)
);
$conn->condition('uid',$current_user,'=');
$conn->execute();
$conn->update('user__field_mobile')->fields(
array(
'field_mobile_value' => $form_state->getValue('numberone'),
)
);
$conn->condition('entity_id',$current_user,'=');
$conn->execute();
$conn->update('user__field_mobile_2')->fields(
array(
'field_mobile_2_value' => $form_state->getValue('numbertwo'),
)
);
$conn->condition('entity_id',$current_user,'=');
$conn->execute();
	// $form_state->setRedirect('acme_hello');
 // return;
  }
}
?>