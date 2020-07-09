<?php
namespace Drupal\bfss_admin\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\bfss_assessment\AssessmentService;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;

class ManagersPendingPaymentController extends ControllerBase {

	 public function managers_pending_payment() {

	 	    if( isset($_POST['maid_payment_submit']) ){
			      if(isset($_POST['items_selected'])){  	
			        foreach ($_POST['items_selected'] as $key => $value) {
			          $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($value);
			          $entity->payment_status->value = 'paid';
			          $entity->notes->value = 'Payment status chnaged by bfss admin.';
			          $entity->save();
			        } 
			      }
     		}

	 	$booked_ids = \Drupal::entityQuery('bfsspayments')
		->condition('payment_status','unpaid', '=')
        ->execute();
        $data = [];
        foreach ($booked_ids as $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
        		$paid_date = date('F d, Y',$entity->created->value);
		      	$amount = $entity->service->value;
		      	$nid = $entity->assessment->value;
		      	$assessmentDate = date('F d, Y',$entity->time->value);
		      	$node = Node::load($nid);
					$type = $node->field_type_of_assessment->value;

					$program = AssessmentService::getFormTypeFromPrice($amount);

		      	$full_name = $entity->first_name->value.' '.$entity->last_name->value;
		      	$city = $entity->city->value;
		      	$state = $entity->state->value;

		      	if(isset($nid)){
		      		$node = Node::load($nid);
		      		if(!empty($node))
		      		{
		      			$m_uid = $node->getOwnerId();
				      	if(isset($m_uid)){
					      	$m_user = User::load($m_uid);
					      	$roles = $m_user->getRoles();
					      	if(in_array('bfss_manager', $roles)){
					      		$m_name = $m_user->field_first_name->value.' '.$m_user->field_last_name->value;
					      	}
				      	}
		      		}
		      	}

		      	if (strpos($amount, 'freecredit') !== false) {
          			#code for this condition
        		}else{

			       	$data[] = [
			       		'booked_id' => $booked_id,
			       		'purchased_date' => $paid_date,
			       		'program' => $program,
			       		'amount' => $amount,
			       		'assessment_date' => $assessmentDate,
			       		'customer_name' => $full_name,
			       		'city' => $city,
			       		'state' => $state,
			       		'user_id' => $entity->user_id->value,
			       		'manager_name' => $m_name,
			       	];
		       	}
        }
		$tb1 = '<form class="athletes-unfollow-form" action="" method="post" id="manager-pending_payments-form" onsubmit="return false;" accept-charset="UTF-8">
			<div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
              	<th class="th-hd"><a><span></span>Select</a>
                </th>
                <th class="th-hd"><a><span></span>Purchased Date</a>
                </th>
                  <th class="th-hd long-th th-last"><a><span></span>Customer Name</a>
                </th>
                <th class="th-hd long-th th-fisrt"><a><span></span>City</a>
                </th>
                <th class="th-hd long-th th-fisrt"><a><span></span>State</a>
                </th>
                <th class="th-hd long-th th-fisrt"><a><span></span>Program</a>
                </th>
                 <th class="th-hd long-th th-fisrt"><a><span></span>Amount</a>
                </th>
                </th>
                 <th class="th-hd long-th th-fisrt"><a><span></span>BFSS Manager</a>
                </th>
              </tr>
            </thead>
            <tbody>';

         $reg_payments = $this->GET_bfss_register_user_payments();
		 $data = array_merge($data,$reg_payments);
		 foreach ($data as $key => $part) {
       		$sort[$key] = $part['purchased_date'];
  		 }
  		array_multisort($sort, SORT_DESC, $data);
  		// print_r($data);
    //         die;
        foreach ($data as $value) {
        	$uid = $value['user_id'];
	        $tb1 .= '<tr>
	         <td><input class="form-checkbox getcheckboxid" type="checkbox" name="items_selected[]" value="'.$value['booked_id'].'"><span class="unfollow-checkbox"></span></td>
	        <td>'.$value['purchased_date'].'</td>
	        <td><a href="/users-editable-account?uid='.$uid.'" target="_blank">'.$value['customer_name'].'</a></td>
	        <td>'.$value['city'].'</td>
	        <td>'.$value['state'].'</td>
	        <td>'.$value['program'].'</td>
	        <td>'.$value['amount'].'</td>
	        <td>'.$value['manager_name'].'</td>
	         </tr>';
        }

         $tb1 .= '<div class="unfollow-sub"><i class="fas fa-times"></i><input type="submit" name="maid_payment_submit" value="PAYMENT MADE" onclick="payment_status_change();" ></div>
            </tbody>
            </table>
             </div>
            </div>
             </div>
            </div></form></form>';

	      	return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'managers_pending_payment_page',
		    '#managers_pending_payment_block' =>  Markup::create($tb1),
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		];

  	}

  	function GET_bfss_register_user_payments(){
		$reg_payments = \Drupal::database()->select('bfss_register_user_payments', 'athw')
          ->fields('athw')
          ->condition('payment_status','unpaid', '=')
          ->execute()->fetchAll();
        $register_payment_data = [];
        if(!empty($reg_payments) && is_array($reg_payments)){
	        foreach ($reg_payments as $key => $value) {
        	 	if($value->program_term == 1){
		          $description = 'Starter';
		        }elseif($value->program_term == 2){
		          $description = 'Professional';
		        }elseif($value->program_term == 3){
		          $description = 'Elite';
		        }else{
		          $description = '';
		        }
	        	$user = User::load($value->uid);

	        	if(isset($value->booking_id)){
	        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($value->booking_id);
	        		$nid = $entity->assessment->value;
					if(isset($nid)){
		      		$node = Node::load($nid);
			      		if(!empty($node))
			      		{
			      			$m_uid = $node->getOwnerId();
					      	if(isset($m_uid)){
						      	$m_user = User::load($m_uid);
						      	$roles = $m_user->getRoles();
						      	if(in_array('bfss_manager', $roles)){
						      		$m_name = $m_user->field_first_name->value.' '.$m_user->field_last_name->value;
						      	}
					      	}
			      		}
		      		}
	        	}

				if($user){
					$register_payment_data[] = [
						'booked_id' =>'',
						'purchased_date' => date('F d, Y',$value->created),
						'program' =>$description,
						'amount' => $value->amount,
						'assessment_date' => date('F d, Y',$value->assessment_date),
						'customer_name' => $value->bi_first_name.' '.$value->bi_last_name,
						'city' => $value->bi_city,
						'state' => $value->bi_state,
						'user_id' => $value->uid,
						'manager_name' => $m_name,
					];
				}
	        }
    	}
       return $register_payment_data;
  	}
}