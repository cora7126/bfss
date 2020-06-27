<?php

namespace Drupal\edit_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\Element\EntityAutocomplete;
/**
 * Defines a route controller for entity autocomplete form elements.
 */
class AutocompleteController extends ControllerBase {

  // /**
  //  * Handler for autocomplete request.
  //  */
  // public function handleAutocomplete(Request $request, $field_name, $count) {
  //   $results = [];

  //   // Get the typed string from the URL, if it exists.
  //   if ($input = $request->query->get('q')) {
  //     $typed_string = Tags::explode($input);
  //     $typed_string = Unicode::strtolower(array_pop($typed_string));
  //     // @todo: Apply logic for generating results based on typed_string and other
  //     // arguments passed.
  //       $query = \Drupal::entityQuery('node');
  //       $query->condition('type', 'bfss_organizations');
  //       $query->condition('field_type', $field_name, 'IN');
  //       $nids = $query->execute();
  //       $org_name=[];
  //       foreach($nids as $nid){
  //         $node = Node::load($nid);
  //         $org_name[]= $node->field_organization_name->value;
  //       }
  //   }

  //   return new JsonResponse($org_name);
  // }


  //   public function Get_Org_Name($type){
  //     if(isset($type)){
  //       $query = \Drupal::entityQuery('node');
  //       $query->condition('type', 'bfss_organizations');
  //       $query->condition('field_type', $type, 'IN');
  //       $nids = $query->execute();
  //       $org_name=[];
  //       foreach($nids as $nid){
  //         $node = Node::load($nid);
  //         $org_name[]= $node->field_organization_name->value;
  //       }
  //       $result = implode(",",$org_name);
  //     }
  //     return $result;
  //   }

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorage
   */
  protected $nodeStorage;
/**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->nodeStroage = $entity_type_manager->getStorage('node');
  }
/**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      $container->get('entity_type.manager')
    );
  }
/**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request,$state_name , $org_type, $count) {
    //print_r($state_name);
    $results = [];
    $input = $request->query->get('q');
// Get the typed string from the URL, if it exists.
    if (!$input) {
      return new JsonResponse($results);
    }
$input = Xss::filter($input);
$query = $this->nodeStroage->getQuery()
      ->condition('type', 'bfss_organizations')
      ->condition('field_organization_name', $input, 'CONTAINS')
      ->condition('field_type', $org_type, 'IN')
      ->condition('field_state', $state_name, 'IN')
      ->condition('status', 1)
      ->groupBy('nid')
      ->sort('created', 'DESC')
      ->range(0, 10);
$ids = $query->execute();
    $nodes = $ids ? $this->nodeStroage->loadMultiple($ids) : [];
foreach ($nodes as $node) {
      switch ($node->isPublished()) {
        case TRUE:
          $availability = '✅';
          break;
case FALSE:
        default:
          $availability = '🚫';
          break;
      }
$label = [
        $node->field_organization_name->value,
      ];
$results[] = [
        'value' => $label,
        'label' => implode(' ', $label),
      ];
    }
return new JsonResponse($results);
  }

}
