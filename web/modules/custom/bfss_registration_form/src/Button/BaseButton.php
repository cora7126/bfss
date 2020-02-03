<?php

namespace Drupal\bfss_registration_form\Button;

/**
 * Class BaseButton.
 *
 * @package Drupal\bfss_registration_form\Button
 */
abstract class BaseButton implements ButtonInterface {

  /**
   * {@inheritdoc}
   */
  public function ajaxify() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmitHandler() {
    return FALSE;
  }

}
