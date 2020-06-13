<?php
/**
 * @file
 * Contains \Drupal\bfss_admin\Form\ContributeForm.
 */

namespace Drupal\bfss_admin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use \Drupal\user\Entity\User;


/**
 * Contribute form.
 */
class UsersEditableAccount extends FormBase {
	 /**
	 * {@inheritdoc}
	 */
	 public function getFormId() {
	    return 'users_editable_account';
	 }

    /**
    * {@inheritdoc}
    */
	public function buildForm(array $form, FormStateInterface $form_state) {

	  $form['username'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
     # '#default_value' => $results4['name'],
	  '#attributes' => array('disabled'=>true),
      ];

      $form['email'] = [
      '#type' => 'textfield',
      '#placeholder' => t('Email'),
      '#required' => TRUE,
     # '#default_value' => $results4['mail'],
     # '#attributes' => array('disabled'=>$dis_status),
      ];

       $form['fname'] = [
      '#type' => 'textfield',
      #'#default_value' => $results1['field_first_name_value'],
      '#attributes' => ['disabled'=>true],
      ];


      $states = $this->getStates();
      $form['az'] = [
        '#type' => 'select',
        '#options'=>$states,
        #'#default_value' => $results18['field_az'],
        '#required' => TRUE,
      ];

       $form['city'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),
        '#required' => TRUE,
       # '#default_value' => $results18['field_city'],
      ];

      $gender_arr =  [
        '' => 'Select Gender',
        'male' => 'Male',
        'female' => 'Female',
      ];

      $form['sextype'] = [
        '#type' => 'select',
        '#options' => $gender_arr,
        '#required' => TRUE,
       # '#default_value' => $results18['field_birth_gender'],
      ];

      return $form;

	}

	public function validateForm(array &$form, FormStateInterface $form_state) {
	
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {

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