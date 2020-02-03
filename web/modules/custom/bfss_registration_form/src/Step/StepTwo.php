<?php

namespace Drupal\bfss_registration_form\Step;

use Drupal\bfss_registration_form\Button\StepTwoNextButton;
use Drupal\bfss_registration_form\Button\StepTwoPreviousButton;
use Drupal\bfss_registration_form\Validator\ValidatorRequired;

/**
 * Class StepTwo.
 *
 * @package Drupal\bfss_registration_form\Step
 */
class StepTwo extends BaseStep {

  /**
   * {@inheritdoc}
   */
  protected function setStep() {
    return StepsEnum::STEP_TWO;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return [
      new StepTwoPreviousButton(),
      new StepTwoNextButton(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildStepFormElements() {
    $form['interests'] = [
      '#type' => 'checkboxes',
      '#title' => t('Nice to meet you! So, what are you interests?'),
      '#options' => [1 => 'interest 1', 2 => 'interest 2', 3 => 'interest 3'],
      '#default_value' => isset($this->getValues()['interests']) ? $this->getValues()['interests'] : [],
      '#required' => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldNames() {
    return [
      'interests',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsValidators() {
    return [
      'interests' => [
        new ValidatorRequired("It would be a lot easier for me if you could fill out some of your interests."),
      ],
    ];
  }

}
