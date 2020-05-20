<?php

namespace Drupal\bfss_basic_pages\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Render\Markup;
class PrivacyPolicyBasicPage extends ControllerBase {


	public function content() {
    $nid = 203;
    if(isset($nid)){
      $node = Node::load($nid);
      $BODY = $node->body->value;
      $BODY =  Markup::create($BODY);
    }
    $HTML = $BODY;

    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'privacy_policy_basic_page',
      '#privacy_policy_basic_page_block' => $HTML,
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
  	}
}