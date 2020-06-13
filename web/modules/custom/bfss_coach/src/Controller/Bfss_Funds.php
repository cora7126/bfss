<?php

namespace Drupal\bfss_coach\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
class Bfss_Funds extends ControllerBase {
	  public function content() {
	    $current_user = \Drupal::currentUser()->id(); 
        $param = \Drupal::request()->query->all();


        $query_bfss_coach = \Drupal::database()->select('bfss_coach', 'bc');
        $query_bfss_coach->fields('bc');
        $query_bfss_coach->condition('coach_uid',$current_user, '=');
        $results_bfss_coach = $query_bfss_coach->execute()->fetchAll();

        $orgname1 = 'Williams Field High School'; // DEFAULT VALUE

       
        if(!empty($param['orgname'])){
            $orgname = $param['orgname'];
        }else{
            $orgname = $orgname1; 
        }



        #GET ATHLETE IDS
        $athlete_uids = $this->get_uids_by_orgname($orgname);
        // print_r($athlete_uids);
        // die;
       
        foreach ($athlete_uids as $athlete_uid) {
    	  	$booked_ids = \Drupal::entityQuery('bfsspayments')
                ->condition('user_id',$athlete_uid->athlete_uid, '=')
    			->condition('payment_status','unpaid', '=')
            	->execute();
                //print_r($booked_ids);
                //die;
            $TableRowsPending = [];
        	foreach ($booked_ids as $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
        		$user_id = $entity->user_id->value;

        		$timestamp = intval($entity->time->value);
        		
        		$booking_date = date("Y/m/d",$timestamp);
        		$firstname = $entity->first_name->value;
        		
        		if(!empty($firstname)){
    				$firstname = '<a href="/preview/profile?uid='.$user_id.'" target="_blank">'.$firstname.'</a>';	
    			}
    			$lastname = $entity->last_name->value;
    			if(!empty($lastname)){
    				$lastname = '<a href="/preview/profile?uid='.$user_id.'" target="_blank">'.$lastname.'</a>';	
    			}
        		

        		$price = $entity->service->value;
        		$payment_status = $entity->payment_status->value;
        		

        		$query5 = \Drupal::database()->select('athlete_school', 'ats');
                $query5->fields('ats');
                $query5->condition('athlete_uid', $user_id,'=');
                $results5 = $query5->execute()->fetchAssoc();            
                $sport = $results5['athlete_school_sport'];

        		$TableRowsPending[] = [
    		    		'booking_date'	=> $booking_date,
    		    		'firstname'	=> $firstname,
    		    		'lastname'	=> $lastname,
    		    		'sport'	=> $sport,
    		    		'price'	=> $price,
    		    		'payment_status'=> $payment_status,
        		];
        		
        	}
        }



        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'bfss_organizations');
        $query->condition('uid', $current_user, 'IN');
        //$query->condition('status', 1, 'IN');
        $nids = $query->execute();

        $orgnames = [];
        foreach ($nids as $nid) {
           $node = Node::load($nid);
           $orgnames[] = $node->field_organization_name->value;
            
        }
        $orgname_ar = [];
        foreach ($orgnames as $orgname_v) {  
           $orgname_ar[$orgname_v] = $orgname_v;
        }
        // print_r($orgname_ar);
        // die;
        $arrradios = array('Williams Field High School'=>'Williams Field High School') + $orgname_ar;
     
        $radios = '<div class="org-radio">';
        foreach (array_unique($arrradios) as $arrradio) {
           
            $cls = '';
            $checked = '';
        if(!empty($arrradio)){ 
            if(!empty($param['orgname']) ){
                if($param['orgname']==$arrradio){
                    $checked = 'checked=checked';
                    $cls = 'active';
                }else{
                    $checked = '';
                    $cls = ''; 
                }
                 $ck = '';
            }else{
                 if($arrradio==$orgname1){
                    $ck = 'checked=checked';
                }else{
                    $ck = '';
                }
            }
                $radios .= '<div class="orgradios"><input type="radio" class="tabs-orgs '.$cls.'" name="orgname" value="'.$arrradio.'" '.$checked.' '.$ck.'><span>'.$arrradio.'</span></div>';
            }  
        }
        $radios .= '</div>';
       
	  	$tb1 = '<div class="search_athlete_main user_pro_block" >
          <div class="org_name_tabs">
          <form class="org-tab-form" action="/bfss-funds" method="get" id="org-tab-form-plx" accept-charset="UTF-8">
            '.$radios.'
            <input type="submit" name="org-submit" style="display:none;">
          </form>
          </div>
          <div class="paid-unpaid">
          <ul>
          <li><i class="fal fa-money-bill-wave"></i> <a href="#Pending_section">Pending</a></li>
          <li><i class="far fa-usd-circle"></i> <a href="#Paid_section_ppp">Paid</a></li>
          </ul>
          </div>
          <div class="wrapped_div_main">
           <h2 id="Pending_section">BFSS Payments Pending</h2>
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
               	<th class="th-hd"><a><span></span>Date</a>
                </th>  
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th> 
                <th class="th-hd"><a><span></span>Sport</a>
                </th>  
                <th class="th-hd"><a><span></span>Amount</a>
                </th> 
                 <th class="th-hd"><a><span></span>Paid</a>
                </th> 
              </tr>
            </thead>
            <tbody>';
        foreach ($TableRowsPending as $RowValue) {
        	$tb1 .= '<tr>
                <td>'.$RowValue['booking_date'].'</td>
                <td>'.$RowValue['firstname'].'</td>
                <td>'.$RowValue['lastname'].'</td>
                <td>'.$RowValue['sport'].'</td>
                <td>$'.$RowValue['price'].'</td>
                <td>'.$RowValue['payment_status'].'</td>
        	</tr>';
        }
	      $tb1 .= '</tbody>
	          </table>
	           </div>
	          </div>
	           </div>
	          </div>';
	


