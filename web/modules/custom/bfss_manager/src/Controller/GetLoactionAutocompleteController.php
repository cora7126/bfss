<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Database\Database;
/**
 * Defines a route controller for entity autocomplete form elements.
 */
class GetLoactionAutocompleteController extends ControllerBase {
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
    public function handleAutocomplete(Request $request, $field_name, $count) {
      $conn = Database::getConnection();
      $results = [];
      $input = $request->query->get('q');
      // Get the typed string from the URL, if it exists.
      if (!$input) {
        return new JsonResponse($results);
      }
        $input = Xss::filter($input);

        $results = \Drupal::database()->select('us_cities', 'athw')
                  ->fields('athw')
                  ->condition('name',"%".$input."%",'LIKE')
                  ->condition('state_code',$field_name, '=')
                  ->range(0, 2000)
                  ->execute()->fetchAll();
         
        foreach ($results as $result) {

        $label = [
                $result->name,
              ];
        $results_arr[] = [
                'value' => $label,
                'label' => $label,
              ];
            }
        return new JsonResponse($results_arr);
    }

}
