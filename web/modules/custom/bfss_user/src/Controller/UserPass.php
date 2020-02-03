<?php
/**
 * Created by PhpStorm.
 * User: Valentine
 * Date: 02.02.2020
 * Time: 14:11
 */

namespace Drupal\bfss_user\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Url;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Class UserPass
 *
 * return user_password form by ajax if not redirect to user_password form
 *
 * @package Drupal\bfss_user\Controller
 */
class UserPass extends ControllerBase {

  function content() {
    //  check if request is ajax
    if (\Drupal::request()->isXmlHttpRequest()) {
      $response = new \Drupal\Core\Ajax\AjaxResponse();

      $build = \Drupal::formBuilder()->getForm('\Drupal\user\Form\UserPasswordForm');

      $response->addCommand(new \Drupal\Core\Ajax\ReplaceCommand('.user-pass-load', \Drupal::service('renderer')->render($build)));

      return $response;
    } else {
      return $this->redirect('user.pass');
    }
  }

}