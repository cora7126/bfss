<?php

namespace Drupal\acme\Controller;

use Drupal\Core\Controller\ControllerBase;

class DefaultController extends ControllerBase {
  public function dashboard() {
    // the {name} in the route gets captured as $name variable
    // in the function called
	
    return [
      '#theme' => 'hello_page',
      '#name' => 'Shubham Rana',
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
  }
}