<?php

namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\bfss_assessment\AssessmentService;

/**
 * Class AssessmentController.
 */
class AssessmentController extends ControllerBase {

  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
  * Drupal\bfss_assessment\AssessmentService definition.
  *
  * @var \Drupal\bfss_assessment\AssessmentService
  */
  protected $assessmentService;

  /**
   * Constructs a new AssessmentController object.
   */
  public function __construct(RequestStack $request_stack, ConfigFactoryInterface $config_factory, AssessmentService $assessment_service) {
    $this->requestStack = $request_stack;
    $this->configFactory = $config_factory;
    $this->assessmentService = $assessment_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack'),
      $container->get('config.factory'),
      $container->get('bfss_assessment.default')
    );
  }

  /**
   * @return markup
   */
  public function allNodes() {
  
  }

  /**
   * @return markup
   */
  public function modalNodeView() {
    global $base_url;
    $nid = \Drupal::request()->get('node_id');
    $data = [];
    if ($this->assessmentService->check_assessment_node($nid)) {
      $data = $this->assessmentService->getNodeData($nid);
    }
    if (isset($data['schedules']) && !empty($data['schedules'])) {
      $data['url'] = $base_url.'/assessment/type/'.$nid;
      return [
          '#theme' => 'modal_assessment',
          '#data' => $data,
        ];
    }
    return [
      '#type' => 'markup',
      '#markup' => $this->t('The event you are looking for book is not available! Please select another.'),
      '#attached' =>[
        'library' => [
          'bfss_assessment/custom',
        ],
      ],
    ];
  }

  /**
   * @return markup
   */
  public function assessmentDone() {
    return [
      '#theme' => 'assessment_success',
      '#message' => $this->t('Thank you for your booking your private assessment with BFSS. An email with the details of your booking has been sent to you.'),
      '#attached' =>[
        'library' => [
          'bfss_assessment/assessment_mulitform_lib',
        ],
      ],
    ];
  }

}
