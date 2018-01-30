<?php

class Index extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        header('Location: '. URL.'dashboard' );
    }

    public function search($page=null) {

    	/*if( !empty($page[0]) ){
            if( in_array($page[0], array('printer')) ){
                $this->pathName = $page[0];
                $this->_modify();
            }
        }*/
        $this->error();
    }
}
