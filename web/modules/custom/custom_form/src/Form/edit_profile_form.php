<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class edit_profile_form extends FormBase {
	public function getFormId() {
		return 'edit_profile_form';
	}

	  /**
	   * {@inheritdoc}
	   */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#required' => TRUE,
    ); 
		return $form;
	}

	  /**
	   * {@inheritdoc}
	   */
	public function validateForm(array &$form, FormStateInterface $form_state) {
	}

	  /**
	   * {@inheritdoc}
	   */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		 // Display result.
		foreach ($form_state->getValues() as $key => $value) {
		  drupal_set_message($key . ': ' . $value);
		}
		
	}

}