<?php

namespace Drupal\bfss_assessment\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Assessment Snapshot Block' Block.
 *
 * @Block(
 *   id = "asssessmen_snapshot_block",
 *   admin_label = @Translation("Assessment Snapshot Block"),
 *   category = @Translation("Assessment Snapshot Block"),
 * )
 */
class AssessmentSnapshotBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {


  	$html = '<div class="user_pro_block">
                        	<section class="assessmentshot">
			<div class="container">
				<div class="row">
					<h2>ASSESSMENT SNAPSHOT</h2>
					<div class="strengthRow">
						<div class="centeralizeRow">
							<div class="rightText">
								<span>14.1</span>
							</div>
							<div class="rightText">
								<h4>MY REACTIVE STRENGTH (IN)</h4>
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
					<div class="infoRow leftBg">
						<div class="inner">
							<span>5.89/1.66</span>
							<h4>SPEED / ACCELERATION 40M / 10M (SECS)</h4>
						</div>
						<img src="/modules/custom/bfss_assessment/img/run.svg" class="sideIcon" alt="">
					</div>
					<div class="infoRow">
						<div class="centeralizeRow white large">
							<div class="rightText">
								<span>14.9</span>
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
								<span>14.9</span>
							</div>
							<div class="rightText">
								<h4>BALLISTIC STRENGTH (IN)</h4>
								<p>Squat Jump</p>
							</div>
						</div>
						<img src="/modules/custom/bfss_assessment/img/arrows_data.png" class="sideIcon" alt="">
					</div>
					<div class="infoRow leftBg">
						<div class="centeralizeRow small whiteDesc">
							<div class="rightText">
								<span>16.4</span>
							</div>
							<div class="rightText">
								<h4>ELASTIC STRENGTH (IN)</h4>
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