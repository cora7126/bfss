<?php

namespace Drupal\bfss_organizations\Form;

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
/**
 * Class AddOrganizations.
 */
class AddOrganizations extends FormBase {

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
    return 'add_organizations_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $session = $this->getRequest()->getSession();


  //Permissions
 $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
 $rel = $permissions_service->bfss_admin_permissions();
 $Organizations_permissions =  unserialize($rel['Organizations']);
 if($Organizations_permissions['create']==1 || $Organizations_permissions['admin']==1){
    $form['#tree'] = TRUE;
    #$form['#attached']['library'][] = 'renter_landlord_reference/request_form';

    // $form['#attributes']['class'][] = 'card';
    $form['#prefix'] = '<div class="main_section_plx">';
    $form['#suffix'] = '</div>';
    $form['#attached']['library'][] = 'bfss_admin/bfss_admin_autocomplete_lib';       //here can add
  

    // $form['loader-container']['loader'] = [
    //   '#markup' => '<div class="ajax-progress ajax-progress-throbber"><div class="throbber">&nbsp;<h1>Please wait</h1></div></div>',
    // ];

    $form['left_section_start'] = [
      '#type' => 'markup',
      '#markup' => '<div class="left_section">',
    ];

    
    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
    ];

    for ($i = 0; $i <= $this->residentCount; $i++) {
      $form['resident'][$i] = [
       '#type' => 'container',
        #'#title' => $this->t('ADD NEW ORGANIZATION'),
        // '#attributes' => ['id' => 'edit-resident'],
        '#attributes' => [
                 'class' => [
                            'accommodation',
                  ],
        ],
        '#prefix' => '
                        <div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>ADD NEW ORGANIZATION</h3><div class="items_div" style="">',
        '#suffix' => '</div>
              </div>
         '
      ];

     



      $types = ['' => 'Type', 'school' => 'School', 'club' => 'Club','university' => 'University'];
      $form['resident'][$i]['type'] = [
        '#placeholder' => t('Type'),
        '#type' => 'select',
         '#required' => TRUE,
        '#options' => $types,
        '#default_value' => '',
      ];

      $form['resident'][$i]['organization_name'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Organization Name'),
        #'#title' => $this->t('Organization Name'),
        '#required' => TRUE,
        '#default_value' => '',
      ];

      $form['resident'][$i]['address_1'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Address 1'),
        #'#title' => $this->t('Address 1'),
        '#required' => TRUE,
        '#default_value' => '',
      ];

      $form['resident'][$i]['address_2'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Address 2'),
        #'#title' => $this->t('Address 2'),
        '#required' => TRUE,
        '#default_value' => '',
      ];

      $states = $this->get_state();
      $form['resident'][$i]['state'] = [
        '#placeholder' => t('State'),
        '#type' => 'select',
         '#required' => TRUE,
        '#options' => $states,
        '#default_value' => '',
        '#prefix' => '<div id="cover-area-state-'.$i.'" class="cover_area_state_wrapp">',
        '#suffix' => '',
        '#attributes' => [
              'class' => ['cover_area_state']
            ]
      ];

      $form['resident'][$i]['city'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),
        #'#title' => $this->t('City'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '</div>',
        '#attributes' => [
              'class' => ['cover_area_city'],
              'id' => ['cover_area_city-'.$i]
            ]
      ];

      $form['resident'][$i]['zip'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Zip'),
        #'#title' => $this->t('Zip'),
        '#required' => TRUE,
        '#default_value' => '',
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
      '#value' =>  Markup::create('<p><i class="fa fa-plus"></i>Add another organization<p>'),
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
      '#suffix' => '</div><!--LEFT SECTION END-->'
    ];

    $form['left_section_end'] = [
      '#type' => 'markup',
      '#markup' => '<div class="right_section"><!--RIGHT SECTION START-->
                      <div class="athlete_right">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>ORGANIZATION SEARCH</h3>
                        <div class="items_div" style="">
      ',
    ];

