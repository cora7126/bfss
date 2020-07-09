<?php

namespace Drupal\bfss_assessment\Plugin\Block;

use Drupal\Core\Block\BlockBase;

use  \Drupal\user\Entity\User;
use \Drupal\node\Entity\Node;

/**
 * Provides a 'Assessment Snapshot Block' Block.
 *
 * @Block(
 *   id = "asssessmen_snapshot_block",
 *   admin_label = @Translation("Assessment Snapshot Block"),
 *   category = @Translation("Assessment Snapshot Block"),
 * )
 */

//Things going good and I'm learning lots - I'm very aware of deadline so I'm cutting out all the fluff.  Gonna take a 4-hour break.

class AssessmentSnapshotBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  	$uid = \Drupal::currentUser()->id();
	$booked_ids = \Drupal::entityQuery('bfsspayments')
              ->condition('user_id', $uid,'IN')
              ->sort('created' , 'DESC')
              ->execute();
      $nid = '';
      if(!empty($booked_ids) && is_array($booked_ids)){
          foreach ($booked_ids as $booked_id) {
              if(isset($booked_id)){
                $nid = \Drupal::entityQuery('node')
                ->condition('type', 'athlete_assessment_info')
                ->condition('field_booked_id',$booked_id,'=')
                ->condition('status', 1)
                ->sort('created' , 'DESC')
                ->execute();
              }
          }
      }

      #latest
      $nid = array_values($nid);
      if(isset($nid[0])){
        $node = Node::load($nid[0]);
        $field_form_type = $node->field_form_type->value;

        #Snapshot
        $MY_REACTIVE_STRENGTH = isset($node->field_jump_height_in_reactive->value) ? $node->field_jump_height_in_reactive->value : 0;
        $REACTIVE_STRENGTH_NATIONAL_AVERAGE = isset($node->field_rsi_reactive_b->value) ? $node->field_rsi_reactive_b->value : 0;
        $ACCELERATION_SPEED = isset($node->field_10m_time_sec_sprint->value) ? $node->field_10m_time_sec_sprint->value : 0;
        $MAXIMAL_STRENGTH = isset($node->field_peak_force_n_maximal->value) ? $node->field_peak_force_n_maximal->value : 0;
        $ELASTIC_STRENGTH = isset($node->field_jump_height_in_elastic->value) ? $node->field_jump_height_in_elastic->value : 0;
        $BALLISTIC_STRENGTH = isset($node->field_jump_height_in_ballistic->value) ? $node->field_jump_height_in_ballistic->value : 0;

        $data = [
          'my_reactive_strength' => $MY_REACTIVE_STRENGTH,
          'reactive_strength_avg' => $REACTIVE_STRENGTH_NATIONAL_AVERAGE,
          'acc_speed' => $ACCELERATION_SPEED,
          'max_strength' => $MAXIMAL_STRENGTH,
          'elastic_strength' => $ELASTIC_STRENGTH,
          'ballistic_strength' => $BALLISTIC_STRENGTH,
        ];

      }

	// ksm(['user id, assessmentData, nids...', $uid->id(), $assessmentData, $nids]);

  	$html = '<div class="user_pro_block">
		<section class="assessmentshot">
			<div class="container">
				<div class="row">
					<h2>ASSESSMENT SNAPSHOT</h2>
					<div class="strengthRow">
						<div class="centeralizeRow">
							<div class="rightText">
								<span>'.((!empty($data)&&is_array($data))?$data['my_reactive_strength']:0).'</span>
							</div>
							<div class="rightText">
								<h4>MY REACTIVE<br>
								STRENGTH (IN)
								<img src="/modules/custom/bfss_assessment/img/coin.png" class="sideIcon" alt=""></h4>
								<p>Rebound Jump Test (RSI)</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="accelaration">
			<div class="container">
				<div class="row">
					<div class="infoRow leftBg leftBg1">
						<div class="inner">
							<span>'.((!empty($data)&&is_array($data))?$data['acc_speed']:0).' / '.((!empty($data)&&is_array($data))?$data['acc_speed']:0).'</span>
							<h4 style="white-space:nowrap;">SPEED / ACCELERATION<br>
							40M / 10M (SECS)</h4>
						</div>
						<img src="/modules/custom/bfss_assessment/img/run.svg" class="sideIcon" alt="">
					</div>
					<div class="infoRow">
						<div class="centeralizeRow white large">
							<div class="rightText">
								<span>'.((!empty($data)&&is_array($data))?$data['max_strength']:0).'</span>
							</div>
							<div class="rightText">
								<h4>MAXIMAL<br>
								STRENGTH (LBS)</h4>
								<p>Isometric <br> Mid-Thigh Pull</p>
							</div>
						</div>
						<img src="/modules/custom/bfss_assessment/img/builder.png" class="sideIcon" alt="">
					</div>
				</div>
				<div class="row">
					<div class="infoRow">
						<div class="centeralizeRow white small">
							<div class="rightText">
								<span>'.((!empty($data)&&is_array($data))?$data['ballistic_strength']:0).'</span>
							</div>
							<div class="rightText">
								<h4>BALLISTIC<br>
								STRENGTH (IN)</h4>
								<p>Squat Jump</p>
							</div>
						</div>
						<img src="/modules/custom/bfss_assessment/img/arrows_data.png" class="sideIcon" alt="">
					</div>
					<div class="infoRow leftBg leftBg2">
						<div class="centeralizeRow small whiteDesc">
							<div class="rightText">
								<span>'.((!empty($data)&&is_array($data))?$data['elastic_strength']:0).'</span>
							</div>
							<div class="rightText">
								<h4>ELASTIC<br>
								STRENGTH (IN)</h4>
								<p>Countermovement Jump</p>
							</div>
						</div>
						<img src="/modules/custom/bfss_assessment/img/jump.png" class="sideIcon" alt="">
					</div>
				</div>
			</section>
			</div>';
    return [
      '#cache' => ['max-age' => 0,],
      '#markup' => $html,
    ];
  }

}
