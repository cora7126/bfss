<?php
namespace Drupal\edit_form\Controller;
use Drupal\Core\Controller\ControllerBase;
class AthleticProfile extends ControllerBase {
    public function content() {
      // return [
      //   '#type' => 'markup',
      //   '#markup' => $this->t('Hello, World!'),
      // ];


      $form = \Drupal::formBuilder()->getForm('Drupal\edit_form\Form\ContributeForm');
      return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'athletic_Profile_form_page',
          '#name' => 'G.K',
          '#athletic_Profile_form_block' => $form,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
      
    }
}