     $states = $this->get_state();
//   $form['search_state'] = [
//   '#type' => 'select',
//    '#placeholder' => t('State'),
//   '#options' => $states,
//   '#ajax' => [
//     'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
//     //'callback' => [$this, 'myAjaxCallback'], //alternative notation
//     'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
//     'event' => 'change',
//     'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
//     // 'progress' => [
//     //   'type' => 'throbber',
//     //   'message' => $this->t('Verifying entry...'),
//     // ],
//   ]
// ];

    // $form['search_org'] = [
    //   '#placeholder' => t('Search'),
    //   '#type' => 'textarea', 
    //   '#default_value' => '',
    //    '#rows' => 4,
    //   '#cols' => 5,
    //   '#prefix' => '<div id="edit-output" class="orgtextarea">',
    //   '#suffix' => '</div>',
    // ];


    // $form['orgNames_search'] = [
    //   '#placeholder' => t('Search'),
    //   '#type' => 'textfield', 
    //   // '#default_value' => '',
    //   //  '#rows' => 4,
    //   // '#cols' => 5,
    //    '#attributes' => [
    //     'class' => ['orgNames_searchs'],
    //   ],
    //   '#prefix' => '<div id="orgNames_search" class="orgNames_search">',
    //   '#suffix' => '</div>',
    // ];
     $form_state_values = $form_state->getValues();
       
      // if(empty($form_state_values)){
      //   $VNS = 'AZ';
      // }else{
        $VNS = !empty($form_state_values['venue_state'])?$form_state_values['venue_state']:'AZ';
      //}
       $form['venue_state'] = array(
        '#type' => 'select',
        '#options' => $states,
        '#default_value' => $state,
        '#ajax' => [
          'callback' => '::VenueLocationAjaxCallback', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output-22', // This element is updated with this AJAX callback.
        ]
        );

      $form['venue_loaction'] = [
            '#type' => 'textfield',
            '#placeholder' => t('Search'),
             '#default_value' => $results18['field_city'],
            '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
            '#autocomplete_route_parameters' => array('field_name' => $VNS, 'count' => 10), 
            '#prefix' => '<div id="edit-output-22" class="org-3">',
            '#suffix' => '</div>',
        ];

  // $out =  Markup::create('<input type="text" name="orgNames_search" id="orgNames_search" autocomplete="off" class="form-control orgNames_search">');
  //  $form['companyPicker'] = [
  // '#type' => 'markup',
  //     '#markup' => $out,
  //   ];

// $form['hidden_org_name'] = [
//   '#type' => 'textarea',
//   #'#title' => t('Address'),
//   '#rows' => 4,
//   '#cols' => 5,
//  // '#required' => TRUE,
// ];




 // $form['output'] = [
 //      '#type' => 'textfield',
 //      '#size' => '60',
 //      '#disabled' => TRUE,
 //      '#value' => 'Hello, Drupal!!1',      
 //      '#prefix' => '<div id="edit-output">',
 //      '#suffix' => '</div>',
 //    ];
     

    $form['right_section_end'] = [
      '#type' => 'markup',
      '#markup' => '</div>
        </div>
      </div><!--RIGHT SECTION END-->',
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
      $form['#attached']['library'][] = 'bfss_organizations/add_organization';
    }else{
      $form['access_message'] = [ //for custom message "like: ajax msgs"
              '#type' => 'markup',
              '#markup' => '<p>we are sorry. you can not access this page.</p>',
      ];
    }
    return $form;
  }

public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
  if ($selectedValue = $form_state->getValue('search_state')) {
      $selectedText = $form['search_state']['#options'][$selectedValue];
      $orgNames = $this->Get_Org_Name($selectedText);
      $form['search_org']['#value'] = $orgNames;
      //$a = $this->test();
  }
   
    $ajax_response = new AjaxResponse();
    $ajax_response->addCommand(new InvokeCommand(NULL, 'myTest', ['some Var']));
    
  return $form['search_org']; 
}


