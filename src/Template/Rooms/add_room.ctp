<div class="room-item">
    <hr style="margin-top: 5px">
    <div class="row">
        <div class="col-sm-10 col-xs-10">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Tên hạng phòng</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input class="form-control" type="text" name="rooms[0][name]">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Mô tả</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <textarea class="form-control" name="rooms[0][description]"></textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-sm-2 text-right">
            <a href="#" onclick="deleteItem(this, '.room-item');" class="mt10">
                <i class="text-danger fa fa-minus" ></i>
            </a>
        </div>
    </div>
</div>