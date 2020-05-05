<?php
namespace Drupal\edit_form\Controller;
use Drupal\Core\Controller\ControllerBase;

class EditUserProfile extends ControllerBase {
	  public function content() {

	  	$form = \Drupal::formBuilder()->getForm('Drupal\edit_form\Form\test');
	  	return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'edit_user_profile_template_page',
          '#name' => 'G.K',
          '#edit_user_profile_template_block' => $form,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];

	    
  	}
}