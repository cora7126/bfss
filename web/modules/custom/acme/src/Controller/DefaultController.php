<?php

namespace Drupal\acme\Controller;

use Drupal\Core\Controller\ControllerBase;

class DefaultController extends ControllerBase {

  public function dashboard() {
    // the {name} in the route gets captured as $name variable
    // in the function called
	
    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'hello_page',
      '#name' => 'Shubham Rana',
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
  }
  
public function userform()
{
	$build = [
      '#markup' => $this->t('Hello World!'),
    ];
    return $build;
}
   
}