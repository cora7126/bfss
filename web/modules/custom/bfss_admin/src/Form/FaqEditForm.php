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
 * Class FaqEditForm.
 */
class FaqEditForm extends FormBase {
 /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'faq_edit_form';
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
  	  $param = \Drupal::request()->query->all();
      //Permissions
      $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
      $rel = $permissions_service->bfss_admin_permissions();
      $faqs =  unserialize($rel['faqs']);
      $node = Node::load($param['nid']);
  

    if($faqs['edit']==1 || $faqs['admin']==1){
      $form['#attached']['library'][] = 'bfss_admin/bfss_admin_lab'; //here can add library
      $form['message'] = [ //for custom message "like: ajax msgs"
        '#type' => 'markup',
        '#markup' => '',
      ];
      $form['question_faqs'] = [
        '#title' => t('Question:'),
        '#type' => 'textfield',
        #'#required' => TRUE,
        '#default_value' => $node->title->value,
        '#prefix' => '<div class="left_section popup_left_section">
                        <div class="athlete_left"> 
                          <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Edit FAQ</h3><div class="result_message_faq_updated"></div>
                          <div class="items_div">',
      	'#suffix' => '',
      ];

      $form['answer_faqs'] = [
        '#title' => t('Answer:'),
        '#type' => 'textarea',
        '#rows' => 4,
        '#cols' => 5,
        #'#required' => TRUE,
        '#default_value' => $node->body->value,
        '#prefix' => '',
        
      ];

      $form['faqs_role'] =[
        '#type' => 'hidden',
        '#value' => $user_role,
      ];

      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('SAVE'),
          '#prefix' => '<div class="athlete_submit">',
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
        '#markup' => '<div class="acess-message"><p>We are sorry.You can not access.</p></div>',
      ];
    }

 

  	 return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
   
  }

  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
      $param = \Drupal::request()->query->all();
      
      if(empty($form_state->getValue('question_faqs')) || $form_state->getValue('question_faqs') == ''){
        $message = '<p style="color:red;">Question required.</p>';
      }elseif (empty($form_state->getValue('answer_faqs')) || $form_state->getValue('answer_faqs') == '') {
         $message = '<p style="color:red;">Answer required!<p>'; 
      }else{
        if(isset($param['nid'])){
          $node = Node::load($param['nid']);
          $node->body->value = $form_state->getValue('answer_faqs');
          $node->title->value = $form_state->getValue('question_faqs');
          $node->save();
          $message = "successfully Updated!"; 
        }     
      }  

      $response = new AjaxResponse();
      $response->addCommand(
        new HtmlCommand(
          '.result_message_faq_updated',
          '<div class="success_message_faq_updated">'.$message.'</div>'
        )
      );
      return $response;
        
  }



}