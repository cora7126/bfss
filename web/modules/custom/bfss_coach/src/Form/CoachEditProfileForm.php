<?php
/**
 * @file
 * Contains \Drupal\bfss_coach\Form\CoachEditProfileForm.
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
 * CoachEditProfileForm form.
 */
class CoachEditProfileForm extends FormBase {

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
     return 'coach_edit_profile_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $current_user = \Drupal::currentUser()->id();
    $userdt = User::load($current_user);
    $field_state =  $userdt->get('field_state')->value;
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



    $form['#tree'] = TRUE;

    $form['left_section_'] = [
      '#type' => 'markup',
      '#markup' => '<div class="left_section">',
    ];

    $form['username'] = [
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => $results4['name'],
    '#prefix'=>'<div class="lft_sect"> <!--left_section START -->
                  <div class="athlete_left">
                    <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Login Information</h3>
                    <div class=items_div>',
    '#attributes' => array('disabled'=>true),
    ];
   
    $hd_title = "COACHES&#39;s Information";
   
  $form['email'] = [
      '#type' => 'textfield',
      '#placeholder' => t('Email'),
      '#required' => TRUE,
      '#default_value' => $results4['mail'],
      '#attributes' => ['disabled'=>true],
      '#prefix' => '',
      '#suffix' => '<a class="change_pass" id="change_id" href="javascript:void(0)">Change Password</a>
      </div>
      </div>
      <div class="athlete_left">
        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>'.$hd_title.'</h3>
          <div class=items_div>',
      ];

      $form['fname'] = [
      '#type' => 'textfield',
      '#default_value' => $results1['field_first_name_value'],
      '#attributes' => ['disabled'=>true],
      ];
  

      $form['lname'] = [
        '#type' => 'textfield',
        '#default_value' => $results2['field_last_name_value'],
        '#attributes' => ['disabled'=>true],
      ];

      $form['numberone'] = [
        '#type' => 'textfield',
        '#placeholder' => 'Phone Number',
        '#default_value' => $results5['field_mobile_value'],
      ]; 

      $states = $this->getStates();
      $form['az'] = [
        '#type' => 'select',
        '#options'=>$states,
        '#default_value' => $results18['field_az'],
        '#required' => TRUE,
      ];

      $form['city'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),
        '#required' => TRUE,
        '#default_value' => $results18['field_city'],
      ];

      $gender_arr =  ['' => 'Select Gender','male' => 'Male','female' => 'Female','other' => 'Other'];
      $form['sextype'] = [
        '#type' => 'select',
        '#suffix' => '</div>
        </div>
        </div><!--left_section END -->',
        '#options' => $gender_arr,
        '#required' => TRUE,
        '#default_value' => $results18['field_birth_gender'],
      ];

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
      '#suffix' => '</div><!--LEFT SECTION END-->'
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'SAVE', 
      '#prefix' => '<div id="athlete_submit" class="athlete_submit">',
      '#suffix' => '</div>',
    ];

/*
*ORGANIZATION SECTION END
*/


  $form['left_section_1'] = [
      '#type' => 'markup',
      '#markup' => '</div>',
    ];

/*
*RIGHT SECTION
*/
$form['html_image_athlete_start'] = [
  '#type' => 'markup',
  '#markup' => '<div class="right_section">
    <div class="athlete_right">
          <h3><div class="toggle_icon">
              <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
            </div>My Website Photo
          </h3>
          <div class="edit_dropdown">
              <a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a>
              <ul class="dropdown-menu" style="padding:0"></ul>
          </div>
      <div class=items_div>',
];

 $form['image_athlete'] = [
    '#type' => 'managed_file',
    '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        //'file_validate_size' => [25600000], 
    ],
    '#theme' => 'image_widget', 
    '#preview_image_style' => 'medium', 
    '#upload_location' => 'public://',
    '#required' => false,
    '#default_value' => array($img_id),
    '#prefix' => '',
    '#suffix' => '<div class="action_bttn">
                      <span>Action</span><ul><li>Remove</li></ul>
                  </div>',
    ];

  $form['instagram_account'] = array(
  '#type' => 'textfield',
  '#placeholder' => t('TEAM Instagram Account(Optional)'),
  '#default_value' => isset($results18['field_instagram'])?$results18['field_instagram']:'',

  '#prefix' => '</div>
  </div>
  <div class = "athlete_right">
                    <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SCHOOL/TEAM SCOCIAL MEDIA</h3>
                  <div class=items_div>',
  );


  $form['youtube_account'] = array(
    '#type' => 'textfield',
    '#placeholder' => t('TEAM Youtube/Video Channel(Optional)'),
    '#default_value' => isset($results18['field_youtube'])?$results18['field_youtube']:'',
    '#suffix' => '</div>
    </div>',
  );


