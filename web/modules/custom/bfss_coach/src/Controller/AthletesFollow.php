<?php

namespace Drupal\bfss_coach\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;

class AthletesFollow extends ControllerBase {
	  public function content() {
	   if( isset($_POST['follow_submit']) ){
		if(isset($_POST['items_selected'])){
			foreach ($_POST['items_selected'] as $key => $value) {
		   		$user = User::load($value);
				$user->field_coachs_follow->value = 'unfollow';
				$user->save();
	   		}	
		}
	   }

	   $athlete_user_ids = \Drupal::entityQuery('user')
		->condition('roles', 'athlete', 'CONTAINS')
		->condition('field_coachs_follow', 'follow', '=')
		->execute();
	   $tb1 = '<form class="athletes-unfollow-form" action="" method="post" id="athletes-unfollow-form" onsubmit="return false;" accept-charset="UTF-8"><div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
               	<th class="th-hd"><a><span></span>Unfollow</a>
                </th>  
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th> 
                <th class="th-hd"><a><span></span>School Name</a>
                </th>  
                <th class="th-hd"><a><span></span>Sport</a>
                </th> 
                 <th class="th-hd"><a><span></span>Position</a>
                </th> 
              </tr>
            </thead>
            <tbody>';
          
            foreach ($athlete_user_ids as $athlete_user_id) {
            	$user = User::load($athlete_user_id);
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
                <td>
                <input class="form-checkbox" type="checkbox" name="items_selected[]" value="'.$athlete_user_id.'"><span class="unfollow-checkbox"></span></td>
                <td>'.$firstname.'</td>
                <td>'.$lastname.'</td>
                <td>'.$school_name.'</td>
                <td>'.$sport.'</td>
                <td>'.$pos.'</td>
        		</tr>';
            }
            
            $tb1 .= '<div class="unfollow-sub"><i class="fas fa-times"></i><input type="submit" name="follow_submit" value="unfollow" onclick="unfollow_athlete();" ></div>

            </tbody>
	          </table>
	           </div>
	          </div>
	           </div>
	          </div></form>';
	    return [
	      '#cache' => ['max-age' => 0,],
	      '#theme' => 'athletes_follow_page',
	      '#athletes_follow_block' => Markup::create($tb1),
	      '#attached' => [
	        'library' => [
	          'acme/acme-styles', //include our custom library for this response
	        ]
	      ]
	    ];
  	}
}