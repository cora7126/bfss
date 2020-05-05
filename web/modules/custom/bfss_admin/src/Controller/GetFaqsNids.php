<?php
namespace Drupal\bfss_admin\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;

class GetFaqsNids extends ControllerBase {
	  public function get_faqs_nids($nids,$user_role) {
	  	
	  	$params = json_decode($nids);
	  	$str_nids= implode(",",$params);
	  	$current_user = \Drupal::currentUser()->id();
	  	$conn = Database::getConnection();
	  	$bfss_faqs_nids = $this->Get_Data_From_Tables('bfss_faqs_nids','atc',$user_role); 
	  	
			if(empty($bfss_faqs_nids)){
				$conn->insert('bfss_faqs_nids')->fields(
					[
				    	'role' => $user_role,
						'faq_nids' => $str_nids,
					]
				)->execute();	
			}else{
				$conn->update('bfss_faqs_nids')->condition('role', $user_role, '=')->fields(
					[
						'faq_nids' => $str_nids,
					]
				)->execute();
			}	  		
	 
	  	$response = array($user_role);
	    return new JsonResponse($response);
  	}


  	public function Get_Data_From_Tables($TableName,$atr,$user_role){
  		if($TableName){
  			$conn = Database::getConnection();
			$query = $conn->select($TableName, $atr);
		    $query->fields($atr);
		    $query->condition('role', $user_role, '=');
		    $results = $query->execute()->fetchAssoc();
  		}
  		return $results;
	}
}