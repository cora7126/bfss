<?php

namespace Drupal\bfss_month_view\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
class EventDataGet extends ControllerBase {

 
		public function event_data_get()
		{	
			$query = \Drupal::entityQuery('node');
			$query->condition('type', 'assessment');
			$query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
			$query->condition('field_type_of_assessment','private', '!=');
			$query->condition('status', 1);
			$nids = $query->execute();	
			$event_data = $this->get_assessments_data($nids);
			return new JsonResponse($event_data);
		}

		public function group_event_data_get()
		{	
			$query = \Drupal::entityQuery('node');
			$query->condition('type', 'assessment');
			$query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
			$query->condition('field_type_of_assessment','group', '=');
			$query->condition('status', 1);
			$nids = $query->execute();	
			$event_data = $this->get_assessments_data($nids);
			return new JsonResponse($event_data);
		}


		public function private_event_data_get()
		{	
			$query = \Drupal::entityQuery('node');
			$query->condition('type', 'assessment');
			$query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
			$query->condition('field_type_of_assessment','private', '=');
			$query->condition('status', 1);
			$nids = $query->execute();	
			$event_data = $this->get_assessments_data($nids);
			return new JsonResponse($event_data);
		}

		public function get_assessments_data($nids){
			if(is_array($nids) && !empty($nids)){
				$event_data = [];
				foreach( $nids as $nid ){
					$node = Node::load($nid);
					if(!empty($node)){
						$data['title'] = $node->getTitle();
						$field_schedules = $node->get('field_schedules')->getValue();

						foreach ( $field_schedules as $element ) {
							 $pGraph = Paragraph::load($element['target_id'] );
							 $timing = (int) $pGraph->get('field_timing')->value;
							 $date = date("Y-m-d",$timing);
							 $time = $date.'T'.date("h:i:s",$timing);
							 $duration = $pGraph->get('field_duration')->value;
							 $event_data[] = [
							 	'id'=>$nid,
							   'title' => substr($data['title'], 0, 10).'...',
							   //'date' => $date,
							   'url' => '#'.$nid,
							   'start' => $time,
							   'className' => 'use-ajax',
							 ];
						}
					}
					
				}
			}
			return $event_data;
		}

		public function event_data_get_scheduled()
		{	

			$booked_ids = \Drupal::entityQuery('bfsspayments')
		        ->condition('user_id', \Drupal::currentUser()->id())
		        ->condition('time', time(), ">")
		        ->sort('time','ASC')
		        ->execute();
		       // print_r($booked_ids);
		     #if there is data
	         $entity_ids = [];
	         $event_data = []; 
		      if ($booked_ids) {
		        foreach ($booked_ids as $booked_id) {
		          #load entity
		           	   $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
		           	  
		               $node_checked = Node::load($entity->assessment->value);
		               if(!empty($node_checked)){
		               	$date = date("Y-m-d",$entity->time->value);
						$time = $date.'T'.date("h:i:s",$entity->time->value);
		                $event_data[] = [
						   'id' => $entity->assessment->value,
						   'title' => substr($entity->assessment_title->value, 0, 10).'...',
						   'url' => '#'.$entity->assessment->value.'?booked_id='.$booked_id,
						   'start' => $time,
						   'className' => 'use-ajax',

						 ];
		               }
		        }
		      }

			  return new JsonResponse($event_data);
		}
   
}
