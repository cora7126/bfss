<?php
namespace Drupal\bfss_admin\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Database\Database;

class GetStateAutocomplete extends ControllerBase {

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
		public function get_state_autocomplete($var1,$var2)
		{
		print_r($var1);
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
		                  ->condition('state_code','AK', '=')
		                  ->range(0, 10)
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
