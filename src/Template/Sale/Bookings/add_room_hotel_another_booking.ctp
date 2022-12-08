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
                <select class="form-control" name="booking_rooms[0][room_id]" id="">
                    <?php foreach ($listRoom as $k => $room): ?>
                        <option value="<?= $k ?>"><?= $room ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <label class="control-label col-sm-2">Số phòng *</label>
            <div class="col-sm-10">
                <input class="form-control" name="booking_rooms[0][num_room]" required type="text">
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
                            <input type="text" name="booking_rooms[0][start_date]" class="custom-singledate-picker form-control" value="<?= (isset($dataValiError['reservation']) && !empty($dataValiError['reservation'])) ? $dataValiError['reservation'] : '' ?>"/>
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
                            <input type="text" name="booking_rooms[0][end_date]" class="custom-singledate-picker form-control" value=""/>
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
                    <input class="form-control" name="booking_rooms[0][num_adult]" required type="text">
                </div>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-4">Số trẻ em *</label>
                <div class="col-sm-8">
                    <input class="form-control" name="booking_rooms[0][num_children]" required onchange="addListChildAgeAnother(this)" type="text">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="list-child-age-another">

            </div>
        </div>
        <div class="col-sm-12">
            <input type="hidden" name="booking_rooms[0][num_people]">
            <p id="error_booking_rooms_0_num_people" class="error-message"></p>
        </div>

    </div>
</fieldset>

