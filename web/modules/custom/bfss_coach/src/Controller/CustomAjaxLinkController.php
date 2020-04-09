<?php
 
namespace Drupal\bfss_coach\Controller;
 
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Controller\ControllerBase;
 
class CustomAjaxLinkController extends ControllerBase {
 
  public function customAjaxLinkAlert($name){
 
    # New responses
    $response = new AjaxResponse();
 
    # Commands Ajax
    $response->addCommand($name);
 
    # Return response
    return $response;
 
  }
 
}