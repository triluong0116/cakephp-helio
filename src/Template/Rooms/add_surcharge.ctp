<div class="surcharge-item">
    <div class="row">
        <div class="col-sm-11 col-xs-11">
            <div class="row">
                <div class="col-sm-6 col-xs-6 no-pad-r">
                    <?php
                    echo $this->Form->control('hotel_surcharges.0.surcharge_type', [
                        'templates' => [
                            'inputContainer' => '<div class="item form-group">{{content}}</div>',
                            'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                            'select' => '<div class="col-md-7 col-sm-7 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                        ],
                        'options' => $surcharges,
                        'class' => 'form-control',
                        'label' => 'Phụ thu *',
                        'required' => 'required',
                        'onchange' => 'switchCustomSurchage(this)'
                    ]);
                    ?>
                </div>
                <div class="col-xs-6 col-sm-6">
                    <div class="surcharge-normal-price">
                        <?php
                        echo $this->Form->control('hotel_surcharges.0.price', [
                            'templates' => [
                                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                                'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                                'input' => '<div class="col-md-7 col-sm-7 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                            ],
                            'type' => 'text',
                            'class' => 'form-control currency',
                            'label' => 'Đơn giá *',
                            'required' => 'required',
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="custom-other-surcharge" style="display: none;margin-bottom: 10px">
                <div class="row">
                    <div class="col-sm-6 col-xs-6 no-pad-r">
                        <?php
                        echo $this->Form->control('hotel_surcharges.0.other_name', [
                            'templates' => [
                                'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                                'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                            ],
                            'class' => 'form-control',
                            'label' => 'Tên Phụ thu *',
                            'disabled' => true
                        ]);
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        <?php
                        echo $this->Form->control('hotel_surcharges.0.price', [
                            'templates' => [
                                'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                                'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                            ],
                            'type' => 'text',
                            'class' => 'form-control currency',
                            'label' => 'Đơn giá *',
                            'disabled' => true
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <?php
                    echo $this->Form->control('hotel_surcharges.0.description', [
                        'templates' => [
                            'inputContainer' => '<div class="item form-group">{{content}}</div>',
                            'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                            'textarea' => '<div class="col-md-10 col-sm-10 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
                        ],
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => 'Mô tả',
                        'rows' => 2
                    ]);
                    ?>
                </div>
            </div>
            <div class="custom-surcharge-price" style="padding-left: 20px; padding-right: 20px;display: none">
                <div class="list-custom-surcharge"></div>
                <div class="clearfix"></div>
                <div class="text-center mt10">
                    <button type="button" class="btn btn-success btn-xs text-center" onclick="addCustomSurcharge(this)"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-1 col-xs-1 text-right">
            <a href="#" onclick="deleteItem(this, '.surcharge-item');" class="mt10">
                <i class="text-danger fa fa-times"></i>
            </a>
        </div>
    </div>
    <hr>
</div>