$form['html_image_athlete_end'] = [
  '#type' => 'markup',
  '#markup' => '
 ',
];
//   $form['image_athlete'] = [
//     '#type' => 'managed_file',
//     '#upload_validators' => [
//         'file_validate_extensions' => ['gif png jpg jpeg'],
//         //'file_validate_size' => [25600000], 
//     ],
//     '#theme' => 'image_widget', 
//     '#preview_image_style' => 'medium', 
//     '#upload_location' => 'public://',
//     '#required' => false,
//     '#default_value' => array($img_id),
//     '#prefix' => '',
//     '#suffix' => '<div class="action_bttn">
//                     <span>Action</span><ul><li>Remove</li></ul>
//                   </div>
//               </div>
//         </div>
//     </div>',
//     ];

// $form['html_image_athlete'] = [
//   '#type' => 'markup',
//   '#markup' => '</div>
//   <div class ="right_section">
//     <div class = "athlete_right">
//           <h3><div class="toggle_icon">
//               <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
//             </div>My Website Photo
//           </h3>
//           <div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul>
//           </div>
//       <div class=items_div>',
// ];
  // $form['instagram_account'] = array(
  // '#type' => 'textfield',
  // '#placeholder' => t('TEAM Instagram Account(Optional)'),
  // '#default_value' => isset($results_bfss_coach[0]->field_instagram)?$results_bfss_coach[0]->field_instagram:'',

  // '#prefix' => '</div>
  // </div></div>
  // <div class = "athlete_right">
  // <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SCHOOL/TEAM SCOCIAL MEDIA</h3>
  // <div class=items_div>',
  // );


  // $form['youtube_account'] = array(
  //   '#type' => 'textfield',
  //   '#placeholder' => t('TEAM Youtube/Video Channel(Optional)'),
  //   '#default_value' => isset($results_bfss_coach[0]->field_youtube)?$results_bfss_coach[0]->field_youtube:'',
  // );
   





 //CHANGE PASSWORD FIELDS
     $form['pass_label'] = array(
      '#type' => 'label',
      '#value' => t('Your password must be at least 8 characters long and contain at least one number and one character'),
      '#prefix' => '<div class="athlete_right">
      <div id="changepassdiv" class="changePassword_popup">
                      <div class="popup_header change_password_header">
                          <h3>Change Password <i class="fa fa-times right-icon changepassdiv-modal-close spb_close" aria-hidden="true"></i></h3>
                      </div>',
      );
    $form['current_pass'] = array(
      '#type' => 'password',
      '#placeholder' => t('Old Password'),
      );
    $form['newpass'] = array(
      '#type' => 'password',
      '#placeholder' => t('New Password'),
      );
    $form['newpassconfirm'] = array(
      '#type' => 'password',
      '#placeholder' => t('Confirm New Password'),
      );
    
    $form['pass_error'] = array(
      '#type' => 'label',
      '#value' => t('Incorrect entry,please try again.'),
      '#suffix' => '<span class="passerror"> Need more help? Click here </span>',
      );
    $form['changebutton'] = [
        '#type' => 'label',
        '#title' => 'update',
    '#prefix' =>'',
    '#suffix' => '</div>
      </div>
     </div><!--right section end-->',
    '#attributes' => array('id'=>'save_pass','style'=>'cursor:pointer; background:green;padding: 5px;
    border-radius: 3px;'),
    ];

    //end change password
   $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'SAVE', 
      '#prefix' => '<div id="athlete_submit">',
      '#suffix' => '</div>',
    ];
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
   if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
        $form_state->setErrorByName('email', $this->t('Please enter a valid email.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // echo "<pre>";
    // print_r($form_state->getValues()['city']);
    // die;
    $uid = \Drupal::currentUser()->id();
    $user = \Drupal\user\Entity\User::load($uid);
    $roles = $user->getRoles();
    if(in_array('coach', $roles)){
      $role = 'coach';
    }else{
      $role = '';
    } 
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
    $current_user = \Drupal::currentUser()->id();
    $roles_user = \Drupal::currentUser()->getRoles();
    $conn = Database::getConnection();

    //user profile 
    $query_pic = \Drupal::database()->select('user__user_picture', 'uup');
    $query_pic->fields('uup');
    $query_pic->condition('entity_id', $current_user,'=');
    $results_pic = $query_pic->execute()->fetchAll(); 
    $imgid = $form_state->getValue('image_athlete');
        if(empty($results_pic)){
          if(isset($imgid[0])){
           $conn->insert('user__user_picture')->fields(
              array(
              'entity_id' => $current_user,
              'bundle' => 'user',
              'deleted' => '0',
              'revision_id' => $current_user,
              'langcode' => 'en',
              'delta' => '0',
              'user_picture_target_id' => $imgid[0],
              )
          )->execute();
          }
        }else {
          if(!empty($imgid[0])){
            $conn->update('user__user_picture')
            ->condition('entity_id',$current_user,'=')
            ->fields(
              array(
              'user_picture_target_id' => $imgid[0],
              )
            )
            ->execute();
          }else{
            $conn->update('user__user_picture')
            ->condition('entity_id',$current_user,'=')
            ->fields(
              array(
              'user_picture_target_id' => '240',
              )
            )
            ->execute();
          }
                
      }

    //joining date  

       if(in_array('coach', $roles_user) || in_array('assessors', $roles_user)){
         // for assessors or coach here
        }else{
             $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
          $query3->addField('ufln3', 'field_date_value');
          $query3->condition('entity_id', $current_user, '=');
          $results3 = $query3->execute()->fetchAssoc();
          $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
            if (empty($results3)) {
            
                $conn->insert('user__field_date')->fields(array(
                'entity_id' => $current_user,
                'bundle' => 'user',
                'revision_id' => $current_user,
                'delta' => 0,
                'langcode' => $lang_code,
                'field_date_value' => $form_state->getValue('date_joined'),
                ))->execute();
            }
            else {
                $conn->update('user__field_date')->condition('entity_id', $current_user, '=')->fields(array(
                'field_date_value' => $form_state->getValue('date_joined'),
                ))->execute();
            }
        }

    if(!in_array('coach', $roles_user) || !in_array('assessors', $roles_user)){

      
      }


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
      // if(in_array('coach', $roles_user)){
      //   //org
      // $query_bfss_coach = \Drupal::database()->select('bfss_coach', 'bc');
      // $query_bfss_coach->fields('bc');
      // $query_bfss_coach->condition('coach_uid',$current_user, '=');
      // $results_bfss_coach = $query_bfss_coach->execute()->fetchAll();
      // $arrfields = array(
      //         //one
      //         'coach_uid' => $current_user,
      //         'field_organization_type_one' => !empty($form_state->getValue('organizationType1'))?$form_state->getValue('organizationType1'):'',
      //         'field_organization_name_one' => !empty($form_state->getValue('organizationName1'))?$form_state->getValue('organizationName1'):'',
      //         'field_coach_title_one' => !empty($form_state->getValue('coachtitle1'))?$form_state->getValue('coachtitle1'):'',
      //         'field_sport_one' => !empty($form_state->getValue('sport1'))?$form_state->getValue('sport1'):'',
      //         'field_year_one' => !empty($form_state->getValue('year1'))?$form_state->getValue('year1'):'',
      //         //two
      //         #'coach_uid' => $current_user,
      //         'field_organization_type_two' => !empty($form_state->getValue('organizationType2'))?$form_state->getValue('organizationType2'):'',
      //         'field_organization_name_two' => !empty($form_state->getValue('organizationName2'))?$form_state->getValue('organizationName2'):'',
      //         'field_coach_title_two' => !empty($form_state->getValue('coachtitle2'))?$form_state->getValue('coachtitle2'):'',
      //         'field_sport_two' => !empty($form_state->getValue('sport2'))?$form_state->getValue('sport2'):'',
      //         'field_year_two' => !empty($form_state->getValue('year2'))?$form_state->getValue('year2'):'',
      //         //three
      //         #'coach_uid' => $current_user,
      //         'field_organization_type_three' => !empty($form_state->getValue('organizationType3'))?$form_state->getValue('organizationType3'):'',
      //         'field_organization_name_three' => !empty($form_state->getValue('organizationName3'))?$form_state->getValue('organizationName3'):'',
      //         'field_coach_title_three' => !empty($form_state->getValue('coachtitle3'))?$form_state->getValue('coachtitle3'):'',
      //         'field_sport_three' => !empty($form_state->getValue('sport3'))?$form_state->getValue('sport3'):'',
      //         'field_year_three' => !empty($form_state->getValue('year3'))?$form_state->getValue('year3'):'',
      //         //social media
      //         'field_instagram' => !empty($form_state->getValue('instagram_account'))?$form_state->getValue('instagram_account'):'',
      //         'field_youtube' => !empty($form_state->getValue('youtube_account'))?$form_state->getValue('youtube_account'):'',
      //     );
      // if(empty($results_bfss_coach)){
      //     $conn->insert('bfss_coach')
      //     ->fields($arrfields)
      //     ->execute();
      // }else{
      //   $conn->update('bfss_coach')
      //     ->condition('coach_uid',$current_user,'=')
      //     ->fields($arrfields)
      //     ->execute();
      // }
      // }
    
    //mobile field

    $query = \Drupal::database()->select('user__field_mobile', 'ufm');
    $query->fields('ufm');
    $query->condition('entity_id', $current_user,'=');
    $results = $query->execute()->fetchAll();   


   if(in_array('coach', $roles_user) || in_array('assessors', $roles_user)){
     // for assessors or coach here
    }else{  
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
  }

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
