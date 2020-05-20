<?php

namespace Drupal\bfss_basic_pages\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Render\Markup;
class CookiePolicyBasicPage extends ControllerBase {


	public function content() {
    $nid = 204;
    if(isset($nid)){
      $node = Node::load($nid);
      $BODY = $node->body->value;
      $BODY =  Markup::create($BODY);
    }
    $HTML = $BODY;

    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'cookie_policy_basic_page',
      '#cookie_policy_basic_page_block' => $HTML,
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];
  	}
}