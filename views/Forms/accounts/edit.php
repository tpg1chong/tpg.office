<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form   ->hr( '<div class="mbm">'.
        "<p>Before renaming this user, ask the user to sign out of his or her account. After you rename this user:</p>
        <ul class=\"uiListStandard\">
                <li>All contacts in the user's Google Talk chat list are removed.</li>
                <li>The user might not be able to use chat for up to 3 days.</li>
                <li>The rename operation can take up to 10 minutes.</li>
                <li>The user's current address (carine@thaipropertyguide.com) becomes an alias to ensure email delivery.</li>
                <li>The new name might not be available for up to 10 minutes.</li>
        </ul>".
        '</div>' );

$form   ->field("user_name")
        ->label(Translate::Val('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("user_login")
        ->label(Translate::Val('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['login'])? $this->item['login']:'' );


# set form
$arr['hiddenInput'][] = array('name'=>'id', 'value'=> $this->item['id']);
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'accounts/save"></form>';

# body
$arr['body'] = $form->html();

# title
$arr['title']= Translate::Val('Rename user');


# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.Translate::Val('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.Translate::Val('Cancel').'</span></a>';


echo json_encode($arr);