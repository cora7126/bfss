<?php

namespace Drupal\bfss_admin\Form;

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
/**
 * Class ManagerAssessorAccount.
 */
class ManagerAssessorAccount extends FormBase {

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
    return 'manager_assessor_account';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $session = $this->getRequest()->getSession();
    $states = $this->get_state();
    $form['#tree'] = TRUE;

    $form['#prefix'] = '<div class="main_section_plx">';
    $form['#suffix'] = '</div>';
   $form['#attached']['library'][] = 'bfss_admin/bfss_admin_autocomplete_lib';       //here can add
    
  
    
    $form['left_section_start'] = [
      '#type' => 'markup',
      '#markup' => '<div class="left_section">
                      ',
    ];

    $Title1 = "MANAGER'S INFORMATION";
    $form['first_name'] = [
        '#type' => 'textfield',
        '#placeholder' => t('First Name'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '<div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>'.$Title1.'</h3>
                       <div class="items_div" style="">',
        '#suffix' => '',
    ];

    $form['last_name'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Last Name'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '',
    ];
    
    $form['manager_email'] = [
        '#type' => 'email',
        '#placeholder' => t('Email'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '',
    ];

    $form['phone'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Phone'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '',
    ];

    $form['address_1'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Address 1'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '',
    ];

    $form['address_2'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Address 2'),
        #'#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '',
    ];

    // $form['manager_city'] = [
    //     '#type' => 'textfield',
    //     '#placeholder' => t('City'),
    //     '#required' => TRUE,
    //     '#default_value' => '',
    //     '#prefix' => '',
    //     '#suffix' => '',
    // ];
     $form_state_values = $form_state->getValues();
     $state_name = isset($form_state_values['state'])?$form_state_values['state']:'AZ';
     
     $form['manager_state'] = [
          '#type' => 'select',
          '#options' => $states,
           '#default_value' => '',
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
       $form['manager_city'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),  
        '#default_value' => '',
        '#required' => TRUE,  
        '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
        '#autocomplete_route_parameters' => array('field_name' => $state_name, 'count' => 10), 
        '#prefix' => '<div id="edit-output-22" class="full-width-inp">',
        '#suffix' => '</div>',
      ];
  
    // $form['manager_state'] = [
    //      '#placeholder' => t('State'),
    //     '#type' => 'select',
    //      '#required' => TRUE,
    //     '#options' => $states,
    //     '#default_value' => '',
    // ];

    $form['zip'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Zip'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '</div>
        </div>',
    ];



    $Title2 = "MANAGER'S COVERAGE AREA";
    $form['coverage_zone_name'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Coverage Zone Name'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '<div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>'.$Title2.'</h3>
                       <div class="items_div" style="">',
        '#suffix' => '',
    ];

    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
      '#prefix' => '',
      '#suffix' => '',
    ];

    for ($i = 0; $i <= $this->residentCount; $i++) {
      
      $form['resident'][$i]['cover_area_state'] = [
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

      $form['resident'][$i]['cover_area_city'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '</div>',
        '#attributes' => [
              'class' => ['cover_area_city'],
              'id' => ['cover_area_city-'.$i]
            ]
      ];

      // $form['resident'][$i]['test_city'] = [
      //   '#type' => 'textfield',
      //   '#placeholder' => t('Test City'),
      //   '#required' => TRUE,
      //   '#default_value' => '',
      //   '#attributes' => [
      //         'class' => ['auto1']
      //       ]
      // ];


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


    $Title3 = "ASSOCIATED ASSESSOR'S ACCOUNT";
    $form['username'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Username'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '<div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>'.$Title3.'</h3>
                       <div class="items_div" style="">',
        '#suffix' => '',
    ];

    
    $form['associated_email'] = [
        '#type' => 'email',
        '#placeholder' => t('Email'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '</div>
        </div>',
    ];

    $form['left_section_end'] = [
      '#type' => 'markup',
      '#markup' => '</div><!--LEFT SECTION END-->',
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

    $first_name = $form_state->getValue('first_name');
    $last_name = $form_state->getValue('last_name');
    $manager_email = $form_state->getValue('manager_email');
    $phone = $form_state->getValue('phone');
    $address_1 = $form_state->getValue('address_1');
    $address_2 = $form_state->getValue('address_2');
    $manager_city = $form_state->getValue('manager_city');
    $manager_state = $form_state->getValue('manager_state');
    $zip = $form_state->getValue('zip');
    $coverage_zone_name = $form_state->getValue('coverage_zone_name');
    
    $username = $form_state->getValue('username');
    $associated_email = $form_state->getValue('associated_email');
    $username = $first_name.rand();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $password = $this->random_password(8);
    $user = \Drupal\user\Entity\User::create();
    $user->setPassword($password);
    $user->enforceIsNew();
    $user->setEmail($manager_email);
    $user->setUsername($username);//This username must be unique and accept only a-Z,0-9, - _ @ .
    $user->set('field_first_name',$first_name);
    $user->set('field_last_name',$first_name);
    $user->set('field_mobile',$phone);
    $user->set('field_state',$manager_state);
    $user->set("init", $manager_email);
    $user->set("langcode", $language);
    $user->set("preferred_langcode", $language);
    $user->set("preferred_admin_langcode", $language);
    $user->addRole('bfss_manager');
    $user->activate();
    //Save user account
    $user->save();
    $uid = $user->id();

    if(!empty($form_state->getValues('resident')['resident'])){
                $data=[];
             foreach($form_state->getValues('resident')['resident'] as $values) {   
              if(!empty($values['cover_area_state'])){
                $data[] = [
                    'cover_area_state' => $values['cover_area_state'],
                    'cover_area_city' => $values['cover_area_city'],
                  ]; 
              }    
             }
            

            $paragraph_items = []; 
            foreach($data as $ar){
              $paragraph = Paragraph::create([
                'type' => 'manager_cover_area',
                'field_coverarea_city' => $ar['cover_area_city'],
                'field_coverarea_state' => $ar['cover_area_state'],
              ]);
              $paragraph->save();
              $paragraph_items[] = [
                  'target_id' => $paragraph->id(),
                  'target_revision_id' => $paragraph->getRevisionId(),
              ];
            }

            $node = Node::create([
                         'type' => 'manager_cover_area',
            ]);

            $node->set('field_coverage_z', $coverage_zone_name);
            $node->set('field_manager_cover_area', $paragraph_items);

            //aditional user info 
            $node->set('field_associated_assess_username', $username);
            $node->set('field_associated_assess_email', $associated_email);
            $node->set('field_city_manager', $manager_city);
            $node->set('field_zip_code', $zip);
            $node->set('field_a', $address_1);
            $node->set('field_address__2', $address_2);
            $node->field_user_manger[] = ['target_id' => $uid];
            $node->title->value = '(Manager : )'.$username.'-'.$associated_email;
            $node->save();

            _user_mail_notify('register_no_approval_required', $user);

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
  function random_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
  }
    public function StateAjaxCallback(array &$form, FormStateInterface $form_state) {
        return $form['manager_city']; 
      }

}