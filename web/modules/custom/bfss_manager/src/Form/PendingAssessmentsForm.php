<?php
/**
 * @file
 * Contains \Drupal\bfss_manager\Form\PendingAssessmentsForm.
 */
namespace Drupal\bfss_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
class PendingAssessmentsForm extends FormBase {

  /** TODO: make utility class
   * Use this to extract "professional", because $param['formtype'] only contains 'starter' OR 'elite'
   * @param string $assessmentPrice
   */
  public function getFormTypeFromPrice($assessmentPrice) {
    if($assessmentPrice == '299.99'){
      return 'elite';
    }elseif($assessmentPrice == '29.99'){
      return 'starter';
    }elseif($assessmentPrice == '69.99'){
      return 'professional';
    }else{
      return 'UNKNOWN';
    }
  }

  /** TODO: make utility class;
   * Return a url to download the assessment pdf.
   * @param string $pdf_template_fid -- see /admin/structure/fillpdf
   */
  protected function getFillPdfUrl($pdf_template_fid, $nid) {
    $default_entity_id = ''; // $form_state->getValue('form_token'); // currently not used
    return '/fillpdf?fid='.$pdf_template_fid.'&entity_type=node&entity_id='.$nid.'&download=1';
    // http://bfss.mindimage.net/fillpdf?fid=2&entity_type=node&entity_id=310&download=1

  }

  /** TODO: make utility class
   * Find the pdf template "fid" -- see /admin/structure/fillpdf
   * @param string $form_type
   */
  public function getPdfTemplateId($form_type) {
    switch ($form_type) {
      case 'starter':
        return '7';
      case 'professional':
        return '8';
      case 'elite':
        return '9';
    default:
        return -1111;
    }
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pending_assessments_form';
  }

