<?php

namespace Drupal\bfss_registration_form\Validator;

/**
 * Interface ValidatorInterface.
 *
 * @package Drupal\bfss_registration_form\Validator
 */
interface ValidatorInterface {

  /**
   * Returns bool indicating if validation is ok.
   */
  public function validates($value);

  /**
   * Returns error message.
   */
  public function getErrorMessage();

}
