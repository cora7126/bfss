<?php
namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;

class ViewPaymentsAndReceipts extends ControllerBase {
 public function view_payments_and_receipts()
 {
$uid = \Drupal::currentUser()->id();
$register_paytment = $this->register_time_payment_details();
// echo "<pre>";
// print_r($register_paytment);
// die;
$booked_ids = \Drupal::entityQuery('bfsspayments')
       	->condition('user_id', $uid,'IN')
       	->condition('payment_status', 'paid','=')
        ->execute();
  $data = [];      
foreach ($booked_ids as $booked_id) {
	# code...time,created,service
	$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
	$paid_date = date('F d, Y',$entity->created->value);
	$amount = $entity->service->value;
	$nid = $entity->assessment->value;
	$assessmentDate = date('F d, Y',$entity->time->value);
	$node = Node::load($nid);
	$type = $node->field_type_of_assessment->value;
	if($amount == '29.99'){
		$description = 'Starter Assessment';
	}elseif($amount == '69.99'){
		$description = 'Professional Assessment';
	}elseif($amount == '299.99'){
		$description = 'Elite Assessment';
	}else{
		$description = '';
	}

 	$data[] = [
 		'invoice' => Markup::create('<a>'.$booked_id.'</a>'),
 		'paid_date' => $paid_date,
 		'description' => $description,
 		'amount' => $amount,
 		'assessment_date' => $assessmentDate,
 	];
	
}

// echo "<pre>";
// 	print_r($data);
// die;
 	$tb1 = '<div class="wrapped_div_main user_pro_block">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>Invoice #</a>
                </th> 
                  <th class="th-hd long-th th-last"><a><span></span>Paid Date</a>
                </th>  
                <th class="th-hd long-th th-fisrt"><a><span></span>Description</a>
                </th>
                 <th class="th-hd long-th th-fisrt"><a><span></span>Amount</a>
                </th>
                 <th class="th-hd long-th th-fisrt"><a><span></span>Assessment Date</a>
                </th>
              </tr>
            </thead>
            <tbody>';
    foreach($data as $value){
    	 $tb1 .= '<tr>
	     <td>'.$value['invoice'].'</td>
	     <td>'.$value['paid_date'].'</td>
	     <td>'.$value['description'].'</td>
	     <td>'.$value['amount'].'</td>
	     <td>'.$value['assessment_date'].'</td>
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
      '#theme' => 'view_payments_and_receipts_page',
      '#view_payments_and_receipts_block' => Markup::create($tb1),
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
 }
   public function register_time_payment_details(){
    $uid = \Drupal::currentUser()->id();
    $Query = \Drupal::database()->select('bfss_register_user_payments', 'ats');
    $Query->fields('ats');
    $Query->condition('uid', $uid,'=');
    $results = $Query->execute()->fetchAssoc();
    return !empty($results)?$results:'';
  }
}