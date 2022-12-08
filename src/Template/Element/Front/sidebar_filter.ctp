<?php
$prices = [
    '2000000' => 'Dưới 2.000.000',
    '2000000-4000000' => '2.000.000 - 4.000.000',
    '4000000-6000000' => '4.000.000 - 6.000.000',
    '6000000-8000000' => '6.000.000 - 8.000.000',
    '8000000-10000000' => '8.000.000 - 10.000.000',
    '10000000' => 'Trên 10.000.000'
];
$ratings = [5, 4, 3, 2, 1];
?>
<div class="list-location border-bottom-blue pc">
    <div class="row">
        <div class="col-sm-36">
            <div class="mt20 ml30 pb20">
                <span class="semi-bold fs18 box-underline-left pb05">NƠI BẠN MUỐN ĐẾN</span>
            </div>
            <div class="ml30 pb20">
                <ul class="fs16 text-grey pc" id="filter-location" data-list-selected-location="<?= implode(',', $listLocation) ?>">
                    <?php foreach ($listFeaturedlocations as $location): ?>
                        <li class="mb10">
                            <input type="checkbox" class="iCheck location-checkbox" <?= (in_array($location->id, $listLocation)) ? 'checked' : '' ?> value="<?= $location->id ?>">&nbsp;&nbsp;<?= $location->name ?>
                        </li>
                    <?php endforeach; ?>
                    <div class="collapse" id="collapseLocation">
                        <?php foreach ($locations as $location): ?>
                            <li class="mb10">
                                <input type="checkbox" class="iCheck location-checkbox" <?= (in_array($location->id, $listLocation)) ? 'checked' : '' ?> value="<?= $location->id ?>">&nbsp;&nbsp;<?= $location->name ?>
                            </li>
                        <?php endforeach; ?>
                        <li class="mb10">
                            <input type="checkbox" class="iCheck">&nbsp;&nbsp;Chọn tất cả điểm đến
                        </li>
                    </div>
                    <a type="button" id="buttonCollapseLocation" data-toggle="collapse" data-target="#collapseLocation" aria-expanded="false" aria-controls="collapseLocation">
                        Xem thêm khách sạn
                    </a>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="list-location border-bottom-blue mt30 mt0-sp">
    <div class="row pc">
        <div class="col-sm-36">
            <div class="mt20 ml30 pb20">
                <span class="semi-bold fs18 box-underline-left pb05">NGÂN SÁCH CỦA BẠN</span>
            </div>
            <div class="ml30 mr30">
                <input type="text" value="" class="price-range form-control" data-slider-min="0" data-slider-max="10000000"
                       data-slider-step="100000" data-slider-value="[<?= (isset($outputSlider) && !empty($outputSlider)) ? $outputSlider : '100000,100000' ?>]" data-slider-orientation="horizontal"
                       data-slider-tooltip="show" data-slider-id="aqua">
            </div>
            <div class="mt20 ml30 pb20">
                <ul class="fs16 text-grey pc" id="filter-price" data-list-selected-price="<?= implode(',', $listPrice) ?>">
                    <?php foreach ($prices as $priceKey => $price): ?>
                        <li class="mb10">
                            <input type="checkbox" class="iCheck price-checkbox" <?= (in_array((string)$priceKey, $listPrice)) ? 'checked' : '' ?> value="<?= (string)$priceKey ?>">&nbsp;&nbsp;<?= $price ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-sm-36">
            <div class="mt20 ml30 pb20">
                <span class="semi-bold fs18 box-underline-left pb05">HẠNG SAO</span>
            </div>
            <div class="ml30 pb10">
                <ul class="fs16 text-grey pc" id="filter-rating" data-list-selected-rating="<?= implode(',', $listRating) ?>">
                    <?php foreach ($ratings as $rating): ?>
                        <li class="mb10 rating-inline">
                            <input type="checkbox" <?= (in_array($rating, $listRating)) ? 'checked' : '' ?> class="iCheck rating-checkbox" value="<?= $rating ?>">&nbsp;&nbsp;
                            <div class="combo-rating fs18">
                                <p class="star-rating" data-point="<?= $rating ?>"></p>
                            </div>&nbsp;(<?= $rating ?> sao)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="row sp">
        <div class="col-xs-36">
            <div class="pt20">
                <div class="col-xs-36 pb20">
                    <select class="form-control" id="location-filter" style="height: 45px">
                        <option value="" disabled selected>NƠI BẠN MUỐN ĐẾN</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?= $location->id ?>" <?= (in_array($location->id, $listLocation)) ? 'selected' : '' ?>><?= $location->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-36">
            <div class="">
                <div class="">
                    <div class="col-xs-36 pb20">
                        <select class="form-control" id="filter-price" style="height: 45px">
                            <option value="" disabled selected>NGÂN SÁCH CỦA BẠN</option>
                            <?php foreach ($prices as $key => $price): ?>
                                <option value="<?= $key ?>" <?= (in_array($key, $listPrice)) ? 'selected' : '' ?>><?= $price ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-36">
            <div class="pb10">
                <div class="">
                    <div class="col-xs-36 pb20">
                        <select class="form-control" id="rating-filter" style="height: 45px">
                            <option value="" disabled selected>HẠNG SAO</option>
                            <?php foreach ($ratings as $rating): ?>
                                <option value="<?= $rating ?>" <?= (in_array($rating, $listRating)) ? 'checked' : '' ?>><?= $rating ?> sao</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
