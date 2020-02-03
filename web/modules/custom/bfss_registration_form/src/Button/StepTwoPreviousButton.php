<?php

namespace Drupal\bfss_registration_form\Button;

use Drupal\bfss_registration_form\Step\StepsEnum;

/**
 * Class StepTwoPreviousButton.
 *
 * @package Drupal\bfss_registration_form\Button
 */
class StepTwoPreviousButton extends BaseButton {

  /**
   * {@inheritdoc}
   */
  public function getKey() {
    return 'previous';
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'submit',
      '#value' => t('Previous'),
      '#goto_step' => StepsEnum::STEP_ONE,
      '#skip_validation' => TRUE,
    ];
  }

}
