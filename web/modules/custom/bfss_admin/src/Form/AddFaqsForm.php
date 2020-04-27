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
  	 
      
      $form['question_faqs'] = [
        '#title' => t('Question:'),
        '#type' => 'textfield',
        '#required' => TRUE,
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
        '#required' => TRUE,
        '#default_value' => '',
        '#prefix' => '',
        
      ];

      $form['entity_id'] =[
        '#type' => 'hidden',
        '#value' => '',
      ];

      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('SAVE'),
          '#suffix' => '</div>
        </div></div>',
          '#button_type' => 'primary',
           // '#ajax' => [
           //    'callback' => '::submitForm', // don't forget :: when calling a class method.
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



  	 return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}