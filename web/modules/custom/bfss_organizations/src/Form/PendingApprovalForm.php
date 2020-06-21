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
 * Class PendingApprovalForm.
 */
class PendingApprovalForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pending_organizations_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'bfss_organizations');
    //$query->condition('field_user_role', 'coach', 'CONTAINS');
    $query->condition('status', 0, '=');
    $nids = $query->execute();

    // print_r($nids);
    // die;
    $form['#tree'] = TRUE;
    $form['#prefix'] = '<div class="main_section_plx">';
    $form['#suffix'] = '</div>';
   

    $form['left_section_start'] = [
            '#type' => 'markup',
            '#markup' => '<div class="left_section popup_left_section">',
    ];
    if(!empty($nids)){
    	
	    $form['resident'] = [
	      '#type' => 'container',
	      '#attributes' => ['id' => 'resident-details'],
	      '#prefix' => '',
	      '#suffix' =>'',
	    ];

     
  
        foreach ($nids as $i => $nid) {
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
        
            $form['resident'][$i]['left_start'] = [
                '#type' => 'markup',
                '#markup' => ' <div class="athlete_left">
                                  <h3><div class="toggle_icon">
                                      <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
                                    </div>ORGANIZATION APPROVAL
                                  </h3>
                            <div class="items_div">',
            ];   
            // $form['resident'][$i] = [
            //   '#type' => 'fieldgroup',
            //   '#title' => 'ORGANIZATION APPROVE',
            //   // '#attributes' => ['id' => 'edit-resident'],
            // ];

            $form['resident'][$i]['organization_name'] = [
              '#type' => 'textfield',
              '#placeholder' => t('Organization Name'),
              #'#title' => $this->t('Organization Name'),
              #'#required' => TRUE,
              '#default_value' => $field_organization_name,
              '#attributes' => array('readonly' => 'readonly'),
            ];


            $form['resident'][$i]['city'] = [
              '#type' => 'textfield',
             '#placeholder' => t('City'),
              #'#required' => TRUE,
              '#default_value' => $field_city,
              '#attributes' => array('readonly' => 'readonly'),
            ];
            $states = $this->get_state();
            $form['resident'][$i]['state'] = [
              '#placeholder' => t('State'),
              '#type' => 'select',
              # '#required' => TRUE,
              '#options' => $states,
              '#default_value' => $field_state,
               '#attributes' => array('readonly' => 'readonly'),
            ];
        

            $types = ['' => 'Type', 'school' => 'School', 'club' => 'Club'];
            $form['resident'][$i]['type'] = [
                '#placeholder' => t('Type'),
                '#type' => 'textfield',
                #'#required' => TRUE,
                '#default_value' => $field_type,
                '#attributes' => array('readonly' => 'readonly'),
            ];

            $url_approve = "/approve-organization-popup?nid=".$nid;
            $url_edit = "/edit-organization-popup?nid=".$nid;
            $form['resident'][$i]['html_links'] = array(
             '#type' => 'markup',
             '#markup' => '<div class="edit-approve-btn"><p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-approve-org&quot;}" data-dialog-type="modal" href="'.$url_approve.'">APPROVE</a></p><p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-edit-org&quot;}" data-dialog-type="modal" href="'.$url_edit.'">EDIT</a></p></div>',
            );

              $form['resident'][$i]['left_end'] = [
                '#type' => 'markup',
                '#markup' => '</div>
                </div>',
            ]; 
          }
        }
    }else{
      $form['no_pending'] = [
      '#type' => 'markup',
      '#markup' => ' <div class="athlete_left">
                                  <h3><div class="toggle_icon">
                                      <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
                                    </div>NO PENDING
                                  </h3>
                            <div class="items_div"><p class="no_pennding_organization">No organization pending!</p>
                            </div>
                            </div>',
    ];
    }
   $form['left_section_end'] = [
      '#type' => 'markup',
      '#markup' => '</div>
      <div class="right_section"><!--RIGHT SECTION START-->
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
       
      if(empty($form_state_values)){
        $VNS = 'AZ';
      }else{
        $VNS = $form_state_values['venue_state'];
      }
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

    $form['right_section_end'] = [
      '#type' => 'markup',
      '#markup' => '</div>
        </div>
      </div><!--RIGHT SECTION END-->',
    ];
    // $form['actions'] = [
    //   '#type' => 'actions',
    // ];

    // $form['actions']['submit'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('Save'),
    //   '#attributes' => [
    //     'class' => ['btn button--primary'],
    //   ]
    // ];
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