<?php
namespace Drupal\bfss_assessors\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;

class AssessmentEvent extends ControllerBase {
	public function assessment_event() {
        $param = \Drupal::request()->query->all();
        $booked_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('assessment',$param['nid'],'IN')
        ->condition('time',$param['timeslot'],'=')
        ->execute();

          $result = array();
        	foreach ($booked_ids  as $key => $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
            $assess_id = $entity->assessment->value;
            $user_id = $entity->user_id->value;

            $query1 = \Drupal::entityQuery('node');
            $query1->condition('type', 'athlete_assessment_info');
            $query1->condition('field_booked_id',$booked_id, 'IN');
            $nids1 = $query1->execute();
            if(!empty($nids1)){
               foreach ($nids1 as $key => $value) {
                 $node1 = Node::load($value);
                 $field_status = $node1->field_status->value;
              } 
            }else{
               $field_status = 'No Show';
               $st = 0;
            }
            
            $query5 = \Drupal::database()->select('athlete_school', 'ats');
            $query5->fields('ats');
            $query5->condition('athlete_uid', $user_id,'=');
            $results5 = $query5->execute()->fetchAssoc();            
            $sport = $results5['athlete_school_sport'];

            $entity->assessment->value;
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
            
        		$result[] = array(
              'booked_id' => $booked_id,
        		  'id' => $entity->id->value,
              'user_name' => $entity->user_name->value,
              'service' => $entity->service->value,
              'nid' => $nid,
              'formtype' => $formtype,
              'Assess_type' => $Assess_type,
              'booking_date'  => $booking_date,
              'booking_time'  => $booking_time,
              'assessment_title'  => $assessment_title,
              'status' => $field_status,
              'sport' => $sport,
        		);	
        	}
        
        $header = array(
          #array('data' => t('booked_id'), 'field' => 'booked_id'),
          array('data' => t('Name'), 'field' => 'user_name'),
          array('data' => t('sport'), 'field' => 'sport'),
          array('data' => t('Status'), 'field' => 'status'),
        );
        $result = $this->_return_pager_for_array($result, 10);
      // Wrapper for rows
      foreach ($result as $item) {
        $url = 'starter-professional-assessments?nid='.$item['nid'].'&formtype='.$item['formtype'].'&Assess_type='.$item['Assess_type'].'&booked_id='.$item['booked_id'].'&st='.$st;
        $user_name = Markup::create('<a href="'.$url.'">'.$item['user_name'].'</a>');
        $rows[] = array(
          #'booked_id' => $item['booked_id'],
          'user_name' => $user_name,
          'sport' => $item['sport'],
          'status' => $item['status'],
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
      );

          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'assessment_events_page',
          '#name' => 'Shubham Rana',
          '#assessment_events_block' => $element,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
      //return $element;
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


?>