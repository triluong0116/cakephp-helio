<div class="combo-hotel-item">
    <hr />
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <?php
            echo $this->Form->control('hotels.0.id', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                    'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                ],
                'options' => $hotels,
                'empty' => 'Chọn khách sạn',
                'class' => 'form-control select2',
                'required' => 'required',
                'label' => 'Khách sạn *',
                'onchange' => 'countComboPriceByHotel(this)'
            ]);
            ?>
        </div>
        <div class="col-sm-5 col-xs-12">
            <?php
            echo $this->Form->control('hotels.0._joinData.days_attended', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                ],
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'label' => 'Số ngày *',
                'onchange' => 'countComboPriceByHotel(this)'
            ]);
            ?>
        </div>
        <div class="col-sm-1 text-right">
            <a href="#" onclick="deleteItem(this, '.combo-hotel-item');" class="mt10 fs16">
                <i class="text-danger fa fa-times-circle" ></i>
            </a>
        </div>
    </div>
</div>

