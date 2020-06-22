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
class GetCitiesJQueryTokenize2 extends ControllerBase {

 
		public function get_cities()
		{	

			$param = \Drupal::request()->query->all();
			$cities = [];
			$results = \Drupal::database()->select('us_cities', 'athw')
                  ->fields('athw')
                  ->condition('state_code',$param['state'], '=')
                  ->range(0, 10)
                  ->execute()->fetchAll();
                  foreach ($results as $result) {
                  	$cities[] = $result->name;
                  }
			return  new JsonResponse($cities);
		}
   
}
