<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <?= $this->Form->create(null, ['class' => 'form-inline', 'type' => 'get']) ?>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-2 col-xs-12 mt15">Ngày bắt đầu</label>
                        <div class="col-md-8 col-sm-10 col-xs-12 mt10">
                            <div class="input-prepend input-group">
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type="text" name="start_day" class="custom-singledate-picker form-control" value="<?= $startDay ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-2 col-xs-12 mt15">Ngày kết thúc</label>
                        <div class="col-md-8 col-sm-10 col-xs-12 mt10">
                            <div class="input-prepend input-group">
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type="text" name="end_day" class="custom-singledate-picker form-control" value="<?= $endDay ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-2 col-xs-12 mt15">Chọn đối tác</label>
                        <div class="col-md-8 col-sm-10 col-xs-12 mt10">
                            <select name="partner" id="" class="select2 form-control">
                                <?php foreach ($listPartner as $k => $partner): ?>
                                    <option value="<?= $k ?>" <?= $k == $selectedPartner ? 'selected' : '' ?>><?= $partner ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success">Chọn</button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xs-12">
                    <h1 class="text-center">Bảng công nợ đối tác</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Tên đối tác</th>
                                <th>Diễn Giải chi phí</th>
                                <th>Thông tin đối tác</th>
                                <th>ĐƠN GIÁ</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th>Thanh toán</th>
                                <th>Loại hình thanh toán</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $totalFee = 0;
                            ?>
                            <?php foreach ($feeArray as $dayKey => $singleDay): ?>
                                <?php foreach ($singleDay['partner'] as $partnerKey => $partner): ?>
                                    <?php foreach ($partner as $k => $data): ?>
                                        <?php $totalFee += $data->total ?>
                                        <tr>
                                            <?php if (array_key_first($singleDay['partner']) == $partnerKey && array_key_first($partner) == $k): ?>
                                                <td rowspan="<?= $singleDay['count'] ?>"><?= $partnerKey == 0 ? $dayKey : '' ?></td>
                                            <?php endif; ?>
                                            <td><?= $k == 0 ? $partnerKey : '' ?></td>
                                            <td><?= $data->detail ?></td>
                                            <td><?= $data->partnet_information ?></td>
                                            <td><?= number_format($data->single_price) ?></td>
                                            <td><?= number_format($data->amount) ?></td>
                                            <td><?= number_format($data->total) ?></td>
                                            <td><?= $data->payment_status == 0 ? "Chưa thanh toán" : "Đã thanh toán" ?></td>
                                            <td>
                                                <?php
                                                switch ($data->payment_type) {
                                                    case 1:
                                                        echo "Chuyển khoản";
                                                        break;
                                                    case 2:
                                                        echo "Tiền mặt";
                                                        break;
                                                    case 3:
                                                        echo "Công nợ";
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a type="button" class="btn btn-xs btn-warning"
                                                   href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'editControlPayment', $data->id]) ?>">Sửa</a>
                                                <?php
                                                echo $this->Form->postLink(__('Xóa'), ['action' => 'deleteFee', $data->id], ['confirm' => __('Bạn có chắc muốn xóa?'), 'class' => 'btn btn-xs btn-danger']);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    Tổng
                                </td>
                                <td colspan="3">
                                    <?= number_format($totalFee) ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
