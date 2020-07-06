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
use Drupal\Core\Ajax\HtmlCommand;
/**
 * Class EditOrganizationPopup.
 */
class EditOrganizationPopup extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'edit_organization_popup';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();
    $nid = $param['nid'];
    //Permissions
   $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
   $rel = $permissions_service->bfss_admin_permissions();
   $pending_approval =  unserialize($rel['pending_approval']);
  
  if($pending_approval['edit']==1 || $pending_approval['admin']==1){
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
          }
          
              $form['left_section_start'] = [
                  '#type' => 'markup',
                  '#markup' => '<div class="left_section popup_left_section">
                    <div class="athlete_left">
                                    <h3><div class="toggle_icon">
                                        <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
                                      </div>EDIT ORGANIZATION
                                    </h3>
                              <div class="items_div">',
              ];

              
              $form['message'] = [
                  '#type' => 'markup',
                  '#markup' => '<div class="result_message"></div>',
              ];

              $form['organization_name'] = [
                '#type' => 'textfield',
                '#placeholder' => t('Organization Name'),
                #'#title' => $this->t('Organization Name'),
                '#required' => TRUE,
                '#default_value' => $field_organization_name,
              ];

              $form_state_values = $form_state->getValues();
              $state_name = isset($form_state_values['state'])?$form_state_values['state']:'AZ';

              $states = $this->get_state();
              $form['state'] = [
                '#placeholder' => t('State'),
                '#type' => 'select',
                '#required' => TRUE,
                '#options' => $states,
                '#default_value' => $field_state,
                 '#ajax' => [
                    'progress' => array('type' => 'none'),
                    'callback' => '::StateAjaxCallback', // don't forget :: when calling a class method.
                    //'callback' => [$this, 'myAjaxCallback'], //alternative notation
                    'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
                    'event' => 'change',
                    'wrapper' => 'edit-output-221', // This element is updated with this AJAX callback. 
                  ]
              ];
               
               $form['city'] = [
                '#type' => 'textfield',
                '#placeholder' => t('City'),
                '#required' => TRUE,
                '#default_value' => $field_city,
                '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
                '#autocomplete_route_parameters' => array('field_name' => $state_name, 'count' => 10), 
                '#prefix' => '<div id="edit-output-221" class="full-width-inp">',
                '#suffix' => '</div>',
              ];

              $types = ['' => 'Type', 'school' => 'School', 'club' => 'Club', 'university' => 'University'];
              $form['type'] = [
                  '#placeholder' => t('Type'),
                  '#type' => 'select',
                  '#required' => TRUE,
                  '#options' => $types,
                  '#default_value' => $field_type,
              ];

          $form['actions'] = [
            '#type' => 'actions',
          ];
           
           // $form['actions']['submit'] = [
           //          '#type' => 'submit',
           //          '#value' => $this->t(strtoupper("update")),
           //          '#button_type' => 'primary',
           //        ];
           $form['detail_text'] = [
                  '#type' => 'markup',
                  '#markup' => '<p class="detail_text_update_org">Please review edits to make sure there are no spellings errors and confirm a search of the<br/>
system has been performed before updating this organization.</p>',
          ];

           $form['actions']['submit'] = [
                    '#type' => 'submit',
                    '#value' => $this->t(strtoupper("update")),
                    '#button_type' => 'primary',
                     '#ajax' => [
                        'callback' => '::submitForm', // don't forget :: when calling a class method.
                        //'callback' => [$this, 'myAjaxCallback'], //alternative notation
                        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
                        'event' => 'click',
                        'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
                        'progress' => [
                          'type' => 'throbber',
                          'message' => $this->t('Verifying entry...'),
                        ],
                      ]
          ];
           $form['left_section_end'] = [
                  '#type' => 'markup',
                  '#markup' => '</div>
                  </div></div>',
          ];


    }else{
      $form['access_message'] = [ //for custom message "like: ajax msgs"
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

      $param = \Drupal::request()->query->all();
      $nid = $param['nid'];
      $data = $form_state->getValues();
      $organization_name =  $form_state->getValue('organization_name');
      $city = $form_state->getValue('city');
      $state = $form_state->getValue('state');
      $type = $form_state->getValue('type');
      if(isset($nid)){
          if (empty($organization_name)) {
              $message = '<p style="color:red;">"Organization Name" Required.</p>';
          }
          elseif(empty($city)){
            $message = '<p style="color:red;">"City" Required.</p>';
          }
          elseif(empty($state)){
            $message = '<p style="color:red;">"State" Required.</p>';
          }
          elseif(empty($type)){
            $message = '<p style="color:red;">"Type" Required.</p>';
          }else{
             if(isset($nid)){
                $node = Node::load($nid);
                $node->field_city->value = isset($city) ? $city : '';
                $node->field_state->value = isset($state) ? $state : '' ;
                $node->field_organization_name->value = isset($organization_name) ? $organization_name : '';
                $node->field_type->value = isset($type) ? $type : '' ;
                $node->save();
                $message = 'Successfully Updated!';
            }
          }
      }

   
    // for success message show
   
    $response = new AjaxResponse();
    $response->addCommand(
    new HtmlCommand(
      '.result_message',
      '<div class="success_message">'.$message.'</div>'
    )
    );
    return $response;   
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

      public function StateAjaxCallback(array &$form, FormStateInterface $form_state) {
        return $form['city']; 
      }
}