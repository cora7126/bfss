<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;
class ViewEditActive extends ControllerBase {

  public function view_edit_active() {
  	$uid = \Drupal::currentUser()->id();
	$current_user = \Drupal\user\Entity\User::load($uid);
	$current_roles = $current_user->getRoles();

	
     // $user = User::load(349);
     // echo "<pre>";
     // print_r($user);

    if( isset($_POST['active_submit']) ){
    if(isset($_POST['items_selected'])){
      foreach ($_POST['items_selected'] as $key => $value) {
        $user = User::load($value);
        $user->status->value = 0;
        $user->save();
        } 
    }
     }


     $tb1 = '<form class="athletes-unfollow-form" action="" method="post" id="view-edit-active-form" onsubmit="return false;" accept-charset="UTF-8"><div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>Select</a>
                </th> 
                  <th class="th-hd"><a><span></span>Last Name</a>
                </th>  
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
              
                <th class="th-hd"><a><span></span>Organization</a>
                </th>
                <th class="th-hd"><a><span></span>Edit Permissions</a>
                </th>
                 <th class="th-hd"><a><span></span>Role</a>
                </th> 
              </tr>
            </thead>
            <tbody>';

            $RolesDropDown = ['athlete' => 'Athlete','coach' => 'Coach','parent_guardian_registering_athlete_' => 'Parent Guardian','assessors' => 'Assessors','bfss_manager' => 'BFSS Manager','bfss_administrator'=>'Administrator'];

            $role_names = ['athlete','coach','parent_guardian_registering_athlete_','assessors','bfss_manager','bfss_administrator'];
             $athlete_user_ids = \Drupal::entityQuery('user')
            ->condition('roles', $role_names, 'IN')
            ->condition('status', 1, '=')
            ->execute();
            foreach ($athlete_user_ids as $athlete_user_id) {
              $user = User::load($athlete_user_id);
              $sel_role = $user->getRoles();
              $edit_permissions = $user->field_edit_permissions->value;
              $edit_permissions_status = '';
              if(isset($edit_permissions) && $edit_permissions == 'yes'){
                $edit_permissions_status = 'Yes';
              }else{
                $edit_permissions_status = 'No';
              }
               $key = array_search('authenticated', $sel_role);
               unset($sel_role[$key]);
               $sel_role = array_values($sel_role);
              // print_r(array_values($sel_role));
              // die;

              $userroles = Role::loadMultiple($user->getRoles());
              $RolesLabel = [];
              foreach ($userroles as $userrole) {
                              $RolesLabel[] = $userrole->label();
              }
              
              $key = array_search('Authenticated user', $RolesLabel);
              unset($RolesLabel[$key]);
              $RolesLabel = array_values($RolesLabel);
             // print_r($RolesLabel);die;
             
              $firstname = $user->field_first_name->value;
              $lastname = $user->field_last_name->value;
              
              if(!empty($firstname)){
                $firstname = '<a href="/preview/profile?uid='.$athlete_user_id.'" target="_blank">'.$firstname.'</a>';
              }
              
              if(!empty($lastname)){
                $lastname = '<a href="/preview/profile?uid='.$athlete_user_id.'" target="_blank">'.$lastname.'</a>';
              }
              
              $query5 = \Drupal::database()->select('athlete_school', 'ats');
              $query5->fields('ats');
              $query5->condition('athlete_uid', $athlete_user_id,'=');
              $results5 = $query5->execute()->fetchAssoc(); 
         
              $sport = $results5['athlete_school_sport'];
              $pos = $results5['athlete_school_pos'];
              $school_name = $results5['athlete_school_name'];

              $athlete_school = $this->Get_Data_From_Tables('athlete_school','ats',$athlete_user_id,'athlete_uid'); //FOR ORG-1
              $org_name = isset($athlete_school['athlete_school_name']) ? $athlete_school['athlete_school_name'] : '';

              $tb1 .=  '<tr>
                <td><input class="form-checkbox getcheckboxid" type="checkbox" name="items_selected[]" value="'.$athlete_user_id.'"><span class="unfollow-checkbox"></span></td>
                 <td>'.$lastname.'</td>
                <td>'.$firstname.'</td>
               
                <td>'.$org_name.'</td>
                <td>'.$edit_permissions_status.'</td>';
              if(in_array('bfss_administrator', $current_roles) || in_array('administrator', $current_roles)){
              	 $tb1 .= '<td><p class="hide_role">'.$sel_role[0].
                        '</p><div class="box niceselect roles">
                          <span id="dateofshow">
                            <select data-uid="'.$athlete_user_id.'" data-role="'.$sel_role[0].'" data-dropdown="ViewEditActive">';
                            foreach ($RolesDropDown as $key => $userrole) {
                             $selected = ($sel_role[0] == $key) ? " selected='selected'": "";
                              $tb1 .= '<option value="'.$key.'" "'.$selected.'" >'.$userrole.'</option>';
                            }
              $tb1 .= '</select></span></div></td>';
              }else{
              	$tb1 .= '<td>'.$RolesLabel[0].'</td>';
              }

              $tb1 .= '</tr>';
            }
            
            $tb1 .= '<div class="unfollow-sub"><i class="fas fa-times"></i><input type="submit" name="active_submit" value="DEACTIVATE" onclick="deactivate_users();" ></div>
            </tbody>
            </table>
             </div>
            </div>
             </div>
            </div></form>';


            $tb1 .= '<!--Model Popup starts-->
              <div class="container">
                  <div class="row">
                      <div class="modal fade" id="ignismyModal" role="dialog">
                          <div class="modal-dialog drupal-approve-org">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <button type="button" class="close role-close-btn" data-dismiss="modal" aria-label=""><span>×</span></button>
                                   </div>
                                  <div class="modal-body">
                          <div class="thank-you-pop">
                            <img src="/modules/custom/bfss_manager/img/Green-Round-Tick.png" alt="">
                            <h2>Successfully Updated!</h2>
                          </div>
                                  </div>
                        
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!--Model Popup ends-->';

        // $tb1 .=   '<!--Model Popup starts-->
        //           <div class="modal fade" id="ConfirmDeactivateModal" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeactivateLabel" aria-hidden="true">
        //             <div class="modal-dialog drupal-approve-org" role="document">
        //               <div class="modal-content">
                        
        //                           <div class="modal-header">
        //                               <button type="button" class="close deactivate-close" data-dismiss="modal" aria-label=""><span>×</span></button>
        //                            </div>
                        
        //                 <div class="modal-body">
        //                 <div class="message-deactivate"></div>
        //                   <h2>Are you sure , you want to deactivate?</h2>
        //                 </div>
        //                 <div class="modal-footer deactivate-footer">
        //                 <div class="modal-buttons">
        //                   <button id="deactive-no" type="button" class="button btn btn-danger deactive-no" data-dismiss="modal">NO</button>
        //                   <button id="deactive-yes" type="button" class="button btn btn-primary deactive-yes" >YES</button>
        //                 </div>
        //                 </div>
        //               </div>
        //             </div>
        //           </div><!--Model Popup ends-->';

    return [
    '#cache' => ['max-age' => 0,],
    '#theme' => 'view_edit_active_page',
    '#view_edit_active_block' => Markup::create($tb1),
    '#attached' => [
      'library' => [
        'bfss_manager/bfss_manager_lib', //include our custom library for this response
      ]
    ]
  ];   
  
  } 


  public function Get_Data_From_Tables($TableName,$atr,$current_user,$user_key){
      if($TableName){
        $conn = Database::getConnection();
        $query = $conn->select($TableName, $atr);
        $query->fields($atr);
        $query->condition($user_key, $current_user, '=');
        $results = $query->execute()->fetchAssoc();
      }
      return $results;
  }
}
