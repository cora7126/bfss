<?php

namespace Drupal\edit_form\Form;

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
use  \Drupal\user\Entity\User;
/**
 * Class ParentEditForm.
 */
class ParentEditForm extends FormBase {

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
    return 'parent_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $session = $this->getRequest()->getSession();

    $form['#tree'] = TRUE;
    $form['#prefix'] = '<div class="main_section_plx">';
    $form['#suffix'] = '</div>';
    $form['#attached']['library'][] = 'bfss_admin/bfss_admin_autocomplete_lib';       //here can add


    $form['left_section_start'] = [
      '#type' => 'markup',
      '#markup' => '<div class="left_section">',
    ];
    $crr_uid = \Drupal::currentUser()->id();
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'parent_guardian');
    $query->condition('field_ath_uid',$crr_uid, '=');

    $nids = $query->execute();
    $nids = array_values($nids);

    if(isset($nids[0])){
      $node = Node::load($nids[0]);
      $target_ids = array_column($node->field_parent_guardian->getValue(), 'target_id');

      if(is_array($target_ids) && !empty($target_ids)){  
          foreach ($target_ids as $j => $target_id) {
            $paragraph = Paragraph::load($target_id);
            $field_first_name = $paragraph->field_first_name->value;
            $field_last_name = $paragraph->field_last_name->value;
            $field_cell_phone = $paragraph->field_cell_phone->value;
            $field_home_phone = $paragraph->field_home_phone->value;
            if(!empty($field_first_name) || !empty($field_last_name) || !empty($field_cell_phone) || !empty($field_home_phone)){
               $form['update'][$j] = [
                '#type' => 'container',
                '#attributes' => [
                         'class' => [
                                    'accommodation',
                          ],
                ],
                '#prefix' => '
                                <div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN <i  tid="'.$target_id.'" class="athlete-parent fa fa-trash right-icon " aria-hidden="true"></i></h3><div class="items_div" style="">',
                '#suffix' => '</div>
                      </div>
                 '
              ];

               $form['update'][$j]['fname1'] = [
                '#placeholder' => t('First Name'),
                '#type' => 'textfield',
                '#default_value' => $field_first_name,
              ];


              $form['update'][$j]['lname1'] = [
                '#placeholder' => t('Last Name'),
                '#type' => 'textfield',
                '#default_value' => $field_last_name,

              ];

              $form['update'][$j]['cellphone1'] = [
                '#type' => 'textfield',
                '#placeholder' => t('Cell Phone'),
                '#default_value' =>  $field_cell_phone,
                '#prefix' => '',
                '#suffix' => '',
              ];

              $form['update'][$j]['homephone1'] = [
                '#type' => 'textfield',
                '#placeholder' => t('Home Phone'),
                '#default_value' => $field_home_phone,
              ];

              $form['update'][$j]['target_id'] = [
                '#type' => 'hidden',
                '#value' => $target_id,
              ];
            }
            //FOR UPDATE OLD ENTIES [END FROM HERE]
          }
      }
    }
   

    


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
        '#prefix' => '<div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN ( ADD NEW - '.($i+1).' )</h3><div class="items_div" style="">',
        '#suffix' => '</div>
              </div>
         '
      ];

      $form['resident'][$i]['fname1'] = [
        '#placeholder' => t('First Name'),
        '#type' => 'textfield',
        '#default_value' => '',
      ];


      $form['resident'][$i]['lname1'] = [
        '#placeholder' => t('Last Name'),
        '#type' => 'textfield',
        '#default_value' => '',

      ];

      $form['resident'][$i]['cellphone1'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Cell Phone'),
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '',
      ];

      $form['resident'][$i]['homephone1'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Home Phone'),
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
      '#markup' => '<div class="right_section"><div><p>No longer need your Parent / Guardian on your account and want to remove them? <br> You can request Parent / Guardian removal from your account via our ticketing system.</p></div>',
    ];

    $form['right_section_end'] = [
      '#type' => 'markup',
      '#markup' => '</div><!--RIGHT SECTION END-->',
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
    $crr_uid = \Drupal::currentUser()->id();
    $user = User::load($crr_uid);
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'parent_guardian');
    $query->condition('field_ath_uid',$crr_uid, '=');
    $nids = $query->execute();

    if(empty($nids)){
      if(!empty($form_state->getValues('resident')['resident'])){
          $paragraph_items = []; 
          foreach($form_state->getValues('resident')['resident'] as $ar){
            $paragraph = Paragraph::create([
              'type' => 'parent_guardian',
              'field_first_name' => $ar['fname1'],
              'field_last_name' => $ar['lname1'],
              'field_cell_phone' => $ar['cellphone1'],
              'field_home_phone' => $ar['homephone1'],
            ]);

            $paragraph->save();
            $paragraph_items[] = [
                'target_id' => $paragraph->id(),
                'target_revision_id' => $paragraph->getRevisionId(),
            ];
          }
            $node = Node::create([
                         'type' => 'parent_guardian',
            ]);
            //$node->field_organizations->appendItem($paragraph);
            $node->set('field_parent_guardian', $paragraph_items);
            $node->title->value = $user->field_first_name->value.' '.$user->field_last_name->value.'-'.$crr_uid;
            $node->field_ath_uid->value = $crr_uid;
            $node->save();
      }
    }else{
      //else code here
      $nid = array_values($nids);
      $node = Node::load($nid[0]);
      $paragraph_items = []; 
      if(!empty($form_state->getValues('resident')['resident'])){
        foreach($form_state->getValues('resident')['resident'] as $ar){
          if(isset($ar['fname1']) || isset($ar['lname1'])|| isset($ar['cellphone1'])|| isset($ar['homephone1'])){
             $paragraph = Paragraph::create([
              'type' => 'parent_guardian',
              'field_first_name' => $ar['fname1'],
              'field_last_name' => $ar['lname1'],
              'field_cell_phone' => $ar['cellphone1'],
              'field_home_phone' => $ar['homephone1'],
            ]);

            $paragraph->save();
            $paragraph_items[] = [
                'target_id' => $paragraph->id(),
                'target_revision_id' => $paragraph->getRevisionId(),
            ];
          }
        
        }

                 
      }
      
      if(!empty($form_state->getValues('update')['update'])){
        foreach($form_state->getValues('update')['update'] as $ar){
          $paragraph_up = Paragraph::load($ar['target_id']);

          $paragraph_up->field_first_name->value = $ar['fname1'];
          $paragraph_up->field_last_name->value = $ar['lname1'];
          $paragraph_up->field_cell_phone->value = $ar['cellphone1'];
          $paragraph_up->field_home_phone->value = $ar['homephone1'];

          $paragraph_items[] = [
                'target_id' => $paragraph_up->id(),
                'target_revision_id' => $paragraph_up->getRevisionId(),
            ];
          $paragraph_up->save();
        }
      }  
      $node->set('field_parent_guardian', $paragraph_items);  
      $node->title->value = $user->field_first_name->value.' '.$user->field_last_name->value.'-'.$crr_uid;
      $node->field_ath_uid->value = $crr_uid;
      $node->save();
     
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


 
}