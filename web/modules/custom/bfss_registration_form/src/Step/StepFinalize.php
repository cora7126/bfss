<?php

namespace Drupal\bfss_registration_form\Step;

/**
 * Class StepFinalize.
 *
 * @package Drupal\bfss_registration_form\Step
 */
class StepFinalize extends BaseStep {

  /**
   * {@inheritdoc}
   */
  protected function setStep() {
    return StepsEnum::STEP_FINALIZE;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildStepFormElements() {

    $form['completed'] = [
      '#markup' => t('You have completed the wizard, yeah!'),
    ];

    return $form;
  }

}
