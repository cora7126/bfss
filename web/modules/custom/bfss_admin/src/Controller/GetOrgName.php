<?php

namespace Drupal\bfss_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
class GetOrgName extends ControllerBase {

 
		public function get_org_name()
		 {	

			$param = \Drupal::request()->query->all();
			$query = \Drupal::entityQuery('node');
			$query->condition('type', 'bfss_organizations');
			$query->condition('field_type',$param['type'], 'IN');
			$query->condition('field_state',$param['state'], 'IN');
			$query->range(0, 2000);
			$nids = $query->execute();
			$ORG_NAME = [];
			foreach ($nids as $nid) {
				$node = Node::load($nid);
                 $ORG_NAME[] = $node->field_organization_name->value;
            }
			
			 //print_r($ORG_NAME);
			return  new JsonResponse($ORG_NAME);
		}
   
}
