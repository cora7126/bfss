<?php

namespace Drupal\edit_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;

class delparent extends ControllerBase {
	// $abc = 'abc';
 
		public function deleteparent($id=null,$delta=null)
		{ 
		// echo $id; echo $delta;die;
		$abc = 'abc';
		$current_user = \Drupal::currentUser()->id();
			$conn = Database::getConnection();
			$num_deleted = $conn->delete('parent_mobiles')
			  ->condition('entity_id', $current_user)
			  ->condition('parent_id', $delta)
			  ->execute();
			$num_deleted = $conn->delete('user__field_parent_first_name')
			  ->condition('entity_id', $current_user)
			  ->condition('delta', $delta)
			  ->execute();
			$num_deleted = $conn->delete('user__field_parent_last_name')
			  ->condition('entity_id', $current_user)
			  ->condition('delta', $delta)
			  ->execute();
			  $response = 'abcddd';
			  return new JsonResponse($response);
		}
		
		public function changepass($id=null,$delta=null)
		{ 
			$current_user = \Drupal::currentUser()->id();
			$current_user_name = \Drupal::currentUser()->getUsername();
			$conn = Database::getConnection();
			$query1 = \Drupal::database()->select('users_field_data', 'ufd');
				$query1->addField('ufd', 'pass');
				$query1->condition('uid', $current_user,'=');
				$results1 = $query1->execute()->fetchAssoc(); 
		
				$loggedin = \Drupal::service('user.auth')->authenticate($current_user_name, $oldpass);
				if($loggedin == $current_user){
					if($newpass == $newpassconfirm){
						// $conn->update('users_field_data')->fields(
								// array(
								// 'pass' => $newpass,
								// )
						// );
						// $conn->condition('uid',$current_user,'=');
						// $conn->execute();
					}else{
						drupal_set_message('NEW PASS MISMATCH ERROR','error');
					}
				}else{
					drupal_set_message('INCORRECT PASS','error');
				}
			  $response = 'abcddd';
			  return new JsonResponse($response);
		}
   // return $abc;
}
