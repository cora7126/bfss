<?php

namespace Drupal\bfss_coach\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;


class SearchAthlete extends ControllerBase {


	  public function content() {

	  	$athlete_user_ids = \Drupal::entityQuery('user')
	    ->condition('roles', 'athlete', 'CONTAINS')
	    ->execute();
	    $data_user = [];
	    foreach ($athlete_user_ids as $athlete_user_id) {
	    	$data['mydata'] = $this->getUserInfo('mydata','','uid',$athlete_user_id);
	    	$data['athlete_school'] = $this->getUserInfo('athlete_school','','athlete_uid',$athlete_user_id);

			$userdata =	User::load($athlete_user_id);
			
			$firstname = $userdata->field_first_name->value;
			$lastname =  $userdata->field_last_name->value;
			$age = $this->DOB_get_year($athlete_user_id);
			if(!empty($firstname)){
				$firstname = '<a href="/preview/profile?uid='.$athlete_user_id.'" target="_blank">'.$firstname.'</a>';	
			}

			if(!empty($lastname)){
				$lastname = '<a href="/preview/profile?uid='.$athlete_user_id.'" target="_blank">'.$lastname.'</a>';	
			}
			
		
	   		$data_user[] = [
	   			'firstname' => $firstname,
	   			'lastname' => $lastname,
	   			'organization' => $data['athlete_school']['athlete_school_name'],
	   			'state' => $userdata->field_state->value,
	   			'city' => $data['mydata']['field_city'],
	   			'age' => $age,
	   			'sport' => $data['athlete_school']['athlete_school_sport'],
	   		];
	   		
	    }
	   

         $tb = '<div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="SearchAthlete_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th> 
                 <th class="th-hd"><a><span></span>Organization</a>
                </th>  
                 <th class="th-hd"><a><span></span>State</a>
                </th>  
                 <th class="th-hd"><a><span></span>City</a>
                </th>  
                <th class="th-hd"><a><span></span>Age</a>
                </th> 
                <th class="th-hd"><a><span></span>Sport</a>
                </th> 
              </tr>
            </thead>
            <tbody>';

        foreach ($data_user as $value) {
        		 $tb .= '<tr>
                <td>'.$value['firstname'].'</td>
                <td>'.$value['lastname'].'</td>
                <td>'.$value['organization'].'</td>
                <td>'.$value['state'].'</td>
                <td>'.$value['city'].'</td>
                <td>'.$value['age'].'</td>
                <td>'.$value['sport'].'</td>
              </tr>';
        }
	      $tb .= '</tbody>
	          </table>
	           </div>
	          </div>
	           </div>
	          </div>';
		return [
	      '#cache' => ['max-age' => 0,],
	      '#theme' => 'search_athlete_page',
	      '#assessments_block' => Markup::create($tb),
	      '#attached' => [
	        'library' => [
	          'acme/acme-styles', //include our custom library for this response
	        ]
	      ]
	    ];
  	}
  /*
   * get data using d-b query
   */
  public function getUserInfo($table_name = '', $field_name = '', $cond = 'entity_id',$athlete_user_id) {
    $result = null;
    $this->atheleteUserId = $athlete_user_id;
    if ($this->atheleteUserId) {
      $query = \Drupal::database()->select($table_name, 'tb');
      if ($field_name) {
          $query->fields('tb', [$field_name]);
      }else{
          $query->fields('tb');
      }
      $query->condition($cond, $this->atheleteUserId,'=');
      $result = $query->execute()->fetchAssoc();
      if ($field_name && isset($result[$field_name])) {
        return $result[$field_name];
      }
    }
    return $result;
  }

	public function DOB_get_year($uid){
	    if($uid){
	        $user = \Drupal\user\Entity\User::load($uid);
	        $dob = $user->field_date_of_birth->value;
	        $diff = (date('Y') - date('Y',strtotime($dob)));
	    }
	    return $diff;
 	}
}