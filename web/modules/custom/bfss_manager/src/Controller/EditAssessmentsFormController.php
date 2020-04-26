<?php
namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;

class EditAssessmentsFormController extends ControllerBase {
	  public function edit_assessments_form_controller() {
	    // return [
	    //   '#type' => 'markup',
	    //   '#markup' => $this->t('Hello, World!'),
	    // ];
	  	$form = \Drupal::formBuilder()->getForm('Drupal\bfss_manager\Form\EditAssessmentsForm');
	     return [
	      '#cache' => ['max-age' => 0,],
	      '#theme' => 'edit_assessments_data_page',
	      '#edit_assessments_data_block' => $form,
	      '#attached' => [
	        'library' => [
	          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ]; 
  	}
}