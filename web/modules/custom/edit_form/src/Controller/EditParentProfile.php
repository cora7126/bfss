<?php
namespace Drupal\edit_form\Controller;

use Drupal\Core\Controller\ControllerBase;
class EditParentProfile extends ControllerBase {
	  public function content() {
	    // return [
	    //   '#type' => 'markup',
	    //   '#markup' => $this->t('Hello, World!'),
	    // ];


	    $form = \Drupal::formBuilder()->getForm('Drupal\edit_form\Form\ParentEditForm');
	  	return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'edit_parent_profile_page',
          '#name' => 'G.K',
          '#edit_parent_profile_block' => $form,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
  	}
}