<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;
class ViewEditDeactive extends ControllerBase {

  public function view_edit_deactive() {

     // $user = User::load(349);
     // echo "<pre>";
     // print_r($user);

    if( isset($_POST['deactive_submit']) ){
    if(isset($_POST['items_selected'])){
      foreach ($_POST['items_selected'] as $key => $value) {
        $user = User::load($value);
        $user->status->value = 1;
        $user->save();
        } 
    }
     }


     $tb1 = '<form class="athletes-unfollow-form" action="" method="post" id="view-edit-deactive-form" onsubmit="return false;" accept-charset="UTF-8"><div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>Select</a>
                </th>  
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th> 
                 <th class="th-hd"><a><span></span>Role</a>
                </th> 
              </tr>
            </thead>
            <tbody>';

            $role_names = ['athlete','coach','parent_guardian_registering_athlete_','assessors','bfss_manager'];
             $athlete_user_ids = \Drupal::entityQuery('user')
            ->condition('roles', $role_names, 'IN')
            ->condition('status', 0, '=')
            ->execute();
            foreach ($athlete_user_ids as $athlete_user_id) {
              $user = User::load($athlete_user_id);
              $userroles = Role::loadMultiple($user->getRoles());
              $RolesLabel = [];
              foreach ($userroles as $userrole) {
                              $RolesLabel[] = $userrole->label();
              }
              
              $key = array_search('Authenticated user', $RolesLabel);
              unset($RolesLabel[$key]);
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

              $tb1 .=  '<tr>
                <td><input class="form-checkbox" type="checkbox" name="items_selected[]" value="'.$athlete_user_id.'"><span class="unfollow-checkbox"></span></td>
                <td>'.$firstname.'</td>
                <td>'.$lastname.'</td>';
              $tb1 .= '<td>
                        <div class="box niceselect roles">
                          <span id="dateofshow">
                            <select>';
                            foreach ($RolesLabel as $userrole) {
                              $tb1 .= '<option>'.$userrole.'</option>';
                            }
              $tb1 .= '</select></span></div></td>';
              $tb1 .= '</tr>';
            }
            
            $tb1 .= '<div class="unfollow-sub"><i class="fas fa-times"></i><input type="submit" name="deactive_submit" value="DEACTIVE" onclick="activate_users();" ></div>

            </tbody>
            </table>
             </div>
            </div>
             </div>
            </div></form>';

   

    return [
    '#cache' => ['max-age' => 0,],
    '#theme' => 'view_edit_deactive_page',
    '#view_edit_deactive_block' => Markup::create($tb1),
    '#attached' => [
      'library' => [
        'acme/acme-styles', //include our custom library for this response
      ]
    ]
  ];   
  
  } 
}
