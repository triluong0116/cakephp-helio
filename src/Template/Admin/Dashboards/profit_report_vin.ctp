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
                <h2>Báo cáo Doanh thu</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-6">
                        <?= $this->Form->create(null, ['class' => 'form-inline']) ?>
                        <div class="form-group">
                            <label for="">Chọn năm</label>
                            <select name="year" class="form-control select2">
                                <?php for ($i = 2018; $i <= 2030; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $curentYear ? 'selected' : '' ?>>
                                        <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button class="btn btn-success">Chọn</button>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
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

                                <tr>
                                    <th colspan="3" class="text-center">Tổng</th>
                                    <?php foreach ($totalByMonth as $k => $singleMonth): ?>
                                        <th colspan="2"><?= number_format($singleMonth) ?><sup>đ</sup></th>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $indexKey = 0 ?>
                                <?php foreach ($listSale as $singleSale): ?>
                                    <?php $indexKey++ ?>
                                    <tr>
                                        <td>Team name</td>
                                        <td><?= $indexKey ?></td>
                                        <td><?= $singleSale['name'] ?></td>
                                        <?php foreach ($singleSale['month'] as $month => $value): ?>
                                            <td><?= number_format($value) ?><sup>đ</sup></td>
                                            <td>0</td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xs-12">
                    <div class="x_content table-responsive" id="table-search">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

