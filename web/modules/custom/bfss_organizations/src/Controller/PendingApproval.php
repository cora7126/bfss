<?php

namespace Drupal\bfss_organizations\Controller;
use Drupal\Core\Controller\ControllerBase;

class PendingApproval extends ControllerBase {
	  public function pending_approval() {
	  //$form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\PendingApprovalForm');
    	 $form = \Drupal::formBuilder()->getForm('Drupal\bfss_organizations\Form\PendingApprovalForm');
          //Permissions
       $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
       $rel = $permissions_service->bfss_admin_permissions();
       $pending_approval =  unserialize($rel['pending_approval']);
      
        if($pending_approval['view']==1 || $pending_approval['admin']==1){
          $result = $form;
        }else{
          $result = "we are sorry. you can not access this page.";
        }


  	  return [
            '#cache' => ['max-age' => 0,],
            '#theme' => 'pending_approval_page',
            '#name' => 'G.K',
            '#pending_approval_block' => $result,
            '#attached' => [
              'library' => [
                'acme/acme-styles', //include our custom library for this response
              ]
            ]
          ];
    	}
}