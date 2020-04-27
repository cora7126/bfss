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
 * Class UserBySelectFaqsForm.
 */
class UserBySelectFaqsForm extends FormBase {
 /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_by_select_faqs_Form';
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
  	  $users_sel = ['' => 'Select','athletes'=>'Athletes','coach' => 'Coaches' ];
      
      $form['select_faqs'] = [
        '#placeholder' => t('State'),
        '#type' => 'select',
        '#required' => TRUE,
        '#options' => $users_sel,
        '#default_value' => '',
        '#prefix' => '<div class="box niceselect"><span id="dateofshow"><p>Please select the FAQ account you would like to edit</p>',
      	'#suffix' => '</span></div>',
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
      $url = '/add-faqs-user?role='.$selectedText;
      $response->addCommand(new RedirectCommand($url));
      
    }
    return $response;
  }

}