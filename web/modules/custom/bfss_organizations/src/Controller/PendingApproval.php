<?php

namespace Drupal\bfss_organizations\Controller;
use Drupal\Core\Controller\ControllerBase;

class PendingApproval extends ControllerBase {
	  public function pending_approval() {
	  //$form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\PendingApprovalForm');
    	 $form = \Drupal::formBuilder()->getForm('Drupal\bfss_organizations\Form\PendingApprovalForm');
	  return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'pending_approval_page',
          '#name' => 'G.K',
          '#pending_approval_block' => $form,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
  	}
}