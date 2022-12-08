<?php foreach ($booking_rooms as $key => $booking_room): ?>
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
            'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
            'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
            'textarea' => '<div class="col-md-8 col-sm-8 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
            'inputContainer' => '<div class="item form-group">{{content}}</div>',
            'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
            'checkContainer' => ''
        ]);
        ?>
        <div class="row">
            <div class="col-sm-6">
                <input type="hidden" name="booking_rooms[<?= $key ?>][id]" value="<?= $booking_room->id ?>">
                <?php
                echo $this->Form->control('booking_rooms.' . $key . '.room_id', [
                    'type' => 'select',
                    'class' => 'form-control select2',
                    'empty' => 'Chọn hạng phòng',
                    'options' => $listRoom,
                    'label' => 'Chọn hạng phòng *',
                    'required' => 'required',
                    'default' => $booking_room->room_id
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <?php
                echo $this->Form->control('booking_rooms.' . $key . '.num_room', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Số phòng *',
                    'required' => 'required',
                    'default' => $booking_room->num_room
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
                                <input type="text" name="booking_rooms[<?= $key ?>][start_date]" class="custom-singledate-picker form-control" value="<?= $booking_room->start_date->format('d/m/Y') ?>"/>
                            </div>
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
                                <input type="text" name="booking_rooms[<?= $key ?>][end_date]" class="custom-singledate-picker form-control" value="<?= $booking_room->end_date->format('d/m/Y') ?>"/>
                            </div>
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
                        echo $this->Form->control('booking_rooms.' . $key . '.num_adult', [
                            'type' => 'text',
                            'class' => 'form-control',
                            'label' => 'Số người lớn *',
                            'required' => 'required',
                            'default' => $booking_room->num_adult
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        echo $this->Form->control('booking_rooms.' . $key . '.num_children', [
                            'type' => 'text',
                            'class' => 'form-control',
                            'label' => 'Số trẻ con *',
                            'required' => 'required',
                            'onchange' => 'addSelectChildAge(this)',
                            'default' => $booking_room->num_children
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="list-child-age">
                    <?php $child_ages = json_decode($booking_room->child_ages, true); ?>
                    <?php if ($child_ages): ?>
                        <div class="row">
                            <?php foreach ($child_ages as $key => $child_age): ?>
                                <div class="col-sm-3">
                                    <div class="item form-group">
                                        <label class="col-sm-4 control-label text-left"><?= ($key + 1) ?></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="booking_rooms[<?= $key ?>][child_ages][]">
                                                <option></option>
                                                <?php for ($j = 0; $j <= 18; $j++): ?>
                                                    <option value="<?= $j ?>" <?= ($j == $child_age) ? 'selected' : '' ?>><?= $j ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <input type="hidden" name="booking_rooms[0][num_people]">
                    <p id="error_booking_rooms_<?= $key ?>_num_people" class="error-message"></p>
                </div>
            </div>
        </div>
    </fieldset>
<?php endforeach; ?>
