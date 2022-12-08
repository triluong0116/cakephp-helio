<div class="email-item">
    <?php
    $this->Form->setTemplates([
        'formStart' => '<form class="" {{attrs}}>',
        'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
        'input' => '<div class="col-md-9 col-sm-9 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
        'select' => '<div class="col-md-9 col-sm-9 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
        'textarea' => '<div class="col-md-9 col-sm-9 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
        'checkContainer' => ''
    ]) ?>
    <hr/>
    <div class="row">
        <div class="col-sm-10 col-xs-10">
            <?php
            echo $this->Form->control('list_email[0][name]', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                ],
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Email *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
                <div class="radio">
                    <label>
                        <input type="checkbox" class="flat" name="list_email[0][is_main]" value="1">
                    </label>
                </div>
            </div>
        </div>
        <div class="col-sm-1 col-sm-1 text-right">
            <a href="#" onclick="deleteItem(this, '.email-item');" class="mt10">
                <i class="text-danger fa fa-minus"></i>
            </a>
        </div>
    </div>
</div>
