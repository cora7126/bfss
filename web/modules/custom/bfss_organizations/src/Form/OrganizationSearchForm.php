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
 * Class OrganizationSearchForm.
 */
class OrganizationSearchForm extends FormBase {

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
    return 'organization_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $session = $this->getRequest()->getSession();

    $states = $this->get_state();
    $form['search_state'] = [
      '#type' => 'select',
      '#placeholder' => t('State'),
      '#options' => $states,
      '#ajax' => [
        'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
        //'callback' => [$this, 'myAjaxCallback'], //alternative notation
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'change',
        'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
        // 'progress' => [
        //   'type' => 'throbber',
        //   'message' => $this->t('Verifying entry...'),
        // ],
      ]
    ];

    $form['search_org'] = [
      '#placeholder' => t('Search'),
      '#type' => 'textarea', 
      '#default_value' => '',
       '#rows' => 4,
      '#cols' => 5,
      '#prefix' => '<div id="edit-output" class="orgtextarea">',
      '#suffix' => '</div>',
    ];


    $form['orgNames_search'] = [
      '#placeholder' => t('Search'),
      '#type' => 'textfield', 
      // '#default_value' => '',
      //  '#rows' => 4,
      // '#cols' => 5,
       '#attributes' => [
        'class' => ['orgNames_searchs'],
      ],
      '#prefix' => '<div id="orgNames_search" class="orgNames_search">',
      '#suffix' => '</div>',
    ];


    $form['#attached']['library'][] = 'bfss_organizations/add_organization';
    return $form;
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