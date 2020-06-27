<?php

namespace Drupal\bfss_assessment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
class FollowUnfollowForm extends FormBase {
	//from id(unique)
	public function getFormId() {
		return 'follow_unfollow_form';
	}
	
	  public function buildForm(array $form, FormStateInterface $form_state) {
			$param = \Drupal::request()->query->all();
			if(isset($param['uid'])){
				$uid = $param['uid'];
			}
			$user = User::load($uid);
			//print_r($user->field_coachs_follow->value);
			if($user->field_coachs_follow->value == 'follow'){
				$checked = array('follow');
				$FollowUn = '<span><i class="fas fa-star"></i>Coaches - Follow This Athlete</span>';
			}else{
				$checked = '';
				$FollowUn = '<span><i class="far fa-star"></i></i>Coaches - Follow This Athlete</span>';
			}
			$FollowUnHtml = Markup::create($FollowUn);
			$options = array();
			$options['follow'] = $FollowUnHtml;
			#$form['#method'] = 'get';
			$uid1 = \Drupal::currentUser()->id();
		    $user = \Drupal\user\Entity\User::load($uid1);
		    $roles = $user->getRoles();
		if(in_array('coach', $roles)){
			$form['follow_unfollow'] = array(
			  '#type' => 'checkboxes',
			  '#options' => $options,
			  '#default_value' => $checked,
			  '#attributes' => array('class' => 'checkboxfollow'),
			  '#prefix' => '<div class="submit-follow-ck">',
			  '#suffix' => '</div>',
			);
		}else{
			$form['follow_unfollow'] = array(
			  '#type' => 'label',
			  '#prefix' => '<div class="submit-follow-label">'.$FollowUnHtml,
			  '#suffix' => '</div>',
			);
		}
			

			

 		$form['actions']['#type'] = 'actions';
 		$form['actions']['submit'] = [
		      '#type' => 'submit',
		      '#value' => $this->t('SAVE'),
		      '#attributes' => array('class' => 'mytest'),
		      '#prefix' => '<div class="submit-follow-btn">',
		   	  '#suffix' => '</div>',
            ];
		return $form;
		
	}
    public function validateForm(array &$form, FormStateInterface $form_state) {
    
    }


	public function submitForm(array &$form, FormStateInterface $form_state) {

	    $uid = \Drupal::currentUser()->id();
	    $user = \Drupal\user\Entity\User::load($uid);
	    $roles = $user->getRoles();
		if(in_array('coach', $roles)){
				$fowllo_unfollow = $form_state->getValue('follow_unfollow');
				// print_r($fowllo_unfollow);
				// die;
				$param = \Drupal::request()->query->all();
				if(isset($param['uid'])){
					$uid = $param['uid'];
				}
				
				if($fowllo_unfollow['follow']){	
					$user = User::load($uid);
					$user->field_coachs_follow->value = 'follow';
					$user->save();	
				}else{
					$user = User::load($uid);
					$user->field_coachs_follow->value = 'unfollow';
					$user->save();
				}
		}
		
	}

}