        foreach ($athlete_uids as $athlete_uid) {
    	    $booked_ids_paid = \Drupal::entityQuery('bfsspayments')
    			->condition('payment_status','paid', '=')
                 ->condition('user_id',$athlete_uid->athlete_uid, '=')
            	->execute();
            $TableRowsPaid = [];
        	foreach ($booked_ids_paid as $booked_id) {
        		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
        		$timestamp = intval($entity->time->value);
        		$booking_date = date("Y/m/d",$timestamp);
        		$firstname = $entity->first_name->value;
        		$lastname = $entity->last_name->value;
        		$user_id = $entity->user_id->value;
        		if(!empty($firstname)){
    				$firstname = '<a href="/preview/profile?uid='.$user_id.'" target="_blank">'.$firstname.'</a>';	
    			}
    			if(!empty($lastname)){
    				$lastname = '<a href="/preview/profile?uid='.$user_id.'" target="_blank">'.$lastname.'</a>';	
    			}
        		$price = $entity->service->value;
        		$payment_status = $entity->payment_status->value;
        		$created = date("Y/m/d",$entity->created->value);
        		

        		$query5 = \Drupal::database()->select('athlete_school', 'ats');
                $query5->fields('ats');
                $query5->condition('athlete_uid', $user_id,'=');
                $results5 = $query5->execute()->fetchAssoc();            
                $sport = $results5['athlete_school_sport'];

        		$TableRowsPaid[] = [
    		    		'booking_date'	=> $booking_date,
    		    		'firstname'	=> $firstname,
    		    		'lastname'	=> $lastname,
    		    		'sport' => $sport,
    		    		'price'	=> $price,
    		    		'payment_status'=> $created,
        		];
        		
        	}
        }
	    $tb2 = '<div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
           <h2  id="Paid_section_ppp">BFSS Payments Paid</h2>
          <div class="block-bfss-assessors">
          <div class="table-responsive-wrap">
         <table id="bfss_payment_paid_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
 			 <tr>
               	<th class="th-hd"><a><span></span>Date</a>
                </th>  
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th>  
                <th class="th-hd"><a><span></span>Sport</a>
                </th>
                <th class="th-hd"><a><span></span>Amount</a>
                </th> 
                 <th class="th-hd"><a><span></span>Paid</a>
                </th> 
            </tr>
            </thead>
            <tbody >';
      foreach ($TableRowsPaid as $RowValue) {
        	$tb2 .= '<tr>
                <td>'.$RowValue['booking_date'].'</td>
                <td>'.$RowValue['firstname'].'</td>
                <td>'.$RowValue['lastname'].'</td>
                <td>'.$RowValue['sport'].'</td>
                <td>$'.$RowValue['price'].'</td>
                <td>'.$RowValue['payment_status'].'</td>
        	</tr>';
        }
	    $tb2 .= '</tbody>
			      </table>
			       </div>
			      </div>
			       </div>
			      </div>';    
	    return [
	      '#cache' => ['max-age' => 0,],
	      '#theme' => 'bfss_funds_page',
	      '#bfss_payment_pending_block' => Markup::create($tb1),
	      '#bfss_payment_paid_block' => Markup::create($tb2),
	      '#attached' => [
	        'library' => [
	          'acme/acme-styles', //include our custom library for this response
	        ]
	      ]
	    ];
  	}

    public function get_uids_by_orgname($orgname){
        $athlete_uids = [];
        $athlete_club = \Drupal::database()->select('athlete_club', 'n');
        $athlete_club->addField('n', 'athlete_uid');
        $athlete_club->condition('athlete_club_name', $orgname, '=');
        $athlete_club->orderBy('athlete_uid', 'DESC');
        $athlete_club_rels = $athlete_club->execute()->fetchAll();
        foreach ($athlete_club_rels as $athlete_club_rel) {
            $athlete_uids[] = $athlete_club_rel;
        }

        $athlete_uni = \Drupal::database()->select('athlete_uni', 'n');
        $athlete_uni->addField('n', 'athlete_uid');
        $athlete_uni->condition('athlete_uni_name', $orgname, '=');
        $athlete_uni->orderBy('athlete_uid', 'DESC');
        $athlete_uni_rels = $athlete_uni->execute()->fetchAll();
        foreach ($athlete_uni_rels as $athlete_uni_rel) {
          $athlete_uids[] = $athlete_uni_rel;
        }
    
        $athlete_school = \Drupal::database()->select('athlete_school', 'n');
        $athlete_school->addField('n', 'athlete_uid');
        $athlete_school->condition('athlete_school_name', $orgname, '=');
        $athlete_school->orderBy('athlete_uid', 'DESC');
        $athlete_school_rels = $athlete_school->execute()->fetchAll();
        foreach ($athlete_school_rels as $athlete_school_rel) {
           $athlete_uids[] = $athlete_school_rel;
        }
        return $athlete_uids;
    }

}