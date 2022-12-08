<div class="room-by-hotel-item">
    <?php
    echo $this->Form->control('rooms._ids[]', [
        'templates' => [
            'inputContainer' => '<div class="item form-group">{{content}}</div>',
            'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
            'select' => '<div class="col-md-9 col-sm-9 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
        ],
        'options' => $rooms,
        'default' => $room_id,
        'empty' => 'Chọn phòng KS',
        'class' => 'form-control select2',
        'required' => 'required',
        'label' => 'Phòng *'
    ]);
    ?>
</div>