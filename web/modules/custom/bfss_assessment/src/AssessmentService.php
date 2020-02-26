<?php

namespace Drupal\bfss_assessment;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;

/**
 * Class AssessmentService.
 */
class AssessmentService {

  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;
  /**
   * Config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new AssessmentService object.
   */
  public function __construct(RequestStack $request_stack, ConfigFactoryInterface $config_factory) {
    $this->requestStack = $request_stack;
    $this->configFactory = $config_factory;
  }

  /*
   * query
   */
  private function getAssessmentQuery(){
    return \Drupal::entityQuery('node')
              ->condition('type', 'assessment')
              ->condition('status', 1)
              ->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
  }

  /*
   * get node data by id
   */
  public function getComingAssessments($element){
    #get nodes
    $query = $this->getAssessmentQuery();
    $query->pager(10, (int) $element);
    $nids = $query->execute();
    return $nids;
  }

  /**
   * check assesment avail
   */
  public function check_assessment_node($nid = null) {
    if ((int) $nid) {
      $query = $this->getAssessmentQuery();
      $nids = $query->condition('nid',$nid)->execute();
      if ($nids && current($nids)) {
        return $nid;
      }
    }
    return false;
  }

  /*
   * get node data by id
   */
  public function getNodeData($nid){
      $node = Node::load($nid);
      $data = [];
      if ($node instanceof NodeInterface) {
        $data['title'] = $node->getTitle();
        if ($node->hasField('body')) {
          $data['body'] = t($node->get('body')->value);
        }
        if ($node->hasField('field_location')) {
          $data['field_location'] = t($node->get('field_location')->value);
        }
        if ($node->hasField('field_image')) {
          $data['field_image'] = file_create_url($node->get('field_image')->entity->getFileUri());
        }
        if ($node->hasField('field_schedules')) {
          $field_schedules = $node->get('field_schedules')->getValue();
          $latest_timing = null;
          $latest_duration = null;
          if ($field_schedules) {
            foreach ( $field_schedules as $element ) {
              if (isset($element['target_id'])) {
                $pGraph = Paragraph::load($element['target_id'] );
                if ($pGraph->hasField('field_timing') && $pGraph->hasField('field_duration')) {
                  $timing = (int) $pGraph->get('field_timing')->value;
                  $duration = $pGraph->get('field_duration')->value;
                  if ($duration) {
                    $duration = date('h:i A',strtotime('+'.$duration.' minutes',$timing));
                  }
                  if (empty($latest_timing)) {
                    $latest_timing = $timing;
                    $latest_duration = $duration;
                  }else{
                    if ($latest_timing > $timing) {
                      $latest_timing = $timing;
                      $latest_duration = $duration;
                    }
                  }
                  if ($timing > time()) {
                    $data['schedules'][] = [
                      'field_timing' => $timing,
                      'field_duration' => $duration,
                    ];
                  }
                }
              }
            }
            #get the latest upcoming schedule
            $data['latest_timing'] = $latest_timing;
            $data['latest_duration'] = $latest_duration;

          }
        }
      }
    return $data;
  }

  public function getSchedulesofAssessment($nid = null) {
      $node = Node::load($nid);
      $data = [];
      if ($node instanceof NodeInterface) {
        if ($node->hasField('field_schedules')) {
          $field_schedules = $node->get('field_schedules')->getValue();
          if ($field_schedules) {
            foreach ( $field_schedules as $element ) {
              if (isset($element['target_id'])) {
                $pGraph = Paragraph::load($element['target_id'] );
                if ($pGraph->hasField('field_timing')/* && $pGraph->hasField('field_duration')*/) {
                  $timing = (int) $pGraph->get('field_timing')->value;
                  if ($timing > time()) {
                    $data[$timing] = $timing;
                  }
                }
              }
            }
          }
        }
      }
    #sort them all
    ksort($data);
    // $results = [];
    // if ($data) {
    //   foreach ($data as $key => $value) {
    //     if (isset($value['field_timing'])) {
    //       $timing = $value['field_timing'];
    //       if ($timing) {
    //         $results[date('Y',$timing)][date('m',$timing)][date('d',$timing)] = $timing;
    //       }
    //     }
    //   }
    // }
    return $data;
  }

  public function checkDuration($nid = null, $matchTiming =null) {
      $until = 60;
      $node = Node::load($nid);
      $data = [];
      if ($node instanceof NodeInterface) {
        if ($node->hasField('field_schedules')) {
          $field_schedules = $node->get('field_schedules')->getValue();
          if ($field_schedules) {
            foreach ( $field_schedules as $element ) {
              if (isset($element['target_id'])) {
                $pGraph = Paragraph::load($element['target_id'] );
                if ($pGraph->hasField('field_timing') && $pGraph->hasField('field_duration')) {
                  $timing = $pGraph->get('field_timing')->value;
                  $field_duration = $pGraph->get('field_duration')->value;
                  if ($timing && $field_duration && $timing == $matchTiming) {
                    $until = $field_duration;
                  }
                }
              }
            }
          }
        }
      }
    return $until;
  }
  /*
   * check assessment available
   */
  public function notAvailableMessage(){
      drupal_set_message(t('The event you are looking for booking is not available! Please select another.'), 'error');
  }
} //end of class