<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;

class DefaultController extends ControllerBase {

  public function dashboard() {

    //get current user 

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $roles = $user->getRoles();
    // the {name} in the route gets captured as $name variable
    // in the function called
	 #ATTACH BLOCK
   

        $block = \Drupal\block\Entity\Block::load('upcominggroupassessments');
    $block_content = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block);
    $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

    $block1 = \Drupal\block\Entity\Block::load('monthform');
    $block_content1 = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block1);
    $assessments_block1 = \Drupal::service('renderer')->renderRoot($block_content1);
	
          return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'bfssmanager_profile_dashboard_page',
          '#assessments_block' => $assessments_block,
          '#month_block' => $assessments_block1,
          '#rolename' => $roles[1],
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];

    
  
  }
  
   
}
