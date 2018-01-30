<?php

class Printer extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function sticker( $size='A4-2x6' ){

		$this->view->setData('results', $_POST);
		$this->view->render("sticker/{$size}");
	}
}