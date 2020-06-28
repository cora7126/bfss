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

	/** TODO: make utility class
	 * Returns one assessment data record - based on booked_id
	* @param string $booked_id -- id of a single assessment
	* @param string $userId -- current user
	*/
	protected function getAssessmentData(string $booked_id, string $userId = '')
	{
		$assmntData = [];

		// $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
		// $address_1 = $entity->address_1->value;

		$query1 = \Drupal::entityQuery('node');
		$query1->condition('type', 'athlete_assessment_info');
		$query1->condition('field_booked_id',$booked_id, 'IN');
		$nids1 = $query1->execute();

		// ksm(['$booked_id, nids1', $booked_id, $nids1]);

		//sport
		// $query5 = \Drupal::database()->select('athlete_school', 'ats');
		// $query5->fields('ats');
		// $query5->condition('athlete_uid', $userId,'=');
		// $results5 = $query5->execute()->fetchAssoc();
		// $sport = $results5['athlete_school_sport'];

		// $realFormType = $this->getFormTypeFromPrice($entity->service->value);

		if(!empty($nids1)){
			foreach ($nids1 as $key => $value) {
				$node1 = Node::load($value);
				$assmntData['field_status'] = $node1->field_status->value;
				$assmntData['field_age'] = $node1->field_age->value;
				$assmntData['field_sport_assessment'] = $node1->field_sport_assessment->value;
				$assmntData['field_weight'] = $node1->field_weight->value;
				$assmntData['field_sex'] = $node1->field_sex->value;
				$assmntData['field_jump_height_in_reactive'] = $node1->field_jump_height_in_reactive->value;
				$assmntData['field_jump_height_in_elastic'] = $node1->field_jump_height_in_elastic->value;
				$assmntData['field_jump_height_in_ballistic'] = $node1->field_jump_height_in_ballistic->value;
				$assmntData['field_10m_time_sec_sprint'] = $node1->field_10m_time_sec_sprint->value;
				$assmntData['field_peak_force_n_maximal'] = $node1->field_peak_force_n_maximal->value;
				$assmntData['field_rsi_reactive'] = $node1->field_rsi_reactive->value;
			}
		}
		return $assmntData;
	}


  /**
   * {@inheritdoc}
   */
  public function build() {

	$uid = \Drupal::currentUser();
	// $user = \Drupal\user\Entity\User::load($uid->id());

	$query = \Drupal::entityQuery('node');
	$query->condition('type', 'assessment');
	$nids = $query->execute();

	$assessmentData = [];

	foreach ($nids as $nid) {
		$booked_ids = \Drupal::entityQuery('bfsspayments')
		->condition('assessment', $nid,'IN')
		->condition('user_id',$uid->id(),'IN')
		->sort('time','DESC')
		->execute();
		foreach ($booked_ids as $key => $booked_id)
		{
			$assessmentData = $this->getAssessmentData($booked_id, $uid->id());
			if ($assessmentData['field_status'] == 'complete')
			{
				break 2; //#################### brak from 2'nd outer loop.
			}
		}
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
								<span>'.$assessmentData['field_jump_height_in_ballistic'].'</span>
							</div>
							<div class="rightText">
								<h4>MY REACTIVE STRENGTH (IN) <img src="/modules/custom/bfss_assessment/img/coin.png" class="sideIcon" alt=""></h4>
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
							<span>'.$assessmentData['field_10m_time_sec_sprint'].' / '.$assessmentData['field_10m_time_sec_sprint'].'</span>
							<h4 style="white-space:nowrap;">SPEED / ACCELERATION 40M / 10M (SECS)</h4>
						</div>
						<img src="/modules/custom/bfss_assessment/img/run.svg" class="sideIcon" alt="">
					</div>
					<div class="infoRow">
						<div class="centeralizeRow white large">
							<div class="rightText">
								<span>'.$assessmentData['field_peak_force_n_maximal'].'</span>
							</div>
							<div class="rightText">
								<h4>MAXIMAL STRENGTH (LBS)</h4>
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
								<span>'.$assessmentData['field_jump_height_in_ballistic'].'</span>
							</div>
							<div class="rightText">
								<h4>BALLISTIC</br> STRENGTH (IN)</h4>
								<p>Squat Jump</p>
							</div>
						</div>
						<img src="/modules/custom/bfss_assessment/img/arrows_data.png" class="sideIcon" alt="">
					</div>
					<div class="infoRow leftBg leftBg2">
						<div class="centeralizeRow small whiteDesc">
							<div class="rightText">
								<span>'.$assessmentData['field_jump_height_in_elastic'].'</span>
							</div>
							<div class="rightText">
								<h4>ELASTIC</br> STRENGTH (IN)</h4>
								<p>Countermovement Jump</p>
							</div>
						</div>
						<img src="/modules/custom/bfss_assessment/img/jump.png" class="sideIcon" alt="">
					</div>
				</div>
			</section>
			</div>';
    return [
      '#markup' => $html,
    ];
  }

}