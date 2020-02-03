<?php
/**
 * Created by PhpStorm.
 * User: Valentine
 * Date: 01.02.2020
 * Time: 21:23
 */

namespace Drupal\bfss_registration_form\Controller;


use Drupal\Core\Controller\ControllerBase;

class CompleteRegistration extends ControllerBase {

  function content() {
    //  get "complete_registration_form"
    $form = \Drupal::formBuilder()->getForm('\Drupal\bfss_registration_form\Form\CompleteRegistrationForm');

    return $form;
  }

}