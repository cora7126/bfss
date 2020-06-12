<?php
namespace Drupal\bfss_admin\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;

class ManagersPendingPaymentController extends ControllerBase {
	  
	 public function managers_pending_payment() {
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
		       	$data[] = [
		       		'purchased_date' => $paid_date,
		       		'program' => $program,
		       		'amount' => $amount,
		       		'assessment_date' => $assessmentDate,
		       		'customer_name' => $full_name,
		       		'city' => $city,
		       		'state' => $state,
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
              </tr>
            </thead>
            <tbody>';

        foreach ($data as $value) {
	        $tb1 .= '<tr>
	        <td>'.$value['purchased_date'].'</td>
	        <td>'.$value['customer_name'].'</td>
	        <td>'.$value['city'].'</td>
	        <td>'.$value['state'].'</td>
	        <td>'.$value['program'].'</td>
	        <td>'.$value['amount'].'</td>
	         </tr>';
        }
         
         $tb1 .= '
            </tbody>
            </table>
             </div>
            </div>
             </div>
            </div>';

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
}