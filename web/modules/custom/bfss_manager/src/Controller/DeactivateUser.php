<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;
class DeactivateUser extends ControllerBase {
	  public function content($uid,$editpage) {
	  	if(isset($uid) && $editpage == 'ViewEditActive'){ //use for deactivate user
	  		$user = User::load($uid);
        	$user->status->value = 0;
        	$user->save();
        	$response = array('status'=>'true','editpage'=>'ViewEditActive');
	  	}elseif(isset($uid) && $editpage == 'ViewEditDeactive') { //use for reactivate user
	  		$user = User::load($uid);
        	$user->status->value = 1;
        	$user->save();
        	$response = array('status'=>'true','editpage'=>'ViewEditDeactive');
	  	}else{
	  		$response = array('false');
	  	}
	    return new JsonResponse($response);
	 
  	}
}