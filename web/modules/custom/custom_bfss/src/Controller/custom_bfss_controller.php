<?php

namespace Drupal\custom_bfss\Controller; 
use Symfony\Component\HttpFoundation\Response;
class custom_bfss_controller{
    
//    public function user_edit_profile(){
//        
//        return new Response('herer');
//    }
//    
    function custom_bfss_form_alter(&$form, &$form_state, $form_id) {    
     global $user;

        switch ($form_id) {
        case 'user_profile_form':
            echo 'here';die;
            if ($user->uid != 1){
            // fields must have a hidden field w/ proper value as disabled fields are not submitted.
          $form['account']['readonly']['mail'] = $form['account']['mail'];
          $form['account']['readonly']['mail']['#type'] = 'hidden';
          $form['account']['mail']['#disabled'] = TRUE;
            }
            break;
        }
    }
}