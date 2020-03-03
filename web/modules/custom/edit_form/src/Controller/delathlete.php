<?php

namespace Drupal\edit_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;

class delathlete extends ControllerBase {

 
		public function deleteathlete($aid,$orgname)
		{	
		// echo $orgname;die;
			$uid = \Drupal::currentUser()->id();
			$conn = Database::getConnection();
			$num_deleted = $conn->delete($orgname)
			  ->condition('athlete_uid', $uid, '=')
			  ->execute();
			  $response = array($uid);
			  return new JsonResponse($response);
		}
   
}
