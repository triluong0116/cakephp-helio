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
                    <div class="col-sm-6">
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
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Khách sạn</th>
                                    <th scope="col">Địa điểm</th>
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
                                    <th colspan="3" class="text-center">Tổng</th>
                                    <?php foreach ($totalByMonth as $k => $singleMonth): ?>
                                        <th><?= number_format($singleMonth) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $indexKey = 0 ?>
                                <?php foreach($data as $singleData): ?>
                                    <?php $indexKey++ ?>
                                    <tr>
                                        <td><?= $indexKey ?></td>
                                        <td><?= $singleData['name'] ?></td>
                                        <td><?= $singleData['location'] ?></td>
                                        <?php foreach($singleData['month'] as $key => $count): ?>
                                            <td><?= number_format($count) ?></td>
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

