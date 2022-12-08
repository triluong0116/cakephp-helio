<div class="price-room-item">
    <div class="row">
        <div class="col-sm-11 col-xs-11">
            <div class="control-group">
                <div class="controls">
                    <label class="col-md-4 col-sm-4 col-xs-12 text-left">Khoảng thời gian *</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="price_rooms[0][dates]" required="required" class="custom-daterange-picker form-control" value="" required="required"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="col-md-6 col-sm-6 col-xs-12">Giá ngày thường *</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control currency" name="price_rooms[0][weekday]" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="col-md-6 col-sm-6 col-xs-12">Giá cuối tuần *</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control currency" name="price_rooms[0][weekend]" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="col-md-6 col-sm-6 col-xs-12">Lợi nhuận cho MustGo *</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control currency" name="price_rooms[0][price_agency]" required />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="col-md-6 col-sm-6 col-xs-12">Lợi nhuận cho Đại lý *</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control currency" name="price_rooms[0][price_customer]" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1 col-xs-1 text-right">
            <a href="#" onclick="deleteItem(this, '.price-room-item');" class="mt10">
                <i class="text-danger fa fa-times"></i>
            </a>
        </div>
    </div>
    <hr>
</div>
