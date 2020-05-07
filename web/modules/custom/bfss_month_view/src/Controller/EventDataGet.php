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
			$query->condition('status', 1);
			$nids = $query->execute();
			$event_data = [];

			foreach( $nids as $nid ){
				$node = Node::load($nid);
				$data['title'] = $node->getTitle();
				$field_schedules = $node->get('field_schedules')->getValue();

				foreach ( $field_schedules as $element ) {
					 $pGraph = Paragraph::load($element['target_id'] );
					 $timing = (int) $pGraph->get('field_timing')->value;
					 $date = date("Y-m-d",$timing);
					 $time = $date.'T'.date("h:i:s",$timing);
					 $duration = $pGraph->get('field_duration')->value;
					 $event_data[] = [
					   'title' => $data['title'],
					   //'date' => $date,
					   'start' => $time,
					 ];
				}
			}
			 // $s =  array( 
		  //       array(
		  //         'title' => 'All Day Event1111',
		  //         'start' => '2020-02-01'
		  //       ),
			 //      array(
			 //        'title' => 'Long Event',
			 //        'start' => '2020-02-07', 
			 //      ),
			 //      array(
			 //        'title' => 'Repeating Event',
			 //        'start' => '2020-02-09'
			 //      ),
			 //      array(
			 //        'title' => 'Repeating Event',
			 //        'start' => '2020-02-16'
			 //      ),
		  //   );
		
			// $uid = \Drupal::currentUser()->id();
			// $conn = Database::getConnection();
			// $num_deleted = $conn->delete($orgname)
			//   ->condition('athlete_uid', $uid, '=')
			//   ->execute();
			//   $response = array($uid);

			  return new JsonResponse($event_data);
		}
   
}
