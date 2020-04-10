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

			$userdata =	User::load($athlete_user_id);
	   		$query_fname = \Drupal::database()->select('user__field_first_name', 'uffn');
			$query_fname->fields('uffn');
			$query_fname->condition('entity_id', $athlete_user_id,'=');
			$results_fname = $query_fname->execute()->fetchAll();
			$firstname = isset($results_fname[0]->field_first_name_value)?$results_fname[0]->field_first_name_value:'';
			if(!empty($firstname)){
				$firstname = '<a href="/preview/profile?uid='.$athlete_user_id.'" target="_blank">'.$firstname.'</a>';	
			}
			
			    	
			$query_lname = \Drupal::database()->select('user__field_last_name', 'ufln2');
			$query_lname->addField('ufln2', 'field_last_name_value');
			$query_lname->condition('entity_id', $athlete_user_id,'=');
			$results_lname = $query_lname->execute()->fetchAssoc();
			$lastname = $results_lname['field_last_name_value'];
			if(!empty($lastname)){
				$lastname = '<a href="/preview/profile?uid='.$athlete_user_id.'" target="_blank">'.$lastname.'</a>';	
			}
			
			
			$query7 = \Drupal::database()->select('athlete_info', 'ai');
			$query7->fields('ai');
			$query7->condition('athlete_uid', $athlete_user_id,'=');
			$results7 = $query7->execute()->fetchAssoc();

	   		$data_user[] = [
	   			'firstname' => $firstname,
	   			'lastname' => $lastname,
	   			'state' => $userdata->field_state->value,
	   			'city' => $data['mydata']['field_city'],
	   		];
	   		
	    }
	   

         $tb = '<div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
          <div class="block-bfss-assessors">
          <div class="table-responsive">
         <table id="SearchAthlete_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th>  
                 <th class="th-hd"><a><span></span>State</a>
                </th>  
                 <th class="th-hd"><a><span></span>City</a>
                </th>  
              </tr>
            </thead>
            <tbody>';

        foreach ($data_user as $value) {
        		 $tb .= '<tr>
                <td>'.$value['firstname'].'</td>
                <td>'.$value['lastname'].'</td>
                <td>'.$value['state'].'</td>
                <td>'.$value['city'].'</td>
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

}