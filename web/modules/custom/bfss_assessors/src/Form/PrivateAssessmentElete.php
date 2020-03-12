<?php
/**
 * @file
 * Contains \Drupal\bfss_assessors\Form\PrivateAssessmentElete.
 */
namespace Drupal\bfss_assessors\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class PrivateAssessmentElete extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'private_assessment_elete';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

  	//Reactive Strength (CM Rebound Jump)
  	$form['reactive_strength'] = array(
	  '#type' => 'fieldset',
	  '#title' => $this->t('Reactive Strength (CM Rebound Jump)'),
	  '#prefix' => '<div id="reactive_strength">',
	  '#suffix' => '</div>',
	);

    $form['reactive_strength']['starter_weight_rea_str'] = array(
      '#type' => 'textfield',
      '#title' => t('Weight (N) Calculated into Ibs'),
      '#attributes' => array(
        'placeholder' => t('Weight (N) Calculated into Ibs'),
      ),
    );

    $form['reactive_strength']['starter_jump_height_rea_str'] = array(
      '#type' => 'textfield',
      '#title' => t('Jump Height (ln)'),
      '#attributes' => array(
        'placeholder' => t('Jump Height (ln)'),
      ),
    );
    $form['reactive_strength']['starter_rsi_rea_str'] = array (
      '#type' => 'textfield',
      '#title' => t('RSI'),
      '#attributes' => array(
        'placeholder' => t('RSI'),
      ),
    );


    //Elastic Strenght (Countermovement Jump)
	$form['elastic_strenght'] = array(
		  '#type' => 'fieldset',
		  '#title' => $this->t('Elastic Strenght (Countermovement Jump)'),
		  '#prefix' => '<div id="elastic_strenght">',
		  '#suffix' => '</div>',
	);
    $form['elastic_strenght']['starter_jump_height_ela_str'] = array (
      '#type' => 'textfield',
      '#title' => t('Jump Height (ln)'),
      '#attributes' => array(
        'placeholder' => t('Jump Height (ln)'),
      ),
    );

    $form['elastic_strenght']['elastic_strenght']['starter_peak_pro_ela_str'] = array (
      '#type' => 'textfield',
      '#title' => t('Peak Propulslve Force (N)'),
      '#attributes' => array(
        'placeholder' => t('Peak Propulslve Force (N)'),
      ),
    );
    $form['elastic_strenght']['starter_peak_power_ela_str'] = array (
      '#type' => 'textfield',
      '#title' => t('Peak Power (W)'),
      '#attributes' => array(
        'placeholder' => t('Peak Power (W)'),
      ),
    );

    //Ballistic Strength (Squat Jump)
    $form['ballistic_strength'] = array(
		  '#type' => 'fieldset',
		  '#title' => $this->t('Ballistic Strength (Squat Jump)'),
		  '#prefix' => '<div id="ballistic_strength">',
		  '#suffix' => '</div>',
	);
    $form['ballistic_strength']['starter_jump_height_ballistic'] = array (
      '#type' => 'textfield',
      '#title' => t('Jump Height (ln)'),
      '#attributes' => array(
        'placeholder' => t('Jump Height (ln)'),
      ),
    );

    $form['ballistic_strength']['starter_peak_pro_ballistic'] = array (
      '#type' => 'textfield',
      '#title' => t('Peak Propulslve Force (N)'),
      '#attributes' => array(
        'placeholder' => t('Peak Propulslve Force (N)'),
      ),
    );

    $form['ballistic_strength']['starter_peak_power_ballistic'] = array (
      '#type' => 'textfield',
      '#title' => t('Peak Power (W)'),
      '#attributes' => array(
        'placeholder' => t('Peak Power (W)'),
      ),
    );

    // 10M/40M Sprint
    $form['10m_40m_sprint'] = array(
		  '#type' => 'fieldset',
		  '#title' => $this->t('10M/40M Sprint'),
		  '#prefix' => '<div id="10m_40m">',
		  '#suffix' => '</div>',
	);

    $form['10m_40m_sprint'] ['starter_10m'] = array (
      '#type' => 'textfield',
      '#title' => t('10 M Time (sec)'),
      '#attributes' => array(
        'placeholder' => t('10 M Time (sec)'),
      ),
    );

    $form['10m_40m_sprint'] ['starter_40m'] = array (
      '#type' => 'textfield',
      '#title' => t('40 M Time (sec)'),
      '#attributes' => array(
        'placeholder' => t('40 M Time (sec)'),
      ),
    );

    // Maximal Strength (Isometric Mid-Thigh Pull)
    $form['maximal_strength'] = array(
		  '#type' => 'fieldset',
		  '#title' => $this->t('Maximal Strength (Isometric Mid-Thigh Pull)'),
		  '#prefix' => '<div id="10m_40m">',
		  '#suffix' => '</div>',
	);

    $form['maximal_strength']['starter_peak_for_max'] = array (
      '#type' => 'textfield',
      '#title' => t('Peak Force (N)'),
      '#attributes' => array(
        'placeholder' => t('Peak Force (N)'),
      ),
    );

     $form['maximal_strength']['starter_rfd_max'] = array (
      '#type' => 'textfield',
      '#title' => t('RFD @ 100ms (N)'),
      '#attributes' => array(
        'placeholder' => t('RFD @ 100ms (N)'),
      ),
    );

    //UE Power (SPM Ball Throw)
    $form['ue_power'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('10M/40M Sprint'),
      '#prefix' => '<div id="ue_power">',
      '#suffix' => '</div>',
    );

    $form['ue_power']['power'] = array (
      '#type' => 'textfield',
      '#title' => t('Power (W)'),
      '#attributes' => array(
        'placeholder' => t('Power (W)'),
      ),
    );

     //UE Power (SPM Ball Throw)
     $form['ue_power_spm'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('10M/40M Sprint'),
      '#prefix' => '<div id="ue_power">',
      '#suffix' => '</div>',
    );
    
    $form['ue_power_spm']['power_spm'] = array (
      '#type' => 'textfield',
      '#title' => t('Power (W)'),
      '#attributes' => array(
        'placeholder' => t('Power (W)'),
      ),
    );
    //UE Power (RM Ball Throw)
     $form['ue_power_rm'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('UE Power (RM Ball Throw)'),
      '#prefix' => '<div id="ue_power">',
      '#suffix' => '</div>',
    );
    $form['ue_power_rm']['power_rm'] = array (
      '#type' => 'textfield',
      '#title' => t('Power (W)'),
      '#attributes' => array(
        'placeholder' => t('Power (W)'),
      ),
    );

    //Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)
    $form['strength_endurance'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)'),
      '#prefix' => '<div id="strength_endurance">',
      '#suffix' => '</div>',
    );

     $form['strength_endurance']['repetitions'] = array (
      '#type' => 'textfield',
      '#title' => t('Repetitions (#)'),
      '#attributes' => array(
        'placeholder' => t('Repetitions (#)'),
      ),
    );

    //Change of Direction (5-10-5 Pro Agility Test)
       $form['change_of_direction'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Change of Direction (5-10-5 Pro Agility Test)'),
      '#prefix' => '<div id="change_of_direction">',
      '#suffix' => '</div>',
    );

     $form['change_of_direction']['power_ch'] = array (
      '#type' => 'textfield',
      '#title' => t('Power (W)'),
      '#attributes' => array(
        'placeholder' => t('Power (W)'),
      ),
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['draft'] = array(
      '#type' => 'submit',
      '#value' => $this->t('SAVE - INCOMPLETE'),
      '#button_type' => 'primary',
    );

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('SAVE - ALL FIELDS COMPLETED'),
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
}