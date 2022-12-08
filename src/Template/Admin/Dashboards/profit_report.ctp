<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="card card-custom gutter-b">
    <!--begin::Header-->
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Báo cáo Doanh thu</h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline mr-2">
                <button type="button" class="btn btn-light-primary font-weight-bolder" onclick="exportFile(<?= PROFIT_REPORT ?>, this)">
                    <i class="fas fa-download"></i>Export
                </button>
                <!--begin::Dropdown Menu-->
                <!--end::Dropdown Menu-->
            </div>
            <!--end::Dropdown-->
            <!--begin::Button-->
            <!--end::Button-->
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-0 pb-3">
        <!--begin::Search Form-->
        <div class="mb-7">
            <?= $this->Form->create(null, ['class' => 'form-inline']) ?>
            <div class="row align-items-center">
                <div class="col-lg-9 col-xl-8">
                    <div class="row align-items-center">
                        <div class="col-md-3 my-2 my-md-0">
                            <div class="d-flex align-items-center">
                                <label class="mr-2 mb-0 d-md-block text-nowrap">Chọn năm:</label>
                                <select name="year" class="form-control">
                                    <?php for ($i = 2018; $i <= 2030; $i++): ?>
                                        <option value="<?= $i ?>" <?= $i == $curentYear ? 'selected' : '' ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                    <button class="btn btn-light-primary px-6 font-weight-bold">Search</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
        <!--end::Search Form-->
        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table table-vertical-center table-bordered">
                <thead>
                <tr class="text-uppercase">
                    <th rowspan="2"><span class="text-dark-75">Team</span></th>
                    <th rowspan="2"><span class="text-dark-75">#</span></th>
                    <th rowspan="2"><span class="text-dark-75">Sale</span></th>
                    <?php for ($i =1; $i <=12; $i++){ ?>
                    <th colspan="2" class="text-center"><span class="text-dark-75">Tháng <?=$i?></span></th>
                    <?php } ?>
                </tr>
                <tr>
                    <?php for ($i =1; $i <=12; $i++){ ?>
                        <td>Hoàn Thành</td>
                        <td>Chưa hoàn Thành</td>
                    <?php } ?>
                </tr>

<!--                <tr>-->
<!--                    <th colspan="3"><span class="text-dark-75">Tổng</span></th>-->
<!--                    --><?php //foreach ($totalByMonth as $k => $singleMonth): ?>
<!--                        <th colspan="2"><span class="text-dark-75">--><?//= number_format($singleMonth) ?><!--<sup>đ</sup></span></th>-->
<!--                    --><?php //endforeach; ?>
<!--                </tr>-->

                </thead>
                <tbody class="font-size-lg">
                <?php $indexKey = 0 ?>
                <?php foreach ($listSale as $singleSale): ?>
                    <?php $indexKey++ ?>
                    <tr>
                        <td>Team name</td>
                        <td><?= $indexKey ?></td>
                        <td><span><?= $singleSale['name'] ?></span></td>
                        <?php foreach ($singleSale['month'] as $month => $value): ?>
                            <td><span><?= number_format($value) ?><sup>đ</sup></span></td>
                            <td>0</td> <!-- chưa hoàn thành-->
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!--end::Table-->
    </div>
    <!--end::Body-->
</div>

