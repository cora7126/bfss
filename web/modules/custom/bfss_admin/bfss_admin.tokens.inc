<?php
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Render\Markup;
use \Drupal\node\Entity\Node;

/**
 * @file
 * Contains bfss_admin.module..
 */


/*
* implement hook_token_info()
*/
function bfss_admin_token_info(){
  $types['custom_email_token_type'] = array(
    'name' => t('Custom Email Token'),
    'description' => t('use for data send in email template')
  );	
  $tokens['custom_email_token_name'] = array(
    'name' => t('Custom email token'),
    'description' => t('use for data send in email template')
  );
	
  return array(
    'types' => $types,
    'tokens' => array(
      'custom_email_token_type' => $tokens
    )
  );	
}

/*
* implement hook_token()
*/
function bfss_admin_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata){
  
  $node = Node::load(196);
  $body = $node->body->value;

  $replacements = array();
  if($type == 'custom_email_token_type') {
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'custom_email_token_name':
        $text = $body;
      	$replacements[$original] = Markup::create($text);
      	break;
      }
    }
  }
  return $replacements;
}
?>