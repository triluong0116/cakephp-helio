<?php foreach ($listRoom as $k => $room): ?>
    <div class="list-vin-package">
        <?php if (isset($room['package'])): ?>
            <?php foreach ($room['package'] as $packageKey => $package): ?>
                <div class="single-vin-room">
                    <hr class="mt10">
                    <div class="row mt10 mb10">
                        <div class="col-sm-2 col-xs-4 no-pad-right">
                            <p class="fs16 mb15 text-light-blue">
                                <?php
                                $price = $package['totalAmount']['amount']['amount'] + ($package['trippal_price'] + $package['customer_price']);
                                $revenue = $package['customer_price'];
                                $saleRevenue = $package['trippal_price'];
                                ?>
                                <input type="radio" class="iCheck vin-room-search-pick" name="package[<?= $roomIndex ?>]"
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
                        <div class="col-sm-24 col-xs-32">
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
                        <div class="col-sm-9 col-xs-36">
                            <div class="pc">
                                <p class="fs18 pull-right"><?= number_format($price) ?> VNĐ</p>
                                <br>
                                <p class="fs16 pull-right">/<?= $dateDiff->days ?> đêm</p>
                            </div>
                            <div class="sp">
                                <p class="fs14 mt10 mb10 pull-right"><span
                                        class="bold"><?= number_format($price) ?> VNĐ</span><span
                                        class="fs12">/<?= $dateDiff->days ?> đêm</span></p>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-25 col-xs-36">
                            <?php
                            $str = explode("\n", $package['rateAvailablity']['ratePlan']['description']);
                            ?>
                            <p>
                                <span><i class="fas fa-check"></i> <?= isset($str[0]) ? $str[0] : '' ?></span>
                                <br>
                                <?php if (count($str) > 1): ?>
                                    <?php for ($strKey = 1; $strKey < count($str); $strKey++): ?>
                                        <span style="color: red"><i
                                                class="fas fa-check"></i> <?= $str[$strKey] ?></span>
                                        <br>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
