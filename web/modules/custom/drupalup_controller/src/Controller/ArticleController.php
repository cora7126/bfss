<?php 

namespace Drupal/drupalup_controller/Controller;

class ArticleController(){
	public function page(){

		$items = array(
			     array('name' => 'Articles One'),
			     array('name' => 'Articles Two'),
			     array('name' => 'Articles Three'),
			     array('name' => 'Articles Four')
			);

		return array(
			'#theme' => 'article_list',
			'#items' => $items,
			'#title' => 'OUR Articles'
		);

	}
}

?>