public function test(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $ajax_response->addCommand(new InvokeCommand(NULL, 'myTest', ['some Var']));
    return $ajax_response;
   }

  public function Get_Org_Name($state){
    if(isset($state)){
      $query = \Drupal::entityQuery('node');
      $query->condition('type', 'bfss_organizations');
      $query->condition('field_state', $state, 'IN');
      $nids = $query->execute();
      $org_name=[];
      foreach($nids as $nid){
        $node = Node::load($nid);
        $org_name[]= $node->field_organization_name->value;
      }
      $result = implode(",",$org_name);
    }
    return $result;
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
            if(!empty($form_state->getValues('resident')['resident'])){
                $data=[];
             foreach($form_state->getValues('resident')['resident'] as $values) {   
              if(!empty($values['organization_name'])){
                $data[] = [
                    'address_1' => $values['address_1'],
                    'address_2' => $values['address_2'],
                    'city' => $values['city'],
                    'state' => $values['state'],
                    'zip' => $values['zip'],
                    'organization_name' => $values['organization_name'],
                    'type' => $values['type'],
                  ]; 
              }
                 
             }
        
            foreach ($data as $key => $value) {
              $node = Node::create([
                     'type' => 'bfss_organizations',
              ]);
              $node->field_address_1->value = $value['address_1'];
              $node->field_address_2->value = $value['address_2'];
              $node->field_city->value = $value['city'];
              $node->field_state->value = $value['state'];
              $node->field_zip->value = $value['zip'];
              $node->field_organization_name->value = $value['organization_name'];
              $node->field_type->value = $value['type'];
              $node->title->value = $value['type'].'-'.$value['organization_name'];
              $node->setPublished(TRUE);
              $node->save();
            }
              
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

  public function get_state(){
    $states = [
        '' => 'State',
        'AL' => 'AL',
        'AK' => 'AK',
        'AS' => 'AS',
        'AZ' => 'AZ',
        'AR' => 'AR',
        'CA' => 'CA',
        'CO' => 'CO',
        'CT' => 'CT',
        'DE' => 'DE',
        'DC' => 'DC',
        'FM' => 'FM',
        'FL' => 'FL',
        'GA' => 'GA',
        'GU' => 'GU',
        'HI' => 'HI',
        'ID' => 'ID',
        'IL' => 'IL',
        'IN' => 'IN',
        'IA' => 'IA',
        'KS' => 'KS',
        'KY' => 'KY',
        'LA' => 'LA',
        'ME' => 'ME',
        'MH' => 'MH',
        'MD' => 'MD',
        'MA' => 'MA',
        'MI' => 'MI',
        'MN' => 'MN',
        'MS' => 'MS',
        'MO' => 'MO',
        'MT' => 'MT',
        'NE' => 'NE',
        'NV' => 'NV',
        'NH' => 'NH',
        'NJ' => 'NJ',
        'NM' => 'NM',
        'NY' => 'NY',
        'NC' => 'NC',
        'ND' => 'ND',
        'MP' => 'MP',
        'OH' => 'OH',
        'OK' => 'OK',
        'OR' => 'OR',
        'PW' => 'PW',
        'PA' => 'PA',
        'PR' => 'PR',
        'RI' => 'RI',
        'SC' => 'SC',
        'SD' => 'SD',
        'TN' => 'TN',
        'TX' => 'TX',
        'UT' => 'UT',
        'VT' => 'VT',
        'VI' => 'VI',
        'VA' => 'VA',
        'WA' => 'WA',
        'WV' => 'WV',
        'WI' => 'WI',
        'WY' => 'WY',
        'AE' => 'AE',
        'AA' => 'AA',
        'AP' => 'AP',
       ];
       return $states;
  }
    public function VenueLocationAjaxCallback(array &$form, FormStateInterface $form_state){
      return  $form['venue_loaction']; 
    }
}