<?php

namespace Drupal\custom_form\Controller;

use Drupal\Core\Controller\ControllerBase;
/**
 * Provides route responses for the Example module.
 */
class CustomController extends ControllerBase {
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function customfunction() {
    $element = array(
      '#markup' => 'Hello world!',
    );
    return $element;
  }
}
?>