  /**
   * Returns a drupal-style textfield - which is an array of values.
   * @param string $fieldName
   *   Name of the form field, also is the name of the default value (if any)
   * @param int $fieldColumn
   *   0 - for the field to appear on the left
   *   1 - for the field to appear on the right
   * @param string $defaultValue
   *   Optional. Sets the textfield's value attribute.
   * @param string $placeholder
   *   Optional. The text that appears within the textfield when empty.
   * @param string $units
   *   Optional. i.e.  Lbs, In,  Secs
   */
  public function getFormField($fieldName, $fieldColumn, $defaultValue = '', $placeholder = '', $units='') {
    $fieldAry = [];

    $style = ($fieldColumn == 0) ? 'width: 62%;' : 'position: absolute; width: 42%; right: 7.2%;';

    $fieldAry[$fieldName] = array(
      '#type' => 'textfield',
      '#default_value' => $defaultValue,
      #'#required' => TRUE,
      '#attributes' => array(
        'placeholder' => t($placeholder),
        'style' => 'height: calc(1.4em + 0.3rem + 2px); ' . $style,
      ),
    );

    if ($units) {
      $style = ($fieldColumn == 0) ? 'right: 50.3%' : 'right: 7.4%;';
      $fieldWidth = 5;
      $fieldWidth += strlen($units) > 5 ? (strlen($units) * 0.3) : 0;
      $fieldWidth = $fieldWidth . '%';
      $fieldAry[$fieldName.'_unit'] = array(
        '#type' => 'textfield',
        '#default_value' => $units,
        '#attributes' => array(
          'tabindex' => '100',
          'readonly' => 'true',
          'style' => 'height: calc(1.2em + 0.3rem + 2px); text-align: center; margin-top: 1px; border: 1px solid white; background-color: grey; color: white; width: ' . $fieldWidth . '; padding: 0 2px; position: absolute; font-size: 1.12em; font-family: "Agency FB" !important; text-align: center;' . $style
        ),
      );
    }

    return $fieldAry;
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $formFields = $form;
    $param = \Drupal::request()->query->all();
    $nid = $param['nid'];
    $formtype = $param['formtype'];
    $Assess_type = $param['Assess_type'];
    $field_booked_id = $param['booked_id'];
    $st = $param['st'];
    $field_status = $param['field_status'];
    // $assess_nid = $param['assess_nid'];
    $first_name = $param['first_name'];
    $last_name = $param['last_name'];

    if ($field_booked_id) {
      // See if assessment has been started by assessor or mgr.
      // And get latest recorded status (and $assess_nid) for current assessment - see if complete or incomplete.
      $field_status = 'not started';
      $nidPrev = 0;
      $queryStat = \Drupal::entityQuery('node');
      $queryStat->condition('type', 'athlete_assessment_info');
      $queryStat->condition('field_booked_id',$field_booked_id, 'IN');
      $nidsStat = $queryStat->execute();
      if($nidsStat){
        foreach ($nidsStat as $nid) {
          if ($nid > $nidPrev) {
            $nidPrev = $nid;
            $assessmentNode = Node::load($nid);
            $field_status = $assessmentNode->get('field_status')->getValue()[0]['value'];
          }
        }
      }
      $assess_nid = $nidPrev;
    }

    if($nid && $formtype && $Assess_type)
    {
      //jody - use this to extract "professional" (because $formtype only contains 'starter' OR 'elite')
      $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($field_booked_id);

      $sport = $param['sport'] ? $param['sport'] : $entity->mydata->field_sport->value; // to be set in field_sport_assessment
      // $tmp_user_id = $entity->user_id->value;

      $realFormType = $this->getFormTypeFromPrice($entity->service->value);

      $realFormType = $realFormType ? $realFormType : $param['formtype'];

      if($realFormType == 'starter' && $Assess_type == 'individual'){
        $form_title = 'STARTER ASSESSMENT';
      }
      elseif($realFormType == 'professional' && $Assess_type == 'individual'){
        $form_title = 'PROFESSIONAL ASSESSMENT';
      }
      elseif($realFormType == 'elite' && $Assess_type == 'individual'){
        $form_title = 'ELITE ASSESSMENT';
      }
      elseif($realFormType == 'starter' && $Assess_type == 'private'){
        $form_title = 'STARTER ASSESSMENT';
      }
      elseif($realFormType == 'professional' && $Assess_type == 'private'){
        $form_title = 'PROFESSIONAL ASSESSMENT';
      }
      elseif($realFormType == 'elite' && $Assess_type == 'private'){
          $form_title = 'ELITE ASSESSMENT';
      }

      //ksm([' realFormType form_title param', $realFormType, $form_title, $param]);

      $formFields['#attached']['library'][] = 'bfss_assessors/bfss_assessors';
      $formFields['#prefix'] = '
      <!-- Modal start-->
        <div id="assessor_popup_form" class="asse_frm" >
          <div class="">
            <!-- Modal content-->
            <div>
              <div id="accessorform">
                  <div class="accessorform_inner">
                    <div class="usrinfo"><h3>'.$first_name.' '.$last_name.'</h3><ul><li>'.$sport.'</li><li>'.$param['postion'].'</li></ul></div>
                    <h2>'.$form_title.'</h2>
                    <ul class="st_lk">
                    <li>EF-Equipment Failure</li>
                    <li>Al-Athlete Injured</li>
                    <li>ART-Athlete Refused Test</li>
                    </ul>';

      $formFields['#suffix'] = '  </div>
                          </div>
                        </div>
                      </div>
                  </div>
      <!-- Modal end-->';
        $formFields['message'] = [
        '#type' => 'markup',
        '#markup' => '<div class="result_message form_fields_wrap"></div>',
      ];

      if($assess_nid) { // from PendingAssessments.php, assess_nid is 0 by default.
        $node = Node::load($assess_nid);
        // ksm('nnnnnnnnbbbbbbbbbbbbbbb',$assess_nid);
      }
      else {
        $node = array();
      }

      //-------------------------- debug: auto-fill empty form values.
      // $start_value = 200;
      // $debug_fill_inc = 1;
      // foreach ($defaultValues as $key => $val) {
      //   if ($key == 'field_sport_assessment') {
      //     $defaultValues[$key] = 'DEBUG SPORT'; // ALWAYS FORCE-CHANGE THIS VALUE SO WE KNOW WE DUBUGGING.
      //   }
      //   else if ($key == 'field_sex') {
      //     $defaultValues[$key] = $defaultValues[$key] ? $defaultValues[$key] : 'male';
      //   }
      //   else {
      //     $defaultValues[$key] = $defaultValues[$key] ? $defaultValues[$key] : $start_value;
      //     // $defaultValues[$key] = $start_value;
      //   }
      //   $start_value += $debug_fill_inc;
      // }



      // $defaultValues variable NOT USED
      /****xxxxx
      // $defaultValues['starter_weight_rea_str']          = @$node->starter_weight_rea_str->value;       //dd  LEGACY:  starter_weight_rea_str (title???)
      //################ TODO: Remove these and replace $defaultValues[$key]  with @$node->{$fieldName}->value
      $defaultValues['field_peak_propulsive_elastic']   = @$node->field_peak_propulsive_elastic->value;   //dd  LEGACY:  starter_peak_pro_ela_str
      $defaultValues['field_peak_power_w_elastic']      = @$node->field_peak_power_w_elastic->value;      //dd  LEGACY:  starter_peak_power_ela_str
      $defaultValues['field_peak_propulsive_ballistic'] = @$node->field_peak_propulsive_ballistic->value; //dd  LEGACY:  starter_peak_pro_ballistic
      $defaultValues['field_peak_power_w_ballistic']    = @$node->field_peak_power_w_ballistic->value;    //dd  LEGACY:  starter_peak_power_ballistic
      $defaultValues['field_40m_time_sec_sprint']       = @$node->field_40m_time_sec_sprint->value;       //dd  LEGACY:  starter_40m
      $defaultValues['field_rfd_100ms_n_maximal']       = @$node->field_rfd_100ms_n_maximal->value;       //dd  LEGACY:  starter_rfd_max

      // professional and/or elite
      $defaultValues['field_power_w_ssm_ipe']           = @$node->field_power_w_ssm_ipe->value;           // d  LEGACY:  ['ue_power']['power']
      $defaultValues['field_power_w_spm_ipe']           = @$node->field_power_w_spm_ipe->value;           // d  LEGACY:  ['ue_power_spm']['power_spm']
      $defaultValues['field_power_w_rm_ipe']            = @$node->field_power_w_rm_ipe->value;            // d  LEGACY:  ['ue_power_rm']['power_rm']
      $defaultValues['field_repetitions_se_ipe']        = @$node->field_repetitions_se_ipe->value;        // d  LEGACY:  ['strength_endurance']['repetitions']
      $defaultValues['field_power_w_cfd_ipe']           = @$node->field_power_w_cfd_ipe->value;           // d   LEGACY: ['change_of_direction']['power_ch']

      //######## todo: hidden fields hmmmm
      $defaultValues['field_assessment_type']           = @$node->field_assessment_type->value;           //dd  LEGACY:  assessment_type
      // $defaultValues['field_form_type']                 = @$node->field_form_type->value;                 //dd  LEGACY:  form_type
      $defaultValues['field_athelete_nid']              = @$node->field_athelete_nid->value;              //dd  LEGACY:  athelete_nid
      $defaultValues['field_booked_id']                 = @$node->field_booked_id->value;                 //dd  LEGACY:  booked_id
      ****/

      //get rid of fieldNameAry

      $fieldNameAry = [];
      // $fieldNameAry[] = 'field_elite_age_e';
      // $fieldNameAry[] = 'field_rsi_reactive_b';
      // $fieldNameAry[] = 'field_age';
      // $fieldNameAry[] = 'field_sport_assessment';
      // $fieldNameAry[] = 'field_weight';
      // $fieldNameAry[] = 'field_sex';
      // $fieldNameAry[] = 'field_jump_height_in_reactive';
      // $fieldNameAry[] = 'field_jump_height_in_elastic';
      // $fieldNameAry[] = 'field_jump_height_in_ballistic';
      // $fieldNameAry[] = 'field_10m_time_sec_sprint';
      // $fieldNameAry[] = 'field_peak_force_n_maximal';
      // $fieldNameAry[] = 'field_peak_force_n_maximal';

      //------------------------------------ starter form
      if($realFormType == 'starter')
      {
        $formFields['field_wrap_1'] = array(
          '#type' => 'fieldset',
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_age'; $fieldNameAry[] = 'field_age';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'AGE'); // 1
        $fieldName = 'field_sport_assessment'; $fieldNameAry[] = 'field_sport_assessment';  // d
        $default_sport = @$node->{$fieldName}->value ? @$node->{$fieldName}->value : $sport;
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $default_sport, 'SPORT'); // 2

        $fieldName = 'field_weight'; $fieldNameAry[] = 'field_weight';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'WEIGHT', 'Lbs', 'Lbs'); // 3
        $fieldName = 'field_sex'; $fieldNameAry[] = 'field_sex';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'SEX'); // 4

        $fieldName = 'field_jump_height_in_reactive'; $fieldNameAry[] = 'field_jump_height_in_reactive';  //dd  LEGACY:  starter_jump_height_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'REACTIVE STRENGTH', 'In'); // 5
        $fieldName = 'field_jump_height_in_elastic'; $fieldNameAry[] = 'field_jump_height_in_elastic';  //dd  LEGACY:  starter_jump_height_ela_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'ELASTIC STRENGTH', 'In'); // 6

        $fieldName = 'field_jump_height_in_ballistic'; $fieldNameAry[] = 'field_jump_height_in_ballistic';  //dd  LEGACY:  starter_jump_height_ballistic
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'BALLISTIC STRENGTH', 'In'); // 7
        $fieldName = 'field_10m_time_sec_sprint'; $fieldNameAry[] = 'field_10m_time_sec_sprint';  //dd  LEGACY:  starter_10m
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'ACCELERATION/SPEED', 'Sec'); // 8

        // Note: field_rsi_reactive should be called field_jump_height_in_reactive_e
        // Note: field_rsi_reactive_b should be called field_jump_height_in_reactive_b

        $fieldName = 'field_peak_force_n_maximal'; $fieldNameAry[] = 'field_peak_force_n_maximal';  //dd  LEGACY:  starter_peak_for_max
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'MAXIMAL STRENGTH', 'N');  // 9
        $fieldName = 'field_rsi_reactive'; $fieldNameAry[] = 'field_rsi_reactive';  //dd  LEGACY:  starter_rsi_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) REACTIVE STRENGTH', 'In'); // 10

        $fieldName = 'field_rsi_reactive_b'; $fieldNameAry[] = 'field_rsi_reactive_b';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(B) REACTIVE STRENGTH', 'In'); // 11
      }

      //------------------------------------------ professional and elite form
      else if($realFormType == 'professional' || $realFormType == 'elite')
      {
        $formFields['field_wrap_1'] = array(
          '#type' => 'fieldset',
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ASSESSMENT TABLE
        $formFields['field_wrap_1']['#title'] = $this->t('<div style="text-align: center; font-weight: bold;">ASSESSMENT TABLE</div>');

        $fieldName = 'field_age'; $fieldNameAry[] = 'field_age';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'AGE'); // 1
        $fieldName = 'field_sport_assessment'; $fieldNameAry[] = 'field_sport_assessment';  // d
        $default_sport = @$node->{$fieldName}->value ? @$node->{$fieldName}->value : $sport;
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $default_sport, 'SPORT'); // 2

        $fieldName = 'field_weight'; $fieldNameAry[] = 'field_weight';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'WEIGHT', 'Lbs', 'Lbs'); // 3
        $fieldName = 'field_sex'; $fieldNameAry[] = 'field_sex';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'SEX'); // 4

        $fieldName = 'field_jump_height_in_reactive'; $fieldNameAry[] = 'field_jump_height_in_reactive';  //dd  LEGACY:  starter_jump_height_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'REACTIVE STRENGTH', 'In'); // 5
        $fieldName = 'field_jump_height_in_elastic'; $fieldNameAry[] = 'field_jump_height_in_elastic';  //dd  LEGACY:  starter_jump_height_ela_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'ELASTIC STRENGTH', 'In'); // 6

        $fieldName = 'field_jump_height_in_ballistic'; $fieldNameAry[] = 'field_jump_height_in_ballistic';  //dd  LEGACY:  starter_jump_height_ballistic
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'BALLISTIC STRENGTH', 'In'); // 7
        $fieldName = 'field_10m_time_sec_sprint'; $fieldNameAry[] = 'field_10m_time_sec_sprint';  //dd  LEGACY:  starter_10m
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'ACCELERATION/SPEED', 'Sec'); // 8

        // Note: field_rsi_reactive should be called field_jump_height_in_reactive_e
        // Note: field_rsi_reactive_b should be called field_jump_height_in_reactive_b

        $fieldName = 'field_peak_force_n_maximal'; $fieldNameAry[] = 'field_peak_force_n_maximal';  //dd  LEGACY:  starter_peak_for_max
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'MAXIMAL STRENGTH', 'N');  // 9
        $fieldName = 'field_rsi_reactive'; $fieldNameAry[] = 'field_rsi_reactive';  //dd  LEGACY:  starter_rsi_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) REACTIVE STRENGTH', 'In'); // 10

        $fieldName = 'field_rsi_reactive_b'; $fieldNameAry[] = 'field_rsi_reactive_b';  //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(B) REACTIVE STRENGTH', 'In'); // 11
        $fieldName = 'field_jump_height_in_elastic_e'; $fieldNameAry[] = 'field_jump_height_in_elastic_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) ELASTIC STRENGTH', 'In'); // 11

        $fieldName = 'field_jump_height_in_ballistic_e'; $fieldNameAry[] = 'field_jump_height_in_ballistic_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) BALLISTIC STRENGTH', 'In'); // 12
        $fieldName = 'field_10m_time_sec_sprint_e'; $fieldNameAry[] = 'field_10m_time_sec_sprint_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) ACCELERATION/SPEED', 'Sec'); // 13

        $fieldName = 'field_peak_force_n_maximal_e'; $fieldNameAry[] = 'field_peak_force_n_maximal_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) MAXIMAL STRENGTH', 'Lbs'); // 14
        $fieldName = 'field_jump_height_in_elastic_b'; $fieldNameAry[] = 'field_jump_height_in_elastic_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(B) ELASTIC STRENGTH', 'In');

        $fieldName = 'field_jump_height_in_ballistic_b'; $fieldNameAry[] = 'field_jump_height_in_ballistic_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(B) BALLISTIC STRENGTH', 'In'); // 17
        $fieldName = 'field_10m_time_sec_sprint_b'; $fieldNameAry[] = 'field_10m_time_sec_sprint_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(B) ACCELERATION/SPEED', 'Sec'); // 18

        $fieldName = 'field_peak_force_n_maximal_b'; $fieldNameAry[] = 'field_peak_force_n_maximal_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(B) MAXIMAL STRENGTH', 'Lbs'); // 19
        $fieldName = 'field_elite_age_e'; $fieldNameAry[] = 'field_elite_age_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'ELITE PERFORMERS AGE'); // *ELITE PERFORMERS AGE

        $formFields['field_wrap_2'] = array(
          '#type' => 'fieldset',
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ REBOUND JUMP
        $formFields['field_wrap_2']['#title'] = $this->t('<div style="text-align: center; font-weight: bold;">REBOUND JUMP</div>');

        $fieldName = 'field_rebound_jump_rank'; $fieldNameAry[] = 'field_rebound_jump_rank';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'YOUR RANK IS', 'Good'); // 20
        $fieldName = 'field_rebound_jump_rsi'; $fieldNameAry[] = 'field_rebound_jump_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'RSI SCORE', '#.#'); // 21

        $fieldName = 'field_rebound_jump_height'; $fieldNameAry[] = 'field_rebound_jump_height';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'JUMP HEIGHT', 'In'); // 22
        $fieldName = 'field_rj_ground_contact_time'; $fieldNameAry[] = 'field_rj_ground_contact_time';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'GROUND CONTACT TIME', 'ms'); // 23

        $fieldName = 'field_rebound_jump_low_rsi'; $fieldNameAry[] = 'field_rebound_jump_low_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'LOW RSI', '>1.5'); // 24
        $fieldName = 'field_rebound_jump_medium_rsi'; $fieldNameAry[] = 'field_rebound_jump_medium_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'MEDIUM RSI', '1.5-2.0'); // 25

        $fieldName = 'field_rebound_jump_high_rsi'; $fieldNameAry[] = 'field_rebound_jump_high_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'HIGH RSI', '2.0-2.5'); // 26

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ COUNTER MOVEMENT JUMP
        $formFields['field_wrap_3'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">COUNTER MOVEMENT JUMP</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fieldName = 'field_cmj_rank'; $fieldNameAry[] = 'field_cmj_rank';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'YOUR RANK IS', 'Good'); // 27
        $fieldName = 'field_cmj_height'; $fieldNameAry[] = 'field_cmj_height';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'CMJ HEIGHT', 'In'); // 28

        $fieldName = 'field_cmj_height_e'; $fieldNameAry[] = 'field_cmj_height_e';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) CMJ HEIGHT', 'In'); // 29
        $fieldName = 'field_cmj_force'; $fieldNameAry[] = 'field_cmj_force';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'CMJ PEAK FORCE', 'N'); // 30

        $fieldName = 'field_cmj_force_e'; $fieldNameAry[] = 'field_cmj_force_e';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) CMJ PEAK FORCE', 'N'); // 31

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ SQUAT JUMP
        $formFields['field_wrap_4'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">SQUAT JUMP</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_squat_jump_rank'; $fieldNameAry[] = 'field_squat_jump_rank';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 32
        $fieldName = 'field_squat_jump_jump_height'; $fieldNameAry[] = 'field_squat_jump_jump_height';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'JUMP HEIGHT', 'In'); // 33

        $fieldName = 'field_squat_jump_jump_height_e'; $fieldNameAry[] = 'field_squat_jump_jump_height_e';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) JUMP HEIGHT', 'In'); // 34
        $fieldName = 'field_squat_jump_force'; $fieldNameAry[] = 'field_squat_jump_force';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'PEAK FORCE', 'N'); // 35

        $fieldName = 'field_squat_jump_force_e'; $fieldNameAry[] = 'field_squat_jump_force_e';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) PEAK FORCE', 'M'); // 36

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ EUR SCORE
        $formFields['field_wrap_5'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">EUR SCORE</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_eur_score'; $fieldNameAry[] = 'field_eur_score';
        $formFields['field_wrap_5'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'YOUR EUR SCORE'); // 37

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 10M/40M SPRINT
        $formFields['field_wrap_6'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">10M/40M SPRINT</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_sprint_rank'; $fieldNameAry[] = 'field_sprint_rank';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 38
        $fieldName = 'field_sprint_10m'; $fieldNameAry[] = 'field_sprint_10m';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '10M SPRINT', 'Sec'); // 39

        $fieldName = 'field_sprint_10m_e'; $fieldNameAry[] = 'field_sprint_10m_e';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) 10M SPRINT', 'Sec'); // 40
        $fieldName = 'field_sprint_40m'; $fieldNameAry[] = 'field_sprint_40m';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '40M SPRINT', 'Sec'); // 41

        $fieldName = 'field_sprint_40m_e'; $fieldNameAry[] = 'field_sprint_40m_e';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) 40M SPRINT', 'Sec'); // 42
        $fieldName = 'field_sprint_10m_recommend'; $fieldNameAry[] = 'field_sprint_10m_recommend';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Training Recommendation'); // 41

        $fieldName = 'field_sprint_40m_recommend'; $fieldNameAry[] = 'field_sprint_40m_recommend';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Training Recommendation'); // 41

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MID-THIGH PULL
        $formFields['field_wrap_7'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">MID-THIGH PULL</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_mid_thigh_rank'; $fieldNameAry[] = 'field_mid_thigh_rank';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 43
        $fieldName = 'field_mid_thigh_your_weight'; $fieldNameAry[] = 'field_mid_thigh_your_weight';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'YOUR WEIGHT', 'Lbs'); // 44

        $fieldName = 'field_mt_abs_strength_lbs'; $fieldNameAry[] = 'field_mt_abs_strength_lbs';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'ABSOLUTE STRENGTH', 'N'); // 45
        $fieldName = 'field_mt_abs_strength_n'; $fieldNameAry[] = 'field_mt_abs_strength_n';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'ABSOLUTE STRENGTH', 'Lbs'); // 46

        $fieldName = 'field_mt_abs_strength_lbs_e'; $fieldNameAry[] = 'field_mt_abs_strength_lbs_e';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) ABSOLUTE STRENGTH', 'N'); // 47
        $fieldName = 'field_mt_abs_strength_n_e'; $fieldNameAry[] = 'field_mt_abs_strength_n_e';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) ABSOLUTE STRENGTH', 'Lbs'); // 48

        $fieldName = 'field_mid_thigh_rel_strength'; $fieldNameAry[] = 'field_mid_thigh_rel_strength';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'RELATIVE STRENGTH', '%'); // 49
        $fieldName = 'field_mid_thigh_rel_strength_e'; $fieldNameAry[] = 'field_mid_thigh_rel_strength_e';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) RELATIVE STRENGTH', '%'); // 50

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ RATE OF FORCE
        $formFields['field_wrap_8'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">RATE OF FORCE</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_force_rate_peak'; $fieldNameAry[] = 'field_force_rate_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'PEAK FORCE', 'Lbs'); // 51
        $fieldName = 'field_force_rate_your_weight'; $fieldNameAry[] = 'field_force_rate_your_weight';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'YOUR WEIGHT', 'Lbs'); // 52

        $fieldName = 'field_force_rate_peak_weight'; $fieldNameAry[] = 'field_force_rate_peak_weight';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'PEAK FORCE/BODY WEIGHT', '#.#x'); // 53
        $fieldName = 'field_force_rate_force_n'; $fieldNameAry[] = 'field_force_rate_force_n';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'FORCE', 'N'); // 54

        $fieldName = 'field_force_rate_rfd'; $fieldNameAry[] = 'field_force_rate_rfd';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'RFD', '%'); // 55
        $fieldName = 'field_force_rate_low_peak'; $fieldNameAry[] = 'field_force_rate_low_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'LOW PEAK FORCE'); // 56

        $fieldName = 'field_force_rate_medium_peak'; $fieldNameAry[] = 'field_force_rate_medium_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'MEDIUM PEAK FORCE'); // 57
        $fieldName = 'field_force_rate_high_peak'; $fieldNameAry[] = 'field_force_rate_high_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'HIGH PEAK FORCE'); // 58

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ DYNAMIC STRENGTH INDEX
        $formFields['field_wrap_9'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">DYNAMIC STRENGTH INDEX</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_dynamic_strength_impt_peak'; $fieldNameAry[] = 'field_dynamic_strength_impt_peak';
        $formFields['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'IMTP PEAK FORCE', 'N'); // 59
        $fieldName = 'field_dynamic_strength_cmj_peak'; $fieldNameAry[] = 'field_dynamic_strength_cmj_peak';
        $formFields['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'CMJ PEAK FORCE', 'N'); // 60

        $fieldName = 'field_dynamic_strength_dsi_score'; $fieldNameAry[] = 'field_dynamic_strength_dsi_score';
        $formFields['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'YOUR DSI SCORE', '.##'); // 61

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ SUMMARY
        $formFields['field_wrap_91'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">SUMMARY</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_summary_reactive'; $fieldNameAry[] = 'field_summary_reactive';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'REACTIVE STRENGTH', '#.#'); // 62
        $fieldName = 'field_summary_reactive_e'; $fieldNameAry[] = 'field_summary_reactive_e';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) REACTIVE STRENGTH', '#.#'); // 63

        $fieldName = 'field_summary_reactive_b'; $fieldNameAry[] = 'field_summary_reactive_b';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(B) REACTIVE STRENGTH (##TH PERCENTILE)'); // 64
        $fieldName = 'field_summary_dynamic'; $fieldNameAry[] = 'field_summary_dynamic';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'DYNAMIC STRENGTH', '#.#%'); // 65

        $fieldName = 'field_summary_dynamic_e'; $fieldNameAry[] = 'field_summary_dynamic_e';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) DYNAMIC STRENGTH', '#.#%'); // 66
        $fieldName = 'field_summary_dynamic_b'; $fieldNameAry[] = 'field_summary_dynamic_b';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(B) DYNAMIC STRENGTH', 'N/A'); // 67

        $fieldName = 'field_summary_ecc'; $fieldNameAry[] = 'field_summary_ecc';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'ECC. RATIO', '#.##'); // 68
        $fieldName = 'field_summary_ecc_e'; $fieldNameAry[] = 'field_summary_ecc_e';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(E) ECC. RATIO', '#.##'); // 69

        $fieldName = 'field_summary_ecc_b'; $fieldNameAry[] = 'field_summary_ecc_b';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(B) ECC. RATIO', 'N/A'); // 70
        $fieldName = 'field_summary_relative'; $fieldNameAry[] = 'field_summary_relative';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'RELATIVE STRENGTH', 'Lbs'); // 71

        $fieldName = 'field_summary_relative_e'; $fieldNameAry[] = 'field_summary_relative_e';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '(E) RELATIVE STRENGTH', 'Lbs'); // 72
        $fieldName = 'field_summary_relative_b'; $fieldNameAry[] = 'field_summary_relative_b';
        $formFields['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '(B) RELATIVE STRENGTH (##TH PERCENTILE)'); // 73

      }


      //------------------------------------------ elite-only form fields
      if($realFormType == 'elite')
      {

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ BFS Testing: Performance Tests
        $formFields['field_wrap_100'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">BFS Testing: Performance Tests</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        // Files which will probably be hidden or calculated:
        //    'field_elite_percent_e'

        $fieldName = 'field_static_ball_throw'; $fieldNameAry[] = 'field_static_ball_throw';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Static Med Ball Throw', 'W');
        $fieldName = 'field_static_ball_throw_e'; $fieldNameAry[] = 'field_static_ball_throw_e';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite Static Med Ball Throw', 'W');

        $fieldName = 'field_static_ball_throw_b'; $fieldNameAry[] = 'field_static_ball_throw_b';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Static Med Ball Throw Benchmark', 'W');
        $fieldName = 'field_rotate_ball_throw'; $fieldNameAry[] = 'field_rotate_ball_throw';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Rotational Med Ball Throw', 'W');

        $fieldName = 'field_rotate_ball_throw_e'; $fieldNameAry[] = 'field_rotate_ball_throw_e';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Elite Rotational Med Ball Throw', 'W');
        $fieldName = 'field_rotate_ball_throw_b'; $fieldNameAry[] = 'field_rotate_ball_throw_b';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Rotational Med Ball Throw Benchmark', 'W');

        $fieldName = 'field_single_leg_strength'; $fieldNameAry[] = 'field_single_leg_strength';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Single Leg Strength', '#');
        $fieldName = 'field_single_leg_strength_e'; $fieldNameAry[] = 'field_single_leg_strength_e';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite Single Leg Strength', '#');

        $fieldName = 'field_single_leg_strength_b'; $fieldNameAry[] = 'field_single_leg_strength_b';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Single Leg Strength Benchmark', '#');
        $fieldName = 'field_push_ups_num'; $fieldNameAry[] = 'field_push_ups_num';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Push-Ups', '#');

        $fieldName = 'field_push_ups_num_e'; $fieldNameAry[] = 'field_push_ups_num_e';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Elite Push-Ups', '#');
        $fieldName = 'field_push_ups_num_b'; $fieldNameAry[] = 'field_push_ups_num_b';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Push-Ups Benchmark', '#');

        $fieldName = 'field_agility_sec'; $fieldNameAry[] = 'field_agility_sec';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '5-10-5 Agility Test', 'Secs');
        $fieldName = 'field_agility_sec_e'; $fieldNameAry[] = 'field_agility_sec_e';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite 5-10-5 Agility Test', 'Secs');

        $fieldName = 'field_agility_sec_b'; $fieldNameAry[] = 'field_agility_sec_b';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '5-10-5 Agility Test Benchmark', 'Secs');
        $fieldName = 'field_bike_test_time'; $fieldNameAry[] = 'field_bike_test_time';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '1.5 Mile Bike Test', 'Time');

        $fieldName = 'field_bike_test_time_e'; $fieldNameAry[] = 'field_bike_test_time_e';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Elite 1.5 Mile Bike Test', 'Time');
        $fieldName = 'field_bike_test_time_b'; $fieldNameAry[] = 'field_bike_test_time_b';
        $formFields['field_wrap_100'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, '1.5 Mile Bike Test Benchmark', 'Time');


        //~~~~~~~~~~~~~~~~~~~~ Results: Supine Med Ball Chest Throw
        $formFields['field_wrap_101'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Supine Med Ball Chest Throw</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_static_chest_throw'; $fieldNameAry[] = 'field_static_chest_throw';
        $formFields['field_wrap_101'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Static Chest Throw', '');
        $fieldName = 'field_polyometric_chest_throw'; $fieldNameAry[] = 'field_polyometric_chest_throw';
        $formFields['field_wrap_101'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Plyometric Chest Throw', '');

        $fieldName = 'field_eccentric_ratio_eur'; $fieldNameAry[] = 'field_eccentric_ratio_eur';
        $formFields['field_wrap_101'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Eccentric Utilization Ratio (EUR)', '');


        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Results: Rotational med Ball Throw Test
        $formFields['field_wrap_102'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Results: Rotational med Ball Throw Test</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_rot_ball_throw'; $fieldNameAry[] = 'field_rot_ball_throw';
        $formFields['field_wrap_102'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Rotational Power', 'W');
        $fieldName = 'field_rot_ball_throw_e'; $fieldNameAry[] = 'field_rot_ball_throw_e';
        $formFields['field_wrap_102'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite Rotational Power', 'W');

        $fieldName = 'field_rot_rank'; $fieldNameAry[] = 'field_rot_rank';
        $formFields['field_wrap_102'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Rotational Power Rank', '');


        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Results: Strength Endurance Testing
        $formFields['field_wrap_103'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Results: Strength Endurance Testing</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_chin_ups_reps'; $fieldNameAry[] = 'field_chin_ups_reps';
        $formFields['field_wrap_103'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Chin Up Reps', 'per/sec');
        $fieldName = 'field_chin_ups_reps_e'; $fieldNameAry[] = 'field_chin_ups_reps_e';
        $formFields['field_wrap_103'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite Chin Up Reps', 'per/sec');

        $fieldName = 'field_push_ups_reps'; $fieldNameAry[] = 'field_push_ups_reps';
        $formFields['field_wrap_103'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Push Up Reps', 'per/sec');
        $fieldName = 'field_push_ups_reps_e'; $fieldNameAry[] = 'field_push_ups_reps_e';
        $formFields['field_wrap_103'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite Push Up Reps', 'per/sec');

        $fieldName = 'field_single_leg_squat'; $fieldNameAry[] = 'field_single_leg_squat';
        $formFields['field_wrap_103'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Single Leg squat', 'per/sec');
        $fieldName = 'field_single_leg_squat_e'; $fieldNameAry[] = 'field_single_leg_squat_e';
        $formFields['field_wrap_103'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite Single Leg squat', '');


        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Results: 5-10-5 Agility Test
        $formFields['field_wrap_104'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Results: 5-10-5 Agility Test</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_5_10_5_agile'; $fieldNameAry[] = 'field_5_10_5_agile';
        $formFields['field_wrap_104'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '5-10-5 Agility', 'secs');
        $fieldName = 'field_5_10_5_agile_e'; $fieldNameAry[] = 'field_5_10_5_agile_e';
        $formFields['field_wrap_104'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite 5-10-5 Agility', 'secs');

        $fieldName = 'field_5_10_5_rank'; $fieldNameAry[] = 'field_5_10_5_rank';
        $formFields['field_wrap_104'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '5-10-5 Rank', '');


        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Results: 1.5 Mile Bike Test
        $formFields['field_wrap_105'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Results: 1.5 Mile Bike Test</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        $fieldName = 'field_1_5_mile_bike'; $fieldNameAry[] = 'field_1_5_mile_bike';
        $formFields['field_wrap_105'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, '1.5 mile Bike Test', '');
        $fieldName = 'field_1_5_mile_bike_e'; $fieldNameAry[] = 'field_1_5_mile_bike_e';
        $formFields['field_wrap_105'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite 1.5 mile Bike Test', '');

        $fieldName = 'field_heart_rate_recover'; $fieldNameAry[] = 'field_heart_rate_recover';
        $formFields['field_wrap_105'][$fieldName] = $this->getFormField($fieldName, 0, @$node->{$fieldName}->value, 'Heart Rate Recovery', '');
        $fieldName = 'field_heart_rate_recover_e'; $fieldNameAry[] = 'field_heart_rate_recover_e';
        $formFields['field_wrap_105'][$fieldName] = $this->getFormField($fieldName, 1, @$node->{$fieldName}->value, 'Elite Heart Rate Recovery', '');



        /*********** elite fields start ************/
        // if( 0   &&   $realFormType == 'elite'){
        //   $required = TRUE;
        // }else{
        //   $required = FALSE;
        // }
        // if( 0   &&   $realFormType == 'elite') {
        //     //UE Power (SPM Ball Throw)
        //     $formFields['form_fields_wrap']['ue_power'] = array(
        //       '#type' => 'fieldset',
        //       '#title' => $this->t('UE Power (SSM Ball Throw)'),
        //       '#prefix' => '<div id="ue_power1" class="sm_cls">',
        //       '#suffix' => '</div>',
        //     );

        //     $formFields['form_fields_wrap']['ue_power']['power'] = array (
        //       '#type' => 'textfield',
        //       '#default_value' => $field_power_w_ssm_ipe,
        //       #'#required' => $required,
        //       '#attributes' => array(
        //         'placeholder' => t('Power (W)'),
        //       ),
        //     );

        //       //UE Power (SPM Ball Throw)
        //       $formFields['form_fields_wrap']['ue_power_spm'] = array(
        //       '#type' => 'fieldset',
        //       '#title' => $this->t('UE Power (SPM Ball Throw)'),
        //       '#prefix' => '<div id="ue_power2" class="sm_cls">',
        //       '#suffix' => '</div>',
        //     );

        //     $formFields['form_fields_wrap']['ue_power_spm']['power_spm'] = array (
        //       '#type' => 'textfield',
        //       '#default_value' => $field_power_w_spm_ipe,
        //       #'#required' => $required,
        //       '#attributes' => array(
        //         'placeholder' => t('Power (W)'),
        //       ),
        //     );
        //     //UE Power (RM Ball Throw)
        //       $formFields['form_fields_wrap']['ue_power_rm'] = array(
        //       '#type' => 'fieldset',
        //       '#title' => $this->t('UE Power (RM Ball Throw)'),
        //       '#prefix' => '<div id="ue_power3" class="sm_cls">',
        //       '#suffix' => '</div>',
        //     );
        //     $formFields['form_fields_wrap']['ue_power_rm']['power_rm'] = array (
        //       '#type' => 'textfield',
        //       '#default_value' => $field_power_w_rm_ipe,
        //       #'#required' => $required,
        //       '#attributes' => array(
        //         'placeholder' => t('Power (W)'),
        //       ),
        //     );

        //     //Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)
        //     $formFields['form_fields_wrap']['strength_endurance'] = array(
        //       '#type' => 'fieldset',
        //       '#title' => $this->t('Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)'),
        //       '#prefix' => '<div id="strength_endurance" class="sm_cls">',
        //       '#suffix' => '</div>',
        //     );

        //       $formFields['form_fields_wrap']['strength_endurance']['repetitions'] = array (
        //       '#type' => 'textfield',
        //       '#default_value' => $field_repetitions_se_ipe,
        //       #'#required' => $required,
        //       '#attributes' => array(
        //         'placeholder' => t('Repetitions (#)'),
        //       ),
        //     );

        //     //Change of Direction (5-10-5 Pro Agility Test)
        //         $formFields['form_fields_wrap']['change_of_direction'] = array(
        //       '#type' => 'fieldset',
        //       '#title' => $this->t('Change of Direction (5-10-5 Pro Agility Test)'),
        //       '#prefix' => '<div id="change_of_direction" class="sm_cls">',
        //       '#suffix' => '</div>',
        //     );

        //       $formFields['form_fields_wrap']['change_of_direction']['power_ch'] = array (
        //       '#type' => 'textfield',
        //       '#default_value' => $field_power_w_cfd_ipe,
        //       #'#required' => $required,
        //       '#attributes' => array(
        //         'placeholder' => t('Power (W)'),
        //       ),
        //     );

      }

      /*********** elite fields start ************/

        //hidden fields

      if($realFormType == 'elite'){
        $formtype_val = 'elite';
      }elseif($realFormType == 'starter'){
        $formtype_val = 'starter';
      }elseif($realFormType == 'professional'){
        $formtype_val = 'professional';
      }

      $formFields['field_assessment_type'] = array(
        '#type' => 'hidden',
        '#value' => 'individual',
      );

      $formFields['field_form_type'] = array(
        '#type' => 'hidden',
        '#value' => $formtype_val,
      );

      $formFields['field_booked_id'] = array(
        '#type' => 'hidden',
        '#value' => $field_booked_id,
        );

      // not used, using field_sport_assessment, but just in case, maybe set default value
      $formFields['sport'] = array(
        '#type' => 'hidden',
        '#value' => $sport,
        );

        $formFields['field_first_name'] = array(
        '#type' => 'hidden',
        '#value' => $first_name,
        );

        $formFields['field_last_name'] = array(
        '#type' => 'hidden',
        '#value' => $last_name,
        );

        $formFields['field_athelete_nid'] = array(
        '#type' => 'hidden',
        '#value' => $nid,
      );


      $formFields['actions'] = array(
        '#type' => 'fieldset',
        '#attributes' => array(
          'style' => 'position: sticky; bottom: 0; background-color: white; padding-top: 4px;',
        ),
        '#prefix' => '<div>',
        '#suffix' => '</div>',
      );

      $formFields['actions']['#type'] = 'actions';


      $formFields['actions']['draft'] = array(
        '#type' => 'submit',
        '#name' => 'save_unpublished',
        '#value' => $this->t('SAVE'),
        '#button_type' => 'primary',
          '#ajax' => [
            'callback' => '::submitForm', // don't forget :: when calling a class method.
            //'callback' => [$this, 'myAjaxCallback'], //alternative notation
            'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggeringuse Drupal\Core\Ajax\HtmlCommand; element.
            'event' => 'click',
            'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
            'progress' => [
              'type' => 'throbber',
              'message' => $this->t('Verifying entry...'),
            ],
          ]
      );



      $formFields['actions']['submit'] = array(
        '#type' => 'submit',
        '#name' => 'save_published',
        '#value' => $this->t('SAVE & PUBLISH'),
        '#button_type' => 'primary',
        '#ajax' => [
            'callback' => '::submitForm', // don't forget :: when calling a class method.
            //'callback' => [$this, 'myAjaxCallback'], //alternative notation
            'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
            'event' => 'click',
            'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
            'progress' => [
              'type' => 'throbber',
              'message' => $this->t('Verifying entry...'),
            ],
          ]
      );

      // ksm(['formFields', $formFields]);

      return $formFields;
    }
    else
    {
      return;
    }
  }


  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$formFields, FormStateInterface $form_state) {
    }


  /**
   * {@inheritdoc}
   */
  protected function getFormData(FormStateInterface $form_state, bool $includeUnits) {
    $data = [];
    foreach ($form_state->getValues() as $key => $value) {
      if (!preg_match('/^(.+)_unit$/i', $key)) {
        $data[$key] = $value;
      }
    }
    if ($includeUnits) {
      foreach ($form_state->getValues() as $key => $value) {
        if (preg_match('/^(.+)_unit$/i', $key, $matches)) {
          if (isset($data[$matches[1]]) && $data[$matches[1]] != '') {
            // add units to end - ie " lbs"
            $data[$matches[1]] .= ' ' . $value;
          }
        }
      }
    }
    return $data;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$formFields, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();
    $triggerElement = $form_state->getTriggeringElement();
    if(!empty($param)){
        $nid = $param['nid'];
        $formtype = $param['formtype'];
        $Assess_type = $param['Assess_type'];
        $field_booked_id = $param['field_booked_id'];
        $st = $param['st'];
        $assess_nid = $param['assess_nid'];
    }
    //check node already exist
    $query1 = \Drupal::entityQuery('node');
    $query1->condition('type', 'athlete_assessment_info');
    $query1->condition('field_booked_id',$field_booked_id, 'IN');
    $nids1 = $query1->execute();


    //current user
    $current_user = \Drupal::currentUser();
    $user_id = $current_user->id();
    $user = \Drupal\user\Entity\User::load($user_id);

    $form_data = $this->getFormData($form_state, false);

    //jjj hack, added all "session" stuff here, to create pdf.
    // session_start();
    // $_SESSION['temp-session-form-values'] = [];
    // $_SESSION['temp-session-form-values'] = $this->getFormData($form_state, true);
    // Form field to pdf field mappings:
    // $_SESSION['temp-session-form-values']['FULL_NAME_TOP'] = $_SESSION['temp-session-form-values']['first_name'] . ' ' . $_SESSION['temp-session-form-values']['last_name'];

    $form_data['title'] = @$form_data['title'] ? $form_data['title'] : 'Starter Assessment'; // $_SESSION['temp-session-form-values']['field_form_type'];

    $pdf_template_fid = $this->getPdfTemplateId($form_data['field_form_type']);

    $default_entity_id = $form_state->getValue('form_token'); // currently not used

    $message = '<div style="padding: 2px; margin: 0 15px 2px 15px; font-size: 1.1em; font-weight: bold; color:green; background-color: #eee; border-radius: 4px;">';

    if (@$form_data['save_published']) {
      $message .= ' Saved and Published!';
    } else if (@$form_data['save_unpublished']) {
      $message .= ' Saved!';
    }

    // ksm(['$pdf_template_fid, $assess_nid, form_data, $param', $pdf_template_fid, $assess_nid, $form_data, $param]);

    if ($pdf_template_fid) {
      // $fillPdfUrl = $this->getFillPdfUrl($pdf_template_fid, $assess_nid);
      // $message .= ' &nbsp; &nbsp; <a href="'.$fillPdfUrl.'" target="_blank">Generate PDF</a><br>';
    }
    $message .= "</div>";

    /****
     * TODO
     * error checking below.
     */
    /***/
      // elseif(!is_numeric($form_state->getValue('starter_weight_rea_str')) || empty($form_state->getValue('starter_weight_rea_str'))){
      //   $message = '<p style="color:red;">"Weight (N) Calculated into Ibs" Required or Numeric</p>';
      // }

    if (  0  ) {
      // if (!is_numeric($form_state->getValue('starter_weight_rea_str')) || empty($form_state->getValue('starter_weight_rea_str'))) {
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif (!is_numeric($form_state->getValue('field_jump_height_in_reactive')) || empty($form_state->getValue('field_jump_height_in_reactive'))) {
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_rsi_reactive')) || empty($form_state->getValue('field_rsi_reactive'))){
      //   $message = '<p style="color:red;">"RSI" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_jump_height_in_elastic')) || empty($form_state->getValue('field_jump_height_in_elastic'))){
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_propulsive_elastic')) || empty($form_state->getValue('field_peak_propulsive_elastic'))){
      //   $message = '<p style="color:red;">"Peak Propulslve Force (N)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_power_w_elastic')) || empty($form_state->getValue('field_peak_power_w_elastic'))){
      //   $message = '<p style="color:red;">"Peak Power (W)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_jump_height_in_ballistic')) || empty($form_state->getValue('field_jump_height_in_ballistic'))){
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_propulsive_ballistic')) || empty($form_state->getValue('field_peak_propulsive_ballistic'))){
      //   $message = '<p style="color:red;">"Peak Propulslve Force (N)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_10m_time_sec_sprint')) || empty($form_state->getValue('field_10m_time_sec_sprint'))){
      //   $message = '<p style="color:red;">"10 M Time (sec)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_40m_time_sec_sprint')) || empty($form_state->getValue('field_40m_time_sec_sprint'))){
      //   $message = '<p style="color:red;">"40 M Time (sec)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_force_n_maximal')) || empty($form_state->getValue('field_peak_force_n_maximal'))){
      //   $message = '<p style="color:red;">"Peak Force (N)" Required or Numeric</p>';
      // }elseif(!is_numeric($form_state->getValue('field_rfd_100ms_n_maximal')) || empty($form_state->getValue('field_rfd_100ms_n_maximal'))){
      //   $message = '<p style="color:red;">"RFD @ 100ms (N)" Required or Numeric</p>';
      // }
      // elseif( (!is_numeric($form_state->getValue('power')) || empty($form_state->getValue('power'))) && $formtype == 'elite' ){
      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }
      // elseif((!is_numeric($form_state->getValue('power_spm')) || empty($form_state->getValue('power_spm'))) && $formtype == 'elite'){

      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }
      // elseif( (!is_numeric($form_state->getValue('power_rm')) || empty($form_state->getValue('power_rm'))) && $formtype == 'elite'){
      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }
      // elseif((!is_numeric($form_state->getValue('repetitions')) || empty($form_state->getValue('repetitions'))) && $formtype == 'elite'){
      //   $message = '<p style="color:red;">"Repetitions (#)" Required or Numeric</p>';
      // }elseif((!is_numeric($form_state->getValue('power_ch')) || empty($form_state->getValue('power_ch'))) && $formtype == 'elite'){
      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }

        }
        else
        {
          if(!$nids1){
            //--------------------------------------------------------------- create db record.
            $node = Node::create([
              'type' => 'athlete_assessment_info',
            ]);
          }else{
            //--------------------------------------------------------------- update db record.
            $node = Node::load($nids1);
          }

          // $node->set('title', $form_data['starter_weight_rea_str']);
          // $node->set('starter_weight_rea_str', $form_data['starter_weight_rea_str']);
          //??? $node->set('field_jump_height_in_reactive', $form_data['starter_weight_rea_str']);

          $node->set('title', 'starter forms'); // $form_data['title']);  -- think this is needed for fillpdf mod.

          foreach ($form_data as $key => $val) {
            if (isset($form_data[$key])) {
              if ($key != 'draft' && $key != 'submit' && $key != 'save_published' && $key != 'save_unpublished' && $key != 'form_build_id' && $key != 'form_token' && $key != 'form_id') {
                $node->set($key, $form_data[$key]);
              }
            }
          }

          // ksm('$form_data', $form_data);

          // $node->set('field_age', $form_data['field_age']); //dd
          // $node->set('field_sport_assessment', $form_data['field_sport_assessment']); //dd
          // $node->set('field_weight', $form_data['field_weight']); //dd
          // $node->set('field_sex', $form_data['field_sex']); //dd
          // $node->set('field_rsi_reactive_b', $form_data['field_rsi_reactive_b']); //dd
          // $node->set('field_elite_age_e', $form_data['field_elite_age_e']); //dd

          // if (isset($form_data['field_jump_height_in_reactive'])) $node->set('field_jump_height_in_reactive', $form_data['field_jump_height_in_reactive']); //dd
          // if (isset($form_data['field_rsi_reactive'])) $node->set('field_rsi_reactive', $form_data['field_rsi_reactive']); //dd
          // if (isset($form_data['field_jump_height_in_elastic'])) $node->set('field_jump_height_in_elastic', $form_data['field_jump_height_in_elastic']); //dd
          // if (isset($form_data['field_peak_propulsive_elastic'])) $node->set('field_peak_propulsive_elastic', $form_data['field_peak_propulsive_elastic']); //dd
          // if (isset($form_data['field_peak_power_w_elastic'])) $node->set('field_peak_power_w_elastic', $form_data['field_peak_power_w_elastic']); //dd
          // if (isset($form_data['field_jump_height_in_ballistic'])) $node->set('field_jump_height_in_ballistic', $form_data['field_jump_height_in_ballistic']); //dd
          // if (isset($form_data['field_peak_propulsive_ballistic'])) $node->set('field_peak_propulsive_ballistic', $form_data['field_peak_propulsive_ballistic']); //dd
          // if (isset($form_data['field_peak_power_w_ballistic'])) $node->set('field_peak_power_w_ballistic', $form_data['field_peak_power_w_ballistic']); //dd
          // if (isset($form_data['field_10m_time_sec_sprint'])) $node->set('field_10m_time_sec_sprint', $form_data['field_10m_time_sec_sprint']); //dd
          // if (isset($form_data['field_40m_time_sec_sprint'])) $node->set('field_40m_time_sec_sprint', $form_data['field_40m_time_sec_sprint']); //dd
          // if (isset($form_data['field_peak_force_n_maximal'])) $node->set('field_peak_force_n_maximal', $form_data['field_peak_force_n_maximal']); //dd
          // if (isset($form_data['field_rfd_100ms_n_maximal'])) $node->set('field_rfd_100ms_n_maximal', $form_data['field_rfd_100ms_n_maximal']); //dd
          // if (isset($form_data['field_assessment_type'])) $node->set('field_assessment_type', $form_data['field_assessment_type']); //dd - List Text
          // if (isset($form_data['field_form_type'])) $node->set('field_form_type', $form_data['field_form_type']); //dd - List (i.e. starter, professional, or elite)
          // if (isset($form_data['field_athelete_nid'])) $node->set('field_athelete_nid', $form_data['field_athelete_nid']); //dd - Entity Ref
          // if (isset($form_data['field_booked_id'])) $node->set('field_booked_id', $form_data['field_booked_id']); //dd
          // aditional fields for elite
          // $node->set('field_power_w_ssm_ipe', $form_data['power']); //d
          // $node->set('field_power_w_spm_ipe', $form_data['power_spm']); //d
          // $node->set('field_power_w_rm_ipe', $form_data['power_rm']); //d
          // $node->set('field_repetitions_se_ipe', $form_data['repetitions']); //d
          // $node->set('field_power_w_cfd_ipe', $form_data['power_ch']); //d
          // $node->set('field_user', ['target_id' => $user_id]); //dd Entity Ref
        }

        if (@$form_data['save_unpublished']) {
          $node->set('field_status', 'incomplete'); //dd
          $node->setPublished(FALSE);
        }
        else
        {
          // if "SAVE - ALL FIELDS COMPLETED" trigger
          $node->set('field_status', 'complete'); //dd
          $node->setPublished(TRUE); // may change to unpublished status below.
        }
        $node->save();

        // for success message show
        $response = new AjaxResponse();
        $response->addCommand(
          new HtmlCommand(
            '.result_message',
            '<div class="success_message">'.$message.'</div>'
          )
        );
        return $response;
   }

}
