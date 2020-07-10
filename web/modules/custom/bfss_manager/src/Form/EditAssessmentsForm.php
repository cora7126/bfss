<?php

namespace Drupal\bfss_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\InvokeCommand;
use \Drupal\user\Entity\User;
use Drupal\file\Entity\File;
use \Drupal\Core\Datetime\DrupalDateTime;
use Drupal\date_popup\DatePopup;
use Drupal\date_popup\DatetimePopup;
/**
 * Class EditAssessmentsForm.
 */
class EditAssessmentsForm extends FormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\Core\Logger\LoggerChannelFactoryInterface definition.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The entity query.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  // Resident count
  protected $residentCount = 0;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'edit_assessments';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $session = $this->getRequest()->getSession();
    $param = \Drupal::request()->query->all();
    if(isset($param['nid'])){ 
    $node = Node::load($param['nid']);
    // echo "<pre>";
    // print_r();
    // die;
    // if(){
    // }
    $img_id = isset($node->get('field_image')->getValue()[0]['target_id']) ? $node->get('field_image')->getValue()[0]['target_id'] : '';
    $title =  $node->title->value;
    $body_val = $node->body->value;
    $body_format = $node->body->format;

    $location =  $node->field_location->value;

    $address_1 = $node->field_address_1_us->value;
    $address_2 = $node->field_address_2_us->value;
    $city = $node->field_city_us->value;
    $state = $node->field_state_us->value;
    $zip = $node->field_zip_us->value;

    $type =  $node->field_type_of_assessment->value;
    $field_venue_location_assess = $node->field_venue_location_assess->value;
    $field_venue_state_assess = $node->field_venue_state_assess->value;

    $schedules =  $node->get('field_schedules')->getValue(); 

    $cat_target_id = [];
    if( !empty( $node->get('field_categories')->getValue() ) ){
      foreach ($node->get('field_categories')->getValue() as $key => $target_id) {
       $cat_target_id[] =  $target_id['target_id'];
      }
    }

    $tags_target_id = [];
    if( !empty( $node->get('field_event_tags')->getValue() ) ){
      foreach ($node->get('field_event_tags')->getValue() as $key => $target_id) {
       $tags_target_id[] =  $target_id['target_id'];
      }
    }

    $form_state_values = $form_state->getValues();
    $form['#tree'] = TRUE;
    $form['#attached']['library'][] = 'bfss_manager/tokenize2';       //here can add library
    $form['#prefix'] = '<div class="add-assessments main_section_plx left_full_width_plx add-group-assessments">';
    $form['#suffix'] = '</div>';
 
    
    
    
    $form['left_section_start'] = [
      '#type' => 'markup',
      '#markup' => '<div class="left_section">',
    ];

    
    $form['title'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Assessment Title'),
        '#required' => TRUE,
        '#default_value' => $title,
        '#prefix' => '<div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Assessment Informations</h3>
                       <div class="items_div" style="">',
        '#suffix' => '',
    ];


     $form['body'] = [
          '#type' => 'text_format',
          '#placeholder' => t('Assessment Body'),
          '#default_value' => $body_val,
          '#format' => $body_format,
          '#prefix' => '<div class="html_body_wrap">',
          '#suffix' => '</div>',
        
          
      ];

    //  $form['body'] = [
    //     '#type' => 'textarea',
    //     '#placeholder' => t('Assessment Body'),
    //     '#required' => TRUE,
    //     '#default_value' => $body,
    //     '#prefix' => '',
    // ]; 

    

    $types = [''=>'Select Type','group'=>'Group','private'=>'Private'];
    $form['type'] = [
        '#type' => 'select',
        '#options' => $types,
        '#placeholder' => t('Type of Assessment'),
        '#required' => TRUE,
        '#default_value' => $type,
        '#prefix' => '',
        '#suffix' => '',
    ];


     $form['location'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Location Name'),
        '#required' => TRUE,
        '#default_value' => $location,
        '#prefix' => '',
        '#suffix' => '',
      ];

      $form['address_1'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Address 1'),
        '#required' => TRUE,
        '#default_value' => $address_1,
        '#prefix' => '',
        '#suffix' => '',
      ];

      $form['address_2'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Address 2'),
        #'#required' => TRUE,
        '#default_value' => $address_2,
        '#prefix' => '',
        '#suffix' => '',
      ];

      // $form['city'] = [
      //   '#type' => 'textfield',
      //   '#placeholder' => t('City'),
      //   '#required' => TRUE,
      //   '#default_value' => $city,
      //   '#prefix' => '',
      //   '#suffix' => '',
      // ];
      // $states_op = $this->getStates();
      // $form['state'] = [
      //   '#type' => 'select',
      //   '#options' => $states_op,
      //   '#placeholder' => t('State'),
      //   '#required' => TRUE,
      //   '#default_value' => $state,
      //   '#prefix' => '',
      //   '#suffix' => '',
      // ];
      $states_op = $this->getStates();
      $state_name = isset($form_state_values['state'])?$form_state_values['state']:'AZ';
      $form['state'] = [
          '#type' => 'select',
          '#options' => $states_op,
           '#default_value' => $state,
          '#placeholder' => t('State'),
          '#required' => TRUE,
           '#prefix' => '<div  class="full-width-inp">',
          '#suffix' => '</div>',
          '#ajax' => [
            'callback' => '::StateAjaxCallback', // don't forget :: when calling a class method.
            //'callback' => [$this, 'myAjaxCallback'], //alternative notation
            'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
            'event' => 'change',
            'wrapper' => 'edit-output-22', // This element is updated with this AJAX callback. 
          ]
      ];
      $form['city'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),  
        '#default_value' => $city,
        '#required' => TRUE,  
        '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
        '#autocomplete_route_parameters' => array('field_name' => $state_name, 'count' => 10), 
        '#prefix' => '<div id="edit-output-22" class="full-width-inp">',
        '#suffix' => '</div>',
      ];

      $form['zip'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Zip'),
        '#required' => TRUE,
        '#default_value' => $zip,
        '#prefix' => '',
        '#suffix' => '</div>
        </div><div class="athlete_left schedule_plx">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SCHEDULES</h3>
                       <div class="items_div" style="">',
      ];


      foreach ($schedules as $key => $value) {
          //$target_id[] = $value['target_id'];
        if(isset($value['target_id'])){
          $pGraph = Paragraph::load($value['target_id']);
          // echo "<pre>";
          // print_r($pGraph);
          // die;
          $form['update'][$key]['field_date'] = [
            '#type' => 'textfield',
            '#placeholder' => t('Select Date'),
           # '#required' => TRUE,
            '#default_value' => date('m/d/Y',$pGraph->field_timing->value),
            '#format' => 'm/d/Y',
            '#attributes' => array('id' => array('datepicker')),
            '#prefix' => '<div class="date_duration date_duration_update">',
          ];

          $time = $this->gettime();
          $time = ['' => 'Start Time'] + $time;
          $form['update'][$key]['field_time'] = [
            '#type' => 'select',
            '#options' => $time,
            '#placeholder' => t('Start Time'),
            #'#required' => TRUE,
            '#default_value' => date('h:i:s',$pGraph->field_timing->value),
            '#prefix' => '<div class="box niceselect times edit-update-time">',
            '#suffix' => '</div>',
          ];

          $hours = [];
          for ($k=1; $k <=5 ; $k++) { 
            $hours[$k]=$k;
          }

          $hours = ['' => 'Duration (Hours)'] + $hours;
          // $form['update'][$key]['field_duration'] = [
          //   #'#placeholder' => t('Duration (Hours)'),
          //   '#options' => $hours,
          //   '#type' => 'select',
          //  # '#required' => TRUE,
          //   '#default_value' => $pGraph->field_duration->value,
          //   '#prefix' => '<div class="box niceselect duration">',
          //   '#suffix' => '</div></div>',
          // ];

          $form['update'][$key]['field_duration'] = [
            '#placeholder' => t('Duration (Minutes)'),
            '#type' => 'textfield',
           # '#required' => TRUE,
            '#default_value' => $pGraph->field_duration->value,
            '#prefix' => '<div class="box niceselect duration">',
            '#suffix' => '</div></div>',
          ];
        }
      }

      $form['schedules_st'] = [
        '#type' => 'markup',
        '#markup' => '</div></div>
        ',
      ];

      
    $form['left_imageuploader_start'] = [
      '#type' => 'markup',
      '#markup' => '<div class="athlete_left assessment-image-uploader">
      <div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>EVENT IMAGE</h3>
                       <div class="items_div">',
    ];
    $form['image'] = [
      #'#title' => 'EVENT IMAGE',
      '#type' => 'managed_file',
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#upload_location' => 'public://',
      '#required' => FALSE,
      '#default_value' => [$img_id],
      '#prefix' => '<div class="imgupload">',
      '#suffix' => '</div>',
      '#attributes' => [
        'class' => ['imageuplode1']
      ],
    ];

    $form['left_imageuploader_end'] = [
      '#type' => 'markup',
      '#markup' => '</div></div></div><div class="athlete_left schedule_plx">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>ADD SCHEDULES</h3>
                       <div class="items_div" style="">',
    ];
      
    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
      '#prefix' => '',
      '#suffix' => '',
    ];

   

    for ($i = 0; $i <= $this->residentCount; $i++) {
      
      $form['resident'][$i]['field_date'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Select Date'),
       # '#required' => TRUE,
        '#default_value' => date('m/d/Y'),
        '#format' => 'm/d/Y',
        '#attributes' => array('id' => array('datepicker')),
        '#prefix' => '<div class="date_duration">',
      ];

      $time = $this->gettime();
      $time = ['' => 'Start Time'] + $time;
      $form['resident'][$i]['field_time'] = [
        '#type' => 'select',
        '#options' => $time,
        '#placeholder' => t('Start Time'),
       # '#required' => TRUE,
        '#default_value' =>'',
        '#prefix' => '<div class="box niceselect times">',
        '#suffix' => '</div>',
      ];

      $hours = [];
      for ($k=1; $k <=5 ; $k++) { 
        $hours[$k]=$k;
      }

      $hours = ['' => 'Duration (Hours)'] + $hours;
      // $form['resident'][$i]['field_duration'] = [
      //   #'#placeholder' => t('Duration (Hours)'),
      //   '#options' => $hours,
      //   '#type' => 'select',
      //   #'#required' => TRUE,
      //   '#default_value' =>'',
      //   '#prefix' => '<div class="box niceselect duration">',
      //   '#suffix' => '</div></div>',
      // ];

     $form['resident'][$i]['field_duration'] = [
        '#placeholder' => t('Duration (Minutes)'),
        #'#options' => $hours,
        '#type' => 'textfield',
        #'#required' => TRUE,
        '#default_value' =>'',
        '#prefix' => '<div class="box niceselect duration">',
        '#suffix' => '</div></div>',
      ];


      $form['resident'][$i]['actions'] = [
        '#type' => 'actions',
      ];

        if ($i > 0) {
          $form['resident'][$i]['actions']['remove_item'] = [
            '#type' => 'submit',
            '#value' => Markup::create('<i class="fas fa-trash"></i>'),
            '#name' => 'resident_remove_' . $i,
            '#submit' => ['::removeRenter'],
            // Since we are removing a name, don't validate until later.
            '#limit_validation_errors' => [],
            '#ajax' => [
              'callback' => '::renterAjaxCallback',
              'wrapper'  => 'resident-details',
            ],
            '#attributes' => [
              'class' => ['delete_item_plx']
            ]
          ];
        }
    }

    
    $form['resident']['actions'] = [
      '#type' => 'actions',
    ];

    $form['resident']['actions']['add_item'] = [
      '#type' => 'submit',
      '#value' => Markup::create('<p><i class="fa fa-plus"></i>Add another<p>'),
      '#submit' => ['::addRenter'],
      '#limit_validation_errors' => [],
      '#ajax' => [
        'callback' => '::renterAjaxCallback',
        'wrapper' => 'resident-details',
        'disable-refocus' => TRUE
      ],
      '#attributes' => [
        'class' => ['add_item_plx']
      ],
      '#prefix' => '',
      '#suffix' => '</div>
        </div>'
    ];



    $cat_vid = 'categories';
    $cat_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($cat_vid);    
    $cat_arr = [];
    foreach ($cat_terms as $cat_term) {
     $cat_arr[$cat_term->tid] = $cat_term->name;
    }
    $form['categories'] = [
        '#type' => 'select',
        '#options' => $cat_arr,
        '#placeholder' => t('categories'),
        #'#required' => TRUE,
         '#multiple' => TRUE,
        '#default_value' => $cat_target_id,
       '#prefix' => '<div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Categories</h3>
                       <div class="items_div">',
        '#suffix' => '</div></div>',
        '#attributes' => array('class' => 'tokenize-remote-demo1'),
    ];

    /*
    *Venue Start from here
    */

    $venue_state = isset($form_state_values['venue_state'])?$form_state_values['venue_state']:'AZ';

    $venue_state_op = $this->getStates();
    // $form['venue_state'] = array(
    //   '#type' => 'select',
    //   '#options' => $venue_state_op,
    //   '#default_value' => $field_venue_state_assess,
    //   #'#attributes' => array('class' => array('full-width-inp')),
    //   '#prefix' => '<div class="athlete_left schedule_plx venue_plx">
    //                   <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Location (Cities)</h3>
    //                  <div class="items_div"><div class="box niceselect duration">',
    //    '#suffix' => '</div>',
    //  # '#default_value' => isset($athlete_uni['athlete_uni_type'])?$athlete_uni['athlete_uni_type']:'school',
    //   '#ajax' => [
    //     'callback' => '::VenueLocationAjaxCallback', // don't forget :: when calling a class method.
    //     'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
    //     'event' => 'change',
    //     'wrapper' => 'edit-output-2', // This element is updated with this AJAX callback.
    //   ]
    //   );

      
      // $form['venue_loaction'] = [
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Location Name'),
      //     '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
      //     '#autocomplete_route_parameters' => array('field_name' => $venue_state, 'count' => 10), 
      //     '#prefix' => '<div id="edit-output-2" class="org-3">',
      //     '#suffix' => '</div></div></div>',
      //     '#default_value' => $field_venue_location_assess,
      // ];

       $form['venue_state'] = [
        '#type' => 'select',
        '#options' => $venue_state_op,
        '#default_value' => $field_venue_state_assess,
        '#prefix' => '<div class="athlete_left schedule_plx venue_plx">
                      <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Location (Cities)</h3>
                     <div class="items_div"><div class="box niceselect duration">',
       '#suffix' => '</div>',
        '#placeholder' => t('State'),
        '#required' => TRUE,
        '#ajax' => [
          'callback' => '::VenueAjaxCallback', // don't forget :: when calling a class method.
          //'callback' => [$this, 'myAjaxCallback'], //alternative notation
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output-222', // This element is updated with this AJAX callback. 
        ]
      ];
    $form['venue_loaction'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),  
        '#required' => TRUE,  
        '#default_value' => $field_venue_location_assess,
        '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
        '#autocomplete_route_parameters' => array('field_name' => $venue_state, 'count' => 10), 
        '#prefix' => '<div id="edit-output-222" class="org-3">',
        '#suffix' => '</div></div></div>',
      ];
    /*
    *Venue end from here
    */
    $tags_vid = 'tags';
    $tags_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($tags_vid);    
    $tags_arr = [];
    foreach ($tags_terms as $tags_term) {
     $tags_arr[$tags_term->tid] = $tags_term->name;
    }
    $form['tags'] = [
        '#type' => 'select',
        '#options' => $tags_arr,
        '#placeholder' => t('tags'),
        #'#required' => TRUE,
         '#multiple' => TRUE,
        '#default_value' => $tags_target_id,
       '#prefix' => '<div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Tags</h3>
                       <div class="items_div">',
        '#suffix' => '</div></div>',
        '#attributes' => array('class' => 'tokenize-remote-demo1'),
    ];

    $form['left_section_end'] = [
      '#type' => 'markup',
      '#markup' => '
      </div><!--LEFT SECTION END-->',
    ];


    $form['actions'] = [
      '#type' => 'actions',
    ];

   $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'SAVE ALL CHANGES',
      '#prefix' => '<div class="bfss_save_all save_all_changes">',
      '#suffix' => '</div>'
     
    ];
    return $form;
    }else{
      $form['left_section_end'] = [
        '#type' => 'markup',
        '#markup' => '<p>You can not edit.</p>',
      ];
      return $form;
    }
  }



  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $data=[];
    if(!empty($form_state->getValues('resident')['resident'])){
               
               foreach($form_state->getValues('resident')['resident'] as $values) {   
                $date = new DrupalDateTime($values['field_date'].$values['field_time']);
                if(!empty($values['field_duration']) && !empty($values['field_time']) && !empty($values['field_date'])){
                  $data[] = [
                      'field_duration' => $values['field_duration']*60,
                      'field_timing' => strtotime($date->format('Y-m-d h:i:sa')),
                    ]; 
                }   
               }
    }

    if(!empty($form_state->getValue('update'))){
      foreach($form_state->getValue('update') as $values) {   
                $date = new DrupalDateTime($values['field_date'].$values['field_time']);
                if(!empty($values['field_duration']) && !empty($values['field_time']) && !empty($values['field_date'])){
                  $data[] = [
                      #'field_duration' => $values['field_duration']*60,
                     'field_duration' => $values['field_duration'],
                      'field_timing' => strtotime($date->format('Y-m-d h:i:sa')),
                    ]; 
                }   
      }
    }


      $paragraph_items = []; 
      foreach($data as $ar){
        $paragraph = Paragraph::create([
          'type' => 'assessment_schedules',
          'field_duration' => $ar['field_duration'],
          'field_timing' => $ar['field_timing'],
        ]);
        $paragraph->save();
        $paragraph_items[] = [
            'target_id' => $paragraph->id(),
            'target_revision_id' => $paragraph->getRevisionId(),
        ];
      }
      $param = \Drupal::request()->query->all();
      if(isset($param['nid'])){

        $node = Node::load($param['nid']);

        $node->set('field_schedules', $paragraph_items);
        //aditional user info 
        $node->title->value = $form_state->getValue('title');

        $node->body->value = $form_state->getValue('body')['value'];
        $node->body->format = $form_state->getValue('body')['format'];

        $node->field_location->value = $form_state->getValue('location');
        $node->field_address_1_us->value = $form_state->getValue('address_1');
        $node->field_address_2_us->value = $form_state->getValue('address_2');
        $node->field_city_us->value = $form_state->getValue('city');
        $node->field_state_us->value = $form_state->getValue('state');
        $node->field_zip_us->value = $form_state->getValue('zip');


        $node->field_type_of_assessment->value = $form_state->getValue('type');

        if(!empty($form_state->getValue('categories'))){
          foreach ($form_state->getValue('categories') as $key => $target_id) {
            $node->field_categories[] = ['target_id' => $target_id];
          }
        }
        
        if(!empty($form_state->getValue('tags'))){
          foreach ($form_state->getValue('tags') as $key => $target_id) {
            $node->field_event_tags[] = ['target_id' => $target_id];
          }
        }
        //venue 
        $node->field_venue_state_assess->value = $form_state->getValue('venue_state');
        $node->field_venue_location_assess->value = $form_state->getValue('venue_loaction');
        //$node->field_image[] = ['target_id' => $img_id, 'alt'=> 'img'];
        $node->save();
        drupal_set_message(t('<p class="bfss-success-msg">Successfully updated Assessment.</p>'), 'success');
      }    
        
  }

  /**
   * Ajax Callback for the form.
   *
   * @param array $form
   *   The form being passed in
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of form.
   *
   * @return array
   *   The form element we are changing via ajax
   */
  function renterAjaxCallback(&$form, FormStateInterface $form_state) {
    return $form['resident'];
  }

  /**
   * Functionality for our ajax callback.
   *
   * @param array $form
   *   The form being passed in
   * @param array $form_state
   *   The form state, passed by reference so we can modify
   */
  function addRenter(&$form, FormStateInterface $form_state) {
    $this->residentCount++;
    $form_state->setRebuild();
  }

  /**
   * Functionality for our ajax callback.
   *
   * @param array $form
   *   The form being passed in
   * @param array $form_state
   *   The form state, passed by reference so we can modify
   */
  function removeRenter(&$form, FormStateInterface $form_state) {
    // Get the triggering element
    $triggering_element = $form_state->getTriggeringElement();

    // Remove the clicked resident group
    if ($triggering_element) {
      if ($triggering_element['#name'] != 'op') {
        $button_name = $triggering_element['#name'];
        $button_name = explode('_', $button_name);

        $userInput = $form_state->getUserInput();
        unset($userInput['resident'][$button_name[2]]);

        $userInput['resident'] = array_values($userInput['resident']);
        $userInput = $form_state->setUserInput($userInput);
      }
    }

    $this->residentCount--;
    $form_state->setRebuild();
  }

      function getStates() {
        return $states=array(
        ''=> t('Select State'),
        'AL'=> t('AL'),
        'AK'=> t('AK'),
        'AZ'=> t('AZ'),
        'AR'=> t('AR'),
        'CA'=> t('CA'),
        'CO'=> t('CO'),
        'CT'=> t('CT'),
        'DE'=> t('DE'),
        'DC'=> t('DC'),
        'FL'=> t('FL'),
        'GA'=> t('GA'),
        'HI'=> t('HI'),
        'ID'=> t('ID'),
        'IL'=> t('IL'),
        'IN'=> t('IN'),
        'IA'=> t('IA'),
        'KS'=> t('KS'),
        'KY'=> t('KY'),
        'LA'=> t('LA'),
        'ME'=> t('ME'),
        'MT'=> t('MT'),
        'NE'=> t('NE'),
        'NV'=> t('NV'),
        'NH'=> t('NH'),
        'NJ'=> t('NJ'),
        'NM'=> t('NM'),
        'NY'=> t('NY'),
        'NC'=> t('NC'),
        'ND'=> t('ND'),
        'OH'=> t('OH'),
        'OR'=> t('OR'),
        'MD'=> t('MD'),
        'MA'=> t('MA'),
        'MI'=> t('MI'),
        'MN'=> t('MN'),
        'MS'=> t('MS'),
        'MO'=> t('MO'),
        'PA'=> t('PA'),
        'RI'=> t('RI'),
        'SC'=> t('SC'),
        'SD'=> t('SD'),
        'TN'=> t('TN'),
        'TX'=> t('TX'),
        'UT'=> t('UT'),
        'VT'=> t('VT'),
        'VA'=> t('VA'),
        'WA'=> t('WA'),
        'WV'=> t('WV'),
        'WI'=> t('WI'),
        'WY'=> t('WY'));
      }

      public function gettime(){
          $start_time = "05:00:00";
          $end_time = "23:00:00";
          $time_op=[];
          while(strtotime($start_time) < strtotime($end_time)){
             $start_time = date("H:i:s", strtotime("$start_time +30 minutes"));
             $time_op[$start_time] = date("H:i A", strtotime($start_time));
          }//end while
          return $time_op;
      }

     

       public function StateAjaxCallback(array &$form, FormStateInterface $form_state) {
        return $form['city']; 
      }

       public function VenueAjaxCallback(array &$form, FormStateInterface $form_state) {
        return $form['venue_loaction']; 
      }
}