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
		  	$HTML = '
		  	<ul id="sortable_faqs" class="faq faqct">';
		  	foreach ($nids as $nid) {
		   	 $node = Node::load($nid);
		   	 $uid = isset($node->get('uid')->getValue()[0]['target_id'])?$node->get('uid')->getValue()[0]['target_id']:'';
	   		 if(isset($uid)){
	   			$user = User::load($uid);
	   		 }
		   		
		   		$created = date('m/d/Y', $node->created->value);
		   		$url_edit = '/faq-edit-form?nid='.$nid.'&role='.$user_role;
		   		$url_delete = '/faq-delete-form?nid='.$nid.'&role='.$user_role;
		   		$HTML .= '<div data-nid="'.$nid.'" class="ui-state-default"><li class="q">
							  	<div class="faq-left">'.$node->title->value.'</div><div class="faq-right faq faqct"><i class="far fa-angle-down"></i></div>
							 </li>
							<li class="a">'.$node->body->value.'
							<div class="faq-footer-bar">
							<div class="faq-auth-date">
							<p class="auth">'.$user->name->value.'</p>
							<p class="date">'.$created.'</p>
							</div>
							<div class="faq-icons-bar">
	<p><a class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-edit-faq-fm edit-faq&quot;}" data-dialog-type="modal" href="'.$url_edit.'"><i class="far fa-edit"></i></a></p>
	<p><a href="'.$url_delete.'" class="use-ajax" data-dialog-options="{&quot;dialogClass&quot;: &quot;drupal-edit-faq-fm delete-faq&quot;}" data-dialog-type="modal"><i class="far fa-trash-alt"></i></a>
							</p>
							</div>
							</div>
							</li>
							</div>';
		   	}	
							
			$HTML .='</ul>';
		  	$out =  Markup::create($HTML);
	  	}


		//Permissions
		$permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
		$rel = $permissions_service->bfss_admin_permissions();
		$faqs =  unserialize($rel['faqs']);

		if($faqs['create']==1 || $faqs['admin']==1){
		  $result = $form;
		  $result1 = $out;
		}else{
		  $result = "we are sorry. you can not access this page.";
		  $result1 = '';
		}

		$role_name = '';
		if(isset($param['role']) && $param['role'] == 'Athletes'){
			$role_name = "ATHLETE'S"; 
		}elseif(isset($param['role']) && $param['role'] == 'Coaches'){
			$role_name = "COACHE'S"; 
		}

	    return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'add_faqs_page',
		    '#add_faqs_block' => $form,
		    '#reorder_faqs_block' => $result1,
		    '#role_name' => $role_name,
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