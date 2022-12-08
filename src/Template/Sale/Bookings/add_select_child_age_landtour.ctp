<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-10 col-sm-10 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);
?>
<div class="row">
    <div class="col-sm-offset-2 col-sm-10">
        <?php for ($i = 0; $i < $numChild; $i++): ?>
            <div class="col-sm-2 col-md-2">
                <?php
                echo $this->Form->control('child.' . $i, [
                    'type' => 'select',
                    'class' => 'form-control select2',
                    'options' => $listAge,
                    'label' => $i + 1,
                    'required' => 'required',
                    'onchange' => 'updateTotalPriceLandtour()'
                ]);
                ?>
            </div>
        <?php endfor; ?>
    </div>
</div>