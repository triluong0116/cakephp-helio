<?php

$config = [
    'Templates' => [
        'formValidate' => [
            'formStart' => '<form class="" {{attrs}}>',
            'label' => '<label class="col-md-2 control-label" {{attrs}}>{{text}}</label>',
            'input' => '<div class="col-md-4"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
            'select' => '<div class="col-md-4"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
            'inputContainer' => '<div class="form-group {{required}}" form-type="{{type}}">{{content}}</div>',
            'checkContainer' => '',],
        'longForm' => [
            'formStart' => '<form class="" {{attrs}}>',
            'label' => '<label class="col-md-2 control-label" {{attrs}}>{{text}}</label>',
            'input' => '<div class="col-md-6"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
            'select' => '<div class="col-md-6"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
            'inputContainer' => '<div class="form-group {{required}}" form-type="{{type}}">{{content}}</div>',
            'checkContainer' => '',],
        'fullForm' => [
            'formStart' => '<form class="" {{attrs}}>',
            'label' => '<label class="col-md-2 control-label" {{attrs}}>{{text}}</label>',
            'input' => '<div class="col-md-10"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
            'select' => '<div class="col-md-10"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
            'inputContainer' => '<div class="form-group {{required}}" form-type="{{type}}">{{content}}</div>',
            'checkContainer' => '',]
    ]
];
