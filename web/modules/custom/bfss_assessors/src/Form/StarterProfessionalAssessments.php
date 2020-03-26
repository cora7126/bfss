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
    $param = \Drupal::request()->query->all();
    $nid = $param['nid'];
    $formtype = $param['formtype'];
    $Assess_type = $param['Assess_type'];
    $booked_id = $param['booked_id'];
    
  if(isset($nid) && isset($formtype) && isset($Assess_type))
    {
            if($formtype == 'starter' && $Assess_type == 'individual'){
              $form_title = 'STARTER/PROFESSIONAL ASSESSMENTS';
            }
            elseif($formtype == 'elete' && $Assess_type == 'individual'){
              $form_title = 'ELITE ASSESSMENT';
            }
            elseif($formtype == 'starter' && $Assess_type == 'private'){
               $form_title = 'STARTER/PROFESSIONAL ASSESSMENTS';
             }
            elseif($formtype == 'elete' && $Assess_type == 'private'){
               $form_title = 'ELITE ASSESSMENT';
             }
          
            $form['#attached']['library'][] = 'bfss_assessors/bfss_assessors';
            $form['#prefix'] = '
            <!-- Modal start-->
                    <div class="modal fade" id="assessor_popup_form" role="dialog">
                    
                                    <div class="modal-dialog">
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div id="accessorform">
                                        <a type="button" class="close" data-dismiss="modal">&times;</a>
                                            <div class="accessorform_inner">
                                              <h2>'.$form_title.'</h2><ul class="st_lk">
                                              <li>EF-Equipment Failure</li>
                                              <li>Al-Athlete Injured</li>
                                              <li>ART-Athlete Refused Test</li>
                                              </ul>';

                    $form['#suffix'] = '    </div>
                                        </div>       
                                      </div>   
                                    </div>
                                </div>
            <!-- Modal end-->
                        ';
            $form['form_fields_wrap'] = array(
            '#type' => 'fieldset',
           # '#title' => $this->t(''),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
             );

          	//Reactive Strength (CM Rebound Jump)
          	$form['form_fields_wrap']['reactive_strength'] = array(
        	  '#type' => 'fieldset',
        	  '#title' => $this->t('Reactive Strength (CM Rebound Jump)'),
        	  '#prefix' => '<div id="reactive_strength" class="sm_cls">',
        	  '#suffix' => '</div>',
        	   );

            $form['form_fields_wrap']['reactive_strength']['starter_weight_rea_str'] = array(
              '#type' => 'textfield',
              #'#title' => t('Weight (N) Calculated into Ibs'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Weight (N) Calculated into Ibs'),
              ),
            );

            $form['form_fields_wrap']['reactive_strength']['starter_jump_height_rea_str'] = array(
              '#type' => 'textfield',
              #'#title' => t('Jump Height (ln)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Jump Height (ln)'),
              ),
            );
            $form['form_fields_wrap']['reactive_strength']['starter_rsi_rea_str'] = array (
              '#type' => 'textfield',
              #'#title' => t('RSI'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('RSI'),
              ),
            );

            //Elastic Strenght (Countermovement Jump)
        	$form['form_fields_wrap']['elastic_strenght'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('Elastic Strenght (Countermovement Jump)'),
        		  '#prefix' => '<div id="elastic_strenght" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);
            $form['form_fields_wrap']['elastic_strenght']['starter_jump_height_ela_str'] = array (
              #'#type' => 'textfield',
              '#title' => t('Jump Height (ln)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Jump Height (ln)'),
              ),
            );

            $form['form_fields_wrap']['elastic_strenght']['elastic_strenght']['starter_peak_pro_ela_str'] = array (
              '#type' => 'textfield',
              #'#title' => t('Peak Propulslve Force (N)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Propulslve Force (N)'),
              ),
            );
            $form['form_fields_wrap']['elastic_strenght']['starter_peak_power_ela_str'] = array (
              '#type' => 'textfield',
              #'#title' => t('Peak Power (W)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Power (W)'),
              ),
            );

            //Ballistic Strength (Squat Jump)
            $form['form_fields_wrap']['ballistic_strength'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('Ballistic Strength (Squat Jump)'),
        		  '#prefix' => '<div id="ballistic_strength" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);
            $form['form_fields_wrap']['ballistic_strength']['starter_jump_height_ballistic'] = array (
              '#type' => 'textfield',
              #'#title' => t('Jump Height (ln)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Jump Height (ln)'),
              ),
            );

            $form['form_fields_wrap']['ballistic_strength']['starter_peak_pro_ballistic'] = array (
              '#type' => 'textfield',
              #'#title' => t('Peak Propulslve Force (N)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Propulslve Force (N)'),
              ),
            );

            $formv['ballistic_strength']['starter_peak_power_ballistic'] = array (
              '#type' => 'textfield',
              #'#title' => t('Peak Power (W)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Power (W)'),
              ),
            );

            // 10M/40M Sprint
            $form['form_fields_wrap']['10m_40m_sprint'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('10M/40M Sprint'),
        		  '#prefix' => '<div id="10m_40m" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);

            $form['form_fields_wrap']['10m_40m_sprint'] ['starter_10m'] = array (
              '#type' => 'textfield',
              #'#title' => t('10 M Time (sec)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('10 M Time (sec)'),
              ),
            );

            $form['form_fields_wrap']['10m_40m_sprint'] ['starter_40m'] = array (
              '#type' => 'textfield',
              #'#title' => t('40 M Time (sec)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('40 M Time (sec)'),
              ),
            );

            // Maximal Strength (Isometric Mid-Thigh Pull)
            $form['form_fields_wrap']['maximal_strength'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('Maximal Strength (Isometric Mid-Thigh Pull)'),
        		  '#prefix' => '<div id="10m_40m" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);

            $form['form_fields_wrap']['maximal_strength']['starter_peak_for_max'] = array (
              '#type' => 'textfield',
              #'#title' => t('Peak Force (N)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Force (N)'),
              ),
            );

             $form['form_fields_wrap']['maximal_strength']['starter_rfd_max'] = array (
              '#type' => 'textfield',
              #'#title' => t('RFD @ 100ms (N)'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('RFD @ 100ms (N)'),
              ),
            );

             /*********** elete fields start ************/
                       if($formtype == 'elete'){
                          $required = TRUE;
                       }else{
                          $required = FALSE;
                       }
            if($formtype == 'elete'){
                //UE Power (SPM Ball Throw)
                $form['form_fields_wrap']['ue_power'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('UE Power (SSM Ball Throw)'),
                  '#prefix' => '<div id="ue_power1" class="sm_cls">',
                  '#suffix' => '</div>',
                );

                $form['form_fields_wrap']['ue_power']['power'] = array (
                  '#type' => 'textfield',
                  #'#title' => t('Power (W)'),
                  '#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );

                 //UE Power (SPM Ball Throw)
                 $form['form_fields_wrap']['ue_power_spm'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('UE Power (SPM Ball Throw)'),
                  '#prefix' => '<div id="ue_power2" class="sm_cls">',
                  '#suffix' => '</div>',
                );
                
                $form['form_fields_wrap']['ue_power_spm']['power_spm'] = array (
                  '#type' => 'textfield',
                 # '#title' => t('Power (W)'),
                  '#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );
                //UE Power (RM Ball Throw)
                 $form['form_fields_wrap']['ue_power_rm'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('UE Power (RM Ball Throw)'),
                  '#prefix' => '<div id="ue_power3" class="sm_cls">',
                  '#suffix' => '</div>',
                );
                $form['form_fields_wrap']['ue_power_rm']['power_rm'] = array (
                  '#type' => 'textfield',
                  #'#title' => t('Power (W)'),
                  '#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );

                //Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)
                $form['form_fields_wrap']['strength_endurance'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)'),
                  '#prefix' => '<div id="strength_endurance" class="sm_cls">',
                  '#suffix' => '</div>',
                );

                 $form['form_fields_wrap']['strength_endurance']['repetitions'] = array (
                  '#type' => 'textfield',
                  #'#title' => t('Repetitions (#)'),
                  '#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Repetitions (#)'),
                  ),
                );

                //Change of Direction (5-10-5 Pro Agility Test)
                   $form['form_fields_wrap']['change_of_direction'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('Change of Direction (5-10-5 Pro Agility Test)'),
                  '#prefix' => '<div id="change_of_direction" class="sm_cls">',
                  '#suffix' => '</div>',
                );

                 $form['form_fields_wrap']['change_of_direction']['power_ch'] = array (
                  '#type' => 'textfield',
                  #'#title' => t('Power (W)'),
                  '#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );
            }
            /*********** elete fields start ************/

             //hidden fields

            if($formtype == 'elete'){  
             $formtype_val = 'elete';
            }elseif($formtype == 'starter'){
             $formtype_val = 'starter';
            }else{
              $formtype_val = '';
            }

            if($Assess_type == 'individual'){  
             $Assess_type_val = 'individual';
            }elseif($Assess_type == 'private'){
             $Assess_type_val = 'private';
            }else{
              $Assess_type_val = '';
            }

            $form['assessment_type'] = array(
             '#type' => 'hidden',
             '#value' => 'individual',
            );

            $form['form_type'] = array(
             '#type' => 'hidden',
             '#value' => $formtype_val,
            );

            $form['booked_id'] = array(
             '#type' => 'hidden',
             '#value' => $booked_id,
            );
            
             $form['athelete_nid'] = array(
             '#type' => 'hidden',
             '#value' => $nid,
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
      }else{
        return;
      }
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
        $param = \Drupal::request()->query->all();
        if(!empty($param)){
          $nid = $param['nid'];
          $formtype = $param['formtype'];
          $Assess_type = $param['Assess_type'];  
        } 
    		$triggerElement = $form_state->getTriggeringElement();
        //current user
        $current_user = \Drupal::currentUser();
        $user_id = $current_user->id();
        $user = \Drupal\user\Entity\User::load($user_id);

  	  	$form_data = [];
  	  	foreach ($form_state->getValues() as $key => $value) {
  	  	 	$form_data[$key] = $value;
  	  	}
        
        //insert
        $node = Node::create([
           'type' => 'athlete_assessment_info',
           'title' => $form_data['starter_weight_rea_str'],
        ]);  
        $node->set('field_jump_height_in_reactive', $form_data['starter_jump_height_rea_str']);
        $node->set('field_rsi_reactive', $form_data['starter_rsi_rea_str']);
        $node->set('field_jump_height_in_elastic', $form_data['starter_jump_height_ela_str']);
        $node->set('field_peak_propulsive_elastic', $form_data['starter_peak_pro_ela_str']);
        $node->set('field_peak_power_w_elastic', $form_data['starter_peak_power_ela_str']);
        $node->set('field_jump_height_in_ballistic', $form_data['starter_jump_height_ballistic']);
        $node->set('field_peak_propulsive_ballistic', $form_data['starter_peak_pro_ballistic']);
        $node->set('field_peak_power_w_ballistic', $form_data['starter_peak_power_ballistic']);
        $node->set('field_10m_time_sec_sprint', $form_data['starter_10m']);
        $node->set('field_40m_time_sec_sprint', $form_data['starter_40m']);
        $node->set('field_peak_force_n_maximal', $form_data['starter_peak_for_max']);
        $node->set('field_rfd_100ms_n_maximal', $form_data['starter_rfd_max']);
        $node->set('field_assessment_type', $form_data['assessment_type']);
        $node->set('field_form_type', $form_data['form_type']);
        $node->set('field_athelete_nid', $form_data['athelete_nid']);
        $node->set('field_booked_id', $form_data['booked_id']);
        //aditional fields for elete
        $node->set('field_power_w_ssm_ipe', $form_data['power']);
        $node->set('field_power_w_spm_ipe', $form_data['power_spm']);
        $node->set('field_power_w_rm_ipe', $form_data['power_rm']);
        $node->set('field_repetitions_se_ipe', $form_data['repetitions']);
        $node->set('field_power_w_cfd_ipe', $form_data['power_ch']);
        //user target id 
        $node->set('field_user', ['target_id' => $user_id]);
  	  	if(isset($triggerElement['#id']) && $triggerElement['#id'] == 'edit-draft'){
  	  		// if "SAVE - INCOMPLETE" button trigger
          $node->set('field_status', 'incomplete');
  	  	}
  	  	if (isset($triggerElement['#id']) && $triggerElement['#id'] == 'edit-submit') {
  	  		// if "SAVE - ALL FIELDS COMPLETED" trigger
            $node->set('field_status', 'complete'); 
  	  	}
  	  	$node->setPublished(TRUE);
        $node->save();

   }
}