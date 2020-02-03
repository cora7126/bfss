<?php
/**
 * Created by PhpStorm.
 * User: Valentine
 * Date: 02.02.2020
 * Time: 14:11
 */

namespace Drupal\bfss_user\Controller;


use Drupal\Core\Controller\ControllerBase;

class UserLogin extends ControllerBase {

  function content() {
    $build = [];

    //  get user form
    $build['user_login_form'] = \Drupal::formBuilder()->getForm('\Drupal\User\Form\UserLoginForm');

    //  set area for load forgot password
    $build['user_forgot_pass'] = [
      '#markup' => '<div class="user-forgot-pass-area"></div>'
    ];

    return $build;
  }

}