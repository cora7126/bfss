<?php
/**
 * Time selection
 * @file
 * Contains \Drupal\bfss_assessment\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\bfss_assessment\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MultistepTwoForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'multistep_form_two';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);
    #if not avail
    $nid = $this->store->get('assessment');
    //echo $nid;
    //die;
    if(!$nid) {
      returnForm:
      $this->assessmentService->notAvailableMessage();
      $form['actions']['submit']['#access'] = false;
      return $form;
    }
    #add container class to form
    $form['#attributes']['class'][] = 'container';
    $timings = $this->assessmentService->getSchedulesofAssessment($nid);
    #if older entites are not set
    if (!$this->store->get('service')) {
      goto returnForm;
    }
    #attach library for styling
    $form['#attached']['library'][] = 'bfss_assessment/assessment_mulitform_lib';
    #add status bar class
    $form['heading']['#prefix'] = '<div class="two">';
    $form['heading']['#suffix'] = '</div>';
    $sortedTimings = [];

    if(empty($timings)){
      $form['time_date_priv'] = [
                                  '#type' => 'datetime',
                                  '#title' => $this->t('Scheduled'),
                                  '#size' => 20,
                                  '#date_date_element' => 'date', // hide date element
                                  '#date_time_element' => 'time', // you can use text element here as well
                                  '#date_time_format' => 'H:i',
                                  '#default_value' => '00:00',
                                ];
    }else{
        foreach ($timings as $key => $value) {
          $value = date('h:i a',$value);
          $sortedTimings[date('Ymd',$key)][$key] = '<span class="radiobtn"></span>'.$value.'<span>';
        }
        foreach ($sortedTimings as $key => $value) {
          $maintitle = current(array_keys($value));
            $form['time'.$key] = array(
              '#type' => 'radios',
              '#title' => $this->t(date('D, M d Y',$maintitle)),
              '#options' => $value,
              '#prefix' => '<div class="timeslots">',
              '#suffix' => '</div>',
            );
            foreach ($value as $key1 => $value1) {
              if ($key1 == $this->store->get('time')) {
                $form['time'.$key]['#default_value'] = $this->store->get('time');
              }
            }
        }
    }

    $form['actions']['previous'] = array(
      '#type' => 'link',
      '#title' => $this->t('Back'),
      '#attributes' => array(
        'class' => array('button'),
      ),
      '#weight' => 0,
      '#url' => Url::fromRoute('bfss_assessment.multistep_one'),
    );
    $form['actions']['submit']['#value'] = $this->t('Next');
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if (!$this->getSelectedTime($form_state)) {
      $form_state->setErrorByName('time', $this->t('Please select time slot for booking!'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $time = $this->getSelectedTime($form_state);
    if ($time) {
      $this->store->set('time', $time);
      $form_state->setRedirect('bfss_assessment.multistep_three');
    }else{
      drupal_set_message('Your selection is not working, please try again.','error');
    }
  }
  /**
   * check there is on radio box selected
   */
  public function getSelectedTime(FormStateInterface $form_state) {
    $allTimings = $form_state->cleanValues()->getValues();
    $defTime = $this->store->get('time');
    $newVal = null;
    if ($allTimings) {
      foreach ($allTimings as $key => $value) {
        if (stripos($key, 'time') !== false && !empty($value)) {
          if ($value != $defTime) {
            return $value;
          }else{
            $newVal = $value;
          }
        }
      }
    }
    return !empty($newVal) ? $newVal : false;
  }

}