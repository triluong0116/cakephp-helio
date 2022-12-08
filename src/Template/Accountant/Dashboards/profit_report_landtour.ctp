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
            <div class="x_content">
                <a onclick="exportFile(<?= PROFIT_REPORT ?>, this)" class="btn btn-success"><i class="fa fa-cog fa-spin fa-fw hidden" id="cog-3"></i>Xuất File Excel</a>
            </div>
            <div class="x_content" id="download-link">

            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">SALE ADMIN</th>
                                    <th scope="col">Tháng 1</th>
                                    <th scope="col">Tháng 2</th>
                                    <th scope="col">Tháng 3</th>
                                    <th scope="col">Tháng 4</th>
                                    <th scope="col">Tháng 5</th>
                                    <th scope="col">Tháng 6</th>
                                    <th scope="col">Tháng 7</th>
                                    <th scope="col">Tháng 8</th>
                                    <th scope="col">Tháng 9</th>
                                    <th scope="col">Tháng 10</th>
                                    <th scope="col">Tháng 11</th>
                                    <th scope="col">Tháng 12</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center">Tổng</th>
                                    <?php foreach ($totalByMonth as $k => $singleMonth): ?>
                                        <th><?= number_format($singleMonth) ?><sup>đ</sup></th>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $indexKey = 0 ?>
                                <?php foreach ($listSale as $singleSale): ?>
                                    <?php $indexKey++ ?>
                                    <tr>
                                        <td><?= $indexKey ?></td>
                                        <td><?= $singleSale['name'] ?></td>
                                        <?php foreach ($singleSale['month'] as $month => $value): ?>
                                            <td><?= number_format($value) ?><sup>đ</sup></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="x_content">
                <form action="" id="sale-data">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Chọn Sale</label>
                            <select class="select2 col-md-8 col-sm-8 col-xs-12" name="sale_id">
                                <?php foreach ($listPickSale as $saleKey => $sale): ?>
                                    <option value="<?= $saleKey ?>"><?= $sale ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-5 col-xs-12">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Chọn khoảng thời gian</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input type="text" name="reservation" class="custom-daterange-picker form-control" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <a onclick="processDataSale(this)" class="btn btn-success" id="blog-submit">
                                <i class="fa fa-cog fa-spin fa-fw hidden" id="cog-3"></i> Thực hiện
                            </a>
                        </div>
                    </div>
                </form>
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

