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
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Database;
/**
 * Class AddFaqsForm.
 */
class AddFaqsForm extends FormBase {
 /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_faqs_form';
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
  	  $param = \Drupal::request()->query->all();

      if( !empty($param['role']) ){
          if($param['role'] == 'Athletes'){
            $user_role = 'athlete';
          }elseif ($param['role'] == 'Coaches') {
            $user_role = 'coach';
          }else{
            $user_role = '';
          }
      }else{
        $user_role = '';
      }
    //Permissions
    $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
    $rel = $permissions_service->bfss_admin_permissions();
    $faqs =  unserialize($rel['faqs']);

    if($faqs['create']==1 || $faqs['admin']==1){
      $form['#attached']['library'][] = 'bfss_admin/bfss_admin_lab'; //here can add library
      $form['message'] = [ //for custom message "like: ajax msgs"
        '#type' => 'markup',
        '#markup' => '<div class="result_message"></div>',
      ];
      $form['question_faqs'] = [
        '#title' => t('Question:'),
        '#type' => 'textfield',
        #'#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '<div class="left_section popup_left_section">
                        <div class="athlete_left"> 
                          <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Add FAQ</h3>
                          <div class="items_div">',
      	'#suffix' => '',
      ];

      $form['answer_faqs'] = [
        '#title' => t('Answer:'),
        '#type' => 'textarea',
        '#rows' => 4,
        '#cols' => 5,
        #'#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        
      ];

      $form['faqs_role'] =[
        '#type' => 'hidden',
        '#value' => $user_role,
      ];

      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => 'SAVE ALL CHANGES',
          '#prefix' => '<div class="bfss_save_all save_all_changes">',
          '#suffix' => '</div>
          </div>
           </div></div>',
          '#button_type' => 'primary',
           '#ajax' => [
              'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
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

    
      
    }else{
      
      $form['access_message'] = [ //for custom message "like: ajax msgs"
          '#type' => 'markup',
          '#markup' => '<p>we are sorry. you can not access this page.</p>',
        ];
        
    }
    
    return $form;

  	// return $result;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
   
  }

  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
      $current_user = \Drupal::currentUser()->id();
      $conn = Database::getConnection();
      if(empty($form_state->getValue('question_faqs')) || $form_state->getValue('question_faqs') == ''){
        $message = '<p style="color:red;">Question required.</p>';
      }elseif (empty($form_state->getValue('answer_faqs')) || $form_state->getValue('answer_faqs') == '') {
         $message = '<p style="color:red;">Answer required!<p>'; 
      }else{
          $node = Node::create([
                               'type' => 'faq',
                  ]);
          $node->body->value = $form_state->getValue('answer_faqs');
          $node->title->value = $form_state->getValue('question_faqs');
          $node->field_roles->value = $form_state->getValue('faqs_role');
          $node->enforceIsNew();
          $node->save();
          $message = "successfully saved!";

          $query = $this->Get_Data_From_Tables('bfss_faqs_nids','at',$form_state->getValue('faqs_role'));
          if(empty($query)){
            $conn->insert('bfss_faqs_nids')->fields(
              [
                'role' => $form_state->getValue('faqs_role'),
                'faq_nids' => $node->id(),
              ]
            )->execute(); 
          }else{
            $nids = explode(',', $query['faq_nids']);
            array_unshift($nids,$node->id());
            $str_nids= implode(",",$nids);
            $conn->update('bfss_faqs_nids')->condition('role', $form_state->getValue('faqs_role'), '=')->fields(
              [
                'faq_nids' => $str_nids,
              ]
            )->execute();            
          }
      }  

      $response = new AjaxResponse();
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="success_message">'.$message.'</div>'
        )
      );
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