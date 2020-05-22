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
/**
 * Class AddGroupAssessmentsForm.
 */
class AddGroupAssessmentsForm extends FormBase {

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
    return 'add_group_assessments';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $session = $this->getRequest()->getSession();

    $form['#tree'] = TRUE;

    $form['#prefix'] = '<div class="main_section_plx left_full_width_plx">';
    $form['#suffix'] = '</div>';
 
    
  
    
    $form['left_section_start'] = [
      '#type' => 'markup',
      '#markup' => '<div class="left_section">',
    ];

    
    $form['title'] = [
        '#type' => 'textfield',
        '#placeholder' => t('Assessment Title'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '<div class="athlete_left">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Assessment Informations</h3>
                       <div class="items_div" style="">',
        '#suffix' => '',
    ];


     $form['body'] = [
        '#type' => 'textarea',
        '#placeholder' => t('Assessment Body'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
    ]; 

    

   $types = [''=>'Select','group'=>'Group','private'=>'Private'];
    $form['type'] = [
        '#type' => 'select',
        '#options' => $types,
        '#placeholder' => t('Type of Assessment'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '',
    ];

  $form['location'] = [
        '#type' => 'textarea',
        '#placeholder' => t('Location'),
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        '#suffix' => '</div>
        </div><div class="athlete_left schedule_plx">
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SCHEDULES</h3>
                       <div class="items_div" style="">',
      ];
    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
      '#prefix' => '',
      '#suffix' => '',
    ];

    for ($i = 0; $i <= $this->residentCount; $i++) {
      //$states = $this->get_state();
      $form['resident'][$i]['field_duration'] = [
        '#placeholder' => t('Duration'),
        '#type' => 'number',
        '#required' => TRUE,
      ];

      $form['resident'][$i]['field_timing'] = [
        '#type' => 'datetime',
        '#placeholder' => t('Timing'),
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

    $form['image'] = [
      '#type' => 'managed_file',
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#upload_location' => 'public://',
      '#required' => FALSE,
      '#default_value' => '',
      '#prefix' => '<div class="imgupload">',
      '#suffix' => '</div>',
      '#attributes' => [
        'class' => ['imageuplode1']
      ],
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
      '#value' => $this->t('SAVE'),
      '#prefix' => ' <div id="athlete_submit" class="athlete_submit">',
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


  
    $img_id = isset($form_state->getValue('image')[0]) ? $form_state->getValue('image')[0] : '';
    // print_r($img_id);
    // die;
    if(!empty($form_state->getValues('resident')['resident'])){
                $data=[];
               foreach($form_state->getValues('resident')['resident'] as $values) {   
                if(!empty($values['field_timing'])){
                  $data[] = [
                      'field_duration' => $values['field_duration'],
                      'field_timing' => $values['field_timing']->getTimestamp(),
                    ]; 
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

            $node = Node::create([
                         'type' => 'assessment',
            ]);

            $node->set('field_schedules', $paragraph_items);
            //aditional user info 
            $node->title->value = $form_state->getValue('title');
            $node->body->value = $form_state->getValue('body');
            $node->field_location->value = $form_state->getValue('location');
            $node->field_type_of_assessment->value = $form_state->getValue('type');
            $node->field_image[] = ['target_id' => $img_id, 'alt'=> 'img'];
            $node->save();

            //drupal_set_message(t('Successfully inserted Assessment.'), 'success');

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