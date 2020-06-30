<?php
namespace Drupal\bfss_organizations\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
class DeletePendingOrg extends ControllerBase {
	  public function delete_pending_org($nid) {
	    $node = Node::load();
	    if ($node) {
  			//$node->delete();
		}
		print_r($nid);
	  	$response = array($nid);
	    return new JsonResponse($response);
  	}
}