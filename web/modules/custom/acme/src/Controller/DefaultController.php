<?php

namespace Drupal\acme\Controller;

use Drupal\Core\Controller\ControllerBase;
use  \Drupal\user\Entity\User;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\Core\Render\Markup;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\bfss_assessment\AssessmentService;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
class DefaultController extends ControllerBase {

  public function dashboard() {

    //get current user
    $uid = \Drupal::currentUser()->id();
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $roles = $user->getRoles();
    $param = \Drupal::request()->query->all();


    // the {name} in the route gets captured as $name variable
    // in the function called
	   #ATTACH BLOCK
	

      if(in_array('assessors', $roles)){
        $block1 = \Drupal\block\Entity\Block::load('eventslisting');
        $block_content1 = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block1);
        $assessments_block1 = \Drupal::service('renderer')->renderRoot($block_content1);

        $block2 = \Drupal\block\Entity\Block::load('privateaccessmentsblock');
        $block_content2 = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block2);
        $assessments_block2 = \Drupal::service('renderer')->renderRoot($block_content2);


        $tb1 = $this->Events_lsiting_assessor_block();
        $tb2 = $this->Private_Accessments_Block();

          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'assessors_profile_dashboard_page',
          '#name' => '',
          '#event_listing_block' => $tb1,
          '#private_assessment_listing_block' => $tb2,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];

      }elseif( in_array('coach', $roles) || in_array('athlete', $roles) || in_array('parent_guardian_registering_athlete_', $roles) ){
        if(in_array('coach', $roles)){
            $rolename = 'coach';
        }else{
          $rolename = '';
        }

        $block = \Drupal\block\Entity\Block::load('assessmentscustomview');
        $block_content = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block);
        $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

        //$assessments_block = "here";
        //Month view block
        $block_m_v = \Drupal\block\Entity\Block::load('monthviewblock');
        $block_content_m_v = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block_m_v);
        $assessments_block_m_v = \Drupal::service('renderer')->renderRoot($block_content_m_v);

        //FILTERS FROM
        $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\MonthSelectForm');
        $SearchFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\SearchForm');
        $MonthViewFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_month_view\Form\MonthViewForm');
        $CTVfilter = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\CTVfilter');
        //MY Assessments
        $myAssessments = $this->My_assessments($uid);
        if($param['MonthView'] == 'MonthView'){
                $BlockData = $assessments_block_m_v;
        }else{
         $BlockData = $assessments_block;
        }

        $fund_raised_data = $this->FUNDS_RAISED();


          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'hello_page',
          '#name' => 'Shubham Rana',
          '#assessments_block' => $BlockData,
          '#month_block' => $form,
          '#search_filter_block' =>  $SearchFilterForm,
          '#month_view_filter_block' =>  $MonthViewFilterForm,
          '#my_assessments_section_block' => $myAssessments,
          '#rolename' => $rolename,
          '#CTVfilter_block' => $CTVfilter,
          '#fund_raised_block' => $fund_raised_data,
          '#attached' => [
            'library' => [
              'bfss_month_view/month_view_lib',//include our custom library for this response
            ]
          ]
        ];
      }elseif(in_array('bfss_manager', $roles)){
        $block = \Drupal\block\Entity\Block::load('assessmentscustomview');
        $block_content = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block);
        $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

        //Month view block
        $block_m_v = \Drupal\block\Entity\Block::load('monthviewblock');
        $block_content_m_v = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block_m_v);
        $assessments_block_m_v = \Drupal::service('renderer')->renderRoot($block_content_m_v);
        $Pending_Approval_Data = $this->Pending_Approval();
        //FILTERS FROM
        $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\MonthSelectForm');
        $SearchFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\SearchForm');
        $MonthViewFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_month_view\Form\MonthViewForm');
        $CTVfilter = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\CTVfilter');

        //MY Assessments
        $myAssessments = $this->My_assessments($uid);
        if($param['MonthView'] =='MonthView'){
                $BlockData = $assessments_block_m_v;
        }else{
         $BlockData = $assessments_block;
        }

          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'bfss_manager_profile_dashboard_page',
          '#bfss_manager_profile_dashboard_block' => $BlockData,
          '#month_block' => $form,
          '#search_filter_block' =>  $SearchFilterForm,
          '#month_view_filter_block' =>  $MonthViewFilterForm,
          '#CTVfilter_block' => $CTVfilter,
          '#rolename' => $roles[1],
          '#Pending_Approval_Data_Block' => $Pending_Approval_Data,
          '#attached' => [
            'library' => [
              'bfss_month_view/month_view_lib', //include our custom library for this response
            ]
          ]
        ];
      }
      elseif(in_array('administrator', $roles) || in_array('bfss_administrator', $roles)){

        $Pending_Approval_Data = $this->Pending_Approval();
        // print_r($Pending_Approval_Data);
        // die();
        $assessments_block = $this->LATEST_REVENUE();
        return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'admin_profile_dashboard_page',
          '#name' => '',
          '#admin_profile_block' => $assessments_block,
          '#Pending_Approval_Data_Block' => $Pending_Approval_Data,
          '#attached' => [
            'library' => [
              'bfss_admin/bfss_admin_lab_dash', //include our custom library for this response
            ]
          ]
        ];
      }

  }

