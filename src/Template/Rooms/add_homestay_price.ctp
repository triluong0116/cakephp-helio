<div class="homestay-price-by-date">
    <div class="row">
        <hr/>
        <div class="col-sm-10 col-xs-7">
            <div class="control-group">
                <div class="controls">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Khoảng thời gian *</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i
                                        class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="homestay[price_homestay][0][date]" required="required"
                                   class="custom-daterange-picker form-control" value=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1 col-xs-1 text-right">
            <a href="#" onclick="deleteItem(this, '.homestay-price-by-date');" class="mt10">
                <i class="text-danger fa fa-times"></i>
            </a>
        </div>
        <div class="col-sm-6 col-xs-6">
            <span>Từ thứ 2 đến thứ 5</span>
        </div>
        <div class="col-sm-6 col-xs-6">
            <?php
            echo $this->Form->control("homestay.price_homestay.0.price_weekday", [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>'
                ],
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-6 col-xs-6">
            <span>Từ thứ 6 đến chủ nhật</span>
        </div>
        <div class="col-sm-6 col-xs-6">
            <?php
            echo $this->Form->control("homestay.price_homestay.0.price_weekend", [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>'
                ],
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá *',
                'required' => 'required',
            ]);
            ?>
        </div>
    </div>
</div>