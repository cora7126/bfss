<?php
namespace Drupal\bfss_assessment\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bfss_assessment\AssessmentService;

/**
 * Provides a 'Assessors User Block' Block.
 *
 * @Block(
 *   id = "assessors_user_block",
 *   admin_label = @Translation("Assessors User Block"),
 *   category = @Translation("Assessors User Block"),
 * )
 */
class AssessorsUserBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    global $base_url;
    $data = "Content";
  

  #send results
    if (!empty($data)) {
      return [
        'results' => [
              '#theme' => 'assessors_user_block',
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