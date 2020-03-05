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
          '#attached' =>[
            'library' => [
              'bfss_assessment/custom',
            ],
          ],
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

  /**
   * @return markup
   */
  public function scheduledAppointments() {
    $data = [];
    $requriedFields = [
      'id',
      'time',
      'assessment_title',
      'assessment',
      'until',
      'created',
    ];
    $entity_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('user_id', \Drupal::currentUser()->id())
        ->condition('time', time(), ">")
        ->sort('time','ASC')
        ->execute();
    #if there is data
    if ($entity_ids) {
      foreach ($entity_ids as $entity_id) {
        #load entity
        $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($entity_id);
        if ($entity instanceof \Drupal\Core\Entity\ContentEntityInterface) {
            $val = [];
          foreach ($requriedFields as $field) {
            if ($entity->hasField($field)) {
              $val[$field] = $entity->get($field)->value;
            }
          }
          #if assessment avail
          if (isset($val['assessment'])) {
            $nodeData = $this->assessmentService->getNodeData($val['assessment']);
              #udpate title
              if (isset($nodeData['title']) && !empty($nodeData['title'])) {
                $val['assessment_title'] = $nodeData['title'];
              }
              #img
              $val['image'] = isset($nodeData['field_image']) ? $nodeData['field_image'] : null;
              #loc
              $val['location'] = isset($nodeData['field_location']) ? $nodeData['field_location'] : null;
              #body
              $val['body'] = isset($nodeData['body']) ? $nodeData['body'] : null;
          }
          if ($val) {
            $data[] = $val;
          }
        }
      }
    }
    // foreach ($data as $key => $value) {
      // dpm($value);
    // }
    // $data = [];
    return [
      '#theme' => 'scheduled__appointments',
      '#data' => $data,
      '#attached' =>[
        'library' => [
          'bfss_assessment/custom',
          'bfss_assessment/upcoming_appointment',
        ],
      ],
    ];
  }

}