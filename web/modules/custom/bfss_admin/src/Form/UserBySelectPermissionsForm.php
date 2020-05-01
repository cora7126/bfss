<?php
namespace Drupal\bfss_admin\Form;

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
 * Class UserBySelectPermissionsForm.
 */
class UserBySelectPermissionsForm extends FormBase {
 /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_by_select_permissions_form';
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();
    $role = '';
    if(isset($param['role'])){
      if($param['role'] == 'Athletes'){
        $role = 'athlete';
      }elseif($param['role'] == 'Athlete Guardian'){
          $role = 'parent_guardian_registering_athlete_';
      }elseif($param['role'] == 'Coaches'){
         $role = 'coach';
      }elseif($param['role'] == 'Assessor'){
         $role = 'assessors';
      }elseif($param['role'] == 'BFSS Manager'){
         $role = 'bfss_manager';
      }else{
        $role = '';
      }
    }


  	  $users_sel = ['' => 'User Type','athlete'=>'Athletes','parent_guardian_registering_athlete_' => 'Athlete Guardian','coach' => 'Coaches', 'assessors' => 'Assessor','bfss_manager'=>'BFSS Manager'];
      
      $form['select_faqs'] = [
        #'#placeholder' => t('State'),
        '#type' => 'select',
        '#required' => TRUE,
        '#options' => $users_sel,
        '#default_value' => $role,
        '#prefix' => '<div class="left_section popup_left_section">
                       <div class="athlete_left"> 
                        <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SELECT USER TYPE</h3>
                                          <div class="items_div">
                                          <div class="box niceselect"><span id="dateofshow">',
                	'#suffix' => '</span></div>
                  </div>
          </div>
          </div>',
          '#ajax' => [
              'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
              //'callback' => [$this, 'myAjaxCallback'], //alternative notation
              'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
              'event' => 'change',
              'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
              'progress' => [
                'type' => 'throbber',
                'message' => $this->t('Verifying entry...'),
          ],
        ],
      ];


   

  	 return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    
  }

  public function myAjaxCallback(array &$form, FormStateInterface $form_state){


     if ($selectedValue = $form_state->getValue('select_faqs')) {

      $selectedText = $form['select_faqs']['#options'][$selectedValue];
      $response = new \Drupal\Core\Ajax\AjaxResponse();
      $url = '/user-type-permissions?role='.$selectedText;
      $response->addCommand(new RedirectCommand($url));
      
    }
    return $response;
  }

}