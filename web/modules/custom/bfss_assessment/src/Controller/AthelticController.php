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
use  \Drupal\user\Entity\User;
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
      #check another username
      $query = $this->db->select('athlete_addweb', 'tb');
      $query->fields('tb');
      $query->condition('athlete_addweb_name', $username,'=');
      $query->condition('athlete_addweb_visibility', 1,'=');
      $result = $query->execute()->fetchAssoc();

      if (isset($result['athlete_uid']) && !empty($result['athlete_uid'])) {
        return $result['athlete_uid'];
      }
      #check last username
      $query = $this->db->select('athlete_clubweb', 'tb');
      $query->fields('tb');
      $query->condition('athlete_clubweb_name', $username,'=');
      $query->condition('athlete_clubweb_visibility', 1,'=');
      $result = $query->execute()->fetchAssoc();

      if (isset($result['athlete_uid']) && !empty($result['athlete_uid'])) {
        return $result['athlete_uid'];
      }
    }
    return false;
  }

  public function getBasicData(){
     $uid = \Drupal::currentUser()->id();
     if(isset($_GET['uid'])){
     $uid =$_GET['uid'];
    }
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
    //$imgID = $this->getUserInfo('user__user_picture','user_picture_target_id');

     $imgID = $this->Get_ath_Data('athlete_prof_image', 'atsim','athlete_id',$uid)['athlete_target_image_id'];
     // print_r($imgID);
     // die;
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
    return $data;
  }


  public function InstagramUrl(&$data, $preview =false) {
    //print_r($data['athlete_social']['athlete_social_1']);die;
    if (!$preview) {
      $data['mydata']['field_instagram'] = $instagram_url = isset($data['athlete_social']['athlete_social_1']) ? $data['athlete_social']['athlete_social_1'] : null;
    }
      // $instagram_url = $data['athlete_social']['athlete_social_1'];
      $regex = '/(?:(?:http|https):\/\/)?(?:www\.)?(?:instagram\.com|instagr\.am)\/([A-Za-z0-9-_\.]+)/im';

      // Verify valid Instagram URL
        if ( preg_match( $regex, $instagram_url, $matches ) ) {
              $data['mydata']['field_instagram'] = $matches[1];
          }
    }

  public function YoutubeUrl(&$data, $preview =false) {
    if (!$preview) {
      $data['mydata']['field_youtube'] = isset($data['athlete_social']['athlete_social_2']) ? $data['athlete_social']['athlete_social_2'] : null;
    }
    if (isset($data['mydata']['field_youtube']) && !empty($data['mydata']['field_youtube'])) {
      $url     = $data['mydata']['field_youtube'];
      $xml_url = $this->getYouTubeXMLUrl($url);
      $xmlfile = file_get_contents($xml_url);
      // Convert xml string into an object
      $new = simplexml_load_string($xmlfile);
      // Convert into json
      $con = json_encode($new);
      // Convert into associative array
      $newArr = json_decode($con, true);
      $video_id = explode("?v=",  isset($newArr['entry'][0]['link']['@attributes']['href']) ? $newArr['entry'][0]['link']['@attributes']['href'] : null);
      $video_id = isset($video_id[1]) ? $video_id[1] : null;
      $data['mydata']['field_youtube'] = $video_id;
    }
  }


    public function Bfss_assessments(&$data, $preview =false) {

    $block = \Drupal\block\Entity\Block::load('bfssassessmentlistblock');
    if ($block) {
      $block_content = \Drupal::entityManager()->getViewBuilder('block')->view($block);
      if ($block_content) {
        $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);
        $data['mydata']['bfss_assessments'] = $assessments_block;
      }
    }
      //$data['mydata']['bfss_assessments'] = 'field_youtube1';

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
  public function getShareBlock(&$data) {
    $block = \Drupal\block\Entity\Block::load('socialsharingblock');
    if ($block) {
      $block_content = \Drupal::entityManager()->getViewBuilder('block')->view($block);
      if ($block_content) {
        $assessments_block = \Drupal::service('renderer')->renderRoot($block_content);
        $data['mydata']['social_share'] = $assessments_block;
      }
    }
  }

  public function follow_unfollow(&$data) {

     $uid = \Drupal::currentUser()->id();
     $user = \Drupal\user\Entity\User::load($uid);
     $roles = $user->getRoles();
     $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessment\Form\FollowUnfollowForm');

     $data['mydata']['follow_unfollow'] = $form;



  }
  public function updateInfoForTmeplate(&$data, $username = null) {
    // echo "<pre>";
    // print_r($data);
    // die;

    if ($username) {
      //if (isset($data['athlete_web']['athlete_web_name']) && $data['athlete_web']['athlete_web_name'] == $username) {
        // $data['org_info']['name']
        $relSchl = isset($data['athlete_school']) ? $data['athlete_school'] : null;
        if ($relSchl) {
          foreach ($relSchl as $key => $value) {
            $data['org_info'][str_replace('athlete_school_','', $key)] = $value;
          }
        }


      //}

      if (isset($data['athlete_addweb']['athlete_addweb_name']) && $data['athlete_addweb']['athlete_addweb_name'] == $username) {
        // $data['org_info']['name']
        $relSchl = isset($data['athlete_uni']) ? $data['athlete_uni'] : null;
        if ($relSchl) {
          foreach ($relSchl as $key => $value) {
            $data['org_info'][str_replace('athlete_uni_','', $key)] = $value;
          }
        }
      }

      if (isset($data['athlete_clubweb']['athlete_clubweb_name']) && $data['athlete_clubweb']['athlete_clubweb_name'] == $username) {
        // $data['org_info']['name']
        $relSchl = isset($data['athlete_club']) ? $data['athlete_club'] : null;
        if ($relSchl) {
          foreach ($relSchl as $key => $value) {
            $data['org_info'][str_replace(['athlete_club_','athlete_school_'],'', $key)] = $value;
          }
        }
      }


    }

  }

  public function updateTempInfoForTmeplate(&$data) {
    $req = $this->requestStack->getCurrentRequest();
    // echo "<pre>";
    // print_r($req);
    // die;
    $uid = \Drupal::currentUser()->id();
    #update values
    if ($val = $req->get('fname')) {
      $data['first_name'] = $val;
    }
    if ($val = $req->get('lname')) {
      $data['last_name'] = $val;
    }
    if ($val = $req->get('email')) {
      $data['mail'] = $val;
    }


    if ($val = $req->get('sex')) {
      switch ($val) {
        case 1:
          $newval = 'Male';
          break;

        case 2:
          $newval = 'Female';
          break;

        case 3:
          $newval = 'Other';
          break;

        default:
          $newval = '';
          break;
      }
      $data['athlete_info']['athlete_state'] = $newval;
    }

    if ($val = $req->get('gradyear')) {
      $data['athlete_info']['athlete_year'] = $val;
    }

    if ($val = $req->get('height')) {
      $data['athlete_info']['field_height'] = $val;
    }

    if ($val = $req->get('weight')) {
      $data['athlete_info']['field_weight'] = $val;
    }

    if ($val = $req->get('aboutme')) {
      $data['athlete_about_me'] = $val;
    }

    if ($val = $req->get('instagram')) {
      $data['mydata']['field_instagram'] = $val;
      $this->InstagramUrl($data, true);
    }

    if ($val = $req->get('youtube')) {
      $data['mydata']['field_youtube'] = $val;
    $this->YoutubeUrl($data, true);

    }

   // $data['mydata']['field_youtube1'] = 'field_youtube1';

    // if ($val = $req->get('btnId')) {
    //   $pr = $name = $type = '';
    //   if ($val == 1) {
    //     $pr = '';
    //     $name = 'organizationName';
    //     $type = 'organizationType';
    //   }elseif ($val == 2) {
    //     $pr = '_1';
    //     $name = 'schoolname_1';
    //     $type = 'education_1';
    //   }elseif ($val == 3) {
    //     $pr = '_2';
    //     $name = 'schoolname_2';
    //     $type = 'education_2';
    //   }

    // $resulttype = \Drupal::database()->select('athlete_school', 'ats');
    // $resulttype->fields('ats');
    // $resulttype->condition('athlete_uid', $current_user, '=');
    // $resulttype->condition('id', $id1, '=');
    // $resulttype1 = $resulttype->execute()->fetchAssoc();

    if(isset($_GET['uid'])){
     $uid =$_GET['uid'];
    }
    $org = $this->Get_ath_Data('athlete_school', 'ats','athlete_uid',$uid);
   // if($org)
      $data['org_info']['name'] = $org['athlete_school_name'];
      $data['org_info']['type'] = $org['athlete_school_type'];
      $data['org_info']['coach'] = $org['athlete_school_coach'];
      $data['org_info']['sport'] = $org['athlete_school_sport'];
      $data['org_info']['pos'] = $org['athlete_school_pos'];
      $data['org_info']['stat'] = $org['athlete_school_stat'];
      $data['org_info']['pos2'] = $org['athlete_school_pos2'];
      $data['org_info']['pos3'] = $org['athlete_school_pos3'];
   // }
    //}


  }
  /**
   * @return markup
   *url bfss_assessment.atheltic_profile
   */
  public function profilePage() {

    $data = [];
    $username = \Drupal::request()->get('username');

    $profileuser = $this->getUserNameValiditity($username);
  //  print '<pre>'; print_r($profileuser);die;
    #if username doesn't exist
    if (!$profileuser) {
      return [
        '#type' => 'markup',
        '#markup' => t('<h3 style="padding: 10px;color:#db0000;font-weight: bold;">OOPS! The profile you are looking for is not available!</h3>'),
      ];
    }
    $this->atheleteUserId = $profileuser;
    $data = $this->getBasicData();
    $data['username'] = $username;

    $this->YoutubeUrl($data);
    $this->Bfss_assessments($data);
    $this->InstagramUrl($data);
    $this->getShareBlock($data);
    $this->follow_unfollow($data);
    #data on username
    $this->updateInfoForTmeplate($data, $username);
    // echo "<pre>";
    // print_r($data);die;
    // send output here
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




/**
   * @return markup
   *url bfss_assessment.preview_atheltic_profile
   */
  public function previewProfile() {
    if(isset($_GET['uid'])){
      $this->atheleteUserId = $_GET['uid'];
    }else{
      $this->atheleteUserId = \Drupal::currentUser()->id();
    }


    $data = $this->getBasicData();
    $this->YoutubeUrl($data);
    $this->Bfss_assessments($data);
    $this->InstagramUrl($data);
    $this->getShareBlock($data);
    $this->follow_unfollow($data);
    #update data with new things
    $this->updateTempInfoForTmeplate($data, $username);
    #send the output
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

    public function Get_ath_Data($table,$atr,$uid_key,$current_user){
      //if($table){
        $query = \Drupal::database()->select($table, $atr);
        $query->fields($atr);
        $query->condition($uid_key, $current_user, '=');
        $result = $query->execute()->fetchAssoc();
      //}
      return isset($result)?$result:'null';
    }

}
