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
      $param = \Drupal::request()->query->all();
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
          'invoice_id' => $booked_id,
       		'invoice' => '#M-'.$booked_id,
       		'paid_date' => $paid_date,
       		'description' => $description,
       		'amount' => $amount,
       		'assessment_date' => $assessmentDate,
          'form' => 'multistep',
       	];
      	$reg_data = $this->register_form_payment_receipts_listing();
        $PaymentData = array_merge($data,$reg_data);
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
          foreach($PaymentData as $value){
          	 $tb1 .= '<tr>
      	     <td><a href="/view-payments-and-receipts?invoice_id='.$value['invoice_id'].'&f_type='.$value['form'].'">'.$value['invoice'].'</a></td>
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

         if(isset($param['invoice_id']) && $param['f_type'] == 'multistep'){
          $data = $this->payment_receipts($param['invoice_id']);
          return [
            'results' => [
                  '#cache' => ['max-age' => 0,],
                  '#theme' => 'payments_receipts_print_page',
                  '#data' => $data,
                  '#empty' => 'no',
                ],
           ];
         }elseif(isset($param['invoice_id']) && $param['f_type'] == 'register'){
           $data = $this->register_form_payment_receipts($param['invoice_id']);
           return [
            'results' => [
                  '#cache' => ['max-age' => 0,],
                  '#theme' => 'payments_receipts_print_page',
                  '#data' => $data,
                  '#empty' => 'no',
                ],
           ];
         }else{
            $page_data = $tb1;
         }
         

       	 return [
            '#cache' => ['max-age' => 0,],
            '#theme' => 'view_payments_and_receipts_page',
            '#view_payments_and_receipts_block' => Markup::create($page_data),
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

  public function payment_receipts($booked_id){
        $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
        $extra = $entity->extra->value;
        if(isset($entity->extra->value)){
          $data_tr = explode('/',$entity->extra->value);
        }
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

        $data = [
          'invoice_number' => '#M-'.$booked_id,
          'full_name' => $entity->first_name->value.' '.$entity->last_name->value,
          'invoice_date' => $paid_date,
          'assessment_date' => $assessmentDate,
          'assessment_type' => $description,
          'total' => $amount,
          'authorized_code' =>(isset($data_tr[0])?$data_tr[0]:''),
          'transaction_id' => (isset($data_tr[1])?$data_tr[1]:''),
        ];
        return $data;
  }

    public function register_form_payment_receipts($id){
      $reg_payments = \Drupal::database()->select('bfss_register_user_payments', 'rup')
      ->fields('rup')
      ->condition('payment_status','paid', '=')
       ->condition('id',$id, '=')
      ->execute()->fetchAssoc();

           if($reg_payments['program_term'] == 1){
            $description = 'Starter Assessment';
          }elseif($reg_payments['program_term'] == 2){
            $description = 'Professional Assessment';
          }elseif($reg_payments['program_term'] == 3){
            $description = 'Elite Assessment';
          }else{
            $description = '';
          }
        $data = [
          'invoice_number' => '#R-'.$id,
          'full_name' => $reg_payments['bi_first_name'].' '.$reg_payments['bi_last_name'],
          'invoice_date' => date('F d, Y',$reg_payments['created']),
          'assessment_date' => '',
          'assessment_type' => $description,
          'total' => $reg_payments['amount'],
          'authorized_code' => '',
          'transaction_id' => '',
        ];
      return $data;  
    }

    public function register_form_payment_receipts_listing(){
      $reg_payments = \Drupal::database()->select('bfss_register_user_payments', 'rup')
        ->fields('rup')
        ->condition('payment_status','paid', '=')
        ->execute()->fetchAll();
        $data = [];
        foreach ($reg_payments as $key => $value) {
          $user = User::load($value->uid);
          if($value->program_term == 1){
            $description = 'Starter Assessment';
          }elseif($value->program_term == 2){
            $description = 'Professional Assessment';
          }elseif($value->program_term == 3){
            $description = 'Elite Assessment';
          }else{
            $description = '';
          }
        
          if($user){
            $data[] = [
              'invoice_id' => $value->id,
              'invoice' => '#R-'.$value->id,
              'paid_date' => date('F d, Y',$value->created),
              'description' => $description,
              'amount' => $value->amount,
              'assessment_date' => '',
              'form' => 'register',
            ];
          }
        }
        return $data;
    }
}