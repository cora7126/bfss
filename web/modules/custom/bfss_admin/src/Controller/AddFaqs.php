<?php

namespace Drupal\bfss_admin\Controller;

use Drupal\Core\Controller\ControllerBase;

class AddFaqs extends ControllerBase {
	  public function add_faqs() {

	  	$form = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\AddFaqsForm');
	    return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'add_faqs_page',
		    '#add_faqs_block' => $form,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		]; 
  	}
}