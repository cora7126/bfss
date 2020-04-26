<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;

class AddGroupAssessments extends ControllerBase {

  public function add_group_assessments() {

    $form = \Drupal::formBuilder()->getForm('Drupal\bfss_manager\Form\AddGroupAssessmentsForm');
    
    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'add_group_assessments_page',
      '#add_group_assessments_block' => $form,
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];   
  
  } 
}
