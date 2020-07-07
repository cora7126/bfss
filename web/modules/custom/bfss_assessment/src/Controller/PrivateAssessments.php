<?php
namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Render\Markup;
/**
 * Provides route responses for the Example module.
 */
class PrivateAssessments extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function private_assessments() {
    $param = \Drupal::request()->query->all();
    $block = \Drupal\block\Entity\Block::load('privateassessmentsblockpxl');
    $block_content = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block);
    $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

    //Month view block
    $block_m_v = \Drupal\block\Entity\Block::load('monthviewblock');
    $block_content_m_v = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block_m_v);
    $assessments_block_m_v = \Drupal::service('renderer')->renderRoot($block_content_m_v);

    //FILTERS FROM
    $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\MonthSelectForm');
    $SearchFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\SearchForm');
    $MonthViewFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_month_view\Form\MonthViewForm');
    $CTVfilter = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\CTVfilter');
    
    if($param['MonthView'] == 'MonthView'){
      $BlockData = Markup::create('<div class="block-month-view-block"><div id="calendar-private-assessments" ></div></div>');
    }else{
       $BlockData = $assessments_block;
    }
    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'private_assessments_page',
      '#private_assessments_block' => $BlockData,
      '#search_filter_block' =>  $SearchFilterForm,
      '#month_block' => $form,
      '#month_view_filter_block' =>  $MonthViewFilterForm,
      '#CTVfilter_block' =>  $CTVfilter,
      '#attached' => [
        'library' => [
           'bfss_month_view/month_view_lib', //include our custom library for this response
        ]
      ]
    ];
    // $element = array(
    //   '#markup' => 'upcoming group assessments',
    // );
    // return $element;
  }

}
