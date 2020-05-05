<?php
namespace Drupal\bfss_organizations\Controller;
use Drupal\Core\Controller\ControllerBase;

class EditOrganizations extends ControllerBase {
	  public function edit_organizations() {
	    $form = \Drupal::formBuilder()->getForm('Drupal\bfss_organizations\Form\EditOrganizations');


       //Permissions
       $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
       $rel = $permissions_service->bfss_admin_permissions();
       $Organizations_permissions =  unserialize($rel['Organizations']);
    
        if($Organizations_permissions['view']==1 || $Organizations_permissions['admin']==1){
          $result = $form;
        }else{
          $result = "we are sorry. you can not access this page.";
        }

    	return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'edit_organizations_page',
          '#name' => 'G.K',
          '#edit_organizations_block' => $result,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
  	}
}