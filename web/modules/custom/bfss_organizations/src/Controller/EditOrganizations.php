<?php
namespace Drupal\bfss_organizations\Controller;
use Drupal\Core\Controller\ControllerBase;

class EditOrganizations extends ControllerBase {
	  public function edit_organizations() {
	    $form = \Drupal::formBuilder()->getForm('Drupal\bfss_organizations\Form\EditOrganizations');
    	return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'edit_organizations_page',
          '#name' => 'G.K',
          '#edit_organizations_block' => $form,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
  	}
}