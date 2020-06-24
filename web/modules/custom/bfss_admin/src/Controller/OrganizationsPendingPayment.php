<?php
namespace Drupal\bfss_admin\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;

class OrganizationsPendingPayment extends ControllerBase {
	  
	 public function organizations_pending_payment() {
	 	$booked_ids = \Drupal::entityQuery('bfsspayments')
		->condition('payment_status','unpaid', '=')
        ->execute();
        $data = [];
        foreach ($booked_ids as $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
        		$uid = $entity->user_id->value;
        		// echo "<pre>";
        		// print_r($entity);
        		// die;
        		$queryorg = \Drupal::database()->select('athlete_school', 'ats');
				$queryorg->fields('ats');
				$queryorg->condition('athlete_uid', $uid, '=');
				$resultorg = $queryorg->execute()->fetchAssoc();

        		$paid_date = date('F d, Y',$entity->created->value);
		      	$amount = $entity->service->value;
		      	$nid = $entity->assessment->value;
		      	$assessmentDate = date('F d, Y',$entity->time->value);
		      	$node = Node::load($nid);
		      	$type = $node->field_type_of_assessment->value;
			      	if($amount == '29.99'){
			      		$program = 'Starter';
			      	}elseif($amount == '69.99'){
			      		$program = 'Professional';
			      	}elseif($amount == '299.99'){
			      		$program = 'Elite';
			      	}else{
			      		$program = '';
			      	}
		      	$full_name = $entity->first_name->value.' '.$entity->last_name->value;
		      	$city = $entity->city->value;
		      	$state = $entity->state->value;
		      	$coach = $this->GetCoach($resultorg['athlete_school_name']);
		       	$data[] = [
		       		'purchased_date' => $paid_date,
		       		'program' => $program,
		       		'amount' => $amount,
		       		'assessment_date' => $assessmentDate,
		       		'customer_name' => $full_name,
		       		'city' => $city,
		       		'state' => $state,
		       		'organizations_name' => $resultorg['athlete_school_name'],
		       		'coach' => $coach,
		       		'user_id' => $entity->user_id->value,
		       	];
        }	
		$tb1 = '<div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>Purchased Date</a>
                </th> 
                  <th class="th-hd long-th th-last"><a><span></span>Organizations Name</a>
                </th>
                  <th class="th-hd long-th th-last"><a><span></span>Customer Name</a>
                </th>  
                <th class="th-hd long-th th-fisrt"><a><span></span>City</a>
                </th>
                <th class="th-hd long-th th-fisrt"><a><span></span>State</a>
                </th>
                 <th class="th-hd long-th th-fisrt"><a><span></span>Amount</a>
                </th>
                 <th class="th-hd long-th th-fisrt"><a><span></span>Coach</a>
                </th>
              </tr>
            </thead>
            <tbody>';

        foreach ($data as $value) {
        	$uid = $value['user_id'];
	        $tb1 .= '<tr>
	        <td>'.$value['purchased_date'].'</td>
	        <td><a href="/users-editable-account?uid='.$uid.'" target="_blank">'.$value['organizations_name'].'</a></td>
	        <td>'.$value['customer_name'].'</td>
	        <td>'.$value['city'].'</td>
	        <td>'.$value['state'].'</td>
	        <td>'.$value['amount'].'</td>
	          <td>'.$value['coach'].'</td>
	         </tr>';
        }
         
         $tb1 .= '</tbody>
            </table>
             </div>
            </div>
             </div>
            </div>';

	      	return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'organizations_pending_payment_page',
		    '#organizations_pending_payment_block' =>  Markup::create($tb1),
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		]; 
	   
  	}

		public function GetCoach($organization_name){
			if(!empty($organization_name)){
				$query = \Drupal::entityQuery('node');
				$query->condition('type', 'bfss_organizations');
				$query->condition('field_organization_name',$organization_name, '=');
				$nids = $query->execute();
				foreach($nids as $nid){
					$node = Node::load($nid);
					$coach = $node->field_coach_title->value;
				}
			}
			return $coach;
		}

}