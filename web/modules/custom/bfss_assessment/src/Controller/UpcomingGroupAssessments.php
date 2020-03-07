<?php
namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class UpcomingGroupAssessments extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function UpcomingGroup() {
    
    $block = \Drupal\block\Entity\Block::load('upcominggroupassessments');
    $block_content = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block);
    $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'upcoming_page',
      '#assessments_block' => $assessments_block,
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
    // $element = array(
    //   '#markup' => 'upcoming group assessments',
    // );
    // return $element;
  }

}