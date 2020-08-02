<?php
namespace Drupal\bfss_manager\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\bfss_assessment\AssessmentService;

class PendingAssessments extends ControllerBase {

  public function pending_assessments() {
      $param = \Drupal::request()->query->all();

      //current user
      $current_user = \Drupal::currentUser();
      $user_id = $current_user->id();
      $user = \Drupal\user\Entity\User::load($user_id);
      $roles = $user->getRoles();

      $includeAll = 0;
      $allStatusLink = '';
      if (in_array('bfss_administrator', $roles)) { //  || in_array('bfss_manager', $roles)
        $includeAll = $_GET['includeAll'] ? 1 : 0;
        if ($includeAll) {
          $allStatusLink = '<div><a href="/pending-assessments?includeAll=0" class="button js-form-submit form-submit btn-primary btn ticketing-button-href">Pending Only</a><br><br></div>';
        }
        else {
          $allStatusLink = '<div><a href="/pending-assessments?includeAll=1" class="button js-form-submit form-submit btn-primary btn ticketing-button-href">Include ALL Status\'</a><br><br></div>';
        }
      }

      $booked_ids = \Drupal::entityQuery('bfsspayments')
      //->condition('assessment',$param['nid'],'IN')
      //->condition('time',$param['timeslot'],'=')
      ->execute();

      $result = array();
      foreach ($booked_ids  as $key => $booked_id) {
        $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);

        $node_id =  $entity->assessment->value;
        if($node_id != '9999999999'){
          $nid = $node_id;
        }else{
          $nid = '';
        }

        $assess_id = $entity->assessment->value;
        $user_id = $entity->user_id->value;
        $timestamp = $entity->time->value;

        $athlete_school = $this->Get_Data_From_Tables('athlete_school','ats',$user_id,'athlete_uid'); //FOR ORG-1
        $org_name = isset($athlete_school['athlete_school_name']) ? $athlete_school['athlete_school_name'] : '';
        $athlete_school_sport = isset($athlete_school['athlete_school_sport']) ? $athlete_school['athlete_school_sport'] : '';
        $athlete_school_pos = isset($athlete_school['athlete_school_pos']) ? $athlete_school['athlete_school_pos'] : '';
        $athlete_school_pos .= @$athlete_school['athlete_school_pos2'] ? ', '. $athlete_school['athlete_school_pos2'] : '';
        $athlete_school_pos .= @$athlete_school['athlete_school_pos3'] ? ', '. $athlete_school['athlete_school_pos3'] : '';
        $org_name = isset($athlete_school['athlete_school_name']) ? $athlete_school['athlete_school_name'] : '';

        $mydata = $this->Get_Data_From_Tables('mydata','md',$user_id,'uid');
        $city = isset($mydata['field_city']) ? $mydata['field_city'] : '';
        $height = isset($mydata['field_height']) ? $mydata['field_height'] : '';
        $weight = isset($mydata['field_weight']) ? $mydata['field_weight'] : '';
        $gender = isset($mydata['field_birth_gender']) ? $mydata['field_birth_gender'] : '';

        if(empty($mydata['field_az'])){
          $statequery =  $this->Get_Data_From_Tables('user__field_state','ufln',$user_id,'entity_id');
          $state = isset($statequery['field_state_value']) ? $statequery['field_state_value'] : '';
        }else{
          $state = $mydata['field_az'];
        }
        if(empty($mydata['field_dob'])){
          $statedob =  $this->Get_Data_From_Tables('user__field_date_of_birth','ufdoj',$user_id,'entity_id');
          $dob = $statedob['field_date_of_birth_value'];
        }else{
          $dob = $mydata['field_dob'];
        }

        // if ($booked_id == 103) {
        //   ksm('$dob', $dob);
        //   ksm('$statedob', $statequery);
        //   ksm('$mydata', $mydata);
        //   ksm('$statequery', $statequery);
        //   ksm('$athlete_school', $athlete_school);
        //   // $athlete_info = $this->Get_Data_From_Tables('athlete_info','ai',$user_id,'athlete_uid');
        //   // ksm('$athlete_info', $athlete_info);
        // }

        // See if assessment has been started by assessor or mgr.
        // And get latest recorded status (and $assess_nid) for current assessment - see if complete or incomplete.
        $field_status = 'not started';
        $nidPrev = 0;
        $queryStat = \Drupal::entityQuery('node');
        $queryStat->condition('type', 'athlete_assessment_info');
        $queryStat->condition('field_booked_id',$booked_id, 'IN');
        $nidsStat = $queryStat->execute();
        if($nidsStat){
          foreach ($nidsStat as $nid) {
            if ($nid > $nidPrev) {
              $nidPrev = $nid;
              $assessmentNode = Node::load($nid);
              $field_status = $assessmentNode->get('field_status')->getValue()[0]['value'];
            }
          }
        }
        $assess_nid = $nidPrev;

        // ksm($booked_id, $assess_nid, $field_status);

        if($includeAll || !$nidsStat || $field_status == 'incomplete'){
          $booking_date = date("Y/m/d",$timestamp);
          $booking_time = date("h:i:sa",$timestamp);

          $formtype = AssessmentService::getFormTypeFromPrice($entity->service->value);

          if(!empty($entity->assessment->value)){
            $Assess_type = 'individual';
          }else{
            $Assess_type = 'private';
          }
          // ksm('$entity', $entity->getFieldDefinitions() );

          $result[] = array(
            'booked_id' => $booked_id,
            'id' => $entity->id->value,
            'user_name' => $entity->user_name->value,
            'nid' => $nid,
            'formtype' => $formtype,
            'Assess_type' => $Assess_type,
            'field_status' => $field_status,
            'booking_date'  => $booking_date,
            'booking_time'  => $booking_time,
            'assess_nid' => $assess_nid,
            'first_name' => $entity->first_name->value,
            'last_name' => $entity->last_name->value,
            'org_name' => $org_name,
            'city' => $city,
            'state' => $state,
            'height' => $height,
            'weight' => $weight,
            'gender' => $gender,
            'dob' => $dob,
            'school_sport' => $athlete_school_sport,
            'school_position' => $athlete_school_pos,
          );
        }
      }

