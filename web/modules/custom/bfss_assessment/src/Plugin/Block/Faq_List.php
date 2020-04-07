<?php

namespace Drupal\bfss_assessment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bfss_assessment\AssessmentService;
use Drupal\Core\Render\Markup;
use  \Drupal\user\Entity\User;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "faq_list",
 *   admin_label = @Translation("Faq List"),
 * )
 */
class Faq_List extends BlockBase implements ContainerFactoryPluginInterface {

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
        $uid = \Drupal::currentUser();
        $user = \Drupal\user\Entity\User::load($uid->id());
        $roles = $user->getRoles();

        if(in_array('athlete', $roles) && !empty($roles)){
          $query = \Drupal::entityQuery('node');
          $query->condition('status', 1);
          $query->condition('type', 'faq');
          $query->condition('field_roles', 'athlete', '=');
          $nids = $query->execute();
        }elseif( in_array('coach', $roles) && !empty($roles) ){
          $query = \Drupal::entityQuery('node');
          $query->condition('status', 1);
          $query->condition('type', 'faq');
          $query->condition('field_roles', 'coach', '=');
          $nids = $query->execute();
        }else{
          $query = \Drupal::entityQuery('node');
          $query->condition('status', 1);
          $query->condition('type', 'faq');
          $nids = $query->execute();
        }


        $data = [];
        foreach ($nids as $nid) {
          $node = Node::load($nid);
          $title = $node->title->value; 
     
            $body =  Markup::create($node->body->value);
          $data[] = [
            'que' => $title,
            'ans' => $body
          ];
        }
       
      if (!empty($data)) {
        return [
          'results' => [
                '#theme' => 'faq_list',
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