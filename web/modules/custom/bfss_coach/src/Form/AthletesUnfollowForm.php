<?php

namespace Drupal\bfss_coach\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use \Drupal\user\Entity\User;

class AthletesUnfollowForm extends FormBase {
	//from id(unique)
	public function getFormId() {
		return 'athletes_unfollow_form';
	}
	
	public function buildForm(array $form, FormStateInterface $form_state) {
			
		$athlete_user_ids = \Drupal::entityQuery('user')
		->condition('roles', 'athlete', 'CONTAINS')
		->condition('field_coachs_follow', 'follow', '=')
		->execute();

		foreach ($athlete_user_ids as $key => $value) {
				$options[$value] = $value;
		}

		$form['items_selected'] = array(
		  '#type' => 'checkboxes',
		  '#options' => $options,
		  #'#title' => $this->t('Title you want to give'),
		);


		$form['actions']['#type'] = 'actions';
		$form['actions']['submit'] = [
	      '#type' => 'submit',
	      '#value' => $this->t('SAVE - ALL FIELDS COMPLETED'),
	      '#button_type' => 'primary',
	    ];
		return $form;

	}


    public function validateForm(array &$form, FormStateInterface $form_state) {
    
    }
	
	public function submitForm(array &$form, FormStateInterface $form_state) {
	
		foreach ($form_state->getValue('items_selected') as $key => $value) {
			// $user = User::load($value);
			// $user->field_coachs_follow->value = 'unfollow';
			// $user->save();
		}
		 
	}

}
