<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
class TagsDataGet extends ControllerBase {

 
		public function tags_data_get()
		{	
			$vid = 'tags';
			$terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);

			$sports_arr = [];
			foreach ($terms as $term) {
				
			 $sports_arr[] = [
			 	'text' => $term->name,
			 	'value' => $term->tid,
			 ];
			}
			return new JsonResponse($sports_arr);
		}

		public function categories_data_get()
		{	
			$vid = 'categories';
			$terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
			$sports_arr = [];
			foreach ($terms as $term) {
			 $sports_arr[] = [
			 	'text' => $term->name,
			 	'value' => $term->tid,
			 ];
			}
			return new JsonResponse($sports_arr);
		}
   
}
