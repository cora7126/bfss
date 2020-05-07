<?php

namespace Drupal\bfss_month_view\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventDataGet extends ControllerBase {

 
		public function event_data_get()
		{	
			 $s =  array( 
		        array(
		          'title' => 'All Day Event1111',
		          'start' => '2020-02-01'
		        ),
			      array(
			        'title' => 'Long Event11111',
			        'start' => '2020-02-07', 
			      ),
			      array(
			        'title' => 'Repeating Event111',
			        'start' => '2020-02-09'
			      ),
			      array(
			        'title' => 'Repeating Event111',
			        'start' => '2020-02-16'
			      ),
		    );
		
			// $uid = \Drupal::currentUser()->id();
			// $conn = Database::getConnection();
			// $num_deleted = $conn->delete($orgname)
			//   ->condition('athlete_uid', $uid, '=')
			//   ->execute();
			//   $response = array($uid);

			  return new JsonResponse($s);
		}
   
}
