<?php if (count($listRoom) > 0): ?>
<?php foreach ($listRoom as $k => $room): ?>
    <div class="list-vin-package">
        <?php if (isset($room['package'])): ?>
            <?php foreach ($room['package'] as $packageKey => $package): ?>
                <div class="single-vin-room">
                    <hr class="mt10">
                    <div class="row mt10 mb10">
                        <div class="col-sm-1 no-pad-right">
                            <p class="fs16 mb15 text-light-blue">
                                <?php
                                $price = $package['totalAmount']['amount']['amount'] + ($package['trippal_price'] + $package['customer_price']);
                                $revenue = $package['customer_price'];
                                $saleRevenue = $package['trippal_price'];
                                ?>
                                <input type="radio" class="iCheck search-package vin-room-search-pick" name="package[<?= $roomIndex ?>]"
                                       data-rate-plan-code="<?= $package['rateAvailablity']['ratePlan']['rateCode'] ?>"
                                       data-room-type-code="<?= $package['rateAvailablity']['roomTypeCode'] ?>"
                                       data-allotment-id="<?= $package['rateAvailablity']['allotments'][0]['allotmentId'] ?>"
                                       data-package-name="<?= $package['rateAvailablity']['ratePlan']['name'] ?>"
                                       data-package-code="<?= $package['rateAvailablity']['ratePlanCode'] ?>"
                                       data-revenue="<?= $revenue ?>" data-sale-revenue="<?= $saleRevenue ?>"
                                       data-package-id="<?= $package['rateAvailablity']['propertyId'] ?>"
                                       data-rateplan-id="<?= $package['ratePlanID'] ?>"
                                       data-room-index="<?= $roomIndex ?>"
                                       data-room-key="<?= $k ?>"
                                       data-package-pice="<?= number_format($price) ?>"
                                       data-package-default-price="<?= $package['totalAmount']['amount']['amount'] ?>"
                                       data-package-left="<?= $package['amount_left'] ?>">
                            </p>
                        </div>
                        <div class="col-sm-8">
                            <?php
                            $arrText = explode('-', $package['rateAvailablity']['ratePlan']['name']);
                            $packageName = '';
                            foreach ($arrText as $kText => $text) {
                                $text = trim($text);
                                $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                            }
                            ?>
                            <p class="fs18 fs16-sp" style="text-decoration: underline"><?= $packageName ?></p>
                        </div>
                        <div class="col-sm-3">
                            <div class="pc">
                                <p class="fs18 pull-right bold"><?= number_format($price) ?> VNĐ</p>
                            </div>
                        </div>
                        <div class="col-sm-offset-1 col-sm-11">
                            <p>
                                <?= $package['rateAvailablity']['ratePlanCode'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
<?php else: ?>
<p class="text-center">Không có gói khả dụng</p>
<?php endif; ?>
