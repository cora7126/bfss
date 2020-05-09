<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;
class UserRoleUpdate extends ControllerBase {
	  public function content($uid,$oldrole,$newrole,$dropdown) {
	  	//print_r($dropdown);

	  	if(isset($uid) && isset($oldrole) && isset($newrole) && $dropdown=='ViewEditActive'){
		  	$user = User::load($uid);
		  	$roles = $user->getRoles();
		  	$user->removeRole($oldrole);
			$user->addRole($newrole);
		    $user->save();
		    $response = array('status'=>'true','uid'=>$user->id());
		}elseif(isset($uid) && isset($oldrole) && isset($newrole) && $dropdown=='ViewEditDeactive'){
		  	$user = User::load($uid);
		  	$roles = $user->getRoles();
		  	$user->removeRole($oldrole);
			$user->addRole($newrole);
		    $user->save();
		    $response = array('status'=>'true','uid'=>$user->id());
		}else{
			$response = array('null');
		}
	    return new JsonResponse($response);
	 
  	}
}