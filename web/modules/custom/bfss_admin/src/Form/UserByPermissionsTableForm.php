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
use Drupal\Core\Database\Database;
/**
 * Class UserByPermissionsTableForm.
 */
class UserByPermissionsTableForm extends FormBase {
 /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_by_permissions_table_form';
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
    $conn = Database::getConnection();
    $query = $this->Get_Data_From_Tables('bfss_admin_permissions','at',$role);
    $profile = unserialize($query['profile']);
    $Organizations = unserialize($query['Organizations']);
    $faqs = unserialize($query['faqs']);
    $ticketing = unserialize($query['ticketing']);
    $users = unserialize($query['users']);
    $pending_approval = unserialize($query['pending_approval']);
    $users_type_permissions = unserialize($query['users_type_permissions']);
    $editable_templates = unserialize($query['editable_templates']);
    $assessments = unserialize($query['assessments']);


    if(isset($param['role'])){
       $form['#tree'] = TRUE;

       $form['html_start'] = [ 
        '#type' => 'markup',
        '#markup' => '<div class="left_section popup_left_section">
                     <div class="athlete_left"> 
                      <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>SELECT PERMISSIONS</h3>
                      <div class="items_div">
                      <div class="table">
                      <div class="form-group js-form-wrapper form-wrapper first-thead">
                        <ul>
                          <li>PERMISSIONS</li>
                          <li>View</li>
                          <li>Create</li>
                          <li>Edit</li>
                          <li>Admin</li>
                        </ul>
                      </div>',
      ];

      //Profile
      $form['profile'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];

      $form['profile']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $profile['view'],
            '#prefix' => '<ul>
                        <li>Profile</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['profile']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $profile['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['profile']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $profile['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['profile']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $profile['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];
      //Organizations
      $form['Organizations'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['Organizations']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $Organizations['view'],
            '#prefix' => '<ul><li>Organizations</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['Organizations']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $Organizations['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['Organizations']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $Organizations['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['Organizations']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $Organizations['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];
      //faqs
      $form['faqs'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['faqs']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $faqs['view'],
            '#prefix' => '<ul><li>FAQs</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['faqs']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $faqs['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['faqs']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $faqs['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['faqs']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $faqs['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];
      //ticketing
      $form['ticketing'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['ticketing']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $ticketing['view'],
            '#prefix' => '<ul><li>Ticketing</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['ticketing']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $ticketing['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['ticketing']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $ticketing['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['ticketing']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $ticketing['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];

      //users
      $form['users'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['users']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users['view'],
            '#prefix' => '<ul><li>Users</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['users']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['users']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['users']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];

      //pending_approval
      $form['pending_approval'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['pending_approval']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $pending_approval['view'],
            '#prefix' => '<ul><li>Pending Approval</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['pending_approval']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $pending_approval['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['pending_approval']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $pending_approval['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['pending_approval']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $pending_approval['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];


      //users_type_permissions
      $form['users_type_permissions'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['users_type_permissions']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users_type_permissions['view'],
            '#prefix' => '<ul><li>Users Type Permissions</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['users_type_permissions']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users_type_permissions['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['users_type_permissions']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users_type_permissions['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['users_type_permissions']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $users_type_permissions['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];


      //editable_templates
      $form['editable_templates'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['editable_templates']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $editable_templates['view'],
            '#prefix' => '<ul><li>Editable Templates</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['editable_templates']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $editable_templates['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['editable_templates']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $editable_templates['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['editable_templates']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $editable_templates['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];

      //assessments
      $form['assessments'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'profile-details'],
      ];
      $form['assessments']['view'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $assessments['view'],
            '#prefix' => '<ul><li>Assessments</li>
                        <li>',
            '#suffix' =>'</li>
            ',
        ];

      $form['assessments']['create'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $assessments['create'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['assessments']['edit'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $assessments['edit'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            ',
      ];

      $form['assessments']['admin'] = [ 
            '#type' => 'checkbox',
            '#default_value' => $assessments['admin'],
            '#prefix' => '<li>',
            '#suffix' =>'</li>
            </ul>',
      ];


      $form['html_end'] = [ 
        '#type' => 'markup',
        '#markup' => '</div> <!--table end -->
          </div>
        </div>
        </div>',
      ];
      
      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => Markup::create('<em class="desktop">SAVE ALL CHANGES</em><em class="mobile">SAVE</em>'),
          '#prefix' => '<div class="bfss_save_all save_all_changes">',
          '#suffix' => '</div>',
          '#button_type' => 'primary',
           // '#ajax' => [
           //    'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
           //    //'callback' => [$this, 'myAjaxCallback'], //alternative notation
           //    'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
           //    'event' => 'click',
           //    'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
           //    'progress' => [
           //      'type' => 'throbber',
           //      'message' => $this->t('Verifying entry...'),
           //    ],
           //  ]
      ];
    }else{
      $form['html_start'] = [ 
          '#type' => 'markup',
          '#markup' => '',
        ];
    }
   


   

  	 return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();
    $conn = Database::getConnection();
    
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

  
        $query = $this->Get_Data_From_Tables('bfss_admin_permissions','at',$role);
        if(empty($query)){

            $conn->insert('bfss_admin_permissions')->fields(
              [
                'role' => $role,
                'profile' => serialize($form_state->getValue('profile')),
                'Organizations' => serialize($form_state->getValue('Organizations')),
                'faqs' => serialize($form_state->getValue('faqs')),
                'ticketing' => serialize($form_state->getValue('ticketing')),
                'users' => serialize($form_state->getValue('users')),
                'pending_approval' => serialize($form_state->getValue('pending_approval')),
                'users_type_permissions' => serialize($form_state->getValue('users_type_permissions')),
                'editable_templates' => serialize($form_state->getValue('editable_templates')),
                'assessments' => serialize($form_state->getValue('assessments')),
              ]
            )->execute(); 
          }else{
         
            $conn->update('bfss_admin_permissions')->condition('role', $role, '=')->fields(
              [
                'profile' => serialize($form_state->getValue('profile')),
                'Organizations' => serialize($form_state->getValue('Organizations')),
                'faqs' => serialize($form_state->getValue('faqs')),
                'ticketing' => serialize($form_state->getValue('ticketing')),
                'users' => serialize($form_state->getValue('users')),
                'pending_approval' => serialize($form_state->getValue('pending_approval')),
                'users_type_permissions' => serialize($form_state->getValue('users_type_permissions')),
                'editable_templates' => serialize($form_state->getValue('editable_templates')),
                'assessments' => serialize($form_state->getValue('assessments')),
              ]
            )->execute();            
        }
    
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

  public function Get_Data_From_Tables($TableName,$atr,$user_role){
      if($TableName){
        $conn = Database::getConnection();
        $query = $conn->select($TableName, $atr);
        $query->fields($atr);
        $query->condition('role', $user_role, '=');
        $results = $query->execute()->fetchAssoc();
      }
      return $results;
  }

}