<?php

namespace Drupal\bfss_registration_form\Validator;

/**
 * Class ValidatorRequired.
 *
 * @package Drupal\bfss_registration_form\Validator
 */
class ValidatorRequired extends BaseValidator {

  /**
   * {@inheritdoc}
   */
  public function validates($value) {
    return is_array($value) ? !empty(array_filter($value)) : !empty($value);
  }

}
