<div class="vin-caption-item">
    <hr/>
    <div class="row">
        <div class="col-sm-10 col-xs-10">
            <div class="mb10">
                <div class="col-sm-3">
                    <label>Ảnh Tiện nghi</label>
                </div>
                <div class="col-sm-9">
                    <input class="form-control" type="file" name="list_vin_caption[caption][0][image]">
                </div>
            </div>
            <?php
            echo $this->Form->control('list_vin_caption.caption.0.content', [
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
            <a href="#" onclick="deleteItem(this, '.vin-caption-item');" class="mt10">
                <i class="text-danger fa fa-minus"></i>
            </a>
        </div>
    </div>
</div>
