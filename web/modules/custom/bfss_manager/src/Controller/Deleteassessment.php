<?php
namespace Drupal\bfss_manager\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
class Deleteassessment extends ControllerBase {
	  public function content($nid) {
	    $node = Node::load($nid);
	    if ($node) {
  			$node->delete();
		}
	  	$response = array($nid);
	    return new JsonResponse($response);
  	}
}