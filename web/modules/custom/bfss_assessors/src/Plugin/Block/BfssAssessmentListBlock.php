<?php

namespace Drupal\bfss_assessors\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\bfss_assessment\AssessmentService;


/**
 * Provides a 'Bfss Assessment List Block' Block.
 *
 * @Block(
 *   id = "bfss_assessment_list_block",
 *   admin_label = @Translation("Bfss Assessment List Block"),
 *   category = @Translation("Bfss Assessment List Block"),
 * )
 */
class BfssAssessmentListBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
          $param = \Drupal::request()->query->all();
          //assessment get by current assessors
          $uid = \Drupal::currentUser()->id();
          $user = \Drupal\user\Entity\User::load($uid);
          $roles = $user->getRoles();

          if(in_array('athlete', $roles)){
            $athlete_uid = $uid;
          }elseif(in_array('coach', $roles)){
            if(isset($param['uid'])){
              $athlete_uid = $param['uid'];
            }
          }

          $result = array();
        	$booked_ids = \Drupal::entityQuery('bfsspayments')
          ->condition('user_id',$athlete_uid,'IN')
        	->execute();
        	foreach ($booked_ids  as $key => $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
            $assessment_nid = $entity->assessment->value;
            $node = Node::load($assessment_nid);
            if(!empty($node)){
                $address_1 = $entity->address_1->value;

                $timestamp = $entity->time->value;
                $booking_date = date("m/d/Y",$timestamp);
                $booking_time = date("h:i a",$timestamp);

                $query1 = \Drupal::entityQuery('node');
                $query1->condition('type', 'athlete_assessment_info');
                $query1->condition('field_booked_id',$booked_id, 'IN');
                $nids1 = $query1->execute();

                 //sport
                $query5 = \Drupal::database()->select('athlete_school', 'ats');
                $query5->fields('ats');
                $query5->condition('athlete_uid', $athlete_uid,'=');
                $results5 = $query5->execute()->fetchAssoc();
                $sport = $results5['athlete_school_sport'];

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
                  'id' => $entity->id->value,
                  'user_name' =>$entity->user_name->value,
                  'nid' => $nid,
                  'formtype' => $formtype,
                  'Assess_type' => $Assess_type,
                  'booking_date'  => $booking_date,
                  'booking_time'  => $booking_time,
                  'booked_id' => $booked_id,
                  'st' =>  $st,
                  'assess_nid' => $assess_nid,
                  'address_1' => $address_1,
                  'sport' => $sport,
                );
            }     
        	}
        


      /**********For JS Library start********/
        $tb = '<div class="eventlisting_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="dtBasicExample" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span> Date</a></th>
                <th class="th-hd"><a><span></span> Program</a></th>
                <th class="th-hd"><a><span></span> Sport</a></th>
                 <th class="th-hd"><a><span></span> Location1</a></th>
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
                // $url = 'starter-professional-assessments?nid='.$nid.'&formtype='.$type.'&Assess_type='.$Assesstype.'&booked_id='.$booked_id.'&st='.$st.'&assess_nid='.$item['assess_nid'];

                $formtype = Markup::create('<p><a style="color:#f4650f;">'.ucfirst($item['formtype']).'</a></p>');
                $rows[] = array(
                  #'id' => $item['booked_id'],
                  'date' => $item['booking_date'],
                  'program' => $formtype,
                  'sport' => $item['sport'],
                  'location' => $item['address_1'],
                );
                $tb .= '<tr>
                <td>'.$item['booking_date'].'</td>
                <td>'.$formtype.'</td>
                <td>'.$item['sport'].'</td>
                <td>'.$item['address_1'].'</td>
              </tr>';
              }

              $tb .= '</tbody>
          </table>
           </div>
          </div>
           </div>
          </div>';
        return [
          '#cache' => ['max-age' => 0,],
          '#markup' => $tb,
        ];
        /**********For JS Library end********/

  }



}
