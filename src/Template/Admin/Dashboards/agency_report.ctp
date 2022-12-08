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
                <h2>Báo cáo Đại lý</h2>
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
                            <select name="agency" class="form-control select2">
                                <?php foreach ($listAgency as $k => $agency): ?>
                                    <option value="<?= $k ?>" <?= $k == $currentAgency ? 'selected' : '' ?>>
                                        <?= $agency ?>
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
                <a onclick="exportFile(<?= AGENCY_REPORT ?>, this)" class="btn btn-success"><i class="fa fa-cog fa-spin fa-fw hidden" id="cog-3"></i>Xuất File Excel</a>
            </div>
            <div class="x_content" id="download-link">

            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-vertical-center table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col" rowspan="2">STT</th>
                                    <th scope="col" rowspan="2">ĐẠI LÝ</th>
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
<!--                                    <th colspan="2" class="text-center">Tổng</th>-->
<!--                                    --><?php //foreach ($totalByMonth as $k => $singleMonth): ?>
<!--                                        <th>--><?//= $singleMonth ?><!--</th>-->
<!--                                    --><?php //endforeach; ?>
<!--                                </tr>-->

                                </thead>
                                <tbody>
                                <?php foreach ($listSale as $singleSale): ?>
<!--                                    <tr>-->
<!--                                        <td colspan="2">TEAM --><?//= $singleSale['name'] ?><!--</td>-->
<!--                                    </tr>-->
                                    <?php $agencyKey = 0 ?>
                                    <?php foreach ($singleSale['child'] as $agency): ?>
                                        <?php $agencyKey++ ?>
                                        <tr>
                                            <td><?= $agencyKey ?></td>
                                            <td><?= $singleSale['name'] == $agency['agency_name'] ? 'Sale ' . $agency['agency_name'] : $agency['agency_name'] ?></td>
                                            <?php foreach ($agency['month'] as $monthKey => $val): ?>
                                                <td><?= $val ?></td>
                                                <td></td> <!-- số roomnight-->
                                                <td></td> <!-- lợi nhuận-->
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
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

