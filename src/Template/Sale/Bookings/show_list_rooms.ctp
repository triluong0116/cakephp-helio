<fieldset class="booking-room-item">
    <legend>Hạng Phòng</legend>
    <div class="row">
        <div class="col-sm-offset-11 col-sm-1 text-right">
            <a href="#" onclick="deleteItem(this, '.booking-room-item');" class="mt10">
                <i class="text-danger fa fa-times"></i>
            </a>
        </div>
    </div>
    <?php
    $this->Form->setTemplates([
        'formStart' => '<form class="" {{attrs}}>',
        'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
        'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /><p class="error-message"></p></div>',
        'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select><p class="error-message"></p></div>',
        'textarea' => '<div class="col-md-8 col-sm-8 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea><p class="error-message"></p></div>',
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
        'checkContainer' => ''
    ]);
    ?>
    <div class="row mt10">
        <div class="col-sm-6">
            <?php
            echo $this->Form->control('booking_rooms.0.room_id', [
                'type' => 'select',
                'class' => 'form-control select2',
                'empty' => 'Chọn hạng phòng',
                'options' => $listRoom,
                'label' => 'Chọn hạng phòng *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?php
            echo $this->Form->control('booking_rooms.0.num_room', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số phòng *',
                'required' => 'required'
            ]);
            ?>
        </div>
    </div>
    <div class="row mt10">
        <div class="col-sm-6">
            <?php
            echo $this->Form->control('booking_rooms.0.room_single_price', [
                'type' => 'text',
                'readonly' => true,
                'class' => 'form-control ',
                'label' => 'Giá phòng',
            ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?php
            echo $this->Form->control('booking_rooms.0.room_total_price', [
                'type' => 'text',
                'readonly' => true,
                'class' => 'form-control',
                'label' => 'Tổng Giá phòng',
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="control-group">
                <div class="controls">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Check in *</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="booking_rooms[0][start_date]" class="custom-singledate-picker form-control" value="<?= (isset($dataValiError['reservation']) && !empty($dataValiError['reservation'])) ? $dataValiError['reservation'] : '' ?>"/>
                        </div>
                        <p id="checkin" class="error-messages"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="control-group">
                <div class="controls">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Check out *</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="booking_rooms[0][end_date]" class="custom-singledate-picker form-control" value=""/>
                        </div>
                        <p id="checkout" class="error-messages"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    echo $this->Form->control('booking_rooms.0.num_adult', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Số người lớn *',
                        'required' => 'required'
                    ]);
                    ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo $this->Form->control('booking_rooms.0.num_children', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Số trẻ con *',
                        'required' => 'required',
                        'onchange' => 'addSelectChildAge(this)'
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <input type="hidden" name="booking_rooms[0][num_people]">
                    <p id="error_booking_rooms_0_num_people" class="error-message"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="list-child-age">
                <input type="hidden" name="booking_rooms[0][child_ages][]">
            </div>
        </div>

    </div>

</fieldset>
