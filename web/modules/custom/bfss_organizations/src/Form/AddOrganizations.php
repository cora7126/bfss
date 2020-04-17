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

    $form['#tree'] = TRUE;
    #$form['#attached']['library'][] = 'renter_landlord_reference/request_form';

    // $form['#attributes']['class'][] = 'card';

    $form['loader-container'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'loader-container',
      ],
    ];

    // $form['loader-container']['loader'] = [
    //   '#markup' => '<div class="ajax-progress ajax-progress-throbber"><div class="throbber">&nbsp;<h1>Please wait</h1></div></div>',
    // ];



    
    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
    ];

    for ($i = 0; $i <= $this->residentCount; $i++) {
      $form['resident'][$i] = [
        '#type' => 'fieldgroup',
        '#title' => $this->t('ADD NEW ORGANIZATION'),
        // '#attributes' => ['id' => 'edit-resident'],
      ];

     

      $states = $this->get_state();
      $form['resident'][$i]['state'] = [
        '#placeholder' => t('State'),
        '#type' => 'select',
         '#required' => TRUE,
        '#options' => $states,
        '#default_value' => '',
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

      $form['resident'][$i]['city'] = [
        '#type' => 'textfield',
        '#placeholder' => t('City'),
        #'#title' => $this->t('City'),
        '#required' => TRUE,
        '#default_value' => '',
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
          '#value' => $this->t('Remove Organization'),
          '#name' => 'resident_remove_' . $i,
          '#submit' => ['::removeRenter'],
          // Since we are removing a name, don't validate until later.
          '#limit_validation_errors' => [],
          '#ajax' => [
            'callback' => '::renterAjaxCallback',
            'wrapper'  => 'resident-details',
          ],
          '#attributes' => [
            'class' => ['btn btn-colored btn-raised btn-danger remove-resident-btn']
          ]
        ];
      }
    }

    $form['resident']['actions'] = [
      '#type' => 'actions',
    ];

    $form['resident']['actions']['add_item'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add another organization'),
      '#submit' => ['::addRenter'],
      '#limit_validation_errors' => [],
      '#ajax' => [
        'callback' => '::renterAjaxCallback',
        'wrapper' => 'resident-details',
        'disable-refocus' => TRUE
      ],
      '#attributes' => [
        'class' => ['btn btn-colored btn-raised add-resident-btn']
      ]
    ];


      $form['actions'] = [
        '#type' => 'actions',
      ];

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
        '#attributes' => [
          'class' => ['btn button--primary'],
        ]
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
              $node->setPublished(FALSE);
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

}