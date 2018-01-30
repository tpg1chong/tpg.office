<?php

$tr = "";
$tr_total = "";
if( !empty($this->results['lists']) ){ 

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) { 
          
        // print_r($item['type']['name']);        die();
        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";
        // set Name

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            // '<td class="check-box"><label class="checkbox"><input id="toggle_checkbox" type="checkbox" value="'.$item['id'].'"></label></td>'.
            

            '<td class="name">'.
                '<a class="fwb link-hover" href="'.URL.'properties/'.$item['id'].'"><span>'.$item['name'].'</span><i class="icon-pencil mls"></i></a>'.
            '</td>'.

            '<td class="email">['.$item['type']['code'].'] '.$item['type']['name'].'</td>'.

            '<td class="email">['.$item['zone']['code'].'] '.$item['zone']['name'].'</td>'.

            '<td class="actions">'.
                '<div class="">'.
                    '<a data-plugins="dialog" href="'.URL.'property/edit/'.$item['id'].'" class="btn btn-orange"><i class="icon-pencil"></i></a>'.
                    '<a data-plugins="dialog" href="'.URL.'property/del/'.$item['id'].'" class="btn btn-red"><i class="icon-trash"></i></a>'.
                '</div>'.
            '</td>'.

        '</tr>';
        
    }
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';