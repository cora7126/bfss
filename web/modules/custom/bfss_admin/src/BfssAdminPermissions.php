<?php
//module name - bfss_admin

namespace Drupal\bfss_admin;

use Drupal\Core\Database\Database;
use  \Drupal\user\Entity\User;

class BfssAdminPermissions {

    public function bfss_admin_permissions() {

	  	$conn = Database::getConnection();
	  	$uid = \Drupal::currentUser()->id();
	  	$user = User::load($uid);
	  	$roles = $user->getRoles();
	  	
	  	$role = '';

	    if(!empty($roles)){
	      if(in_array('administrator', $roles)){
	        $role = 'administrator';
	      }elseif(in_array('parent_guardian_registering_athlete_', $roles)){
	          $role = 'parent_guardian_registering_athlete_';
	      }elseif(in_array('coach', $roles)){
	         $role = 'coach';
	      }elseif(in_array('assessors', $roles)){
	         $role = 'assessors';
	      }elseif(in_array('bfss_manager', $roles)){
	         $role = 'bfss_manager';
	      }elseif(in_array('athlete', $roles)){
	         $role = 'athlete';
	      }else{
	        $role = '';
	      }
	    }
	    
	  	$query = $this->Get_Data_From_Tables('bfss_admin_permissions','at',$role);
	  	// print_r($query);
	   //  die;
	    return $query;
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