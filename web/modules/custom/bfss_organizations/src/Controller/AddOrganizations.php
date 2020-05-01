<?php
namespace Drupal\bfss_organizations\Controller;
use Drupal\Core\Controller\ControllerBase;

class AddOrganizations extends ControllerBase {
	  public function Add_Organizations() {
	    $form = \Drupal::formBuilder()->getForm('Drupal\bfss_organizations\Form\AddOrganizations');

      //
       $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
       $rel = $permissions_service->bfss_admin_permissions();
       $Organizations_permissions =  unserialize($rel['Organizations']);
    
        if( !empty($Organizations_permissions) && $Organizations_permissions['create']==1){
          $result = $form;
        }else{
          $result = "we are sorry. you can not access this page.";
        }
    	return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'add_organization_page',
          '#name' => 'G.K',
          '#add_organization_block' => $result,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
  	}
}