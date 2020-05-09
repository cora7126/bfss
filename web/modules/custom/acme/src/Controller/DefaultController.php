<?php

namespace Drupal\acme\Controller;

use Drupal\Core\Controller\ControllerBase;
use  \Drupal\user\Entity\User;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

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
        
          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'assessors_profile_dashboard_page',
          '#name' => '',
          '#event_listing_block' => $assessments_block1,
          '#private_assessment_listing_block' => $assessments_block2,
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
        
        //MY Assessments
        $myAssessments = $this->My_assessments($uid);
        if($param['MonthView']){
                $BlockData = $assessments_block_m_v;
        }else{
         $BlockData = $assessments_block;
        }
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
        
        //MY Assessments
        $myAssessments = $this->My_assessments($uid);
        if($param['MonthView']){
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
        $assessments_block = "listing here";
        return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'admin_profile_dashboard_page',
          '#name' => '',
          '#admin_profile_block' => $assessments_block,
          '#Pending_Approval_Data_Block' => $Pending_Approval_Data,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
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
          $query = \Drupal::entityQuery('node');
          $query->condition('type', 'assessment');
          $nids = $query->execute();

          $result = array();
          if(!empty($nids) && is_array($nids)){
              foreach ($nids as $nid) {
                $booked_ids = \Drupal::entityQuery('bfsspayments')
                ->condition('assessment', $nid,'IN')
                ->condition('user_id',$uid,'IN')
                ->execute();
                if(!empty($booked_ids) && is_array($booked_ids)){
                  foreach ($booked_ids  as $key => $booked_id) {
                            $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
                            $timestamp = $entity->time->value;
                            $booking_date = date("F j, Y",$timestamp);
                            if($entity->service->value == '199.99'){
                                $formtype = 'Elete';
                            }elseif($entity->service->value == '29.99'){
                                $formtype = 'Starter';
                            }
                            
                            $result[] = [
                              'formtype' => $formtype,
                              'date' => $booking_date,
                            ];
                  }
                }

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
   
}
