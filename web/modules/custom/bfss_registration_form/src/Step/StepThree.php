<?php

namespace Drupal\bfss_registration_form\Step;

use Drupal\bfss_registration_form\Button\StepThreeFinishButton;
use Drupal\bfss_registration_form\Button\StepThreePreviousButton;
use Drupal\bfss_registration_form\Validator\ValidatorRegex;
use Drupal\bfss_registration_form\Validator\ValidatorRequired;

/**
 * Class StepThree.
 *
 * @package Drupal\bfss_registration_form\Step
 */
class StepThree extends BaseStep {

  /**
   * {@inheritdoc}
   */
  protected function setStep() {
    return StepsEnum::STEP_THREE;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return [
      new StepThreePreviousButton(),
      new StepThreeFinishButton(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildStepFormElements() {

    $form['linkedin'] = [
      '#type' => 'textfield',
      '#title' => t('What is your LinkedIn URL?'),
      '#default_value' => isset($this->getValues()['linkedin']) ? $this->getValues()['linkedin'] : NULL,
      '#required' => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldNames() {
    return [
      'linkedin',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsValidators() {
    return [
      'linkedin' => [
        new ValidatorRequired("Tell me where I can find your LinkedIn please."),
        new ValidatorRegex(t("I don't think this is a valid LinkedIn URL..."), '/(ftp|http|https):\/\/(.*)linkedin(.*)/'),
      ],
    ];
  }

}
