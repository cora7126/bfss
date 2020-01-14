<?php
/**
 * @file
 * @author Rakesh James
 * Contains \Drupal\example\Controller\ExampleController.
 * Please place this file under your example(module_root_folder)/src/Controller/
 */
namespace Drupal\registration_form\Controller;

use Drupal\Core\Controller\ControllerBase;
/**
 * Provides route responses for the Example module.
 */
class RegistrationFormController {
    /**
     * Returns a simple page.
     *
     * @return array
     *   A simple renderable array.
     */
    public function myPage() {
        $element = array(
            '#markup' => 'Hello world!',
            '#theme' => 'registration_form'
        );
        return $element;
    }
}

class RegistrationFormTwigController extends ControllerBase {
    public function content() {
        return [
            '#theme' => 'my_template',
            '#test_var' => $this->t('Test Value'),
        ];
    }
}