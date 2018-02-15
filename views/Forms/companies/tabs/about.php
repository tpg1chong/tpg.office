<?php


$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert');

$form   ->field("company_name")
        ->label($this->lang->translate('Company Name'))
        ->addClass('inputtext')
        ->placeholder('Add Name')
        ->attr('autoselect', 1)
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form->hr('<div class="clearfix"></div>');

/*$this->groups[] = array('id'=>'__add', 'name' => '--- Add New ---');
$form   ->field("company_group_id")
        ->label($this->lang->translate('Category'))
        ->addClass('inputtext')
        ->attr('data-plugins', 'addselect')
        ->attr('data-options', $this->fn->stringify(array('url'=>URL.'companies/add_group')))
        ->select( $this->groups )
        ->value( !empty($this->item['group_id'])? $this->item['group_id']:'' );*/

$form   ->field("company_address")
        // ->name('company[address]')
        ->label($this->lang->translate('Address'))
        ->addClass('inputtext')
        ->placeholder('Add Address')
        ->type('textarea')
        ->value( !empty($this->item['address'])? $this->item['address']:'' );

$form   ->field("company_phone")
        ->label($this->lang->translate('Phone').'')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add Phone')
        ->value( !empty($this->item['phone'])? $this->item['phone']:'' );

$form   ->field("company_email")
        ->label($this->lang->translate('Email'))
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('Add Email')
        ->value( !empty($this->item['email'])? $this->item['email']:'' );

$form   ->field("company_description")
        ->label($this->lang->translate('Description'))
        ->type('textarea')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'autosize')
        ->placeholder('Add Description')
        ->value( !empty($this->item['description'])? $this->fn->q('text')->textarea($this->item['description']):'' );


echo $form->html();