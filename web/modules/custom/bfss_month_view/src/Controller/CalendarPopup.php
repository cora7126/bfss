<?php

namespace Drupal\bfss_month_view\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Render\Markup;

use Drupal\bfss_assessment\AssessmentService;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use \Drupal\user\Entity\User;
class CalendarPopup extends ControllerBase {

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
		public function calendar_modal_show($nid)
		{	
			// echo $orgname;die;
			// $uid = \Drupal::currentUser()->id();
			// $conn = Database::getConnection();
			// $num_deleted = $conn->delete($orgname)
			//   ->condition('athlete_uid', $uid, '=')
			//   ->execute();
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
		  
		      $data['url'] = $base_url.'/assessment/type/'.$nid;
		      $data['roles'] = $roles;

			$html = '<div class="modal fade" id="myModal" role="dialog">
					    <div class="modal-dialog">
					    
					      <!-- Modal content-->
					      <div class="modal-content">
					            <div class="modal-header">
					              <button type="button" class="close" data-dismiss="modal">&times;</button>
					              <h4 class="modal-title">Modal Header</h4>
					            </div>
					            <div class="modal-body">
					              <p>Some text in the modal.'.$data['title'].'</p>
					            </div>
					            <div class="modal-footer">
					              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					            </div>
					      </div>
					      
					    </div>
					  </div>';
			  //print_r($nid);
			  $response = array('nid' => $nid,'modal' => Markup::create($html));
			  return new JsonResponse($response);
		}
   
}
