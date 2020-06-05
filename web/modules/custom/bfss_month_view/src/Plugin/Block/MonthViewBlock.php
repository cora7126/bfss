<?php
namespace Drupal\bfss_month_view\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;
/**
 * Provides a 'Month View Block' Block.
 *
 * @Block(
 *   id = "month_view_block",
 *   admin_label = @Translation("Month View Block"),
 *   category = @Translation("Month View Block"),
 * )
 */
class MonthViewBlock extends BlockBase {
  public function build() {
    $data = []; 
    $current_path = \Drupal::service('path.current')->getPath();	
  	$data['current_path'] = $current_path;
   

  	$element = 1;
    return [
        'results' => [
              '#cache' => ['max-age' => 0,],
              '#theme' => 'month_view_temp',
              '#data' => $data,
              '#empty' => 'no',
            ],
        'pager' =>[
          '#type' => 'pager',
          '#element' => $element,
          ],
        '#attached' =>[
          'library' => [
            'bfss_month_view/month_view_lib',
          ],
        ],
    ];
}

}