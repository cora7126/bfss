<?php
/**
 * Time selection
 * @file
 * Contains \Drupal\bfss_assessment\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\bfss_assessment\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
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

     $assessment_type = '';
     $node = Node::load($nid);
     if(!empty($node)){
      if($node->hasField('field_type_of_assessment')) {
        $assessment_type  = $node->field_type_of_assessment->value;
      }
     }
    #add container class to form
    $form['#attributes']['class'][] = 'container';

    if($nid == '9999999999' || $assessment_type == 'private'){
      //private
      $query = \Drupal::entityQuery('node');
      $query->condition('status', 1);
      $query->condition('type', 'assessment');
      //$query->sort('time' , 'ASC'); 
      $query->condition('field_type_of_assessment', 'private','=');
      $private_nids = $query->execute();
      $timings_private = [];
      foreach ($private_nids as $private_nid) {
        //$timings_private[] = $this->assessmentService->getSchedulesofAssessment($private_nid);
          $timings_private[] = [
          'private_nid' => $private_nid,
          'schedule' => $this->assessmentService->getSchedulesofAssessment($private_nid),
        ];
      }
      // $timings_private = array_filter($timings_private);
      // arsort($timings_private);
      //  echo "<pre>";
       
      // print_r($timings_private);
      // die;
     
    }else{
       $timings = $this->assessmentService->getSchedulesofAssessment($nid);
    }

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

     $form['test'] = array(
		'#type' => 'markup',
		'#markup' => '&nbsp;',
		'#prefix' => "<p class='service-top-head'>Below is a list of available time slots and days for your assessment. Click on a time slot to proceed with booking.</p>",
    '#suffix' => "<div class='timeslots-main'>",
	  );
     
//print_r($assessment_type);
 if($nid == '9999999999' ||  $assessment_type == 'private'){
      foreach ($timings_private as $timings_pri) {
           foreach ($timings_pri['schedule'] as $key => $value) {
                $booked_ids = \Drupal::entityQuery('bfsspayments')
                ->condition('user_id', \Drupal::currentUser()->id())
                ->condition('assessment', $timings_pri['private_nid'], "=")
                ->condition('time',$value, "=")
                ->condition('payment_status','paid', "=")
                ->execute();
                $value = date('h:i a',$value);              
                
                if(empty($booked_ids)){
                  $sortedTimings[date('Ymd',$key)][$key] = '<span class="radiobtn" data-nid="'.$timings_pri['private_nid'].'"></span>'.$value.'<span>';
                }
        }
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
 }else{
        foreach ($timings as $key => $value) {
                $booked_ids = \Drupal::entityQuery('bfsspayments')
                ->condition('user_id', \Drupal::currentUser()->id())
                ->condition('assessment',$nid, "=")
                ->condition('time',$value, "=")
                ->condition('payment_status','paid', "=")
                ->execute();

                $value = date('h:i a',$value);
                if(empty($booked_ids)){
                  $sortedTimings[date('Ymd',$key)][$key] = '<span class="radiobtn"></span>'.$value.'<span>';
                }
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

        
    $form['test1'] = array(
    '#type' => 'markup',
    '#markup' => '&nbsp;',
    '#prefix' => "</div>",
    );

    $form['entity_id'] = array(
      '#type' => 'hidden',
      '#prefix' => '<div class="timeslots-nid">',
      '#suffix' => '</div>',
    );
    $form['actions']['previous'] = array(
      '#type' => 'link',
      '#title' => $this->t('Back'),
       #'#prefix' => '</div>',
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
  	// $entity_id = $form_state->getValue('entity_id');
  	// print_r($form_state->getValues());
  	// die;
  	if(!empty($form_state->getValue('entity_id'))){
  		$this->store->set('assessment',$form_state->getValue('entity_id'));
 	 }
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
    if (isset($allTimings['tim_date_priv'])) {
      return $allTimings['tim_date_priv']->getTimestamp();
    }
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