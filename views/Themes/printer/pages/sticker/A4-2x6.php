<?php 
echo '<ul class="sticker_A4-2x6 clearfix">';

$prefixName = !empty($prefixName) ? $prefixName.' ': '';

if( !empty($this->results['title']) ){
    for ($i=0; $i < count($this->results['title']); $i++) { 
    	echo '<li><div class="outer"><div class="inner"><div class="box">'.
    		'<h1>'.$prefixName.nl2br($this->results['title'][$i]).'</h1>'.
    		// '<h2>'.nl2br($this->results['context'][$i]).'</h2>'.
    		'<h3>'.nl2br($this->results['text'][$i]).'</h3>'.
    	'</div></div></div></li>';
    }

}

echo '</ul>';

?>
<script type="text/javascript">

	var beforePrint = function() {};

    var afterPrint = function() {
    	window.top.close();
    };

    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function(mql) {
            if (mql.matches) {
                beforePrint();
            } else {
                afterPrint();
            }
        });
    }

    window.onbeforeprint = beforePrint;
    window.onafterprint = afterPrint;

    window.print();
</script>