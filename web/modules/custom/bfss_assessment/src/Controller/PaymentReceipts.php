<?php
namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;

use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\node\Entity\Node;
use Drupal\bfss_assessment\AssessmentService;
Use Drupal\node\NodeInterface;
/**
 * Provides route responses for the Example module.
 */
class PaymentReceipts extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function payment_receipts() {

      $reg_payments_data = $this->GET_bfss_register_user_payments();
      $uid = \Drupal::currentUser()->id();
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

        $description = AssessmentService::getFormTypeFromPrice($amount);
        $description += ' Assessment';

        if (strpos($amount, 'freecredit') !== false) {
          #code for this condition
        }else{
          $data[] = [
            'id' => $booked_id,
            'invoice' => '#M-'.$booked_id,
            'paid_date' => $paid_date,
            'form' => 'multistep',
          ];
        }
      }
      $data = array_merge($data,$reg_payments_data);
      // echo"<pre>";
      // print_r($data);
      // die;
    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'payment_receipts_page',
      '#invoice_history_section' => $data,
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
  }

  function GET_bfss_register_user_payments(){
    $uid = \Drupal::currentUser()->id();
        $reg_payments = \Drupal::database()->select('bfss_register_user_payments', 'rup')
              ->fields('rup')
              ->condition('uid',$uid, '=')
              ->condition('payment_status','paid', '=')
              ->execute()->fetchAll();
        $register_payment_data = [];
        foreach ($reg_payments as $key => $value) {
          $user = User::load($value->uid);
          if($user){
            $register_payment_data[] = [
              'id'=> $value->id,
              'invoice' => '#R-'.$value->id,
              'paid_date' => date('F d, Y',$value->created),
              'form' => 'register',
            ];
          }
        }
        return $register_payment_data;
    }


}