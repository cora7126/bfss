<?php
namespace Drupal\bfss_admin\Controller;
use Drupal\Core\Controller\ControllerBase;


class EditableTemplates extends ControllerBase {
	  public function editable_tamplates() {
	   		$form = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\EditableTemplatesForm');
	  		
	      	return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'editable_tamplates_page',
		    '#editable_tamplates_block' => $form,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		]; 
	   
  	}
}