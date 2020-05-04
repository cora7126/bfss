<?php
/**
 * @file
 * Contains \Drupal\bfss_coach\Form\ContributeForm.
 */

namespace Drupal\bfss_coach\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use \Drupal\user\Entity\User;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Contribute form.
 */
class EditCoachUserProfile extends FormBase {


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
     return 'edit_coach_form_popup';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $current_user = \Drupal::currentUser()->id();
    $roles_user = \Drupal::currentUser()->getRoles();


    $query18 = \Drupal::database()->select('mydata', 'md');
    $query18->fields('md');
    $query18->condition('uid', $current_user, '=');
    $results18 = $query18->execute()->fetchAssoc();
  
    $conn = Database::getConnection();
    $query1 = \Drupal::database()->select('user__field_first_name', 'ufln');
        $query1->addField('ufln', 'field_first_name_value');
        $query1->condition('entity_id', $current_user,'=');
        $results1 = $query1->execute()->fetchAssoc(); 
  $query2 = \Drupal::database()->select('user__field_last_name', 'ufln2');
        $query2->addField('ufln2', 'field_last_name_value');
        $query2->condition('entity_id', $current_user,'=');
        $results2 = $query2->execute()->fetchAssoc();
  $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
        $query3->addField('ufln3', 'field_date_value');
        $query3->condition('entity_id', $current_user,'=');
        $results3 = $query3->execute()->fetchAssoc();
  $query4 = \Drupal::database()->select('users_field_data', 'ufln4');
        $query4->fields('ufln4');
        $query4->condition('uid', $current_user,'=');
        $results4 = $query4->execute()->fetchAssoc();
  $query5 = \Drupal::database()->select('user__field_mobile', 'ufm');
        $query5->addField('ufm', 'field_mobile_value');
        $query5->condition('entity_id', $current_user,'=');
        $results5 = $query5->execute()->fetchAssoc();
  $query6 = \Drupal::database()->select('user__field_mobile_2', 'ufm2');
        $query6->addField('ufm2', 'field_mobile_2_value');
        $query6->condition('entity_id', $current_user,'=');
        $results6 = $query6->execute()->fetchAssoc();
  $query_img = \Drupal::database()->select('user__user_picture', 'n');
        $query_img->addField('n', 'user_picture_target_id');
        $query_img->condition('entity_id', $current_user,'=');
        $results = $query_img->execute()->fetchAssoc();
  $img_id = $results['user_picture_target_id'];
        $file = File::load($img_id);
    if(!empty($file)){
      $url = $file->url();
    }

    $fname=$results1['field_first_name_value'];


    $form['#prefix'] = '<div class="edit-coach-profile-form-main main_section_plx"> 
    <div class="modal" id="edit-coach-profile-form-modal"><!--popupstart-->
      <div class="modal-dialog">
        <div class="modal-content">  
          <!-- Modal body -->
          <div class="modal-body"><p class="wlcm_cont">Welcome '.$fname.', to continue you must complete all the required fields below.<p>';
    $form['suffix'] = ' </div>
      </div>
    </div>
  </div><!--popupend-->
  </div>';

  
      
    $form['#tree'] = TRUE;
    $form['fname'] = array(
    '#type' => 'textfield',
    '#prefix' => '<div class="left_section popup_left_section athlete_left">
                  <div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>COACHES INFORMATION</h3>
                  <div class=items_div>',
    '#default_value' => $results1['field_first_name_value'],
    '#attributes' => array('disabled'=>true),
    );
    $form['lname'] = array(
      '#type' => 'textfield',
      '#default_value' => $results2['field_last_name_value'],
    '#attributes' => array('disabled'=>true),
      );

    
      $form['numberone'] = array(
          '#type' => 'textfield',
          '#placeholder' => 'Phone Number',
          #'#required' => TRUE,
           '#default_value' => $results5['field_mobile_value'],
        ); 
    
    

   
      $states = $this->getStates();
      $form['az'] = array(
      '#type' => 'select',
      '#options'=>$states,
      '#default_value' => $city,
        );
      $form['city'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('City (Organization)'),
        '#default_value' => $results18['field_city'],
      );
      $gender_arr =  array('' => 'Select Gender','male' => 'Male','female' => 'Female','other' => 'Other');
      $form['sextype'] = array(
      '#type' => 'textfield',
      '#suffix' => '</div></div>',
      '#placeholder' => t("Your Athlete's Gender"),
      '#options' => $gender_arr,
      '#default_value' => $results18['field_birth_gender'],
      '#suffix' => '</div>
      </div>
     ',
      );

