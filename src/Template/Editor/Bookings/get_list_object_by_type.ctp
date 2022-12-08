<?php

echo $this->Form->control('item_id', [
    'empty' => 'Chọn',
    'templates' => [
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
        'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    ],
    'class' => 'form-control select2',
    'label' => $label,
    'default' => $item_id,
    'options' => $objects,
    'required' => 'required'
]);
