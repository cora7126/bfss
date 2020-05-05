<?php

namespace Drupal\bfss_admin\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\user\Entity\User;

class UserTypePermissions extends ControllerBase {
	  public function user_type_permissions() {
	    $form_sel = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\UserBySelectPermissionsForm');
	    $form_table = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\UserByPermissionsTableForm');

	   	$our_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
		$rel = $our_service->bfss_admin_permissions();


		// print_r($rel);
		// die;

	  	return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'user_type_permissions_page',
		    '#user_type_permissions_selcet_block' => $form_sel,
		    '#user_type_permissions_table_block' => $form_table,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		]; 
	   
  	}


}