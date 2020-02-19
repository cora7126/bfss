<?php

namespace Drupal\acme\Form;

use Drupal\Core\Form\FormBase;

class athlete_form extends FormBase {

 
public function userform()
{
	$build = [
      '#markup' => $this->t('Hello World!'),
    ];
    return $build;
}
   
}
