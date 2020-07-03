<?php  
/**
 * @file
 * Contains \Drupal\bfss_assessment\Form\CTVfilter.
 */

namespace Drupal\bfss_assessment\Form;

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
use Drupal\Core\Ajax\RedirectCommand;

/**
 * Contribute form.
 */
class CTVfilter extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ctv_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;
    $current_path = \Drupal::service('path.current')->getPath();

    #Categories
    $vid = 'categories';
    $Categories = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    $cat_arr = array();
    foreach ($Categories as $Category) {
     $cat_arr[$Category->tid] = $Category->name;
    }
    $cat_arr = ['' => 'Categories'] + $cat_arr;

    $form['#prefix'] = '<div class="ctv_filter_main">';
    $form['#suffix'] = '</div>';

    $form['categories'] = [
      '#type' => 'select',
      '#options' => $cat_arr,
      '#default_value' => '',
      #'#multiple' => TRUE,
      '#prefix' => '<div class="cat_tags_wrpp"><div class="box niceselect"><span id="categories-flt">',
      '#suffix' => '</span></div>',
      '#ajax' => [
              'progress' => array('type' => 'none'),
              'callback' => '::CategoriesAjaxCallback', // don't forget :: when calling a class method.
              //'callback' => [$this, 'myAjaxCallback'], //alternative notation
              'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
              'event' => 'change',
              'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
        ],
      ];

    #Tags
    $vid = 'tags';
    $tags = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    $tags_arr = array();
    foreach ($tags as $tag) {
     $tags_arr[$tag->tid] = $tag->name;
    }
    $tags_arr = ['' => 'Tags'] + $tags_arr;
    $form['tags'] = [
      '#type' => 'select',
      '#options' => $tags_arr,
      '#default_value' => '',
      #'#multiple' => TRUE,
      '#prefix' => '<div class="box niceselect"><span id="tags-flt">',
      '#suffix' => '</span></div><div class="box niceselect"><p class="venue"><a data-toggle="modal" data-target="#StateCityFilter" >Venues </a></p></div></div>',
      '#ajax' => [
              'progress' => array('type' => 'none'),
              'callback' => '::TagsAjaxCallback', // don't forget :: when calling a class method.
              //'callback' => [$this, 'myAjaxCallback'], //alternative notation
              'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
              'event' => 'change',
              'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
        ],
      ];


    #Venue
    $form_state_values = $form_state->getValues();
    $stateName = isset($form_state_values['venue_state'])?$form_state_values['venue_state']:'AZ';
    $states = $this->getStates();

    $form['popup_start'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="StateCityFilter" class="customModal requestCallback sitepopup-default-bfss modal fade" role="dialog">
                    <div class="modal-dialog sitepopup-wrap">
                      <!-- Modal content-->
                      <div class="modal-content spb-popup-main-wrapper spb_top_center sitepopup-default-bfss-content">
                       <div class="spb-controls">
                                  <span class="closepopup close"  data-dismiss="modal">×</span>
                                </div>
                      <div class="popup_header change_password_header">
                                            <h3>Venues</h3>
                       </div>
                        <div class="modal-body success-msg">',
    );
 

    $form['venue_state'] = [
        '#type' => 'select',
        '#options' => $states,
        '#default_value' => $state,
        '#prefix' => '<div class="st_ct_wp1">',
      
        '#ajax' => [
          'progress' => array('type' => 'none'),
          'callback' => '::VenueLocationAjaxCallback', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'edit-output-22', // This element is updated with this AJAX callback.
        ]
    ];
    
    $form['venue_loaction'] = [
        '#type' => 'textfield',
        '#placeholder' => t('city'),
         '#default_value' => $results18['field_city'],
        '#autocomplete_route_name' => 'bfss_manager.get_location_autocomplete',
        '#autocomplete_route_parameters' => array('field_name' => $stateName, 'count' => 10), 
        '#prefix' => '<div id="edit-output-22" class="org-3">',
        '#suffix' => '</div>',
    ];

    // $form['actions'] = [
    //     '#type' => 'actions',
    // ];

     $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#suffix' => '</div>',
      '#ajax' => [
          'callback' => '::StateCityAjaxCallback',
          'disable-refocus' => FALSE, 
          'wrapper'  => 'filter-details',
          'event' => 'click',
        ],
    ];
 $form['popup_end'] = [
    '#type' => 'markup',
    '#markup' => ' </div>
                       
                      </div>
                    </div>
                  </div>
              ',
  ];

    return $form;
  }



  function StateCityAjaxCallback(&$form, FormStateInterface $form_state) {
    global $base_url;
    $current_path = \Drupal::service('path.current')->getPath();
    if (!empty($form_state->getValue('venue_state')) && !empty($form_state->getValue('venue_loaction'))) {
        $url = $base_url.$current_path.'?state='.$form_state->getValue('venue_state').'&city='.$form_state->getValue('venue_loaction');
        $response = new \Drupal\Core\Ajax\AjaxResponse();
        $response->addCommand(new RedirectCommand($url)); 
        return $response;
    }else{
      $message = ['#markup' => 'empty'];
      return $message;
    }
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

  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    
  }

  public function CategoriesAjaxCallback(array &$form, FormStateInterface $form_state){
    global $base_url;
    $current_path = \Drupal::service('path.current')->getPath();
     if ($selectedValue = $form_state->getValue('categories')) {
      $selectedText = $form['categories']['#options'][$selectedValue];
      $response = new \Drupal\Core\Ajax\AjaxResponse();
      $url = $base_url.$current_path.'?categories='.$form_state->getValue('categories');
      $response->addCommand(new RedirectCommand($url));  
    }
    return $response;
  }

  public function TagsAjaxCallback(array &$form, FormStateInterface $form_state){
      global $base_url;
      $current_path = \Drupal::service('path.current')->getPath();
     if ($selectedValue = $form_state->getValue('tags')) {
      $selectedText = $form['tags']['#options'][$selectedValue];
      $response = new \Drupal\Core\Ajax\AjaxResponse();
      $url = $base_url.$current_path.'?tags='.$form_state->getValue('tags');
      $response->addCommand(new RedirectCommand($url));  
    }
    return $response;
  }

  public function VenueLocationAjaxCallback(array &$form, FormStateInterface $form_state){
    return  $form['venue_loaction']; 
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
      'KS'=> t('KS'),
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
      'ND'=> t('ND'),
      'OH'=> t('OH'),
      'OR'=> t('OR'),
      'MD'=> t('MD'),
      'MA'=> t('MA'),
      'MI'=> t('MI'),
      'MN'=> t('MN'),
      'MS'=> t('MS'),
      'MO'=> t('MO'),
      'PA'=> t('PA'),
      'RI'=> t('RI'),
      'SC'=> t('SC'),
      'SD'=> t('SD'),
      'TN'=> t('TN'),
      'TX'=> t('TX'),
      'UT'=> t('UT'),
      'VT'=> t('VT'),
      'VA'=> t('VA'),
      'WA'=> t('WA'),
      'WV'=> t('WV'),
      'WI'=> t('WI'),
      'WY'=> t('WY'));
  }

}// class close 