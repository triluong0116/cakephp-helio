<div class="caption-combo-item">
    <hr/>
    <div class="row">
        <div class="col-sm-10 col-xs-10">
            <?php
            echo $this->Form->control('list_review.0.name', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                ],
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên *',
                'required' => 'required',
            ]);
            echo "<div class='clearfix'></div>";
            echo $this->Form->control('list_review.0.address', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                ],
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Địa chỉ *',
                'required' => 'required',
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