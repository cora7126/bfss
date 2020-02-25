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
class changepass extends FormBase {	
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
     return 'changepass_id';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	$current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
	$form['#attributes'] = array('id'=> 'changepassword');
   $form['pass_label'] = array(
      '#type' => 'label',
      '#value' => t('Your password must be at least 8 characters long and contain at least one number and one character'),
      );
	  $form['current_pass'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Old Password'),
      );
	  $form['newpass'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('New Password'),
      );
	  $form['newpassconfirm'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Confirm New Password'),
      );
	  
	  $form['pass_error'] = array(
      '#type' => 'label',
      '#value' => t('Incorrect enrty,please try again.'),
	  '#suffix' => '<span class=passerror> Need more help? Click here </span>',
      );
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
		'#prefix' =>'	</div></div><div id="athlete_submit">',
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
	$current_user_name = \Drupal::currentUser()->getUsername();
	
    $conn = Database::getConnection();
	$oldpass = $form_state->getValue('current_pass');
	$newpass = $form_state->getValue('newpass');
	$newpassconfirm = $form_state->getValue('newpassconfirm');
	$query1 = \Drupal::database()->select('users_field_data', 'ufd');
        $query1->addField('ufd', 'pass');
        $query1->condition('uid', $current_user,'=');
        $results1 = $query1->execute()->fetchAssoc(); 
		
		$loggedin = \Drupal::service('user.auth')->authenticate($current_user_name, $oldpass);
		if($loggedin == $current_user){
			if($newpass == $newpassconfirm){
				$conn->update('users_field_data')->fields(
						array(
						'pass' => $newpass,
						)
				);
				$conn->condition('uid',$current_user,'=');
				$conn->execute();
			}else{
				// drupal_set_message('MISMATCH ERROR','error');
			}
		}else{
			// drupal_set_message('ERROR MESSAGE HERE','error');
		}	
	
	// $form_state->setRedirect('acme_hello');
 // return;
  }
}
?>