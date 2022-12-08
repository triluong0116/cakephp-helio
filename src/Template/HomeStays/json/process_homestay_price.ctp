<?php
//$checkWeekday = $checkWeekend = false;
//foreach ($arrayWeek as $day) {
//    if ($day == WEEK_DAY) {
//        $checkWeekday = true;
//    }
//    if ($day == WEEK_END) {
//        $checkWeekend = true;
//    }
//}
?>
<?php foreach ($homestay->price_home_stays as $key => $price) : ?>
    <div class="panel" data-result="<?= $room_result = $homestay->name . ', check in ' . $startDate . ' check out ' . $endDate; ?>" data-price="<?= number_format($totalPrice) ?> VNĐ" onclick="Frontend.filterHighlight(this);">
        <div class="row pb10 pt10 no-mar-left no-mar-right panel-row">
            <div class="col-xs-22 col-sm-30 no-mar-left no-mar-right panel-row">
                <div class="row-xs-36">
                    <div class="col-xs-36 col-sm-20">
                        <p class="text-left text-filter"><?= $this->System->convertHomestayType($price->type) ?></p>
                    </div>
                    <div class="col-xs-36 col-sm-10">
                        <p class="text-left text-filter"><?= number_format($price->price + $homestay->price_agency + $homestay->price_customer) ?> VNĐ</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-14 col-sm-6">
                <a class="detail text-right collapsed" data-toggle="collapse" data-target="#<?= $homestay->slug . '-' . $price->type ?>" data-parent="#filter_result">
                    Xem chi tiết <span class="filter-dropdown-arrow"></span>
                </a>
            </div>
        </div>
        <div id="<?= $homestay->slug . '-' . $price->type ?>" class="collapse filter-accordion-content">
            <div class="pl30 pr30 pt15 pb15">
                <p><?= $price->description ?></p>
            </div>
        </div>
    </div>
<?php endforeach; ?>