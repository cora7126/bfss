<?php

namespace Drupal\bfss_assessment\Controller;
use Drupal\Core\Controller\ControllerBase;


/**
 * Defines PrivateAssessment class.
 */
class PrivateAssessment extends ControllerBase {
  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function private_assessment() {

    
      $html = "<h2>Private Assessment</h2>";
      return [
        '#type' => 'markup',
        '#markup' => $html,
      ];
  }

}