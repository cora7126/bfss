<?php

namespace Drupal\acme\Controller;

use Drupal\Core\Controller\ControllerBase;
use  \Drupal\user\Entity\User;

class DefaultController extends ControllerBase {

  public function dashboard() {
    //get current user 
    $uid = \Drupal::currentUser()->id();
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $roles = $user->getRoles();

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

        $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\MonthSelectForm');
        
        //MY Assessments
        $myAssessments = $this->My_assessments($uid);

          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'hello_page',
          '#name' => 'Shubham Rana',
          '#assessments_block' => $assessments_block,
          '#month_block' => $form,
          '#my_assessments_section_block' => $myAssessments,
          '#rolename' => $rolename,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
      }elseif(in_array('bfss_manager', $roles)){
        $block = \Drupal\block\Entity\Block::load('assessmentscustomview');
        $block_content = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block);
        $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

        // $block1 = \Drupal\block\Entity\Block::load('monthform');
        // $block_content1 = \Drupal::entityManager()
        //   ->getViewBuilder('block')
        //   ->view($block1);
        // $assessments_block1 = \Drupal::service('renderer')->renderRoot($block_content1);
        $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\MonthSelectForm');
      
          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'bfss_manager_profile_dashboard_page',
          '#bfss_manager_profile_dashboard_block' => $assessments_block,
          '#month_block' => $form,
          '#rolename' => $roles[1],
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
      }
      elseif(in_array('administrator', $roles) || in_array('bfss_administrator', $roles)){
       
        $assessments_block = "listing here";
        return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'admin_profile_dashboard_page',
          '#name' => '',
          '#admin_profile_block' => $assessments_block,
          #'#month_block' => $assessments_block1,
          #'#rolename' => $rolename,
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
   
}