      if(!empty($_GET['par_page_item'])){
        $parpage = $_GET['par_page_item'];
      }else{
        $parpage = 10;
      }
      //$result = $this->_return_pager_for_array($result, $parpage);
      // Wrapper for rows
      $tb = $allStatusLink . '
      <div class="wrapped_div_main user_pro_block">
        <div class="block-bfss-assessors">
        <div class="table-responsive-wrap">
        <table id="dtBasicExample" class="table table-hover table-striped" cellspacing="0" width="100%" >
          <thead>
            <tr>
              <th class="th-hd"><a><span></span> Date</a>
              </th>
              <th class="th-hd"><a><span></span> First Name</a>
              </th>
              <th class="th-hd"><a><span></span> Last Name</a>
              </th>
              <th class="th-hd"><a><span></span> Assessment Type</a>
              </th>
              <th class="th-hd"><a><span></span> Status</a>
              </th>
              <th class="th-hd"><a><span></span> Organization</a>
              </th>
              <th class="th-hd"><a><span></span> State</a>
              </th>
              <th class="th-hd"><a><span></span> City</a>
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
      $user_name = $item['field_status'];
      $field_status = $item['field_status'];

      // ksm('$item', $item);
      $url = 'pending-assessments-form?nid='.urlencode($nid).'&formtype='.urlencode($type).'&Assess_type='.urlencode($Assesstype).'&booked_id='.urlencode($booked_id).'&first_name='.urlencode($item['first_name']).'&last_name='.urlencode($item['last_name']).'&field_status='.urlencode($item['field_status']).'&weight='.urlencode($item['weight']).'&gender='.urlencode($item['gender']).'&dob='.urlencode($item['dob']).'&height='.urlencode($item['height']).'&school_sport='.urlencode($item['school_sport']).'&school_position='.urlencode($item['school_position']);

      $first_name = $item['first_name']; // Markup::create('<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm&quot;}" data-dialog-type="modal" href="'.$url.'">'.$item['first_name'].'</a></p>');
      $last_name = $item['last_name']; // Markup::create('<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm&quot;}" data-dialog-type="modal" href="'.$url.'">'.$item['last_name'].'</a></p>');

      // $field_status = Markup::create('<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm&quot;}" data-dialog-type="modal" href="'.$url.'">'.$field_status.'</a></p>');
      $type = Markup::create('<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm&quot;}" data-dialog-type="modal" href="'.$url.'">'.$type.'</a></p>');

      $tb .= '
          <tr>
            <td>'.$item['booking_date'].'</td>
            <td>'.$first_name.'</td>
            <td>'.$last_name.'</td>
            <td>'.$type.'</td>
            <td>'.$field_status.'</td>
            <td>'.$item['org_name'].'</td>
            <td>'.$item['state'].'</td>
            <td>'.$item['city'].'</td>
          </tr>';
    }
    $tb .= '
      </tbody>
        </table>
          </div>
        </div>
          </div>';

    //$rows = $this->_records_nonsql_sort($rows, $header);

    // Create table and pager
    $out = array(
      '#type' => 'markup',
      '#markup' => 'This block list the article.',
    );
    // $element['#prefix'] = '<div class="wrapped_div_main"><h2>'.$title.'</h2>';
    // $element['#suffix'] = '</div>';
    // //$form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessors\Form\ParPageItemShow');
    // //$element['out'] = $form;
    // // $par_page_item = $_GET['par_page_item'];
    // $element['table'] = array(
    //   '#theme' => 'table',
    //   '#prefix' => '<div class="block-bfss-assessors">',
    //   '#suffix' => '</div>',
    //   '#header' => $header,
    //   '#attributes'=>['id' => ['dtBasicExample']],
    //   '#rows' => $rows,
    //   '#empty' => t('There is no data available.'),
    // );
    // $element['pager'] = array(
    //   '#type' => 'pager',
    // );

    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'pending_assessments_page',
      '#name' => 'G.K',
      // '#prefix' => '<div class="block-bfss-assessors">',
      // '#suffix' => '</div>',
      '#pending_assessments_block' => Markup::create($tb),
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
    //return $element;
  }

  public function Get_Data_From_Tables($TableName,$atr,$current_user,$user_key){
      if($TableName){
        $conn = Database::getConnection();
      $query = $conn->select($TableName, $atr);
        $query->fields($atr);
        $query->condition($user_key, $current_user, '=');
        $results = $query->execute()->fetchAssoc();
      }
      return $results;
  }

}

?>
