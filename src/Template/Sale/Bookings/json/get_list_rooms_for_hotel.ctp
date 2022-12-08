<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-10 col-sm-10 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-10 col-sm-10 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);
if ($hotel->is_special == 1) {
    echo $this->Form->control('information', [
        'type' => 'textarea',
        'class' => 'form-control',
        'value' => isset($booking) ? $booking->information : "",
        'label' => 'Danh sách đoàn, ngày sinh của trẻ em *',
        'required' => 'required'
    ]);
}
?>
<div id="list-hotel-room-another">
    <?php if (isset($booking)): ?>
        <?php foreach ($booking->booking_rooms as $bkRoomKey => $booking_room): ?>
            <fieldset class="booking-room-item">
                <legend>Hạng phòng</legend>
                <div class="row">
                    <div class="col-sm-offset-11 col-sm-1 text-right">
                        <a href="#" onclick="deleteItem(this, '.booking-room-item');" class="mt10">
                            <i class="text-danger fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label col-sm-3 col-md-3 col-xs-12">Chọn hạng phòng</label>
                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <select class="form-control" name="booking_rooms[<?= $booking_room->id ?>][room_id]" id="">
                                <?php foreach ($listRoom as $roomKey => $room): ?>
                                    <option value="<?= $roomKey ?>" <?= $booking_room->room_id == $roomKey ? 'selected' : '' ?> ><?= $room ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label col-sm-2">Số phòng *</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="booking_rooms[<?= $booking_room->id ?>][num_room]" value="<?= $booking_room->num_room ?>" required type="text">
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-sm-6">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Check in *</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input type="text" name="booking_rooms[<?= $booking_room->id ?>][start_date]" class="custom-singledate-picker form-control" value="<?= date_format($booking_room->start_date, 'd/m/Y') ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Check out *</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input type="text" name="booking_rooms[<?= $booking_room->id ?>][end_date]" class="custom-singledate-picker form-control" value="<?= date_format($booking_room->end_date, 'd/m/Y') ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-sm-6">
                        <div class="col-sm-6">
                            <label class="control-label col-sm-4">Số người lớn *</label>
                            <div class="col-sm-8">
                                <input class="form-control" name="booking_rooms[<?= $booking_room->id ?>][num_adult]" value="<?= $booking_room->num_adult ?>" required type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label col-sm-4">Số trẻ em *</label>
                            <div class="col-sm-8">
                                <input class="form-control" name="booking_rooms[<?= $booking_room->id ?>][num_children]" value="<?= $booking_room->num_children ?>" required onchange="addListChildAgeAnother(this)" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="list-child-age-another">
                            <?php $childAge = json_decode($booking_room->child_ages) ?>
                            <div class="row">
                                <?php foreach ($childAge as $ageKey => $age): ?>
                                    <div class="col-sm-3">
                                        <div class="item form-group">
                                            <label class="col-sm-4 control-label text-left"><?= ($ageKey + 1) ?></label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="booking_rooms[<?= $booking_room->id ?>][child_ages][]">
                                                    <option></option>
                                                    <?php for ($j = 0; $j <= 18; $j++): ?>
                                                        <option value="<?= $j ?>" <?= $j == $age ? 'selected' : '' ?>><?= $j ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <input type="hidden" name="booking_rooms[<?= $booking_room->id ?>][num_people]">
                        <p id="error_booking_rooms_<?= $booking_room->id ?>_num_people" class="error-message"></p>
                    </div>
                </div>
            </fieldset>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="row">
    <div class="col-sm-offset-2 col-sm-10">
        <a class="btn btn-success" onclick="addRoomHotelAnotherBooking(<?= $hotel->id ?>)">
            Thêm hạng phòng
        </a>
    </div>
</div>

