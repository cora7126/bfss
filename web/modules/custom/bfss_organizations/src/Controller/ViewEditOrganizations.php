<?php


namespace Drupal\bfss_organizations\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;

class ViewEditOrganizations extends ControllerBase {
	  public function view_edit_organizations() {

     // $user = User::load(349);
     // echo "<pre>";
     // print_r($user);

    // if( isset($_POST['edit_org_submit']) ){
    // if(isset($_POST['items_selected'])){
    //   foreach ($_POST['items_selected'] as $key => $value) {
    //     $user = User::load($value);
    //     $user->status->value = 0;
    //     $user->save();
    //     } 
    // }
    //  }


     $tb1 = '<form class="edit-view-org-form" action="" method="post" id="edit-view-org-form" onsubmit="return false;" accept-charset="UTF-8"><div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>Select</a>
                </th>  
                <th class="th-hd"><a><span></span>Organization</a>
                </th>
                <th class="th-hd"><a><span></span>Type</a>
                </th> 
                 <th class="th-hd"><a><span></span>State</a>
                </th> 
                 <th class="th-hd"><a><span></span>City</a>
                </th>
              </tr>
            </thead>
            <tbody>';

    	$query = \Drupal::entityQuery('node');
			$query->condition('type', 'bfss_organizations');
      $query->condition('status', 1, 'IN');
			$nids = $query->execute();
			foreach ($nids as $nid) {
				$node = Node::load($nid);
				//print_r($node->type->value);
				$tb1 .=  '<tr>
		            <td><input class="form-checkbox edit-ckeckbox-plx" type="checkbox" name="items_selected[]" value="'.$nid.'"><span class="unfollow-checkbox"></span></td>
		            <td><a>'.$node->field_organization_name->value.'</a>  </td>
		            <td>'.$node->field_type->value.'</td>
		            <td>'.$node->field_state->value.'</td>
		            <td>'.$node->field_city->value.'</td>     
             	</tr>';
			}
			
                   
            $tb1 .= '
            </tbody>
            </table>
             </div>
            </div>
             </div>
            </div></form>';

              //Permissions
             $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
             $rel = $permissions_service->bfss_admin_permissions();
             $Organizations_permissions =  unserialize($rel['Organizations']);
          
              if($Organizations_permissions['view']==1 || $Organizations_permissions['admin']==1){
                $result = Markup::create($tb1);
              }else{
                $result = "we are sorry. you can not access this page.";
              }
			
	    return [
          '#cache' => ['max-age' => 0,],
          '#theme' => 'view_edit_organizations_page',
          '#name' => 'G.K',
          '#view_edit_organizations_block' => $result,
          '#attached' => [
            'library' => [
              'acme/acme-styles', //include our custom library for this response
            ]
          ]
        ];
  	}
}