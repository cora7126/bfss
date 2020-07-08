<?php
namespace Drupal\bfss_assessment\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bfss_assessment\AssessmentService;

/**
 * Provides a 'My Scheduled AssessmentBlock' Block.
 *
 * @Block(
 *   id = "my_scheduled_assessmentBlock",
 *   admin_label = @Translation("MyScheduled Assessment Block"),
 *   category = @Translation("My Scheduled AssessmentBlock"),
 * )
 */
class MyScheduledAssessmentBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    $data = [];
    $element = 1;
    $param = \Drupal::request()->query->all();
    #get nodes by paginations
    // if(isset($param['SearchAssessments'])){
    //   $nids = $this->assessmentService->Assessments_Search_Filter($element,$param['SearchAssessments'],'scheduled');
    // }else{
    //   $nids = $this->assessmentService->My_Scheduled_Assessment_Block($element);
    // }
    // #load data
    // $current_path = \Drupal::service('path.current')->getPath();
    // $res = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);

    // foreach($nids as $nid){  
    //   $arr = $this->assessmentService->getNodeData($nid);
    //   if ($arr) {
    //     $arr['url'] = '/assessment/scheduled/node/'.$nid;
    //     $data[] = $arr;
    //   }
    // }
    $booked_assessments = $this->assessmentService->My_Booked_Scheduled_Assessment_Block($element);
    #send results
    if (!empty($booked_assessments)) {
      return [
        'results' => [
              '#cache' => ['max-age' => 0,],
              '#theme' => 'my_scheduled_assessment',
              '#data' => $booked_assessments,
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
      '#cache' => ['max-age' => 0,],
	    '#markup' => $this->t('There is no assignment avaialble for now'),
      '#attached' =>[
        'library' => [
          'bfss_assessment/custom',
        ],
      ],
	  );
  }
}