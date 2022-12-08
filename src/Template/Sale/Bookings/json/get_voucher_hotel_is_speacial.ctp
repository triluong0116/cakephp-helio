<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-10 col-sm-10 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-10 col-sm-10 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);
if ($voucher->hotel->is_special == 1) {
    echo $this->Form->control('information', [
        'type' => 'textarea',
        'class' => 'form-control',
        'value' => isset($booking) ? $booking->information : "",
        'label' => 'Danh sách đoàn, ngày sinh của trẻ em *',
        'required' => 'required'
    ]);
}
echo $this->Form->control('amount', [
    'class' => 'form-control',
    'value' => isset($booking) ? $booking->amount : "",
    'label' => 'Số lượng Voucher *',
    'required' => 'required'
]);
?>
<div class="control-group">
    <div class="controls">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Check in *</label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <div class="input-prepend input-group">
                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                <input type="text" name="start_date" class="custom-singledate-picker form-control" value=""/>
            </div>
        </div>
    </div>
</div>

