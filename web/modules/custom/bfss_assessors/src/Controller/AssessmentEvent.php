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
        $title = $param['title'];
        $booked_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('assessment',$param['nid'],'IN')
        ->condition('time',$param['timeslot'],'=')
        ->execute();

          $result = array();
        	foreach ($booked_ids  as $key => $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
            $assess_id = $entity->assessment->value;
            $user_id = $entity->user_id->value;
            $timestamp = $entity->time->value;
            $assessment_title = $entity->assessment_title->value;

            //check athlete_assessment_info info node
            $query1 = \Drupal::entityQuery('node');
            $query1->condition('type', 'athlete_assessment_info');
            $query1->condition('field_booked_id',$booked_id, 'IN');
            $nids1 = $query1->execute();
        
            if(!empty($nids1)){
               foreach ($nids1 as $key => $value) {
                 $assess_nid = $value;
                 $node1 = Node::load($value);
                 $field_status = $node1->field_status->value;
                 
              } 
              $st = 1;
            }else{
               $assess_nid = '';
               $field_status = 'No Show';
               $st = 0;
            }

            //sport
            $query5 = \Drupal::database()->select('athlete_school', 'ats');
            $query5->fields('ats');
            $query5->condition('athlete_uid', $user_id,'=');
            $results5 = $query5->execute()->fetchAssoc();            
            $sport = $results5['athlete_school_sport'];
            $postion = $results5['athlete_school_pos'];

      

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
              'nid' => $param['nid'],
              'formtype' => $formtype,
              'Assess_type' => $Assess_type,
              'booking_date'  => $booking_date,
              'booking_time'  => $booking_time,
              'assessment_title'  => $assessment_title,
              'status' => $field_status,
              'sport' => $sport,
              'st' => $st,
              'assess_nid' => $assess_nid,
              'first_name' =>$entity->first_name->value,
              'last_name' =>$entity->last_name->value,
              'postion' => $postion,
        		);	
        	}
        
        $header = array(
          array('data' => Markup::create('Name <span></span>'), 'field' => 'user_name'),
          array('data' => Markup::create('Sport <span></span>'), 'field' => 'sport'),
          array('data' => Markup::create('Status <span></span>'), 'field' => 'status'),
        );

        if(!empty($_GET['par_page_item'])){
          $parpage = $_GET['par_page_item'];
        }else{
          $parpage = 10;
        }
        //$result = $this->_return_pager_for_array($result, $parpage);
      // Wrapper for rows


         $tb = '
          <div class="wrapped_div_main">
          <h2>Athletic Profile Assessments - Highland High School</h2>
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="dtBasicExample" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span> Name</a>
                </th>
                <th class="th-hd"><a><span></span> Position</a>
                </th>
                <th class="th-hd"><a><span></span> Office</a>
                </th>
              
              </tr>
            </thead>
            <tbody>';
      foreach ($result as $item) {
        $nid = $item['nid'];
        $type = $item['formtype'];
        $Assesstype = $item['Assess_type'];
        $booked_id = $item['booked_id'];
        $st = $item['st'];
        $user_name = $item['user_name'];
        $url = 'starter-professional-assessments?nid='.$nid.'&formtype='.$type.'&Assess_type='.$Assesstype.'&booked_id='.$booked_id.'&st='.$st.'&assess_nid='.$item['assess_nid'].'&first_name='.$item['first_name'].'&last_name='.$item['last_name'].'&sport='.$item['sport'].'&postion='.$item['postion'];
       
        $user_name = Markup::create('<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm private-assesspopup&quot;}" data-dialog-type="modal" href="'.$url.'">'.$user_name.'</a></p>');
        // $rows[] = array(
        //   'user_name' => $user_name,
        //   'sport' => $item['sport'],
        //   'status' => $item['status'],
        // );
         $tb .= '<tr>
                <td>'.$user_name.'</td>
                <td>'.$item['sport'].'</td>
                <td>'.$item['status'].'</td>
              </tr>';
      }
      $tb .= '</tbody>
          </table>
           </div>
          </div>
           </div>
          
          ';

      //$rows = $this->_records_nonsql_sort($rows, $header);

      // Create table and pager
        $out = array(
          '#type' => 'markup',
          '#markup' => 'This block list the article.',
        );
         $element['#prefix'] = '<div class="wrapped_div_main"><h2>'.$title.'</h2>';
         $element['#suffix'] = '</div>';
        //$form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessors\Form\ParPageItemShow');
        //$element['out'] = $form;
        // $par_page_item = $_GET['par_page_item'];
        $element['table'] = array(
          '#theme' => 'table',
          '#prefix' => '<div class="block-bfss-assessors">',
          '#suffix' => '</div>',
          '#header' => $header,
          '#attributes'=>['id' => ['dtBasicExample']],
          '#rows' => $rows,
          '#empty' => t('There is no data available.'),
        );

        $element['pager'] = array(
          '#type' => 'pager',
        );
       
          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'assessment_events_page',
          '#name' => 'G.K',
          // '#prefix' => '<div class="block-bfss-assessors">',
          // '#suffix' => '</div>',
          '#assessment_events_block' => Markup::create($tb),
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