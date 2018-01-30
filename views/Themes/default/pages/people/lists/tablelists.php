<?php

$tr = "";
$tr_total = "";
if( !empty($this->results['lists']) ){ 

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) { 


        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";

        $image = '';
        if( !empty($item['image_url']) ){
            $image = '<div class="avatar lfloat mrm"><img class="img" src="'.$item['image_url'].'" alt="'.$item['fullname'].'"></div>';
        }
        else{
            $image = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
        }

        $qty = !empty($item['total_item'])
            ? $item['total_item']
            : '-';


        $express = '';
        if( !empty($item['phone']) ){
            $express .= '<li><i class="icon-phone mrs"></i><a href="tel:'.$item['phone'].'">'.$item['phone'].'</a></li>';
        }

        if( !empty($item['email']) ){
             $express .= '<li><i class="icon-envelope mrs"></i><a href="mailto:'.$item['email'].'" title="'.$item['email'].'">'.$item['email'].'</a></li>';
        }

        if( !empty($item['lineID']) ){
            $express .= '<li><i class="icon-line mrs"></i><a href="line:'.$item['lineID'].'">'.$item['lineID'].'</a></li>';
        }

        $age = '';
        if( $item['birthday'] !='0000-00-00' ){
            $age = '<span class="fsm fwn mls meta">(อายุ '.$this->fn->q('time')->age( $item['birthday'] ).' ปี)</sapn>';
        }

        if( !empty($item['nickname']) ){
            $item['fullname'] .=" ({$item['nickname']})";
        }
        
        if( !empty($item['category_name']) ){
            $item['agency_name'] .="<span class=\"fwn fcg\"> - {$item['category_name']}</span>";
        }

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="check-box"><label class="checkbox"><input id="toggle_checkbox" type="checkbox" value="'.$item['id'].'"></label></td>'.

            '<td class="name">'.
                '<div class="anchor clearfix">'.
                    $image.
                    '<div class="content"><div class="spacer"></div><div class="massages">'.
                        '<div class="fullname"><a class="fwb" href="'.URL .'customers/'.$item['id'].'">'. $item['name'].'</a></div>'.
                        '<div class="subname fsm meta fcg">Last update: '.$this->fn->q('time')->live( $item['updated'] ).'</div>'.
                    '</div>'.
                '</div></div>'.
            '</td>'.

            '<td class="express">'.( !empty($express) ? '<ul class="fsm">'.$express.'</ul>':'-' ).'</td>'.

            '<td class="company">'.
                '<div class="fwb">'.$item['agency_name'].'</div>'.
                '<div class="fsm">'.$item['agency_address'].'</div>'.
            '</td>'.
            
            '<td class="type"><span class="ui-status">'.$item['position_name'].'</span></td>'.

            // '<td class="type"><span class="ui-status">'.$item['country_name'].'</span></td>'.
            // '<td class="type"><span class="ui-status">'.$item['category_name'].'</span></td>'.
            
            '<td class="actions"><div class="group-btn whitespace">'.

                ( $this->pagePermit['edit']
                    ? '<a class="btn" data-plugins="dialog" href="'.$this->pageURL.'edit/'.$item['id'].'"><i class="icon-pencil"></i></a>'
                    : ''
                ).

                ( $this->pagePermit['del']
                    ? '<a class="btn" data-plugins="dialog" href="'.$this->pageURL.'del/'.$item['id'].'"><i class="icon-trash"></i></a>
                </div></td>'
                    : ''
                ).

            '</td>'.

        '</tr>';
        
    }
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';