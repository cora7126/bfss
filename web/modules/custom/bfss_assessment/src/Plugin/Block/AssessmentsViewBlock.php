<?php
namespace Drupal\bfss_assessment\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bfss_assessment\AssessmentService;

/**
 * Provides a 'push button' block.
 *
 * @Block(
 *   id = "assessments_view_block",
 *   admin_label = @Translation("Assessments Custom View"),
 *   category = @Translation("Bfss Assessments")
 * )
 */
class AssessmentsViewBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    //$nids =$this->assessmentService->getComingAssessments($element);


    if(isset($param['SearchAssessments'])){
        $nids = $this->assessmentService->Assessments_Search_Filter($element,$param['SearchAssessments'],'all');
    }elseif(isset($param['categories'])){
        $nids = $this->assessmentService->assessment_categories_filter($element,'dashboard');
    }elseif(isset($param['tags'])){
       $nids = $this->assessmentService->assessment_tags_filter($element,'dashboard');
    }elseif(isset($param['state']) && isset($param['city'])){
      $nids = $this->assessmentService->assessment_venues_filter($element,'dashboard');
    }else{
       $nids = $this->assessmentService->assessment_after_month_filter($element);
    }
    #load data
    $current_path = \Drupal::service('path.current')->getPath();
    $res = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
   // $data['current_page'] = $res;
    foreach($nids as $nid){
      $arr = $this->assessmentService->getNodeData($nid);
      // echo "<pre>";
      // print_r($arr);
      // die;
      if ($arr) {
        // $arr['url'] = $base_url.'/assessment/node/'.$nid;
        // $arr['url'] = 'http://bfss.mindimage.net/assessment/node/'.$nid;
        $arr['url'] = '/assessment/node/'.$nid;
        $data[] = $arr;
      }
    }
  #send results
    if (!empty($data)) {
      return [
        'results' => [
              '#cache' => ['max-age' => 0,],
              '#theme' => 'page_assessment',
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
      '#cache' => ['max-age' => 0,],
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