<?php
/**
 * Assessment Type
 * Service in $
 * @file
 * Contains \Drupal\bfss_assessment\Form\Multistep\MultistepOneForm.
 */

namespace Drupal\bfss_assessment\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use \Drupal\user\Entity\User;
use Drupal\Core\Database\Database;
class MultistepOneForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'multistep_form_one';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $uid = \Drupal::currentUser()->id();
    $Query = \Drupal::database()->select('bfss_register_user_payments', 'ats');
    $Query->fields('ats');
    $Query->condition('uid', $uid,'=');
    $results = $Query->execute()->fetchAssoc();
    if(!empty($results) && isset($results['program_term'])){
          $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($results['program_term']);
    }

    // echo "<pre>";
    // print_r($term->name->value);
    // die;

    $nid = \Drupal::request()->get('node_id');
    if ($nid || !$this->store->get('is_private')) {
      parent::deleteStore();
    }
    if (!$nid && !$this->store->get('assessment')) {
      $this->store->set('is_private', true);
      $nid = 9999999999;
    }else{
      // $this->store->set('is_private', false);
    }
// $test = $this->store->get('assessment');
    // echo $test;
    // die;
    if (!$this->assessmentService->check_assessment_node($nid)) {
      // echo "one";
      if(!$this->store->get('assessment')) {
      // echo "two";
        $this->assessmentService->notAvailableMessage();
        $form['actions']['submit']['#access'] = false;
        return $form;
      }
    }
    // die;
    if ($nid) {
      $this->store->set('assessment', $nid);
    }
    #add container class to form
    $form['#attributes']['class'][] = 'container';
    #attach library for styling
    $form['#attached']['library'][] = 'bfss_assessment/assessment_mulitform_lib';
    #add status bar class
    $form['heading']['#prefix'] = '<div class="one">';
    $form['heading']['#suffix'] = '</div>';
    #get config set services
    $options = [];
    $saved_prices =  \Drupal::config('bfss_assessment.settings')->get('assessment_prices');
    if ($saved_prices) {
      $saved_pricesArr = json_decode($saved_prices, true);
      if ($saved_pricesArr) {
        foreach ($saved_pricesArr as $key => $value) {
          if (isset($value['plan']) && isset($value['price'])) {
            $plan = $value['plan'];
            $price = $value['price'];
            if (!empty($price) && !empty($plan)) {
              $options[$price] = $this->t($plan." - $".$price);
            }
          }
        }
      }
    }

    #set custom if not possing
    if (empty($options)) {
      $options = [
        "29.99" => "Starter - $29.99",
        "69.99" => "Professional - $69.99",
        "299.99" => "Elite - $299.99",
      ];
    }
    //$options = $options + [$results['amount'] => $term->name];
   // print_r($results['amount']);die;
    if(!empty($results) && isset($results['amount']) && !empty($term) && $results['firsttime_purchase_status']=='PurchasePending'){
      $options = [$results['amount'].'_freecredit' => $term->name->value.' ( free credit )'] + $options;
    }


    $form['service'] = array(
      '#type' => 'select',
      '#title' => $this->t('Assessment Type'),
      '#options' => $options,
      "#empty_option"=>t('Select Service'),
      '#default_value' => $this->store->get('service') ? $this->store->get('service') : null,
      '#prefix' => "<p class='service-top-head'> Please select service</p>",
      '#required' => true,

    );
    $form['actions']['submit']['#value'] = $this->t('Next');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->store->set('service', $form_state->getValue('service'));
    $form_state->setRedirect('bfss_assessment.multistep_two');
  }

}