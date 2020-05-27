<?php

namespace Drupal\bfss_coach\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
class DeleteOrgFromCoachProfile extends ControllerBase {

 
		public function deleteaorg($nid)
		{		
			
			  $node = Node::load($nid);
			  $node->delete();
			  $response = array($nid);
			  return new JsonResponse($response);
		}
   
}
