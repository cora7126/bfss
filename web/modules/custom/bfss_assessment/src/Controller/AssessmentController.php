<?php

namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\bfss_assessment\AssessmentService;
use \Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Render\Markup;
use Drupal\Component\Utility\UrlHelper;
Use Drupal\paragraphs\Entity\Paragraph;
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
    $uid = \Drupal::currentUser()->id();
    $user = User::load($uid);
    $roles = $user->getRoles();
    $param = \Drupal::request()->query->all();
    $nid = \Drupal::request()->get('node_id');
    $data = [];
    if ($this->assessmentService->check_assessment_node($nid)) {
      $data = $this->assessmentService->getNodeData($nid);
    }
    if (isset($data['schedules']) && !empty($data['schedules'])) {
      $data['url'] = $base_url.'/assessment/type/'.$nid;
      $data['roles'] = $roles;
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
$popupmess ='<div class="sucss-popup slot-not-available">
  <div class=" requestCallback sitepopup-default-bfss" style="">
    <div class="sitepopup-wrap">
    <div class="spb-popup-main-wrapper spb_top_center alertmessage">
      <div  class="sitepopup-default-bfss-content">
        <div class="popup_header change_password_header">
          <h3>Alert! 
            <i class="fa fa-times right-icon changepassdiv-modal-close spb_close" aria-hidden="true" data-dismiss="modal"></i>
          </h3>
        </div>
        <div class="success-msg">The event you are looking for book is not available! Please select another.</div>
      </div>
    </div>
  </div>
  </div>
</div>';
    return [
      '#type' => 'markup',
      '#markup' => Markup::create($popupmess),
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
      '#message' => $this->t('Thank you for your booking your assessment with BFSS. An email with the details of your booking has been sent to you.'),
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
     $param = \Drupal::request()->query->all();
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
        // print_r($entity_ids);
        // die;
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
          // print_r($val['assessment']);
          // die;
          #if assessment avail
          if (isset($val['assessment'])) { // node id 
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
              $val['booking_status'] = "purchased";
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
	
	
	  $block = \Drupal\block\Entity\Block::load('myscheduledassessmentblock');
    $block_content = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block);
    $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);

    $block1 = \Drupal\block\Entity\Block::load('monthform');
    $block_content1 = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block1);
    $assessments_block1 = \Drupal::service('renderer')->renderRoot($block_content1);
	
    //Month view block
    $block_m_v = \Drupal\block\Entity\Block::load('monthviewblock');
    $block_content_m_v = \Drupal::entityManager()
      ->getViewBuilder('block')
      ->view($block_m_v);
    $assessments_block_m_v = \Drupal::service('renderer')->renderRoot($block_content_m_v);

     if($param['MonthView'] =='MonthView'){
                $BlockData = $assessments_block_m_v;
        }else{
         $BlockData = $assessments_block;
        }
  //FILTERS FROM
  $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\MonthSelectForm');
  $SearchFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\SearchForm');
  $MonthViewFilterForm = \Drupal::formBuilder()->getForm('Drupal\bfss_month_view\Form\MonthViewForm');

	return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'scheduled__appointments',
      '#assessments_block' => $BlockData,
      '#month_block' => $form,
      '#search_filter_block' => $SearchFilterForm,
      '#month_view_filter_block' => $MonthViewFilterForm,
      '#attached' => [
        'library' => [
           'bfss_month_view/month_view_lib', //include our custom library for this response
        ]
      ]
    ];
	
    /* return [
      '#theme' => 'scheduled__appointments',
      '#data' => $data,
      '#attached' =>[
        'library' => [
          'bfss_assessment/custom',
          'bfss_assessment/upcoming_appointment',
        ],
      ],
    ]; */
  }

}