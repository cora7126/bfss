<?php  
/**
 * @file
 * Contains \Drupal\bfss_assessment\Form\MonthSelectForm.
 */

namespace Drupal\bfss_assessment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
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
            '01/'.$year.'' =>'January '.$year.'',
            '02/'.$year.'' =>'February '.$year.'',
            '03/'.$year.'' =>'March '.$year.'',
            '04/'.$year.'' =>'April '.$year.'',
            '05/'.$year.'' =>'May '.$year.'',
            '06/'.$year.'' =>'June '.$year.'',
            '07/'.$year.'' =>'July '.$year.'',
            '08/'.$year.'' =>'August '.$year.'',
            '09/'.$year.'' =>'September '.$year.'',
            '10/'.$year.'' =>'October '.$year.'',
            '11/'.$year.'' =>'November '.$year.'',
            '12/'.$year.'' =>'December '.$year.'',
          ];
    $current_date = date("Y/m/d");
    $date_arr = explode('/',$current_date);
    if(isset($_GET['showdate'])){
     $exp = explode("/",$_GET['showdate']);
      $M =  $exp[0];
      $Y = $exp[1];
      $month_crr = $M.'/'.$Y;  
    }else{
      $M = $date_arr[1];
      $Y =  $date_arr[0];
      $month_crr = $M.'/'.$Y;  
    }
    $form['showdate'] = [
      '#type' => 'select',
      '#options' => $month,
      '#default_value' => $month_crr,
      //'#required' => TRUE,
      //'#title' => $this->t('Date of Show:'),
      '#prefix' => '<div class="box niceselect"><span id="dateofshow"><i class="fal fa-angle-left mr-2"></i>',
      '#suffix' => '<i class="fal fa-angle-right ml-2"></i></span></div>',
      ];



    $form['#method'] = 'get'; 
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Filter'),
      '#button_type' => 'primary',
       '#prefix' => '<div class="filter_btn">',
      '#suffix' => '</div>',
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

    public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
      
    }

}// class close 