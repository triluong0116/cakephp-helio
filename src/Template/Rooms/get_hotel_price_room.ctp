<div class="col-xs-12 col-md-6" >
    <div class="x_panel">
        <div class="x_title">
            <h3>Giá ngày thường của Hạng phòng</h3>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addPriceHotel(this, '#list-normal-price')"><i class="fa fa-plus"></i>Thêm giá <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div id="e-loading-icon" class="text-center">
                <img src="<?= $this->Url->assetUrl('backend/img/e-loading.gif')?>" style="width: 100px;">
            </div>
            <div id="list-normal-price">
                <?php foreach ($dataEdit as $key => $data): ?>
                    <div class="price-room-item">
                        <div class="row">
                            <hr/>
                            <div class="col-sm-11 col-xs-11">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Khoảng thời gian *</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <div class="input-prepend input-group">
                                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                <input type="text" name="price_rooms[<?= $key ?>][date]" required="required" class="custom-daterange-picker form-control" value="" required="required"/>
                                                <div class="date-range-edit-value" data-start-date="<?= $data['start_date'] ?>" data-end-date="<?= $data['end_date'] ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php foreach ($data['items'] as $k => $item): ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h2><?= $item['name']?></h2>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-sm-6 col-xs-12">Giá ngày thường *</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control currency" name="price_rooms[<?= $key ?>][items][<?= $k?>][weekday]" value="<?= $item['weekday']?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-sm-6 col-xs-12">Giá cuối tuần *</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control currency" name="price_rooms[<?= $key ?>][items][<?= $k?>][weekend]" value="<?= $item['weekend']?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-sm-6 col-xs-12">Lợi nhuận cho MustGo *</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control currency" name="price_rooms[<?= $key ?>][items][<?= $k?>][price_agency]" value="<?= $item['price_agency']?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-sm-6 col-xs-12">Lợi nhuận cho Đại lý *</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input class="form-control currency" name="price_rooms[<?= $key ?>][items][<?= $k?>][price_customer]" value="<?= $item['price_customer']?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="price_rooms[<?= $key ?>][items][<?= $k?>][room_id]" value=<?= $item['room_id'] ?>>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-sm-1 col-xs-1 text-right">
                                <a href="#" onclick="deletePriceItem(this);" class="mt10">
                                    <i class="text-danger fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="ln_solid"></div>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="x_panel">
        <div class="x_title">
            <h2>Giá ngày lễ của Hạng phòng</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div >
                <?php foreach ($rooms as $key => $room): ?>
                    <div class="item">
                        <h2>Phòng <?= $room->name ?></h2>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Giá phòng</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control currency" name="holiday_prices[<?= $key ?>][price]" value="<?= number_format($room->holiday_price) ?>" required="required">
                                <input type="hidden"  name="holiday_prices[<?= $key ?>][room_id]" value="<?=$room->id ?>" required="required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="col-md-6 col-sm-6 col-xs-12">Lợi nhuận cho MustGo *</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control currency" name="holiday_prices[<?= $key ?>][holiday_price_agency]" required value="<?= isset($room->holiday_price_agency) ? $room->holiday_price_agency : 0?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="col-md-6 col-sm-6 col-xs-12">Lợi nhuận cho Đại lý *</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control currency" name="holiday_prices[<?= $key ?>][holiday_price_customer]" required value="<?= isset($room->holiday_price_customer) ? $room->holiday_price_customer : 0?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>

            <div class="ln_solid"></div>
        </div>
    </div>
</div>

