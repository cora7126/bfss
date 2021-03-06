<?php
/**
 * Created by PhpStorm.
 * User: Valentine
 * Date: 01.02.2020
 * Time: 21:25
 */

namespace Drupal\bfss_registration_form\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\HtmlCommand;

class CompleteRegistrationForm extends FormBase {

  function getFormId() {
    return 'complete_registration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $user = NULL) {

    // Attach custom javascript
    $form['#attached']['library'][] = 'bfss_registration_form/ajax-complete-registration';

    $form['#prefix'] = '<div id="complete-registration-wrapper" class="complete-registration-wrapper">';
    $form['#suffix'] = '</div>';

    $form['pass_section'] = [
      '#title'=> $this->t('Set Up Password'),
      '#type' => 'fieldset',
    ];

    $form['pass_section']['description_message'] = [
      '#type' => 'item',
      '#markup' => t('<div class="description_message">Password must be at least 8 characters and contain at least one number, one uppercase letter, one lowercase letter and one special character.</div>')
    ];


 // $form['message'] = [ //for custom message "like: ajax msgs"
 //          '#type' => 'markup',
 //          '#markup' => '<div class="result_message"></div>',
 //         ];
    $form['pass_section']['pass'] = [
      '#title'=> $this->t('Password'),
      '#title_display' => 'invisible',
      '#placeholder' => $this->t('Password'),
      '#type' => 'password_confirm',
      '#required' => TRUE,
    ];

    // $form['phone_section'] = [
    //   '#title'=> $this->t('Next, Set up 2 Step Authentication'),
    //   '#type' => 'fieldset',
    // ];

    // $form['phone_section']['description'] = [
    //   '#type' => 'item',
    //   '#markup' => t('<p>2 step authentication will send a code to your cell for your login authentication.</p>')
    // ];

    // $form['phone_section']['phone'] = [
    //   '#type' => 'tel',
    //   '#title' => $this->t('Cell Phone'),
    //   '#title_display' => 'invisible',
    //   '#placeholder' => $this->t('Cell Phone'),
    //   '#default_value' => $form_state->getValue('phone')
    // ];

    // $form['phone_section']['confirm_phone'] = [
    //   '#type' => 'tel',
    //   '#title' => $this->t('Confirm Cell Phone'),
    //   '#title_display' => 'invisible',
    //   '#placeholder' => $this->t('Confirm Cell Phone'),
    //   '#default_value' => $form_state->getValue('confirm_phone')
    // ];

    // $form['phone_section']['confirm_code'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Confirm Code'),
    //   '#title_display' => 'invisible',
    //   '#placeholder' => $this->t('Confirm Code'),
    // ];

    // $form['phone_section']['send_code'] = [
    //   '#type' => 'button',
    //   '#value' => $this->t('Send Code'),
    //   '#placeholder' => $this->t('Send Code'),
    //   '#name' => 'send_code',
    //   '#attributes' => [
    //     'class' => ['btn-send-code']
    //   ],
    //  /*'#ajax' => [
    //     'callback' => [$this, 'sendCode'],
    //     'wrapper' => 'send-code-wrapper'
    //   ],
    //   '#prefix' => '<div id="send-code-wrapper" class="send-code-wrapper">',
    //   '#sufffix' => '</div>',*/
    // ];

    $form['additional_info'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['additional-info']
      ],
      '#weight'=> 10
    ];

    $form['additional_info']['resend_message'] = [
      '#type' => 'item',
      '#markup' => '<div id="resend-message" class="resend-message"></div>'
    ];

    // $form['additional_info']['resend_code'] = [
    //   '#type' => 'link',
    //   '#title' => $this->t('Resend code'),
    //   '#url' => Url::fromRoute('user.register'),
    //   '#attributes' => [
    //     'class' => ['btn-resend-code']
    //   ],
    //   '#weight'=> 19
    // ];

    // $form['additional_info']['trouble'] = [
    //   '#type' => 'link',
    //   '#title' => $this->t('Having trouble getting a code?'),
    //   '#url' => Url::fromRoute('user.register'),  //  @todo: here should be link to trouble page
    //   '#weight'=> 20,
    //   '#attributes' => [
    //     'class' => ['btn-trouble']
    //   ],
    // ];
    $form['pass_section']['description'] = [
      '#type' => 'item',
      '#markup' => t('<div class="result_message"></div>')
    ];
    
    $form['actions'] = ['#type' => 'actions', '#weight'=> 9];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#attributes' => [
        'class' => ['btn-set-password']
      ],
      '#ajax' => [
        'callback' => [$this, 'ajaxSubmit'],
        'wrapper' => 'complete-registration-wrapper'
      ]
    ];

    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array|void
   */
  function validateForm(array &$form, FormStateInterface $form_state) {
    //  set new password
    //parent::validateForm($form, $form_state);

    //  check if phone not same
    // $phone = $form_state->getValue('phone');
    // $confirm_phone = $form_state->getValue('confirm_phone');

    // $is_phones = 0;
    // if ($phone != $confirm_phone) {
    //   $is_phones = 1;
    //   $form_state->setErrorByName('phone', $this->t('The phones do not match or empty.'));
    // }

    // $errors = $form_state->getErrors();

    // $el = $form_state->getTriggeringElement();

    // if ($el['#name'] == 'send_code') {
    //   $form_state->clearErrors();

    //   if ($is_phones) {
    //     $form_state->setErrorByName('phone', $this->t('The phones do not match or empty.'));
    //   }
    // }

    // if (!empty($errors)) {
    //   $form_state->setRebuild();
    //   return $form;
    // }

  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  function submitForm(array &$form, FormStateInterface $form_state) {
  
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  function ajaxSubmit(array &$form, FormStateInterface $form_state) {
     if(!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/", $form_state->getValue('pass'))) {
        $message = '<p style="color:red">Password must be at least 8 characters and contain at least one number, one uppercase letter, one lowercase letter and one special character.<p>';
        $response = new AjaxResponse();
        $response->addCommand(
          new HtmlCommand(
            '.result_message',
            '<div class="success_message">'.$message.'</div>'
          )
        );
        return $response;
    }else{
              if (!empty($_SESSION['user_first_login'])) {
                unset($_SESSION['user_first_login']);
              }
              #password update here
              $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
              $user->setPassword($form_state->getValue('pass'));
              $user->save();

              $current_user = \Drupal::currentUser()->id();
              $roles_user = \Drupal::currentUser()->getRoles();
              if(in_array('coach', $roles_user)){
                  $response = new \Drupal\Core\Ajax\AjaxResponse();
                  $url = '/dashboard/edit-coach-profile';
                  $response->addCommand(new RedirectCommand($url));
                  return $response;
              }else{
                   $response = new \Drupal\Core\Ajax\AjaxResponse();
                    $url = '/user/logout/';
                    $response->addCommand(new RedirectCommand($url));
                    return $response;
              }  
   }
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
 /* function sendCode(array &$form, FormStateInterface $form_state) {

    $response = new \Drupal\Core\Ajax\AjaxResponse();

    $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('.resend-message', $this->t('<div class="alert alert-success alert-dismissible">Code successfully sent</div>')));

    $response->addCommand(new \Drupal\Core\Ajax\SettingsCommand(['bfss_registration_form' => ['code_sent' => 1]], TRUE), TRUE);

    return $response;
  }*/

}
