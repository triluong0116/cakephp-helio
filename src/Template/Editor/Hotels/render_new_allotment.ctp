<div class="col-md-12 col-sm-12 col-xs-12 mt10 single-allotment-code">
    <div class="x_panel">
        <div class="x_title">
            <h2>Mã Allotment</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12 mb15">
                    <?= $this->Form->create(null, ['class' => 'allotment-revenue']) ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mã Allotment</label>
                                <input type="text" class="form-control" name="code">
                            </div>
                        </div>
                    </div>
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th scope="col">
                                Tên phòng
                            </th>
                            <th scope="col">
                                Loại lợi nhuận Mustgo
                            </th>
                            <th scope="col">
                                Lợi nhuận Mustgo
                            </th>
                            <th scope="col">
                                Loại lợi nhuận Đại lý
                            </th>
                            <th scope="col">
                                Lợi nhuận Đại lý
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $k = 0 ?>
                        <?php foreach ($listRoom as $roomCode => $singleRoom): ?>
                            <tr>
                                <td><?= $k + 1 ?></td>
                                <td><?= $singleRoom['name'] ?></td>
                                <input type="hidden" name="vin_room[<?= $k ?>][vin_code]" value="<?= $roomCode ?>">
                                <input type="hidden" name="vin_room[<?= $k ?>][hotel_id]" value="<?= $hotel->id ?>">
                                <input type="hidden" name="vin_room[<?= $k ?>][room_name]" value="<?= $singleRoom['name'] ?>">
                                <td>
                                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" checked name="vin_room[<?= $k ?>][sale_revenue_type]" value="0"> Cố định</i></p>
                                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" name="vin_room[<?= $k ?>][sale_revenue_type]" value="1"> Theo %</i></p>
                                </td>
                                <td><input type="text" name="vin_room[<?= $k ?>][sale_revenue]" value="0" class="form-control"></td>
                                <td>
                                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" checked name="vin_room[<?= $k ?>][revenue_type]" value="0"> Cố định</i></p>
                                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" name="vin_room[<?= $k ?>][revenue_type]" value="1"> Theo %</i></p>
                                </td>
                                <td><input type="text" name="vin_room[<?= $k ?>][revenue]" value="0" class="form-control"></td>
                            </tr>
                            <?php $k++ ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-success" type="button" onclick="saveAllotmentRevenue(this)">
                                Save
                            </button>
                            <button class="btn btn-danger" type="button" onclick="deleteAllotmentRevenue(this, false)">
                                Xóa
                            </button>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
