<?php

namespace Drupal\bfss_assessment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PaymentSettingsForm.
 *
 * @ingroup angelview_donation
 */
class PaymentSettingsForm extends ConfigFormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'bfss_payment_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bfss_assessment.settings',
    ];
  }

  /**
   * Defines the settings form for Donation entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bfss_assessment.settings');
 

    $form['api_login_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Api login id'),
      '#default_value' => $config->get('api_login_id'),
      '#required' => TRUE,
    ];

    $form['transaction_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Transaction key'),
      '#default_value' => $config->get('transaction_key'),
      '#required' => TRUE,
    ];


    $form['is_live'] = [
      '#type' => 'radio',
      '#title' => $this->t('Live environment (<small>uncheck for testing mode</small>)'),
      '#default_value' => $config->get('is_live'),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return parent::buildForm($form, $form_state);
  }



 

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  
    $api_login_id = $form_state->getValue('api_login_id');
    $transaction_key = $form_state->getValue('transaction_key');
    $is_live = $form_state->getValue('is_live');

    $this->configFactory->getEditable('bfss_assessment.settings')->set('api_login_id', $api_login_id);
	  $this->configFactory->getEditable('bfss_assessment.settings')->set('transaction_key', $transaction_key);
    $this->configFactory->getEditable('bfss_assessment.settings')->set('is_live', $is_live);
    $this->configFactory->getEditable('bfss_assessment.settings')->save();
  }

}
