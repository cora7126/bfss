<?php
/**
 * @file
 * Contains \Drupal\edit_form\Form\ContributeForm.
 */

namespace Drupal\edit_form\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

/**
 * Contribute form.
 */
class ContributeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
     return 'edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $result = db_select('table', 't_alias')->fields('t_alias', array('id', 'title'))->execute()->fetchAll();
    // $options = array();
    // foreach ($result as $value) {
    //   $options[$value->id] = $value->title;
    // }
    $form['name'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('name'),
      '#default_value' => '',
    );
    $form['email'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#placeholder' => t('email'),
      '#default_value' => '',
    );
    $form['photo'] = array(
        '#type' => 'markup',
        '#prefix' => '<div id="box" class="image_class">',
        '#suffix' => '</div>',
        '#markup' => '<img src="https://www.twoareone.love/uploads/bb9c194c398c14a859964fb3b58f4249_2.jpg" alt="picture" style="width:25px;height:25px;">'
    );
    $form['develop'] = array(
      '#type' => 'checkbox',
      '#title' => t('I would like to be involved in developing this material'),
    );
    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => t('Description'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );  
    $form['#theme'] = 'my_edit_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate video URL.
    if (!UrlHelper::isValid($form_state->getValue('video'), TRUE)) {
      $form_state->setErrorByName('video', $this->t("The video url '%url' is invalid.", array('%url' => $form_state->getValue('video'))));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }
  }
}
?>