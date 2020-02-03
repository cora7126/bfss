<?php

namespace Drupal\bfss_registration_form\Step;

use Drupal\bfss_registration_form\Button\StepOneNextButton;
use Drupal\bfss_registration_form\Validator\ValidatorRequired;

/**
 * Class StepOne.
 *
 * @package Drupal\bfss_registration_form\Step
 */
class StepOne extends BaseStep {

  /**
   * {@inheritdoc}
   */
  protected function setStep() {
    return StepsEnum::STEP_ONE;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return [
      new StepOneNextButton(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildStepFormElements() {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t("What's your name?"),
      '#required' => FALSE,
      '#default_value' => isset($this->getValues()['name']) ? $this->getValues()['name'] : NULL,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldNames() {
    return [
      'name',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsValidators() {
    return [
      'name' => [
        new ValidatorRequired("Hey stranger, please tell me your name. I would like to get to know you."),
      ],
    ];
  }

}
