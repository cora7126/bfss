<?php
namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;

/**
 * Provides route responses for the Example module.
 */
class MyAssessments extends ControllerBase {

  /** TODO: make utility class
   * Use this to extract "professional", because $param['formtype'] only contains 'starter' OR 'elite'
   * @param string $assessmentPrice
   */
  protected function getFormTypeFromPrice($assessmentPrice) {
    if($assessmentPrice == '299.99'){
      return 'elite';
    }elseif($assessmentPrice == '29.99'){
      return 'starter';
    }elseif($assessmentPrice == '69.99'){
      return 'professional';
    }else{
      return 'UNKNOWN';
    }
  }
  /** TODO: make utility class
   * Find the pdf template "fid" -- see /admin/structure/fillpdf
   * @param string $form_type
   */
  public function getPdfTemplateId($form_type) {
    switch ($form_type) {
        case 'starter':
          return '7';
        case 'professional':
          return '8';
        case 'elite':
          return '9';
      default:
       return -1111;
    }
   }

  /** TODO: make utility class;
   * Return a url to download the assessment pdf.
   * @param string $pdf_template_fid -- see /admin/structure/fillpdf
   */
  protected function getFillPdfUrl($pdf_template_fid, $nid) {
    $default_entity_id = ''; // $form_state->getValue('form_token'); // currently not used
    return '/fillpdf?fid='.$pdf_template_fid.'&entity_type=node&entity_id='.$nid.'&download=1';
    // http://bfss.mindimage.net/fillpdf?fid=2&entity_type=node&entity_id=310&download=1

  }


  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function my_assessments()
  {
    $block = \Drupal\block\Entity\Block::load('assessmentsnapshotblock');
    // ksm(['block...',$block]);
    $block_content = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block);
    // ksm(['block_content...',$block_content]);
    $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);
    // ksm(['assessments_block...',$assessments_block]);

    //assessment get by current assessors
    $uid = \Drupal::currentUser();
    $user = \Drupal\user\Entity\User::load($uid->id());
    $roles = $user->getRoles();

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'assessment');
    $nids = $query->execute();

    // ksm(['$uid->id(), roles, nids...', $uid->id(), $roles, $nids]);

    $result = array();

    foreach ($nids as $nid) {
      $booked_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('assessment', $nid,'IN')
        ->condition('user_id',$uid->id(),'IN')
        ->sort('time','DESC')
        ->execute();
      foreach ($booked_ids  as $key => $booked_id) {
          $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
          $address_1 = $entity->address_1->value;

          $timestamp = $entity->time->value;
          $booking_date = date("F d,Y",$timestamp);
          $booking_time = date("h:i a",$timestamp);

          $query1 = \Drupal::entityQuery('node');
          $query1->condition('type', 'athlete_assessment_info');
          $query1->condition('field_booked_id',$booked_id, 'IN');
          $nids1 = $query1->execute();

            //sport
          $query5 = \Drupal::database()->select('athlete_school', 'ats');
          $query5->fields('ats');
          $query5->condition('athlete_uid', $uid->id(),'=');
          $results5 = $query5->execute()->fetchAssoc();
          $sport = $results5['athlete_school_sport'];

          $realFormType = $this->getFormTypeFromPrice($entity->service->value);

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
            $field_status = Markup::create('<span class="green">Upcoming</span>');
            $st = 0;
        }
        $result[] = array(
          'id' => $entity->id->value,
          'user_name' =>$entity->user_name->value,
          'nid' => $nid,
          'formtype' => $realFormType,
          'Assess_type' => $Assess_type,
          'booking_date'  => $booking_date,
          'booking_time'  => $booking_time,
          'booked_id' => $booked_id,
          'st' =>  $st,
          'assess_nid' => $assess_nid,
          'address_1' => $address_1,
          'sport' => $sport,
          'status' => $field_status,
          'time' => $booking_time,
        );
      }
    }
    /**************drupal table start*****************/
    $header = array(
      array('data' => Markup::create('Date <span></span>'), 'field' => 'date'),
      array('data' => Markup::create('Reports (PDFs) <span></span>'), 'field' => 'program'),
      #array('data' => Markup::create('Sport <span></span>'), 'field' => 'sport'),
      array('data' => Markup::create('Location <span></span>'), 'field' => 'location'),
      array('data' => Markup::create('Status <span></span>'), 'field' => 'status'),
    );
    $result = $this->_return_pager_for_array($result, 10);
    // Wrapper for rows
    foreach ($result as $item) {
      $nid = $item['nid'];

      // old pdf way:
      //xxxxx $type = $item['formtype'];
      // $Assesstype = $item['Assess_type'];
      // $booked_id = $item['booked_id'];
      // $st = $item['st'];
      // $user_name = $item['user_name'];
      // // $url = 'starter-professional-assessments?nid='.$nid.'&formtype='.$type.'&Assess_type='.$Assesstype.'&booked_id='.$booked_id.'&st='.$st.'&assess_nid='.$item['assess_nid'];
      //xxxxxx $pdf_arr = [
      //   'date' => $item['booking_date'],
      //   'user_name' => $user_name,
      //   'Assesstype' => $Assesstype,
      //   'type' => $type,
      //   'status' => $item['status'],
      //   'location' => $item['address_1'],
      //   'sport' => $item['sport'],
      //   'time' => $item['time'],
      // ];
      //xxxxx $url = '/pdf-download?'.http_build_query($pdf_arr); // <a href="'.$url.'">

      $urlhtml = $formtype = '';
      if($item['status'] == 'complete' || $item['status'] == 'incomplete'){
        $pdf_template_fid = $this->getPdfTemplateId($item['formtype']);

        // ksm('iiiiiiiiiiiiiiiiiiiiiiiiii', $item);

        $fillPdfUrl = $this->getFillPdfUrl($pdf_template_fid, $item['booked_id']);
        $urlhtml = '<a href="'.$fillPdfUrl.'" target="_blank">';
      }
      if(!empty($item['formtype'])){
        $formtype = Markup::create('<p>'.$urlhtml.ucfirst($item['formtype']).' Assessment</a></p>');
      }
      $rows[] = array(
        'date' => $item['booking_date'],
        'program' => $formtype,
        #'sport' => $item['sport'],
        'location' => $item['address_1'],
        'status' => $item['status'],
      );
    }
    $rows = $this->_records_nonsql_sort($rows, $header);
    // Create table and pager
    $element['table'] = array(
      '#theme' => 'table',
      '#prefix' => '<div class="">',
      '#suffix' => '</div>',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('There is no data available.'),
    );

    $element['pager'] = array(
      '#type' => 'pager',
    );
    //return $element;

    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'my_assessments_page',
      '#my_assessments_block' => $assessments_block,
      '#my_assessments_records_block' => $element,
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
  }

  //sorting rows
  function _records_nonsql_sort($rows, $header, $flag = SORT_STRING|SORT_FLAG_CASE) {
    if(isset($_GET['sort'])){
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
    }else{
      return $rows;
    }

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