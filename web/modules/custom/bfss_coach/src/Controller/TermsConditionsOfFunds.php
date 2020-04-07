<?php

namespace Drupal\bfss_coach\Controller;
use Drupal\Core\Controller\ControllerBase;

class TermsConditionsOfFunds extends ControllerBase {


	public function content() {

	$block = \Drupal\block\Entity\Block::load('termsconditionsoffundsblock');
    $block_content = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block);
    $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'term_conditions_of_funds_page',
      '#assessments_block' => $assessments_block,
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
  	}
}