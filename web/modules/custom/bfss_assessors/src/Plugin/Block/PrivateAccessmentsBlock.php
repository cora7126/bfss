<?php

namespace Drupal\bfss_assessors\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;


/**
 * Provides a 'Private Accessments Block' Block.
 *
 * @Block(
 *   id = "private_accessments_block",
 *   admin_label = @Translation("Private Accessments Block"),
 *   category = @Translation("Private Accessments Block"),
 * )
 */
class PrivateAccessmentsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

  
    	//assessment get by current assessors
    $ele = 1;
    $uid = \Drupal::currentUser();
    $user = \Drupal\user\Entity\User::load($uid->id());
    $roles = $user->getRoles();
    if(in_array('assessors', $roles)){
      $current_assessors_id = $uid->id();
    }else{
       $current_assessors_id = '';
    }

    	  $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_assessors', $current_assessors_id , '=');
        $query->pager(10, (int) $ele);
        $nids = $query->execute();
        $booked_ids = [];
        $data = [];
        $result = array();
        foreach ($nids as $nid) {
        	$booked_ids = \Drupal::entityQuery('bfsspayments')
       		->condition('assessment', $nid,'IN')
        	->execute();
        	foreach ($booked_ids  as $key => $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
            $timestamp = $entity->time->value;
            $assessment_title = $entity->assessment_title->value;
            $booking_date = date("Y/m/d",$timestamp);
            $booking_time = date("h:i:sa",$timestamp);
            if($entity->service->value == '199.99'){
            
                $formtype = 'elete';
            }elseif($entity->service->value == '29.99'){
                $formtype = 'starter';
            }

            if(!empty($entity->assessment->value)){
              $Assess_type = 'individual';
            }else{
              $Assess_type = 'private';
            }

            $query1 = \Drupal::entityQuery('node');
            $query1->condition('type', 'athlete_assessment_info');
            $query1->condition('field_booked_id',$booked_id, 'IN');
            
            $nids1 = $query1->execute();
           // print_r($nids1);
            $st ='';
            if(!empty($nids1)){
              $st = 1;
               foreach ($nids1 as $key => $value) {
                $node1 = Node::load($value);
                $field_status = $node1->field_status->value;
                $assess_nid = $value;
              } 
            }else{
               $field_status = 'No Show';
               $st = 0;
            }

        		$result[] = array(
              'id' => $entity->id->value,
              'user_name' =>$entity->user_name->value,
              'service' =>$entity->service->value,
              'nid' => $nid,
              'formtype' => $formtype,
              'Assess_type' => $Assess_type,
              'booking_date'  => $booking_date,
              'booking_time'  => $booking_time,
              'assessment_title'  => $assessment_title,
              'booked_id' => $booked_id,
              'st' =>  $st,
              'assess_nid' => $assess_nid,
        		);	
        	}
        } 

        $header = array(
          array('data' => t('Date'), 'field' => 'date'),
          array('data' => t('Time'), 'field' => 'time'),
          array('data' => t('Name'), 'field' => 'user_name'),
        );
        $result = $this->_return_pager_for_array($result, 10);
      // Wrapper for rows
      foreach ($result as $item) {
        $nid = $item['nid'];
        $type = $item['formtype'];
        $Assesstype = $item['Assess_type'];
        $booked_id = $item['booked_id'];
        $st = $item['st'];
         $user_name = $item['user_name'];
        $url = 'starter-professional-assessments?nid='.$nid.'&formtype='.$type.'&Assess_type='.$Assesstype.'&booked_id='.$booked_id.'&st='.$st.'&assess_nid='.$assess_nid;
       

        $user_name = Markup::create('<p><a class="use-ajax" data-dialog-type="modal" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm&quot;}"  href="'.$url.'">'.$user_name.'</a></p>');
        $rows[] = array(
         'date' => $item['booking_date'],
          'time' => $item['booking_time'],
          'user_name' => $user_name,
        );
      }
      $rows = $this->_records_nonsql_sort($rows, $header);
      // Create table and pager
      $element['table'] = array(
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => t('There is no data available.'),
      );

      $element['pager'] = array(
        '#type' => 'pager',
        '#element' => $ele,
      );

   return $element;
  }


      //sorting rows
    function _records_nonsql_sort($rows, $header, $flag = SORT_STRING|SORT_FLAG_CASE) {
      $order = tablesort_get_order($header);
      $sort = tablesort_get_sort($header);
      $column = $order['sql'];
      foreach ($rows as $row) {
        $temp_array[] = $row[$column];
      }
      if ($sort == 'asc') {
        asort($temp_array, $flag);
      }
      else {
        arsort($temp_array, $flag);
      }
      foreach ($temp_array as $index => $data) {
        $new_rows[] = $rows[$index];
      }
      return $new_rows;
    }

    /**
     * Split array for pager.
     *
     * @param array $items
     *   Items which need split
     *
     * @param integer $num_page
     *   How many items view in page
     *
     * @return array
     */
    function _return_pager_for_array($items, $num_page) {
      // Get total items count
      $total = count($items);
      // Get the number of the current page
      $current_page = pager_default_initialize($total, $num_page);
      // Split an array into chunks
      $chunks = array_chunk($items, $num_page);
      // Return current group item
      $current_page_items = $chunks[$current_page];
      return $current_page_items;
    }

}