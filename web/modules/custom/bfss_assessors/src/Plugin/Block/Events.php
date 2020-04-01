<?php

namespace Drupal\bfss_assessors\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a 'Events Listing' Block.
 *
 * @Block(
 *   id = "events_listing",
 *   admin_label = @Translation("Events Listing"),
 *   category = @Translation("Events Listing"),
 * )
 */
class Events extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
 
   
      //assessment get by current assessors
        $ele = 5;
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
        #$query->pager(10, (int) $ele);
        $nids = $query->execute(); 
  
        $result = array();
        foreach ($nids as $nid) {
        
        	$node = Node::load($nid);
          $title = $node->title->value;
          $timeslots = $this->getSchedulesofAssessment_slots($nid);
         
          foreach ($timeslots as $timeslot) {
            $booked_ids = \Drupal::entityQuery('bfsspayments')
            ->condition('assessment',$nid,'IN')
            ->condition('time',$timeslot,'=')
            ->execute();
            $attendees = count($booked_ids);

           $date = date('M d Y',$timeslot);
           $time =  date('h:i a',$timeslot);
           $result[] = [
              'time' => $time,
              'date' => $date,
              'title' => $title,
              'attendees' => $attendees,
              'timeslot' => $timeslot,
              'nid' => $nid,
           ];
          }
        }
       
 
        $header = array(
        'date_e' => array('data' => Markup::create('Date <span></span>'), 'field' => 'date_e','specifier' => 'date_e'),
        'time_e' =>array('data' => Markup::create('Time <span></span>'), 'field' => 'time_e','specifier' => 'time_e'),
        'assessment_title_e' =>array('data' => Markup::create('Event Name <span></span>'), 'field' => 'assessment_title_e','specifier' => 'assessment_title_e'),
        'attendees_e' => array('data' => Markup::create('Attendees <span></span>'), 'field' => 'attendees_e','specifier' => 'attendees_e'),
        );
        //print_r($header);
  
          $result = $this->_return_pager_for_array($result, 10,$ele);  
        
        
         // Wrapper for rows
         foreach ($result as $item) {
          $url = 'assessment-event?nid='.$item['nid'].'&timeslot='.$item['timeslot'].'&title='.$item['title'];
          $title = Markup::create('<a href="'.$url.'">'.$item['title'].'</a>');
          $rows[] = array(
            'date_e' => $item['date'],
            'time_e' => $item['time'],
            'assessment_title_e' => $title,
            'attendees_e' => $item['attendees'],
          );
      }

      //print_r($_GET);
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
    function _return_pager_for_array($items, $num_page,$ele) {
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

    public function getSchedulesofAssessment_slots($nid = null) {
      $node = Node::load($nid);
      $data = [];
      if ($node instanceof NodeInterface) {
        if ($node->hasField('field_schedules')) {
          $field_schedules = $node->get('field_schedules')->getValue();
          if ($field_schedules) {
            foreach ( $field_schedules as $element ) {
              if (isset($element['target_id'])) {
                $pGraph = Paragraph::load($element['target_id'] );
                if ($pGraph->hasField('field_timing')/* && $pGraph->hasField('field_duration')*/) {
                  $timing = (int) $pGraph->get('field_timing')->value;
                
                  //if ($timing > time()) {
                    $data[$timing] = $timing;
                  //}
                }
              }
            }
          }
        }
      }
    #sort them all
    ksort($data);
    return $data;
    }

}