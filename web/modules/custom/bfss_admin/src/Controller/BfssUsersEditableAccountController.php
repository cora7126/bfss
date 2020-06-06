<?php

namespace Drupal\bfss_admin\Controller;

use Drupal\Core\Controller\ControllerBase;

class BfssUsersEditableAccountController extends ControllerBase {
	  public function Bfss_Users_Editable_Account() {
	  	$form = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\BfssUsersEditableAccount');
		return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'users_editable_account_page',
		    '#users_editable_account_block' => $form,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		];  
  	}
}