<div class="caption-combo-item">
    <hr/>
    <div class="row">
        <div class="col-sm-10 col-xs-10">
            <?php
            echo $this->Form->control('list_caption.0.content', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                    'textarea' => '<div class="col-md-9 col-sm-9 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
                ],
                'type' => 'textarea',
                'class' => 'form-control tinymce',
                'label' => 'Mô tả *',
//                'required' => 'required'
            ]);
            ?>
        </div>
        <div class="col-sm-2 col-sm-2 text-right">
            <a href="#" onclick="deleteItem(this, '.caption-combo-item');" class="mt10">
                <i class="text-danger fa fa-minus"></i>
            </a>
        </div>
    </div>
</div>
