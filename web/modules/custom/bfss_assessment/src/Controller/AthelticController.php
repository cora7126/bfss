<?php

namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\bfss_assessment\AssessmentService;

/**
 * Class AthelticController.
 */
class AthelticController extends ControllerBase {

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
  
  /*
   * current user id
   */
  protected $atheleteUserId = null;
  
  /*
   * a database connection
   */
  protected $db;



  /**
   * Constructs a new AthelticController object.
   */
  public function __construct(RequestStack $request_stack, ConfigFactoryInterface $config_factory, AssessmentService $assessment_service) {
    $this->requestStack = $request_stack;
    $this->configFactory = $config_factory;
    $this->assessmentService = $assessment_service;
    $this->db = Database::getConnection();
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

  /*
   * get data using d-b query
   */
  private function getUserInfo($table_name = '', $field_name = '', $cond = 'entity_id') {
    $result = null;
    if ($this->atheleteUserId) {
      $query = $this->db->select($table_name, 'tb');
      if ($field_name) {
          $query->fields('tb', [$field_name]);
      }else{
          $query->fields('tb');
      }
      $query->condition($cond, $this->atheleteUserId,'=');
      $result = $query->execute()->fetchAssoc();
      if ($field_name && isset($result[$field_name])) {
        return $result[$field_name];
      }
    }
    return $result;
  }
  /*
   * check id exists
   */
  private function getUserNameValiditity($username = '') {
    if ($username) {
      $query = $this->db->select('athlete_web', 'tb');
      $query->fields('tb');
      $query->condition('athlete_web_name', $username,'=');
      $query->condition('athlete_web_visibility', 1,'=');
      $result = $query->execute()->fetchAssoc();
      if (isset($result['athlete_uid']) && !empty($result['athlete_uid'])) {
        return $result['athlete_uid'];
      }
    }
    return false;
  }

  /**
   * @return markup
   *url bfss_assessment.atheltic_profile
   */
  public function profilePage() {
    $data = [];
    $data['username'] = $username = \Drupal::request()->get('username');
    $profileuser = $this->getUserNameValiditity($username);
    #if username doesn't exist
    if (!$profileuser) {
      return [
        '#type' => 'markup',
        '#markup' => t('<h3 style="padding: 10px;color:#db0000;font-weight: bold;">OOPS! The profile you are looking for is not available!</h3>'),
      ];
    }
    $this->atheleteUserId = $profileuser;
    $data['first_name'] = $this->getUserInfo('user__field_first_name', 'field_first_name_value');
    $data['last_name'] = $this->getUserInfo('user__field_last_name', 'field_last_name_value');
    $data['date'] = $this->getUserInfo('user__field_date', 'field_date_value');
    if ($data['date']) {
      $from = new \DateTime($data['date']);
      $to   = new \DateTime('today');
      $data['date'] = $from->diff($to)->y;
    }
    $data['mail'] = $this->getUserInfo('users_field_data', 'mail','uid');
    $data['athlete_school'] = $this->getUserInfo('athlete_school', '','athlete_uid');
    $data['athlete_uni'] = $this->getUserInfo('athlete_uni', '','athlete_uid');
    $data['athlete_club'] = $this->getUserInfo('athlete_club','','athlete_uid');
    /*[id] => 3
    [athlete_uid] => 101
    [athlete_school_name] => Organization Name 1
    [athlete_school_coach] => sfdg
    [athlete_school_sport] => Tennis
    [athlete_school_pos] => ert
    [athlete_school_stat] => sfsdfsd
    [athlete_school_type] => Organization Type 1
    [athlete_school_pos2] => er
    [athlete_school_pos3] => ert*/
   
    $data['athlete_info'] = $this->getUserInfo('athlete_info', '','athlete_uid');
    /*
    [id] => 3
    [athlete_uid] => 101
    [athlete_email] => rperry@mindimage.net
    [athlete_state] => 1
    [athlete_city] => 13
    [athlete_coach] => sfdg
    [athlete_year] => 13
    [field_height] => 13
    [field_weight] => 13
    [popup_flag] => */
    #prepare gender
    if (isset($data['athlete_info']['athlete_state'])) {
      switch ($data['athlete_info']['athlete_state']) {
        case 1:
          $val = 'Male';
          break;

        case 2:
          $val = 'Female';
          break;

        case 3:
          $val = 'Other';
          break;
        
        default:
          $val = '';
          break;
      }
      $data['athlete_info']['athlete_state'] = $val;
    }
    $data['state'] = $this->getUserInfo('user__field_state','field_state_value');
    $data['athlete_about_me'] = $this->getUserInfo('athlete_about','athlete_about_me','athlete_uid');
    #result-10
    $data['athlete_social'] = $this->getUserInfo('athlete_social','','athlete_uid');

    /*[id] => 3
    [athlete_uid] => 101
    [athlete_social_1] => Ryan Perry
    [athlete_social_2] => safsdaffsd*/
    #result-12
    $data['athlete_uni'] = $this->getUserInfo('athlete_uni','','athlete_uid');
    $data['athlete_web'] = $this->getUserInfo('athlete_web','','athlete_uid');
    /*[athlete_web_name] => 
    [athlete_web_visibility] => 1*/
    $data['athlete_addweb'] = $this->getUserInfo('athlete_addweb','','athlete_uid');
    // [athlete_addweb_name] => 
    // [athlete_addweb_visibility] =>  
    $imgID = $this->getUserInfo('user__user_picture','user_picture_target_id');
    if ($imgID) {
      $file = File::load($imgID);
      if ($file) {
        $data['image'] = ImageStyle::load('medium')->buildUrl($file->getFileUri());
      }
    }
    // $data['image'] = 
    // 298
    $data['addschool'] = $this->getUserInfo('athlete_addschool','','athlete_uid');
   
    $data['athlete_clubweb'] = $this->getUserInfo('athlete_clubweb','','athlete_uid');
     /*[athlete_clubweb_name] => 
    [athlete_clubweb_visibility] => */ 
    $data['mydata'] = $this->getUserInfo('mydata','','uid');

    /* [id] => 23
    [field_jodi] => Ryan
    [field_bloggs] => Perry
    [field_az] => 2
    [field_city] => Scottsdale
    [field_birth_gender] => 
    [field_dob] => 
    [field_height] => 
    [field_weight] => 
    [field_organization_type] => 0
    [field_organization_name] => 0
    [field_coach_lname] => 
    [field_sport] => 
    [field_position] => 
    [field_instagram] => 
    [field_youtube] => 
    [popup_flag] => filled
    [uid] => 101*/
    #$this->assessmentService->check_assessment_node($nid);
    #get only channel id
    // field_dob
    
    
    $data['mydata']['field_youtube'] = $data['athlete_social']['athlete_social_2'];
    if (isset($data['mydata']['field_youtube']) && !empty($data['mydata']['field_youtube'])) {
      //$data['mydata']['field_youtube'] = $this->parse_channel_id($data['mydata']['field_youtube']);
    	$url     = $data['mydata']['field_youtube'];
		$xml_url = $this->getYouTubeXMLUrl($url);
		$xmlfile = file_get_contents($xml_url);  
		// Convert xml string into an object 
		$new = simplexml_load_string($xmlfile); 
		// Convert into json 
		$con = json_encode($new); 
		// Convert into associative array 
		$newArr = json_decode($con, true);
		$video_id = explode("?v=",  $newArr['entry'][0]['link']['@attributes']['href']);
		$video_id = $video_id[1];
		$data['mydata']['field_youtube'] = $video_id;
    }
     
    	$data['mydata']['field_instagram'] = $data['athlete_social']['athlete_social_1'];
     if (isset($data['mydata']['field_instagram']) && !empty($data['mydata']['field_instagram'])) {
     	$username = $data['mydata']['field_instagram'];
    	$instaResult = file_get_contents('https://www.instagram.com/'.$username.'/?__a=1');
			$insta = json_decode($instaResult);
			$instagram_photos = $insta->graphql->user->edge_owner_to_timeline_media->edges;
			$img_urls = [];
			foreach($instagram_photos as $instagram_photo){
				$img_urls[] = $instagram_photo->node->display_url;
			}
			$data['mydata']['field_instagram'] = $img_urls;
		}

    #only for dummy use
    # should be deleted 
      // $data['mydata']['field_youtube'] = 'https://www.youtube.com/channel/UCfOfVT4F6UiQw75irnS6KFA';
      // $data['athlete_about_me'] = t('<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p><p> Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel e</p>');
     // \Drupal::logger('data')->notice('@type', array('@type' => print_r($data, 1) ));
    #only for dummy use
    # should be deleted 





    #send output here
    return [
        '#theme' => 'atheltic__profile',
        '#data' => $data,
        '#attached' =>[
          'library' => [
            'bfss_assessment/athletic',
          ],
        ],
      ];
  }

  private function parse_channel_id($url = '') {
    try {
      $parsed = parse_url(rtrim($url, '/'));
      if (isset($parsed['path']) && preg_match('/^\/channel\/(([^\/])+?)$/', $parsed['path'], $matches)) {
        if (isset($matches[1])) {
            return $matches[1];
        }
      }
    } catch (\Exception $e) {
      return null;
      // throw new Exception("{$url} is not a valid YouTube channel URL");
    }
    return null;
  }

	private function getYouTubeXMLUrl( $url, $return_id_only = false ) {
	    $xml_youtube_url_base = 'https://www.youtube.com/feeds/videos.xml';
	    $preg_entities        = [
	        'channel_id'  => '\/channel\/(([^\/])+?)$', //match YouTube channel ID from url
	        'user'        => '\/user\/(([^\/])+?)$', //match YouTube user from url
	        'playlist_id' => '\/playlist\?list=(([^\/])+?)$',  //match YouTube playlist ID from url
	    ];
	    foreach ( $preg_entities as $key => $preg_entity ) {
	        if ( preg_match( '/' . $preg_entity . '/', $url, $matches ) ) {
	            if ( isset( $matches[1] ) ) {
	                if($return_id_only === false){
	                    return $xml_youtube_url_base . '?' . $key . '=' . $matches[1];
	                }else{
	                    return [
	                        'type' => $key,
	                        'id' => $matches[1],
	                    ];
	                }

	            }
	        }
	    }

	}


	
		
	
}
