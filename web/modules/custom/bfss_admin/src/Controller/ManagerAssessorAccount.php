<?php

namespace Drupal\bfss_admin\Controller;

use Drupal\Core\Controller\ControllerBase;

class ManagerAssessorAccount extends ControllerBase {

	public function manager_assessor_account() {
		
		$form = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\ManagerAssessorAccount');
		return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'manager_assessor_account_page',
		    '#manager_assessor_account_block' => $form,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		];   
  	}
}