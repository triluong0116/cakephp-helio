<div class="combo-room-item">
    <hr />
    <div class="row">
        <div class="col-sm-5 col-xs-12">
            <?php
            echo $this->Form->control('hotel[]', [
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
                'onchange' => 'getRoomByHotel(this)'
            ]);
            ?>
        </div>
        <div class="col-sm-5 col-xs-12">
            <div class="room-by-hotel">

            </div>
        </div>
        <div class="col-sm-2 text-right">
            <a href="#" onclick="deleteItem(this, '.combo-room-item');" class="mt10">
                <i class="text-danger fa fa-minus" ></i>
            </a>
        </div>
    </div>
</div>

