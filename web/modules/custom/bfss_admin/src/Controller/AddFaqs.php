<?php

namespace Drupal\bfss_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use \Drupal\user\Entity\User;
use \Drupal\node\Entity\Node;
use Drupal\Core\Database\Database;

class AddFaqs extends ControllerBase {
	  public function add_faqs() {
	  	$form = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\AddFaqsForm');
	  	$param = \Drupal::request()->query->all();
	  	$current_user = \Drupal::currentUser()->id();
	  	$conn = Database::getConnection();
	  	if( !empty($param['role']) ){
          if($param['role'] == 'Athletes'){
            $user_role = 'athlete';
          }elseif ($param['role'] == 'Coaches') {
            $user_role = 'coach';
          }else{
            $user_role = '';
          }
	    }else{
	        $user_role = '';
	    }

	  	if( isset($user_role) ){
			$query = $this->Get_Data_From_Tables('bfss_faqs_nids','at',$user_role);		
			if(empty($query)){
				$query = \Drupal::entityQuery('node');
				$query->condition('type', 'faq');
				$query->condition('field_roles', $user_role, '=');
				$nids = $query->execute();
			}else{
				if($user_role == 'athlete'){
					$nids = explode(',', $query['faq_nids']);
				}elseif($user_role == 'coach'){
					$nids = explode(',', $query['faq_nids']) ;
				}else{

				}
			}
		  	$HTML = '<ul id="sortable_faqs" class="faq faqct">';
		  	foreach ($nids as $nid) {
		   		$node = Node::load($nid);
		   		$url_edit = '/faq-edit-form?nid='.$nid.'&role='.$user_role;
		   		$url_delete = '/faq-delete-form?nid='.$nid.'&role='.$user_role;
		   		$HTML .= '<div data-nid="'.$nid.'" class="ui-state-default"><li class="q">
							  	<div class="faq-left"><p>'.$node->title->value.'</p></div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"></div>
							 </li>
							<li class="a"><p>'.$node->body->value.'</p>
							<div class="faq-footer-bar">
							<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-edit-faq-fm&quot;}" data-dialog-type="modal" href="'.$url_edit.'"><i class="far fa-edit"></i></a></p>
							<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-delete-faq-fm&quot;}" data-dialog-type="modal" href="'.$url_delete.'"><i class="far fa-trash-alt"></i></a></p>
							</div>
							</li>
							</div>';
		   	}	
							
			$HTML .='</ul>';
		  	$out =  Markup::create($HTML);
	  	}

	    return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'add_faqs_page',
		    '#add_faqs_block' => $form,
		    '#reorder_faqs_block' => $out,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		]; 
  	}

  	public function Get_Data_From_Tables($TableName,$atr,$user_role){
  		if($TableName){
  			$conn = Database::getConnection();
			$query = $conn->select($TableName, $atr);
		    $query->fields($atr);
		    $query->condition('role', $user_role, '=');
		    $results = $query->execute()->fetchAssoc();
  		}
  		return $results;
	}
}