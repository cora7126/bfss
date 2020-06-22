<?php
/**
 * Payment and Address Selection
 * @file
 * Contains \Drupal\bfss_assessment\Form\Multistep\MultistepFourForm.
 */

namespace Drupal\bfss_assessment\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use \Drupal\user\Entity\User;
use Drupal\Core\Database\Database;

use Drupal\Core\Form\FormBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Ajax\AjaxResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\InvokeCommand;
class MultistepFourForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'multistep_form_four';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	// print_r($this->store->get('service'));
 //  die;
	$month_options = [];
        for ($m = 1; $m <= 12; ++$m) {
          $time = mktime(0, 0, 0, $m, 1);
          $month_options[date('m', $time)] = date('F', $time);
        }

        $year_options = [];

        for ($i = date("Y"); $i < date("Y") + 10; $i++) {
          $year_options[$i] = $i;
        }
    		 $states = [
        ''   => 'Select State',
        'AL' => 'AL',
        'AK' => 'AK',
        'AS' => 'AS',
        'AZ' => 'AZ',
        'AR' => 'AR',
        'CA' => 'CA',
        'CO' => 'CO',
        'CT' => 'CT',
        'DE' => 'DE',
        'DC' => 'DC',
        'FM' => 'FM',
        'FL' => 'FL',
        'GA' => 'GA',
        'GU' => 'GU',
        'HI' => 'HI',
        'ID' => 'ID',
        'IL' => 'IL',
        'IN' => 'IN',
        'IA' => 'IA',
        'KS' => 'KS',
        'KY' => 'KY',
        'LA' => 'LA',
        'ME' => 'ME',
        'MH' => 'MH',
        'MD' => 'MD',
        'MA' => 'MA',
        'MI' => 'MI',
        'MN' => 'MN',
        'MS' => 'MS',
        'MO' => 'MO',
        'MT' => 'MT',
        'NE' => 'NE',
        'NV' => 'NV',
        'NH' => 'NH',
        'NJ' => 'NJ',
        'NM' => 'NM',
        'NY' => 'NY',
        'NC' => 'NC',
        'ND' => 'ND',
        'MP' => 'MP',
        'OH' => 'OH',
        'OK' => 'OK',
        'OR' => 'OR',
        'PW' => 'PW',
        'PA' => 'PA',
        'PR' => 'PR',
        'RI' => 'RI',
        'SC' => 'SC',
        'SD' => 'SD',
        'TN' => 'TN',
        'TX' => 'TX',
        'UT' => 'UT',
        'VT' => 'VT',
        'VI' => 'VI',
        'VA' => 'VA',
        'WA' => 'WA',
        'WV' => 'WV',
        'WI' => 'WI',
        'WY' => 'WY',
        'AE' => 'AE',
        'AA' => 'AA',
        'AP' => 'AP',
       ];
  		 
  		 $country=array('usa'=>'USA','canada'=>'Canada');
	
    $form = parent::buildForm($form, $form_state);
    #if not avail
    if(!$this->store->get('assessment')) {
      $this->assessmentService->notAvailableMessage();
      $form['actions']['submit']['#access'] = false;
      return $form;
    }
    #attach library for styling
    $form['#attached']['library'][] = 'bfss_assessment/assessment_mulitform_lib';
    #add status bar class
    $form['heading']['#prefix'] = '<div class="four">';
    $form['heading']['#suffix'] = '</div>';
    #add container class to form
    $form['#attributes']['class'][] = 'container';
    $form['#attributes']['class'][] = 'parent';

    $results = $this->register_time_payment_details();
    if(!empty($this->store->get('service'))){
     $service = explode( "_",$this->store->get('service'));
    }else{
      $service = [];
    }
    

    if(!empty($results) && $results['firsttime_purchase_status']=='PurchasePending' && in_array('freecredit', $service)){
      //message here
    }else{
        $form['instruction'] = [
          '#type' => 'markup',
          '#markup' => $this->t('<h3>Please add your payment details to continue.</h3><h3 class="cc-info">Credit card information</h3>'),
        ];
        #credit card
        $form['name_on_card'] = array(
          '#type' => 'textfield',
          '#placeholder' => $this->t('Name on Card'),
          '#default_value' => $this->store->get('name_on_card') ? $this->store->get('name_on_card') : '',
          '#required' => true,
        );

        $form['credit_card_number'] = array(
          '#type' => 'number',
          '#placeholder' => $this->t('Credit Card Number'),
          '#default_value' => $this->store->get('credit_card_number') ? $this->store->get('credit_card_number') : '',
          '#required' => true,
        );

       /* $form['expiration_month'] = array(
          '#type' => 'number',
          '#placeholder' => $this->t('Expiration Month'),
          '#default_value' => $this->store->get('expiration_month') ? $this->store->get('expiration_month') : '',
          '#prefix' => $this->t('<div class="expiration">'),
          '#min' => 1,
          '#max' => 12,
          '#required' => true,
        );*/
    	$form['expiration_month'] = array(
          '#type' => 'select',
    	  '#options'=>$month_options,
          //'#placeholder' => $this->t('Expiration Month'),
          '#default_value' => $this->store->get('expiration_month') ? $this->store->get('expiration_month') : '',
          '#prefix' => $this->t('<div class="expiration1">'),
          //'#min' => 1,
          //'#max' => 12,
          '#required' => true,
        );
    	
    	 $form['expiration_year'] = array(
          '#type' => 'select',
    	  '#options'=>$year_options,
          //'#placeholder' => $this->t('Expiration Year'),
          '#default_value' => $this->store->get('expiration_year') ? $this->store->get('expiration_year') : '',
          '#suffix' => $this->t('</div>'),
          //'#min' => date('Y'),
         // '#max' => 50 + (int) date('Y'),
          '#required' => true,
        );

        /*$form['expiration_year'] = array(
          '#type' => 'number',
          '#placeholder' => $this->t('Expiration Year'),
          '#default_value' => $this->store->get('expiration_year') ? $this->store->get('expiration_year') : '',
          '#suffix' => $this->t('</div>'),
          '#min' => date('Y'),
          '#max' => 50 + (int) date('Y'),
          '#required' => true,
        );*/

        $form['cvv'] = array(
          '#type' => 'number',
          '#placeholder' => $this->t('3 Digits (CVV)'),
          '#default_value' => $this->store->get('cvv') ? $this->store->get('cvv') : '',
          '#min' => 100,
          '#required' => true,
        );
        #end of credit card

    }
    $form['address_1'] = array(
      '#type' => 'textfield',
      '#prefix' => $this->t("<h3>Billing Address</h3>"),
      '#placeholder' => $this->t('Address 1'),
      '#default_value' => $this->store->get('address_1') ? $this->store->get('address_1') : '',
      '#required' => true,
    );

    $form['address_2'] = array(
      '#type' => 'textfield',
      '#placeholder' => $this->t('Address 2'),
      '#default_value' => $this->store->get('address_2') ? $this->store->get('address_2') : '',
    );

    $form_state_values = $form_state->getValues();

      // print_r($form_state_values);
      if(empty($form_state_values['state'])){
        $VNS = 'AZ';
      }else{
        $VNS = $form_state_values['state'];
      }

    $form['state'] = array(
      '#type' => 'select',
      '#options'=>$states,
      '#placeholder' => $this->t('State'),
      '#prefix' => $this->t('<div class="expiration"><div class="full-width-inp half-width-inp">'),
      '#suffix' => $this->t('</div>'),
      '#default_value' => $this->store->get('state') ? $this->store->get('state') : '',
      '#required' => true,
      '#ajax' => [
          'callback' => '::VenueLocationAjaxCallback', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output-wrapper', // This element is updated with this AJAX callback.
        ]
    );

    $form['zip'] = array(
      '#type' => 'number',
      '#placeholder' => $this->t('ZIP/Post Code'),
      '#default_value' => $this->store->get('zip') ? $this->store->get('zip') : '',
      '#required' => true,
      '#suffix' => $this->t('</div>'),
    );
     $form['city'] = array(
      '#type' => 'textfield',
      '#placeholder' => $this->t('City'),
      #'#prefix' => $this->t(''),
      '#default_value' => $this->store->get('city') ? $this->store->get('city') : '',
      '#required' => true,
      '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
      '#autocomplete_route_parameters' => array('field_name' => $VNS, 'count' => 10), 
      '#prefix' => '<div id="edit-output-wrapper" class="full-width-inp">',
      '#suffix' => '</div>',
    );
    /*$form['country'] = array(
      '#type' => 'textfield',
      '#placeholder' => $this->t('Country'),
      '#default_value' => $this->store->get('country') ? $this->store->get('country') : '',
      '#required' => true,
    );*/

	$form['country'] = array(
      '#type' => 'select',
	    '#options'=>$country,
      '#placeholder' => $this->t('Country'),
      '#default_value' => $this->store->get('country') ? $this->store->get('country') : '',
      '#required' => true,
	   # '#suffix' => $this->t('</div>'),
    );


    $form['actions']['previous'] = array(
      '#type' => 'link',
      '#title' => $this->t('Back'),
      '#attributes' => array(
        'class' => array('button'),
      ),
      '#weight' => 0,
      '#url' => Url::fromRoute('bfss_assessment.multistep_three'),
    );
    $form['actions']['submit']['#value'] = $this->t('Next');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $free_credit_check = $this->register_time_payment_details();
    if(!empty($this->store->get('service'))){
     $service = explode( "_",$this->store->get('service'));
    }else{
      $service = [];
    }
    if($free_credit_check['firsttime_purchase_status'] == 'PurchasePending' && in_array('freecredit', $service)){
      #NOT NEED payment card
    $this->store->set('name_on_card', NULL);
    $this->store->set('credit_card_number', NULL);
    $this->store->set('expiration_month', NULL);
    $this->store->set('expiration_year', NULL);
    $this->store->set('cvv', NULL);
    }else{
       #payment card
    $this->store->set('name_on_card', $form_state->getValue('name_on_card'));
    $this->store->set('credit_card_number', $form_state->getValue('credit_card_number'));
    $this->store->set('expiration_month', $form_state->getValue('expiration_month'));
    $this->store->set('expiration_year', $form_state->getValue('expiration_year'));
    $this->store->set('cvv', $form_state->getValue('cvv'));
    }
   

    #billing address
    $this->store->set('address_1', $form_state->getValue('address_1'));
    $this->store->set('address_2', $form_state->getValue('address_2'));
    $this->store->set('city', $form_state->getValue('city'));
    $this->store->set('state', $form_state->getValue('state'));
    $this->store->set('zip', $form_state->getValue('zip'));
    $this->store->set('country', $form_state->getValue('country'));
    #save the data
    parent::saveData();
    $form_state->setRedirect('bfss_assessment.assessment_success');
  }

  public function register_time_payment_details(){
    $uid = \Drupal::currentUser()->id();
    $Query = \Drupal::database()->select('bfss_register_user_payments', 'ats');
    $Query->fields('ats');
    $Query->condition('uid', $uid,'=');
    $results = $Query->execute()->fetchAssoc();
    return !empty($results)?$results:'';
  }
   public function VenueLocationAjaxCallback(array &$form, FormStateInterface $form_state){
      return  $form['city']; 
  }
}