        /*
*ORGANIZATION SECTION END
*/

     $form['left_section_start'] = [
      '#type' => 'markup',
      '#markup' => '<div class="lft_sect">',
    ];
    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
    ];
     for ($i = 0; $i <= $this->residentCount; $i++) {
        $form['resident'][$i] = [
         '#type' => 'container',
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

      $form['resident'][$i]['sport'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        #'#title' => $this->t('Organization Name'),
        '#required' => TRUE,
        '#default_value' => '',
      ];

      $form['resident'][$i]['coach_title'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Coach Title'),
        #'#title' => $this->t('Organization Name'),
        '#required' => TRUE,
        '#default_value' => '',
      ];

      $form['resident'][$i]['year'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Year'),
        #'#title' => $this->t('Organization Name'),
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
      '#value' => Markup::create('<p><i class="fa fa-plus"></i>Add another organization<p>'),
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
      '#suffix' => '</div>'
    ];
/*
*ORGANIZATION SECTION END
*/ 
    
 $form['submit'] = [
      '#type' => 'submit', 
      '#value' => 'FINISH', 
      '#prefix' => '<div id="athlete_submit">
                          ',
      '#suffix' => '</div></div><!--LEFT SECTION END-->',
    ];

 $form['instagram_account'] = [
  '#type' => 'textfield',
  '#placeholder' => t('TEAM Instagram Account(Optional)'),
  '#default_value' => isset($results18['field_instagram'])?$results18['field_instagram']:'',
  '#prefix' => '<div class="right_section">
                  <div class = "athlete_right">
                    <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SCHOOL/TEAM SCOCIAL MEDIA</h3>
                      <div class=items_div>',
  ];


  $form['youtube_account'] = [
    '#type' => 'textfield',
    '#placeholder' => t('TEAM Youtube/Video Channel(Optional)'),
    '#default_value' => isset($results18['field_youtube'])?$results18['field_youtube']:'',
    '#suffix' => '</div>
      </div>
    </div>',
  ];



    /* Organization END */

   
    return $form;
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


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
   // if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
   //      $form_state->setErrorByName('email', $this->t('Please enter a valid email.'));
   //  }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser()->id();
    $roles_user = \Drupal::currentUser()->getRoles();
    $conn = Database::getConnection();


    /*
    *ORGANIZATION SAVE START
    */
     $data=[];
     foreach($form_state->getValues('resident')['resident'] as $values) {   
      if(!empty($values['organization_name'])){
        $data[] = [
            'type' => $values['type'],
            'organization_name' => $values['organization_name'],
            'sport' => $values['sport'],
            'coach_title' => $values['coach_title'],
            'year' => $values['year'],
            'city' => isset($form_state->getValues()['city'])?$form_state->getValues()['city']:'',
            'az' =>isset($form_state->getValues()['az'])?$form_state->getValues()['az']:'',
            // 'organization_name' => $values['organization_name'],
            // 'type' => $values['type'],
          ]; 
      }    
     }

      foreach ($data as $key => $value) {
        $node = Node::create([
               'type' => 'bfss_organizations',
        ]);
        $node->field_type->value = $value['type'];
        $node->field_organization_name->value = $value['organization_name'];
        $node->field_sport->value = $value['sport'];
        $node->field_coach_title->value = $value['coach_title'];
        $node->field_year->value = $value['year'];
        $node->field_user_role->value = isset($role)?$role:'';
        $node->field_organization_name->value = $value['organization_name'];
        $node->field_type->value = $value['type'];
        $node->title->value = $value['type'].'-'.$value['organization_name'];
        $node->field_city->value = $value['city'];
        $node->field_state->value = $value['az'];
        $node->setPublished(FALSE);
        $node->save();
      }
    /*
    *ORGANIZATION SAVE END
    */

        
   
    //mydata
    $query_mydata = \Drupal::database()->select('mydata', 'md');
    $query_mydata->fields('md');
    $query_mydata->condition('uid', $current_user, '=');
    $results_mydata = $query_mydata->execute()->fetchAll();

    if (empty($results_mydata)) {
        $conn->insert('mydata')->fields(array(
          'uid' => $current_user,
          'field_az' => $form_state->getValue('az'),
          'field_city' => $form_state->getValue('city'),
          'field_birth_gender' => $form_state->getValue('sextype'),
          'field_instagram' => $form_state->getValue('instagram_account'),
          'field_youtube' => $form_state->getValue('youtube_account'),
          ))->execute();
      } else {
        $conn->update('mydata')->condition('uid', $current_user, '=')->fields(array(
          'field_az' => $form_state->getValue('az'),
          'field_city' => $form_state->getValue('city'),
          'field_birth_gender' => $form_state->getValue('sextype'),
          'field_instagram' => $form_state->getValue('instagram_account'),
          'field_youtube' => $form_state->getValue('youtube_account'),
          ))->execute();
      } 
 
    
    //mobile field
    $query = \Drupal::database()->select('user__field_mobile', 'ufm');
    $query->fields('ufm');
    $query->condition('entity_id', $current_user,'=');
    $results = $query->execute()->fetchAll();     
    if(empty($results)){
    $conn->insert('user__field_mobile')->fields(
            array(
            'entity_id' => $current_user,
            'field_mobile_value' => $form_state->getValue('numberone'),
            'bundle' => 'user',
            'deleted' => '0',
            'revision_id' => $current_user,
            'langcode' => 'en',
            'delta' => '0',
            )
    )->execute();
    }else{
        $conn->update('user__field_mobile')
        ->condition('entity_id',$current_user,'=')
        ->fields([
          'field_mobile_value' => $form_state->getValue('numberone'),
        ])
        ->execute();
    }

    //first name 
    $conn->update('user__field_first_name')
    ->condition('entity_id',$current_user,'=')
    ->fields([
      'field_first_name_value' => $form_state->getValue('fname'),
    ])
    ->execute();

    //email
    $conn->update('users_field_data')
    ->condition('uid',$current_user,'=')
    ->fields([
      'mail' => $form_state->getValue('email'),
    ])
    ->execute();

    
     $form_state->setRedirect('acme_hello');
  }


  public function getStates() {
      return $st=array(
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
          'KS'=>  t('KS'),
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
            'ND'=>t('ND'),
           'OH'=> t('OH'),
            'OR'=>t('OR'),
           'MD'=> t('MD'),
           'MA'=> t('MA'),
           'MI'=> t('MI'),
            'MN'=>t('MN'),
            'MS'=>t('MS'),
           'MO'=> t('MO'),
           'PA'=> t('PA'),
           'RI'=> t('RI'),
           'SC'=> t('SC'),
            'SD'=>t('SD'),
           'TN'=> t('TN'),
            'TX'=>  t('TX'),
             'UT'=> t('UT'),
            'VT'=>  t('VT'),
            'VA'=>  t('VA'),
             'WA'=> t('WA'),
             'WV'=> t('WV'),
            'WI'=>  t('WI'),
            'WY'=>  t('WY'));
    }
}
?>
