<?php

namespace Drupal\acme\Controller;

use Drupal\Core\Controller\ControllerBase;

class DefaultController extends ControllerBase {

  public function dashboard() {

    //get current user 

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $roles = $user->getRoles();
    // the {name} in the route gets captured as $name variable
    // in the function called
	 #ATTACH BLOCK
   

      if(in_array('assessors', $roles)){
         $block = \Drupal\block\Entity\Block::load('assessorsuserblock');
        $block_content = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block);
        $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);
        
          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'assessors_profile_dashboard_page',
          '#name' => 'Shubham Rana',
          '#assessments_block' => $assessments_block,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];

      }else{
        $block = \Drupal\block\Entity\Block::load('assessmentscustomview');
        $block_content = \Drupal::entityManager()
          ->getViewBuilder('block')
          ->view($block);
        $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);
          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'hello_page',
          '#name' => 'Shubham Rana',
          '#assessments_block' => $assessments_block,
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
   
}
