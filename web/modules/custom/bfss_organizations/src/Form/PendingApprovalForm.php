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

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'bfss_organizations');
    $query->condition('status', 0, '=');
   # $query->condition('field_type_of_assessment','private', '=');
    $nids = $query->execute();
    $form['#tree'] = TRUE;

    $form['loader-container'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'loader-container',
      ],
    ];

    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
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
    
            
        $form['resident'][$i] = [
          '#type' => 'fieldgroup',
          '#title' => 'ORGANIZATION APPROVE',
          // '#attributes' => ['id' => 'edit-resident'],
        ];

        $form['resident'][$i]['organization_name'] = [
          '#type' => 'textfield',
          '#placeholder' => t('Organization Name'),
          #'#title' => $this->t('Organization Name'),
          #'#required' => TRUE,
          '#default_value' => $field_organization_name,
        ];


        $form['resident'][$i]['city'] = [
          '#type' => 'textfield',
         '#placeholder' => t('City'),
          #'#required' => TRUE,
          '#default_value' => $field_city,
        ];
        $states = $this->get_state();
        $form['resident'][$i]['state'] = [
          '#placeholder' => t('State'),
          '#type' => 'select',
          # '#required' => TRUE,
          '#options' => $states,
          '#default_value' => $field_state,
        ];
    

        $types = ['' => 'Type', 'school' => 'School', 'club' => 'Club'];
        $form['resident'][$i]['type'] = [
            '#placeholder' => t('Type'),
            '#type' => 'textfield',
            #'#required' => TRUE,
            '#default_value' => $field_type,
        ];

        $url_approve = "/approve-organization-popup";
        $url_edit = "/edit-organization-popup";
        $form['resident'][$i]['html_links'] = array(
         '#type' => 'markup',
         '#markup' => '<div><p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm&quot;}" data-dialog-type="modal" href="'.$url_approve.'">APPROVE</a></p><p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-assess-fm&quot;}" data-dialog-type="modal" href="'.$url_edit.'">EDIT</a></p></div>',
        );
      }
    }
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