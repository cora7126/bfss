<?php

namespace Drupal\bfss_assessment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bfss_assessment\AssessmentService;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "payment_receipts",
 *   admin_label = @Translation("PaymentReceipts"),
 * )
 */
class PaymentReceipts extends BlockBase implements ContainerFactoryPluginInterface {

  /**
  * Drupal\bfss_assessment\AssessmentService definition.
  *
  * @var \Drupal\bfss_assessment\AssessmentService
  */
  protected $assessmentService;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AssessmentService $assessment_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->assessmentService = $assessment_service;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('bfss_assessment.default')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $element = 1;
    $data = "content";
      if (!empty($data)) {
        return [
          'results' => [
                '#theme' => 'payment_receipts',
                '#data' => $data,
                '#empty' => 'no',
              ],
          'pager' =>[
            '#type' => 'pager',
            '#element' => $element,
            ],
          '#attached' =>[
            'library' => [
              'bfss_assessment/custom',
            ],
          ],
        ];
      }
      return array(
        '#type' => 'markup',
        '#markup' => $this->t('There is no assignment avaialble for now'),
        '#attached' =>[
          'library' => [
            'bfss_assessment/custom',
          ],
        ],
      );
  }
}