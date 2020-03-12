<?php
/**
 * @file
 * Contains \Drupal\bfss_assessors\Form\StarterProfessionalAssessments.
 */
namespace Drupal\bfss_assessors\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;

class StarterProfessionalAssessments extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'individual_starter_professional_assessments';
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
  		$triggerElement = $form_state->getTriggeringElement();
	  	$form_data = [];
	  	foreach ($form_state->getValues() as $key => $value) {
	  	 	$form_data[$key] = $value;
	  	}
	  	if(isset($triggerElement['#id']) && $triggerElement['#id'] == 'edit-draft'){
	  		// if "SAVE - INCOMPLETE" button trigger
	  	}

	  	if (isset($triggerElement['#id']) && $triggerElement['#id'] == 'edit-submit') {
	  		// if "SAVE - ALL FIELDS COMPLETED" trigger
	  // 		$node = Node::create([
			//        'type' => 'athlete_assessment_info',
			//        'title' => 'Creating a new node2',
			// ]);  
			// $node->set('field_test_field', 'test');
			// $node->save();
	  	}
	  	
	  	
   }
}