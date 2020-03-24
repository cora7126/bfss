<?php  
/**
 * @file
 * Contains \Drupal\bfss_assessment\Form\MonthSelectForm.
 */

namespace Drupal\bfss_assessment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

/**
 * Contribute form.
 */
class MonthSelectForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'month_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $year = date("Y");
    $month = [
            '1/'.$year.'' =>'January '.$year.'',
            '2/'.$year.'' =>'February '.$year.'',
            '3/'.$year.'' =>'March '.$year.'',
            '4/'.$year.'' =>'April '.$year.'',
            '5/'.$year.'' =>'May '.$year.'',
            '6/'.$year.'' =>'June '.$year.'',
            '7/'.$year.'' =>'July '.$year.'',
            '8/'.$year.'' =>'August '.$year.'',
            '9/'.$year.'' =>'September '.$year.'',
            '10/'.$year.'' =>'October '.$year.'',
            '11/'.$year.'' =>'November '.$year.'',
            '12/'.$year.'' =>'December '.$year.'',
          ];
     
    $form['showdate'] = [
      '#type' => 'select',
      '#options' => $month,
      //'#required' => TRUE,
      '#title' => $this->t('Date of Show:'),
      '#prefix' => '<span id="dateofshow">',
      '#suffix' => '</span>'
      ];

    $form['#method'] = 'get'; 
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Filter'),
      '#button_type' => 'primary',
    );

    return $form;
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
}// class close 