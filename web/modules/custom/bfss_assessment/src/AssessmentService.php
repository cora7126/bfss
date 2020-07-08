<?php

namespace Drupal\bfss_assessment;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Database\Database;

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
              ->condition('field_type_of_assessment','private', '!=')
              ->condition('field_schedules.entity:paragraph.field_timing', time(),'>');

  }

  public function assessment_categories_filter($element,$page){
    // print_r($element);
    // print_r($page);
    // die;
    $param = \Drupal::request()->query->all();
    if(isset($param['categories'])){
      if($page == 'dashboard'){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_categories', $param['categories'], 'IN');
        $query->condition('field_type_of_assessment','private', '!=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }elseif($page == 'group'){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_categories', $param['categories'], 'IN');
        $query->condition('field_type_of_assessment','group', '=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }elseif($page == 'private') {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_categories', $param['categories'], 'IN');
        $query->condition('field_type_of_assessment','private', '=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }

      $nids_arr = [];
      foreach ($nids as $key => $value) {
        $nids_arr[] =  $value;
      }
    }
    return $nids_arr;
  }

 public function assessment_tags_filter($element,$page){
    $param = \Drupal::request()->query->all();
    if(isset($param['tags'])){
      if($page == 'dashboard'){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_event_tags', $param['tags'], 'IN');
        $query->condition('field_type_of_assessment','private', '!=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }elseif($page == 'group'){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_event_tags', $param['tags'], 'IN');
        $query->condition('field_type_of_assessment','group', '=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }elseif($page == 'private') {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_event_tags', $param['tags'], 'IN');
        $query->condition('field_type_of_assessment','private', '=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }
      $nids_arr = [];
      foreach ($nids as $key => $value) {
        $nids_arr[] =  $value;
      }
    }
    return $nids_arr;
  }


 public function assessment_venues_filter($element,$page){
    $param = \Drupal::request()->query->all();
    if(isset($param['state']) && isset($param['city'])){
       if($page == 'dashboard'){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_venue_state_assess', $param['state'], '=');
        $query->condition('field_venue_location_assess', $param['city'], '=');
        $query->condition('field_type_of_assessment','private', '!=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }elseif($page == 'group'){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_venue_state_assess', $param['state'], '=');
        $query->condition('field_venue_location_assess', $param['city'], '=');
        $query->condition('field_type_of_assessment','group', '=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }elseif($page == 'private') {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'assessment');
        $query->condition('field_venue_state_assess', $param['state'], '=');
        $query->condition('field_venue_location_assess', $param['city'], '=');
        $query->condition('field_type_of_assessment','private', '=');
        $query->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
        $query->condition('status', 1);
        $nids = $query->execute();
      }
      $nids_arr = [];
      foreach ($nids as $key => $value) {
        $nids_arr[] =  $value;
      }
    }
    return $nids_arr;
  }


  public function assessment_after_month_filter($element){
    $param = \Drupal::request()->query->all();
    $current_date = date('Y/m/d');
    if(isset($param['showdate'])){
      $exp = explode("/",$param['showdate']);
      $M =  $exp[0];
      $Y = $exp[1];
    }else{
      $current_date = date("Y/m/d");
      $date_arr = explode('/',$current_date);
      $M = $date_arr[1];
      $Y =  $date_arr[0];
    }
    if( !empty($M) && !empty($Y) ){
      $assessment = \Drupal::entityQuery('node')
              ->condition('type', 'assessment')
              ->condition('status', 1)
              ->condition('field_type_of_assessment','private', '!=')
              ->condition('field_schedules.entity:paragraph.field_timing', time(),'>');
      $entity_ids = $assessment->execute();
      $monthdata = [];
      foreach ($entity_ids as $entity_id) {
       $node = Node::load($entity_id);
       if(!empty($node)){
            $target_id = array_column($node->field_schedules->getValue(), 'target_id');
           foreach ($target_id as $target) {
            $paragraph = Paragraph::load($target);
            $timesamp = $paragraph->field_timing->value;
            $monthdata[] = [
              'timesamp' => $timesamp,
              'date' =>  date('Y/m/d', $timesamp),
              'day' =>  date('d', $timesamp),
                'month' =>  date('m', $timesamp),
                'year' =>  date('Y', $timesamp),
                'nid' => $entity_id,
              ];
           }
       }
       
      }

      $NIDS = [];
      	if(isset($param['showdate'])){
	      foreach ($monthdata as $month_data) {
	        if($month_data['month'] == $M && $month_data['year'] == $Y && $current_date <= $month_data['date']){
	          $NIDS[] = $month_data['nid'];
	        }
	      }
	    }
	    else{
	       foreach ($monthdata as $month_data) {
	        if($current_date <= $month_data['date'] && $month_data['month'] == $M){
	          $NIDS[] = $month_data['nid'];
	        }
	      }
	    }

    }
    return !empty(array_unique($NIDS)) ? array_unique($NIDS): null;
  }


//group Assessments function
  public function assessment_after_month_filter_upcoming($element){
    // print_r($_GET['showdate']);
    //die;
     $current_date = date('Y/m/d');
    $param = \Drupal::request()->query->all();
    if(isset($param['showdate'])){
      $exp = explode("/",$param['showdate']);
      $M =  $exp[0];
      $Y = $exp[1];
    }
    // else{
    //   $current_date = date("Y/m/d");
    //   $date_arr = explode('/',$current_date);
    //   $M = $date_arr[1];
    //   $Y =  $date_arr[0];
    // }
    if( !empty($M) && !empty($Y) && $param['showdate'] != 'm/Y'){

		      $assessment = \Drupal::entityQuery('node')
		              ->condition('type', 'assessment')
		              ->condition('field_type_of_assessment','group', '=')
		              ->condition('field_schedules.entity:paragraph.field_timing', time(),'>')
		              ->condition('status', 1);
		      $entity_ids = $assessment->execute();

		      $monthdata = [];
		      foreach ($entity_ids as $entity_id) {
		       $node = Node::load($entity_id);
           if(!empty($node)){
             $target_id = array_column($node->field_schedules->getValue(), 'target_id');
             foreach ($target_id as $target) {
              $paragraph = Paragraph::load($target);
              $timesamp = $paragraph->field_timing->value;
              $monthdata[] = [
                  'timesamp' => $timesamp,
                  'date' =>  date('Y/m/d', $timesamp),
                  'day' =>  date('d', $timesamp),
                  'month' =>  date('m', $timesamp),
                  'year' =>  date('Y', $timesamp),
                  'nid' => $entity_id,
                ];
             }
           }
		       
		      }

		      $NIDS = [];
		      if(isset($param['showdate'])){
			      foreach ($monthdata as $month_data) {
			        if($month_data['month'] == $M && $month_data['year'] == $Y && $current_date <= $month_data['date']){
			          $NIDS[] = $month_data['nid'];
			        }
			      }
			    }
			    else{
			       foreach ($monthdata as $month_data) {
			        if($current_date <= $month_data['date'] && $month_data['month'] == $M){
			          $NIDS[] = $month_data['nid'];
			        }
			      }
			    }
    }else{
    	$assessment = \Drupal::entityQuery('node')
              ->condition('type', 'assessment')
              ->condition('field_type_of_assessment','group', '=')
              ->condition('field_schedules.entity:paragraph.field_timing', time(),'>')
              ->sort('field_schedules.entity:paragraph.field_timing' , 'ASC') 
              ->condition('status', 1);
        $entity_ids = $assessment->execute();
        $NIDS = $entity_ids;
    }

    return !empty(array_unique($NIDS)) ? array_unique($NIDS): null;
  }

// booked assessments
 public function My_Booked_Scheduled_Assessment_Block($element){
    $data = [];
     $param = \Drupal::request()->query->all();
    $requriedFields = [
      'id',
      'time',
      'assessment_title',
      'assessment',
      'until',
      'created',
    ];
    $entity_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('user_id', \Drupal::currentUser()->id())
        ->condition('time', time(), ">")
        ->sort('time','ASC')
        ->execute();
        // print_r($entity_ids);
        // die;
    #if there is data
    if ($entity_ids) {
      foreach ($entity_ids as $entity_id) {
        #load entity
        $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($entity_id);

        if ($entity instanceof \Drupal\Core\Entity\ContentEntityInterface) {
              $node = Node::load($entity->assessment->value);
              if(!empty($node)){
                $val = [];
                $val['latest_timing'] = $entity->time->value;
                $val['url'] = '/assessment/scheduled/node/'.$entity->assessment->value.'/'.$entity_id;
                  foreach ($requriedFields as $field) {
                    if ($entity->hasField($field)) {
                      $val[$field] = $entity->get($field)->value;
                    }
                  }
            // print_r($val['assessment']);
            // die;
            #if assessment avail
                if (isset($val['assessment'])) { // node id 
                  $nodeData = $this->getNodeData($val['assessment']);
                    #udpate title
                    if (isset($nodeData['title']) && !empty($nodeData['title'])) {
                      $val['title'] = $nodeData['title'];
                    }
                    #img
                    $val['field_image'] = isset($nodeData['field_image']) ? $nodeData['field_image'] : null;
                    #loc
                    $val['location'] = isset($nodeData['field_location']) ? $nodeData['field_location'] : null;
                    #body
                    $val['body'] = isset($nodeData['body']) ? $nodeData['body'] : null;
                    $val['booking_status'] = "purchased";
                }
              if ($val) {
                $data[] = $val;
              }
            }
        }
      }
    }
    return $data;

 }


//My Scheduled Assessment
 public function My_Scheduled_Assessment_Block($element){
  $requriedFields = [
      'id',
      'time',
      'assessment_title',
      'assessment',
      'until',
      'created',
    ];
    $current_date = date('Y/m/d');
    $param = \Drupal::request()->query->all();
    if(isset($param['showdate'])){
      $exp = explode("/",$param['showdate']);
      $M =  $exp[0];
      $Y = $exp[1];
    }else{
      $current_date = date("Y/m/d");
      $date_arr = explode('/',$current_date);
      $M = $date_arr[1];
      $Y =  $date_arr[0];
    }
    if( !empty($M) && !empty($Y) ){
      // $assessment = \Drupal::entityQuery('node')
      //         ->condition('type', 'assessment')
      //         ->condition('field_type_of_assessment','group', '=')
      //         ->condition('status', 1);
      // $entity_ids = $assessment->execute();
       $booked_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('user_id', \Drupal::currentUser()->id())
        ->condition('time', time(), ">")
        ->sort('time','ASC')
        ->execute();
         #if there is data
         $entity_ids = []; 
      if ($booked_ids) {
        foreach ($booked_ids as $booked_id) {
          #load entity
           $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
              if($entity->assessment->value != 9999999999){
               $entity_ids[] = $entity->assessment->value;
              }
        }
      }
       
      $monthdata = [];
      foreach ($entity_ids as $entity_id) {
       $node = Node::load($entity_id);
     
       if(!empty($node)){
          if(!empty($node->field_schedules->getValue())){
           $target_id = array_column($node->field_schedules->getValue(), 'target_id');
           foreach ($target_id as $target) {
            $paragraph = Paragraph::load($target);
            $timesamp = $paragraph->field_timing->value;
            $monthdata[] = [
                'timesamp' => $timesamp,
                'date' =>  date('Y/m/d', $timesamp),
                'day' =>  date('d', $timesamp),
                'month' =>  date('m', $timesamp),
                'year' =>  date('Y', $timesamp),
                'nid' => $entity_id,
              ];
           } 
          }
        }
      }

      $NIDS = [];
      if(isset($param['showdate'])){
        foreach ($monthdata as $month_data) {
          if($month_data['month'] == $M && $month_data['year'] == $Y && $current_date <= $month_data['date']){
            $NIDS[] = $month_data['nid'];
          }
        }
      }
      else{
         foreach ($monthdata as $month_data) {
          if($current_date <= $month_data['date'] && $month_data['month'] == $M){
            $NIDS[] = $month_data['nid'];
          }
        }
      }
    }

    return !empty(array_unique($NIDS)) ? array_unique($NIDS): null;
  }

//ASSESSMENTS SEARCH FILTER  FOR  ASSESSMENTS
  public function Assessments_Search_Filter($element,$search_val,$assess_type){
    if(isset($search_val) && $assess_type=='group'){

          $assessment = \Drupal::entityQuery('node')
              ->condition('type', 'assessment')
              ->condition('field_type_of_assessment','group', '=')
               ->condition('field_schedules.entity:paragraph.field_timing', time(),'>')
              ->condition('title','%'.$search_val.'%','LIKE')
              ->condition('status', 1);
      $entity_ids = $assessment->execute();

    }elseif(isset($search_val) && $assess_type=='private'){

      $assessment = \Drupal::entityQuery('node')
              ->condition('type', 'assessment')
              ->condition('field_type_of_assessment','private', '=')
               ->condition('field_schedules.entity:paragraph.field_timing', time(),'>')
              ->condition('title','%'.$search_val.'%','LIKE')
              ->condition('status', 1);
      $entity_ids = $assessment->execute();
    }elseif(isset($search_val) && $assess_type=='scheduled'){

       $booked_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('user_id', \Drupal::currentUser()->id())
        ->condition('time', time(), ">")
        ->condition('assessment_title','%'.$search_val.'%','LIKE')
        ->sort('time','ASC')
        ->execute();
         #if there is data
         $entity_ids = []; 
      if ($booked_ids) {
        foreach ($booked_ids as $booked_id) {
          #load entity
           $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
              if($entity->assessment->value != 9999999999){
               $entity_ids[] = $entity->assessment->value;
              }
        }
      }
     



      // $assessment = \Drupal::entityQuery('node')
      //         ->condition('type', 'assessment')
      //         ->condition('field_type_of_assessment','private', '=')
      //          ->condition('field_schedules.entity:paragraph.field_timing', time(),'>')
      //         ->condition('title','%'.$search_val.'%','LIKE')
      //         ->condition('status', 1);
      // $entity_ids = $assessment->execute();

    }else{

      $assessment = \Drupal::entityQuery('node')
              ->condition('type', 'assessment')
              ->condition('title','%'.$search_val.'%','LIKE')
              ->condition('field_schedules.entity:paragraph.field_timing', time(),'>')
              ->condition('field_type_of_assessment','private', '!=')
              ->condition('status', 1);
      $entity_ids = $assessment->execute();
    }
    return !empty(array_unique($entity_ids)) ? array_unique($entity_ids): null;

  }





//Private Assessments function
  public function assessment_after_month_filter_private($element){
    // print_r($_GET['showdate']);
    // die;
     $current_date = date('Y/m/d');
    $param = \Drupal::request()->query->all();
    if(isset($param['showdate'])){
      $exp = explode("/",$param['showdate']);
      $M =  $exp[0];
      $Y = $exp[1];
    }else{
      $current_date = date("Y/m/d");
      $date_arr = explode('/',$current_date);
      $M = $date_arr[1];
      $Y =  $date_arr[0];
    }
    if( !empty($M) && !empty($Y) ){
      $assessment = \Drupal::entityQuery('node')
              ->condition('type', 'assessment')
              ->condition('field_type_of_assessment','private', '=')
               ->condition('field_schedules.entity:paragraph.field_timing', time(),'>')
              ->condition('status', 1);
      $entity_ids = $assessment->execute();
      $monthdata = [];
      foreach ($entity_ids as $entity_id) {
       $node = Node::load($entity_id);
       if(!empty($node)){
         $target_id = array_column($node->field_schedules->getValue(), 'target_id');
         foreach ($target_id as $target) {
          $paragraph = Paragraph::load($target);
          $timesamp = $paragraph->field_timing->value;
          $monthdata[] = [
               'timesamp' => $timesamp,
          	'date' =>  date('Y/m/d', $timesamp),
          	'day' =>  date('d', $timesamp),
              'month' =>  date('m', $timesamp),
              'year' =>  date('Y', $timesamp),
              'nid' => $entity_id,
            ];
         }
        }
      }

      $NIDS = [];
       if(isset($param['showdate'])){
	      foreach ($monthdata as $month_data) {
	        if($month_data['month'] == $M && $month_data['year'] == $Y && $current_date <= $month_data['date']){
	          $NIDS[] = $month_data['nid'];
	        }
	      }
	    }
	    else{
	       foreach ($monthdata as $month_data) {
	        if($current_date <= $month_data['date'] && $month_data['month'] == $M){
	          $NIDS[] = $month_data['nid'];
	        }
	      }
	    }
    }

    return !empty(array_unique($NIDS)) ? array_unique($NIDS): null;
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
    if ($nid == 9999999999) {
      return true;
    }
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
      global $base_url;
      $current_path = \Drupal::service('path.current')->getPath();
      $data['current_page'] = $base_url;
      $data['assess_type'] = $node->field_type_of_assessment->value;
    
      // echo "<pre>";
      // print_r($data['assess_type']);
      // die;
       // $data['assessment_type'] = $node->get('field_type_of_assessment')->value;
      if ($node instanceof NodeInterface) {
        $data['title'] = $node->getTitle();
        if ($node->hasField('body')) {
          $data['body'] = t($node->get('body')->value);
        }
        if ($node->hasField('field_location')) {
          $data['field_location'] = t($node->get('field_location')->value);
        }

        if ($node->hasField('field_image')) {
        	$imageurl = $node->get('field_image')->entity->uri->value;
        	if(isset($imageurl)){
        		$data['field_image'] = file_create_url($imageurl);
        	}
        	        
          //$data['field_image'] = file_create_url($node->get('field_image')->getFileUri());
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

/**
*get sheduled data
**/
  public function getNodeDataScheduled($nid,$booked_id){
      $node = Node::load($nid);
      $data = [];
      global $base_url;
      $current_path = \Drupal::service('path.current')->getPath();
      $data['current_page'] = $base_url;
      $data['assess_type'] = $node->field_type_of_assessment->value;
      if ($node instanceof NodeInterface) {
        $data['title'] = $node->getTitle();
        if ($node->hasField('body')) {
          $data['body'] = t($node->get('body')->value);
        }
        if ($node->hasField('field_location')) {
          $data['field_location'] = t($node->get('field_location')->value);
        }

        if ($node->hasField('field_image')) {
          $imageurl = $node->get('field_image')->entity->uri->value;
          if(isset($imageurl)){
            $data['field_image'] = file_create_url($imageurl);
          }
        }
        if(isset($booked_id)){
          $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
          $data['latest_timing'] = $entity->time->value;
          $duration = date('h:i A',strtotime('+'.$entity->until->value.' minutes',$entity->time->value));
          $data['latest_duration'] = $duration;
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
                    //print_r($timing);
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
