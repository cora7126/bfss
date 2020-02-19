<?php
/**
 * @file
 * @author Rakesh James (Modified by JP)
 * Contains \Drupal\example\Controller\ExampleController.
 * Please place this file under your example(module_root_folder)/src/Controller/
 */
namespace Drupal\example\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class ExampleController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function content() {
    $build = [
      '#markup' => $this->t('Hello World! Tester'),
    ];
    return $build;
  }
}
?>