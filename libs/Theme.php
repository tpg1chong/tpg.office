<?php 

class Theme extends View{
    
    public function __construct() {
        parent::__construct();
    }

    public function init( $theme_name, $path_name ) {

        $path = 'Theme/' .$theme_name.'_theme.php';

        $_path = $this->getPage('path');
        if( !empty($_path)  ){
           $_path = rtrim($_path, '/').'/'.$path_name;
        }
        else{
            $_path = "Themes/{$this->getPage('theme')}/pages/{$path_name}";
        }

        $this->options = $this->page['theme_options'];

        if( file_exists(WWW_LIBS. $path) ){

            $themeName = $theme_name . '_Theme';

            require $path;
            $this->theme = new $themeName();
            $this->theme->page = $this->page;
            $this->theme->init();
            $this->theme->render($_path, $this->options);
        }
        else{

            $this->_setPage();
            $this->_render($_path);
        }
    }

    public function _setPage() {

        if( !empty($this->page['data']) ){
            foreach ($this->page['data'] as $key => $value) {
                $this->{$key} = $value;
            }
        }

        // has font
        if( !empty($this->page['font']) ){
            $this->css('https://fonts.googleapis.com/css?family='.$this->page['font']['name'], true);
            $this->style('body, input, textarea, select, button,.editor-text{'.$this->page['font']['specify'].'}');
        }

        // has Left Menu
        if( !empty($this->options['has_menu']) ){
            $hasPushedLeft = 1;
            $cls = $this->elem('body')->attr('class');
            
            if( !empty($cls) ){
                if( in_array('is-overlay-left', explode(' ', $cls)) ) {
                    $hasPushedLeft = 0;
                }
            }

            if( $hasPushedLeft==1 ){

                Session::init();
                $isPushedLeft = Session::get('isPushedLeft');

                if( isset($isPushedLeft) ){
                    if( $isPushedLeft==1 ) {
                        $this->elem('body')->addClass('is-pushed-left');
                    }
                }
                else{
                    $this->elem('body')->addClass('is-pushed-left');
                }
            }
        }

        $mode = 'light';
        if( !empty($this->me['mode']) ){

            switch ($this->me['mode']) {
                case 'dark': $mode = 'drak'; break;
                case 'blue': $mode = 'blue'; break;
                case 'green': $mode = 'green'; break;
            }
        }
        $this->elem('body')->addClass( $mode );

        if( !empty($this->options['has_topbar']) ){
            $this->elem('body')->addClass( 'hasTopbar' );
        }

        /**/
        /* source file */
        /**/
        $source = array();
        $source[] = array('type'=>'css', 'name'=> 'icon');
        $source[] = array('type'=>'css', 'name'=> 'respontsive');
        $source[] = array('type'=>'css', 'name'=> 'main');
        $source[] = array('type'=>'js', 'name'=> 'main');

        foreach ($source as $val) {

            $type = $val['type'];
            $path = "Themes/{$this->getPage('theme')}/assets/{$type}/{$val['name']}.{$type}";

            if( file_exists(WWW_VIEW.$path) ){
                $this   ->{$type}( VIEW .$path, true );
            }


        }
        
        if( !empty($this->page['font_icon']) ){
            $this->css( FONTS . $this->page['font_icon'], true);
        }
        else{
            $this->css( FONTS . 'font-awesome/css/font-awesome.css', true);
        }

        $this   ->css('bootstrap')
                
                ->js('custom')
                ->js('plugins/dialog')
                ->js('plugins/default')
                ->js('jquery/jquery');

    }
    public function _render($name, $options=array()) {

        # head
        if( empty($this->options['has_head']) ){
            require 'views/Layouts/default/head.php';
        }
        else{
            require "views/Themes/{$this->getPage('theme')}/layouts/head.php";
        }

        # start: doc
        echo '<div id="doc">';

        # topbar
        if( !empty($this->options['has_topbar']) ){
            require "views/Themes/{$this->getPage('theme')}/layouts/topbar.php";
        }

        # menu
        if( !empty($this->options['has_menu']) ){
            require "views/Themes/{$this->getPage('theme')}/layouts/navigation-main.php";
        }

        # content
        echo '<div id="page-container">';
            require "views/{$name}.php";
        echo '</div>';

        # footer
        if( !empty($this->options['has_footer']) ){
            require "views/Themes/{$this->getPage('theme')}/layouts/footer.php";
        }

        # end: doc
        echo '</div>';

        # footer
        require 'views/Layouts/default/footer.php';
    }
}