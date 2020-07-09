<?php

namespace Drupal\bfss_assessors\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\bfss_assessment\AssessmentService;


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
    $ele = 4;
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
    #$query->condition('field_assessors', $current_assessors_id, '=');
    $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
    $query->condition('field_type_of_assessment','private', '=');
    $nids = $query->execute();
    $result = array();
    foreach ($nids as $nid) {
      $booked_ids = \Drupal::entityQuery('bfsspayments')
      ->condition('assessment', $nid,'IN')
      ->condition('time',time(),'>')
      ->execute();
      //print_r($booked_ids);
      foreach ($booked_ids  as $key => $booked_id) {
            $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
            $address_1 = $entity->address_1->value;
            $timestamp = $entity->time->value;
            $booking_date = date("M d Y",$timestamp);
            $booking_time = date("h:i a",$timestamp);
            $user_id = $entity->user_id->value;
            $query1 = \Drupal::entityQuery('node');
            $query1->condition('type', 'athlete_assessment_info');
            $query1->condition('field_booked_id',$booked_id, 'IN');
            $nids1 = $query1->execute();

            //sport
            $query5 = \Drupal::database()->select('athlete_school', 'ats');
            $query5->fields('ats');
            $query5->condition('athlete_uid', $user_id,'=');
            $results5 = $query5->execute()->fetchAssoc();
            // echo "<pre>";
            // print_r($results5);

            $sport = $results5['athlete_school_sport'];
            $postion = $results5['athlete_school_pos'];

            $formtype = AssessmentService::getFormTypeFromPrice($entity->service->value);

            if(!empty($entity->assessment->value)){
              $Assess_type = 'individual';
            }else{
              $Assess_type = 'private';
            }

            $st ='';
            $assess_nid = '';
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
              'booked_id' => $booked_id,
              'id' => $entity->id->value,
              'user_name' =>$entity->user_name->value,
              'nid' => $nid,
              'formtype' => $formtype,
              'Assess_type' => $Assess_type,
              'field_status' => $field_status,
              'booking_date'  => $booking_date,
              'booking_time'  => $booking_time,
              'assess_nid' => $assess_nid,
              'first_name' =>$entity->first_name->value,
              'last_name' =>$entity->last_name->value,
              'address_1' => $address_1,
              // 'sport' => $sport,
              'postion' => $postion,
              'st' =>  $st,
            );
        	}
        }

        $header = array(
          #array('data' => t('id'), 'field' => 'id'),
          'date' => array('data' => Markup::create('Date <span></span>'), 'field' => 'date'),
          'time' => array('data' => Markup::create('Time <span></span>'), 'field' => 'time'),
          'user_name' => array('data' => Markup::create('Name <span></span>'), 'field' => 'user_name'),
          'type_assess' => array('data' => Markup::create('Assessment Type <span></span>'), 'field' => 'type_assess'),
          'location' => array('data' => Markup::create('Location <span></span>'), 'field' => 'location'),
        );


        $result = $this->_return_pager_for_array($result, 5, $ele);
      // Wrapper for rows
      foreach ($result as $item) {
        $nid = $item['nid'];
        $type = $item['formtype'];
        $Assesstype = $item['Assess_type'];
        $booked_id = $item['booked_id'];
        $st = $item['st'];
        $user_name = $item['user_name'];

        $url = 'pending-assessments-form?nid='.$nid.'&formtype='.$type.'&Assess_type='.$Assesstype.'&booked_id='.$booked_id.'&st='.$st.'&first_name='.$item['first_name'].'&last_name='.$item['last_name'].'&sport='.$item['sport'].'&postion='.$item['postion'].'&field_status='.$item['field_status'].'&assess_nid='.$item['assess_nid'];
        // $url = 'starter-professional-assessments?nid='.$nid.'&formtype='.$type.'&Assess_type='.$Assesstype.'&booked_id='.$booked_id.'&st='.$st.'&assess_nid='.$item['assess_nid'].'&first_name='.$item['first_name'].'&last_name='.$item['last_name'].'&sport='.$item['sport'].'&postion='.$item['postion'];


        $user_name = Markup::create('<p><a class="use-ajax" data-dialog-type="modal" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm private-assesspopup&quot;}"  href="'.$url.'">'.$user_name.'</a></p>');
        $rows[] = array(
          #'id' => $item['booked_id'],
          'date' => $item['booking_date'],
          'time' => $item['booking_time'],
          'user_name' => $user_name,
          'type_assess' => $item['formtype'],
          'location' => $item['address_1'],
        );
      }
      //if($header['date_e']['specifier'] == 'date_e' || $header['time_e']['specifier'] == 'time_e' ||$header['assessment_title_e']['specifier'] == 'assessment_title_e' || $header['attendees_e']['specifier'] == 'attendees_e'){
      $rows = $this->_records_nonsql_sort($rows, $header);
      //}
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
    function _return_pager_for_array($items,$num_page,$ele) {
      // Get total items count
      $total = count($items);
      // Get the number of the current page
      $current_page = pager_default_initialize($total, $num_page,$ele);
      // Split an array into chunks
      $chunks = array_chunk($items, $num_page);
      // Return current group item
      $current_page_items = $chunks[$current_page];
      return $current_page_items;
    }

}