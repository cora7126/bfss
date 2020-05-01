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
 * Class EditOrganizations.
 */
class EditOrganizations extends FormBase {

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
    return 'edit_organizations_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $session = $this->getRequest()->getSession();
    $param = \Drupal::request()->query->all();
    // print_r($param);
    // die;


     //Permissions
     $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
     $rel = $permissions_service->bfss_admin_permissions();
     $Organizations_permissions =  unserialize($rel['Organizations']);
     if($Organizations_permissions['edit']==1 || $Organizations_permissions['admin']==1){
        if( $param['nids'] != '[]'){
        $query_nids = !empty(json_decode($param['nids'])) ? json_decode($param['nids']) : '';

        $form['#tree'] = TRUE;

        $form['loader-container'] = [
          '#type' => 'container',
          '#attributes' => [
            'id' => 'loader-container',
          ],
        ];

        $form['left_section_start'] = [
          '#type' => 'markup',
          '#markup' => '<div class="left_section">',
        ];
        $form['resident'] = [
          '#type' => 'container',
          '#attributes' => ['id' => 'resident-details'],
        ];

             foreach ($query_nids as $i => $nid) {
                if(isset($nid)){
                  $node = Node::load($nid);
                  $field_address_1 = $node->field_address_1->value;
                  $field_address_2 = $node->field_address_2->value;
                  $field_city = $node->field_city->value;
                  $field_state = $node->field_state->value;
                  $field_zip = $node->field_zip->value;
                  $field_organization_name = $node->field_organization_name->value;
                  $field_type = $node->field_type->value;
                  $title = $node->title->value;


                  // $form['resident'][$i] = [
                  //   '#type' => 'fieldgroup',
                  //   '#title' => 'Organization - '.$field_organization_name,
                  //   // '#attributes' => ['id' => 'edit-resident'],
                  // ];

                  $states = $this->get_state();
                  $form['resident'][$i]['state'] = [
                    '#placeholder' => t('State'),
                    '#type' => 'select',
                     '#required' => TRUE,
                    '#options' => $states,
                    '#default_value' => $field_state,
                    '#prefix' => '<div class="athlete_left">
                                    <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Organization - '.$field_organization_name.'</h3>
                                    <div class="items_div" style="">',
                     '#suffix' => ''
                  ];

                  $types = ['' => 'Type', 'school' => 'School', 'club' => 'Club', 'university' => 'University'];
                  $form['resident'][$i]['type'] = [
                    '#placeholder' => t('Type'),
                    '#type' => 'select',
                     '#required' => TRUE,
                    '#options' => $types,
                    '#default_value' => $field_type,
                  ];

                  $form['resident'][$i]['organization_name'] = [
                    '#type' => 'textfield',
                    '#placeholder' => t('Organization Name'),
                    #'#title' => $this->t('Organization Name'),
                    '#required' => TRUE,
                    '#default_value' => $field_organization_name,
                  ];

                  $form['resident'][$i]['address_1'] = [
                    '#type' => 'textfield',
                    '#placeholder' => t('Address 1'),
                    '#required' => TRUE,
                    '#default_value' => $field_address_1,
                  ];

                  $form['resident'][$i]['address_2'] = [
                    '#type' => 'textfield',
                    '#placeholder' => t('Address 2'),
                    '#required' => TRUE,
                    '#default_value' => $field_address_2,
                  ];

                  $form['resident'][$i]['city'] = [
                    '#type' => 'textfield',
                    '#placeholder' => t('City'),
                    '#required' => TRUE,
                    '#default_value' => $field_city,
                  ];

                  $form['resident'][$i]['zip'] = [
                    '#type' => 'textfield',
                    '#placeholder' => t('Zip'),
                    '#required' => TRUE,
                    '#default_value' => $field_zip,
                     '#suffix' => '</div></div>'
                  ];

                  $form['resident'][$i]['nid'] = [
                    '#type' => 'hidden',
                    '#default_value' => $nid,
                  ];

                  $form['resident'][$i]['actions'] = [
                    '#type' => 'actions',
                  ];
                }
              }
              // for ($i = 1; $i <= $count; $i++) {  

              // }

               $form['left_section_end'] = [
              '#type' => 'markup',
              '#markup' => '</div>',
               ];


                $form['actions'] = [
                  '#type' => 'actions',
                ];

                $form['actions']['submit'] = [
                  '#type' => 'submit',
                  '#value' => $this->t('Save'),
                  '#attributes' => [
                    'class' => ['save-button-plx'],
                  ]
                ];
          
          }else{
            $form['access_message'] = [ //for custom message "like: ajax msgs"
              '#type' => 'markup',
              '#markup' => '<p>we are sorry. you can not access this page.</p>',
            ];
          }
    }else{
        $form['access_message1'] = [ //for custom message "like: ajax msgs"
              '#type' => 'markup',
              '#markup' => '<p>we are sorry. you can not access this page.</p>',
            ];
    }
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
                    'nid' => $values['nid'],
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
          //    echo "<pre>";
          // print_r($data);
          // die;
            foreach ($data as $key => $value) {
              // print_r($value['nid']);
              // die;
             // if(isset($value['nids'])){
                $node = Node::load($value['nid']);
                $node->field_address_1->value = $value['address_1'];
                $node->field_address_2->value = $value['address_2'];
                $node->field_city->value = $value['city'];
                $node->field_state->value = $value['state'];
                $node->field_zip->value = $value['zip'];
                $node->field_organization_name->value = $value['organization_name'];
                $node->field_type->value = $value['type'];
                //$node->title->value = $value['type'].'-'.$value['organization_name'];
                //$node->setPublished(FALSE);
                $node->save();
             // }
              
            }
              
    }
        
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