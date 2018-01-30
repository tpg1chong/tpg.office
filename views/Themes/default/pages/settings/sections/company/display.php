<?php

require 'init.php';

?><div class="pal" style="max-width: 720px">

<div class="setting-header cleafix">

    <?php if( count($taps) > 1 ){ ?>

    <nav class="setting-header-taps"><?php

    foreach ($taps as $key => $value) {
    	
    	$active = $this->_tap == $value['id'] ? ' active':'';
    	echo '<a class="tap'.$active.'" href="'.URL.'settings/company/'.$value['id'].'">'.$value['name'].'</a>';

    }

    ?></nav>
    <?php } ?>
</div>

<section class="setting-section" style="max-width: 420px"><?php 
	
	require "sections/{$this->_tap}.php";
?></section>

</div>