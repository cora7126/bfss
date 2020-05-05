<?php

namespace Drupal\bfss_admin\Controller;

use Drupal\Core\Controller\ControllerBase;

class UserBySelectFaqs extends ControllerBase {
	  public function user_by_select_faqs() {
	  	$form = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\UserBySelectFaqsForm');
		return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'faqs_select_page',
		    '#faqs_select_block' => $form,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		];  
  	}
}