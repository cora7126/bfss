<?php
namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
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

    $MonthFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\MonthSelectForm');
    $SearchFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\SearchForm');
   
    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'upcoming_page',
      '#assessments_block' => $assessments_block,
      '#month_block' =>  $MonthFilterForm,
      '#search_filter_block' =>  $SearchFilterForm,
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