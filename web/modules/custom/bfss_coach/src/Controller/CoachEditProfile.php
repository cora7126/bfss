<?php

namespace Drupal\bfss_coach\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines CoachEditProfile class.
 */
class CoachEditProfile extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
      $form = \Drupal::formBuilder()->getForm('Drupal\bfss_coach\Form\CoachEditProfileForm');
      $tb1 = "here";
    return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'edit_form_coach_page',
          '#edit_form_coach_block' => $form,
          '#attached' => [
          'library' => [
            'acme/acme-styles', //include our custom library for this response
          ]
        ]
      ];
  }

}