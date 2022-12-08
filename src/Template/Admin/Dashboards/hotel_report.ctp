<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Báo cáo Khách sạn</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <?= $this->Form->create(null, ['class' => 'form-inline']) ?>
                        <div class="form-group">
                            <label for="">Chọn năm</label>
                            <select name="year" class="form-control select2">
                                <?php for ($i = 2018; $i <= 2030; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>>
                                        <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group ml15">
                            <label for="">Chọn Đại lý</label>
                            <select name="hotel_id" class="form-control select2">
                                <?php foreach ($listHotel as $k => $singleHotel): ?>
                                    <option value="<?= $k ?>" <?= $k == $currentHotel ? 'selected' : '' ?>>
                                        <?= $singleHotel ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn btn-success">Chọn</button>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
            <div class="x_content">
                <a onclick="exportFile(<?= HOTEL_REPORT ?>, this)" class="btn btn-success"><i class="fa fa-cog fa-spin fa-fw hidden" id="cog-3"></i>Xuất File Excel</a>
            </div>
            <div class="x_content" id="download-link">

            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-vertical-center table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col" rowspan="2">STT</th>
                                    <th scope="col" rowspan="2" colspan="2">Khách sạn</th>
                                    <?php for ($i =1; $i <=12; $i++){ ?>
                                        <th colspan="3" class="text-center"><span class="text-dark-75">Tháng <?=$i?></span></th>
                                    <?php } ?>
                                </tr>
                                <tr class="text-uppercase">
                                    <?php for ($i =1; $i <=12; $i++){ ?>
                                        <td>Số đơn hàng</td>
                                        <td>Số roomnight</td>
                                        <td>Tổng lợi nhuận</td>
                                    <?php } ?>
                                </tr>

<!--                                <tr>-->
<!--                                    <th colspan="3" class="text-center">Tổng</th>-->
<!--                                    --><?php //foreach ($totalByMonth as $k => $singleMonth): ?>
<!--                                        <th>--><?//= $singleMonth ?><!--</th>-->
<!--                                    --><?php //endforeach; ?>
<!--                                </tr>-->
                                </thead>
                                <tbody>
                                <?php $indexKey = 0 ?>
                                <?php foreach($data as $singleData): ?>
                                    <?php $indexKey++ ?>
                                    <tr>
                                        <td><?= $indexKey ?></td>
                                        <td colspan="2"><?= $singleData['name'] ?></td>
                                        <?php foreach($singleData['month'] as $key => $count): ?>
                                            <td><?= $count ?></td>
                                            <td></td> <!-- số roomnight-->
                                            <td></td> <!-- lợi nhuận-->
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

