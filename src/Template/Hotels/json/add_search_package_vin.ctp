<div class="col-sm-36 bg-light-grey p15 mt10 single-room-package"
     data-room-index="<?= $listRoom['roomIndex'] ?>"
     data-room-key="<?= $listRoom['roomKey'] ?>"
     data-package-pice="<?= $listRoom['packagePrice'] ?>"
     data-package-id="<?= $listRoom['packageId'] ?>"
     data-rateplan-id="<?= $listRoom['rateplanId'] ?>"
     data-allotment-id="<?= $listRoom['allotmentId'] ?>"
     data-room-type-code="<?= $listRoom['roomTypeCode'] ?>"
     data-rate-plan-code="<?= $listRoom['ratePlanCode'] ?>"
     data-revenue="<?= $listRoom['revenue'] ?>"
     data-sale-revenue="<?= $listRoom['saleRevenue'] ?>"
     data-package-code="<?= $listRoom['packageCode'] ?>"
     data-package-name="<?= $listRoom['packageName'] ?>"
     data-package-default-price="<?= $listRoom['defaultPrice'] ?>">
    <?php
    $arrText = explode('-', $listRoom['packageName']);
    $packageName = '';
    foreach ($arrText as $kText => $text) {
        $text = trim($text);
        $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
        $packageName .= $kText != count($arrText) - 1 ? " - " : '';
    }
    ?>
    <p class="fs18 fs16-sp"><span class="bold"><?= $this->System->splitByWords($packageName, 45) ?></span> <span class="pull-right text-main-blue"><?= $listRoom['packagePrice'] ?> VNĐ</span></p>
    <p><span>Mã gói: <?= $listRoom['packageCode'] ?></span> <span class="pull-right"><?= str_replace('-', '/', $fromDate) ?> - <?= str_replace('-', '/', $toDate) ?></span></p>
    <input type="hidden" class="start-date-vin" value="<?= $fromDate ?>">
    <input type="hidden" class="end-date-vin" value="<?= $toDate ?>">
</div>