public function userform()
{
	return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'athlete_profile',
      '#name' => ' ',
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
}


    function My_assessments($uid){
          $result = array();
          $booked_ids = \Drupal::entityQuery('bfsspayments')
          ->condition('user_id',$uid,'IN')
          ->sort('created', 'DESC')
          ->execute();
               // print_r($booked_ids);die;
                if(!empty($booked_ids) && is_array($booked_ids)){
                  foreach ($booked_ids  as $key => $booked_id) {
                      $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
                      $timestamp = $entity->time->value;
                      $booking_date = date("F j, Y",$timestamp);

                      $formtype = AssessmentService::getFormTypeFromPrice($entity->service->value);
                      $formtype .= ' Assessment';

                      $result[] = [
                        'formtype' => $formtype,
                        'date' => $booking_date,
                      ];
                  }
                }
          return !empty($result)?$result:null;
    }

    public function Pending_Approval(){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'bfss_organizations');
        $query->condition('status', 0);
        $nids = $query->execute();
        $Pending_Approval = [];
        if(!empty($nids) && is_array($nids)){
          foreach ($nids as $nid) {
            $node = Node::load($nid);
            $Pending_Approval[] = [
              'field_organization_name' => $node->field_organization_name->value,
              'field_state' => $node->field_state->value,
              'field_city' => $node->field_city->value,
            ];
          }
        }
        return $Pending_Approval;
    }


    public function Private_Accessments_Block() {
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

        $nid_book =  $entity->assessment->value;

        $address_1 = $entity->address_1->value;
        $timestamp = $entity->time->value;
        $booking_date = date("M d Y",$timestamp);
        $booking_time = date("h:i a",$timestamp);
        $user_id = $entity->user_id->value;
        $query1 = \Drupal::entityQuery('node');
        $query1->condition('type', 'athlete_assessment_info');
        $query1->condition('field_booked_id',$booked_id, 'IN');
        $nids1 = $query1->execute();


        $queryInf = \Drupal::database()->select('athlete_info', 'ainf');
        $queryInf->fields('ainf');
        $queryInf->condition('athlete_uid', $user_id, '=');
        $queryInf->orderBy('id','DESC');
        $athletInfoAry = $queryInf->execute()->fetchAll();
        // ksm($athletInfoAry[0]->field_height);

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

        if(!empty($nid_book)){
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
        if ($field_status != 'complete') {
          $result[] = array(
            'booked_id' => $booked_id,
            'id' => $entity->id->value,
            'user_name' =>$entity->user_name->value,
            'first_name' =>$entity->first_name->value,
            'last_name' =>$entity->last_name->value,
            'nid_book' => $nid_book,
            'formtype' => $formtype,
            'Assess_type' => $Assess_type,
            'field_status' => $field_status,
            'booking_date'  => $booking_date,
            'booking_time'  => $booking_time,
            'st' =>  $st,
            'assess_nid' => $assess_nid,
            'weight' => $athletInfoAry[0]->field_weight,
            'height' => $athletInfoAry[0]->field_height,
            'address_1' => $address_1,
            'sport' => $sport,
            'postion' => $postion,
            'user_id' => $user_id,
          );
        }
      }
    }
    $tb1 = '
    <div class="wrapped_div_main">
    <div class="block-bfss-assessors">
    <div class="table-responsive-wrap">
    <table id="bfss_private_assessor_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
      <thead>
        <tr>
          <th class="th-hd"><a><span></span>Date</a>
          </th>
            <th class="th-hd"><a><span></span>Time</a>
          </th>
          <th class="th-hd"><a><span></span>Name</a>
          </th>
          <th class="th-hd"><a><span></span>Assessment Type</a>
          </th>
          <th class="th-hd"><a><span></span>Status</a>
          </th>
          <th class="th-hd"><a><span></span>Location</a>
          </th>
        </tr>
      </thead>
      <tbody>';

      foreach ($result as $item) {
        $nid_book = $item['nid_book'];
        $type = $item['formtype'];
        $Assesstype = $item['Assess_type'];
        $booked_id = $item['booked_id'];
        $st = $item['st'];
        $user_name = $item['user_name'];

        $url = 'pending-assessments-form?nid='.$nid_book.'&formtype='.$type.'&Assess_type='.$Assesstype.'&booked_id='.$booked_id.'&st='.$st.'&first_name='.$item['first_name'].'&last_name='.$item['last_name'].'&sport='.$item['sport'].'&postion='.$item['postion'].'&field_status='.$item['field_status'].'&assess_nid='.$item['assess_nid'].'&weight='.$item['weight'].'&height='.$item['height'];

        $formtype = Markup::create('<p><a class="use-ajax" data-dialog-type="modal" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm private-assesspopup&quot;}"  href="'.$url.'">'.$type.'</a></p>');

        $tb1 .= '
         <tr>
          <td>'.$item['booking_date'].'</td>
          <td>'.$item['booking_time'].'</td>
          <td>'.$user_name.'</td>
          <td>'.$formtype.'</td>
          <td>'.$item['field_status'].'</td>
          <td>'.$item['address_1'].'</td>
         </tr>';
      }
      $tb1 .= '
          </tbody>
            </table>
             </div>
            </div>
             </div>
            </div>';
      return  Markup::create($tb1);
    }

    public function Events_lsiting_assessor_block(){
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
        #$query->condition('field_assessors', $current_assessors_id , '=');
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
            if($attendees>0){
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
        }
      $tb1 = '
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_event_assessor_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>Date</a>
                </th>
                  <th class="th-hd"><a><span></span>Time</a>
                </th>
                <th class="th-hd"><a><span></span>Event Name</a>
                </th>
                <th class="th-hd"><a><span></span>Attendees</a>
                </th>
              </tr>
            </thead>
            <tbody>';
        // Wrapper for rows
      foreach ($result as $item) {
          $url = 'assessment-event?nid='.$item['nid'].'&timeslot='.$item['timeslot'].'&title='.$item['title'];
          $title = Markup::create('<a href="'.$url.'">'.$item['title'].'</a>');

         $tb1 .= '<tr>
          <td>'.$item['date'].'</td>
          <td>'.$item['time'].'</td>
          <td>'.$title.'</td>
          <td>'.$item['attendees'].'</td>
         </tr>';
      }
      $tb1 .= '</tbody>
            </table>
             </div>
            </div>
             </div>
            </div>';
      return  Markup::create($tb1);
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

                  if ($timing > time()) {
                    $data[$timing] = $timing;
                  }
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

    public function LATEST_REVENUE(){
      $booked_ids = \Drupal::entityQuery('bfsspayments')
        #->condition('payment_status','paid', '=')
        ->range(0, 5)
        ->sort('created','DESC')
        ->execute();
        $data = [];
        foreach ($booked_ids as $booked_id) {
            $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
            $paid_date = $entity->created->value;
            $amount = $entity->service->value;
            $nid = $entity->assessment->value;
            $assessmentDate = date('F d, Y',$entity->time->value);

            $type = $node->field_type_of_assessment->value;

            $program = AssessmentService::getFormTypeFromPrice($amount);

            $full_name = $entity->first_name->value.' '.$entity->last_name->value;
            $city = $entity->city->value;
            $state = $entity->state->value;
            if($nid){
                $node = Node::load($nid);
                if(!empty($node)){
                $m_uid = $node->getOwnerId();

                if(isset($m_uid)){
                  $m_user = User::load($m_uid);
                  $roles = $m_user->getRoles();
                  if(in_array('bfss_manager', $roles)){
                    $m_name = $m_user->field_first_name->value.' '.$m_user->field_last_name->value;
                  }
                }
                }

            }


            if (strpos($amount, 'freecredit') !== false) {
              #code for this condition
            }else{
              $data[] = [
                'purchased_date' => $paid_date,
                'program' => $program,
                'amount' => $amount,
                'assessment_date' => $assessmentDate,
                'customer_name' => $full_name,
                'city' => $city,
                'state' => $state,
                'manager_name' => $m_name,
                'user_id' => $entity->user_id->value,
              ];
            }
        }
        $reg_payments = $this->GET_bfss_register_user_payments();
        $data = array_merge($data,$reg_payments);
        foreach ($data as $key => $part) {
          $sort[$key] = $part['purchased_date'];
        }
        array_multisort($sort, SORT_DESC, $data);
        $tb1 = '<div class="search_athlete_main user_pro_block latest_revenue">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_letest_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>Date</a>
                </th>
                  <th class="th-hd long-th th-last"><a><span></span>Customer Name</a>
                </th>
                <th class="th-hd long-th th-fisrt"><a><span></span>City</a>
                </th>
                <th class="th-hd long-th th-fisrt"><a><span></span>State</a>
                </th>
                <th class="th-hd long-th th-fisrt"><a><span></span>Program</a>
                </th>
                 <th class="th-hd long-th th-fisrt"><a><span></span>Amount</a>
                </th>
                  <th class="th-hd long-th th-fisrt"><a><span></span>BFSS Manager</a>
                </th>
              </tr>
            </thead>
            <tbody>';

        foreach ($data as $value) {
          $uid = $value['user_id'];
          $tb1 .= '<tr>
          <td>'.date('F d, Y',$value['purchased_date']).'</td>
          <td><a href="/users-editable-account?uid='.$uid.'" target="_blank">'.$value['customer_name'].'</a></td>
          <td>'.$value['city'].'</td>
          <td>'.$value['state'].'</td>
          <td>'.$value['program'].'</td>
          <td>'.$value['amount'].'</td>
          <td>'.$value['manager_name'].'</td>
           </tr>';
        }

         $tb1 .= '
            </tbody>
            </table>
             </div>
            </div>
             </div>
            </div>';
            return  Markup::create($tb1);
    }

    function GET_bfss_register_user_payments(){
    $reg_payments = \Drupal::database()->select('bfss_register_user_payments', 'athw')
          ->fields('athw')
          #->condition('payment_status','paid', '=')
          ->execute()->fetchAll();
        $register_payment_data = [];
        if(!empty($reg_payments) && is_array($reg_payments)){
          foreach ($reg_payments as $key => $value) {
            if($value->program_term == 1){
              $description = 'Starter';
            }elseif($value->program_term == 2){
              $description = 'Professional';
            }elseif($value->program_term == 3){
              $description = 'Elite';
            }else{
              $description = '';
            }
            $user = User::load($value->uid);

            if(isset($value->booking_id)){
              $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($value->booking_id);
              $nid = $entity->assessment->value;
              if($nid){
                  $node = Node::load($nid);
                  if(!empty($node)){
                     $m_uid = $node->getOwnerId();
                    if(isset($m_uid)){
                      $m_user = User::load($m_uid);
                      $roles = $m_user->getRoles();
                      if(in_array('bfss_manager', $roles)){
                        $m_name = $m_user->field_first_name->value.' '.$m_user->field_last_name->value;
                      }
                    }
                  }

              }


            }
        if($user){
          $register_payment_data[] = [
            'purchased_date' => $value->created,
            'program' =>$description,
            'amount' => $value->amount,
            'assessment_date' => date('F d, Y',$value->assessment_date),
            'customer_name' => $value->bi_first_name.' '.$value->bi_last_name,
            'city' => $value->bi_city,
            'state' => $value->bi_state,
            'user_id' => $value->uid,
            'manager_name' => $m_name,
            'user_id' => $value->uid,
          ];
        }
          }
      }
       return $register_payment_data;
    }


	public function FUNDS_RAISED(){
		$uid = \Drupal::currentUser()->id();
		$user = User::load($uid);
		$username = $user->name->value;
		 $athlete_uids = \Drupal::entityQuery('user')
      	  ->condition('field_coach',$username, '=') 
          ->execute();
       
		foreach($athlete_uids as $athlete_uid){
			$booked_ids = \Drupal::entityQuery('bfsspayments')
		                ->condition('user_id',$athlete_uid, '=')
		                ->sort('created','DESC')
		                ->range(0, 10)
		            	->execute();
		     foreach ($booked_ids as $booked_id) {
		        $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
		        $data[] = [
				 'first_name' => $entity->first_name->value,
				 'last_name' => $entity->last_name->value,
				 'payment_status' => $entity->payment_status->value,
				 'amount' => $entity->service->value,			 
			 	];
		     }       	
			            	
		}
		return $data;
	}


}
