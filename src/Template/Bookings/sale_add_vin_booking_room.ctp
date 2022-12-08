<div class="bg-grey">
    <div class="container">
        <div class="row">
            <div class="col-sm-24 mt10">
                <ul class="nav nav-tabs">
                    <?php for ($i = 0; $i < $numRoom; $i++): ?>
                        <li class="<?= $i == 0 ? 'active' : '' ?>"><a data-toggle="tab" href="#room-<?= $i ?>">Phòng <?= $i + 1 ?></a></li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-24">
                <div class="tab-content">
                    <?php foreach ($singleVinChooseRoom as $i => $listRoom): ?>
                        <div id="room-<?= $i ?>" class="tab-pane fade in <?= $i == 0 ? 'active' : '' ?>">
                            <div class="row">
                                <?php foreach ($listRoom as $k => $room): ?>
                                    <div class="col-sm-36 bg-white mb15">
                                        <div class="row pt10 pb10 row-eq-height">
                                            <div class="col-sm-14">
                                                <div id="myCarousel-<?= $i ?>-<?= $k ?>" class="carousel slide" data-ride="carousel">
                                                    <!-- Wrapper for slides -->
                                                    <div class="carousel-inner">
                                                        <?php foreach ($room['image'] as $kimage => $roomImage): ?>
                                                            <div class="item <?= $kimage == 0 ? 'active' : '' ?>">
                                                                <img class="w100" style="object-fit: cover; height: 175px" src="<?= $this->Url->assetUrl($roomImage) ?>" alt="">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <a class="left carousel-control" href="#myCarousel-<?= $i ?>-<?= $k ?>" data-slide="prev">
                                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="right carousel-control" href="#myCarousel-<?= $i ?>-<?= $k ?>" data-slide="next">
                                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-sm-22">
                                                <div class="">
                                                    <p class="fs22"><?= $room['information']['name'] ?></p>
                                                </div>
                                                <div class="check-room">
                                                    <input type="hidden" name="room-<?= $k ?>" value='<?= json_encode($room['information']) ?>'>
                                                    <a class="fs14 text-main-blue" onclick="Frontend.checkDetailVinRoom('<?= $k ?>')" data-toggle="modal" data-target="#myModal">Xem phòng</a>
                                                </div>
                                                <div class="choose-room">
                                                    <?php if (isset($room['package'])): ?>
                                                        <div class="vin-price-room" id="check-room-<?= $i ?>-<?= $k ?>-price">
                                                            <p class="fs16 text-right">Chỉ từ <span class="text-main-blue fs18"><?= number_format($room['information']['min_price']) ?> VNĐ</span></p>
                                                            <p class="text-right mb10">/đêm</p>
                                                        </div>
                                                        <?php
                                                        $dataJson = [];
                                                        $dataJson['name'] = $room['information']['name'];
                                                        ?>
                                                        <input type="hidden" name="choose-room-<?= $k ?>" value='<?= json_encode($dataJson) ?>'>
                                                        <button class="btn btn-blue pull-right" data-toggle="collapse" data-target="#check-room-<?= $i ?>-<?= $k ?>" onclick="Frontend.hiddenRoomPrice(this)">Chọn phòng</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse list-vin-package" id="check-room-<?= $i ?>-<?= $k ?>">
                                            <?php if (isset($room['package'])): ?>
                                                <?php foreach ($room['package'] as $packageKey => $package): ?>
                                                    <div class="single-vin-room">
                                                        <hr class="mt10">
                                                        <div class="row mt10 mb10">
                                                            <div class="col-sm-2 no-pad-right">
                                                                <p class="fs16 mb15 text-light-blue">
                                                                    <?php
                                                                    $price = $package['totalAmount']['amount']['amount'] + ($package['trippal_price'] + $package['customer_price']);
                                                                    $revenue = $package['customer_price'];
                                                                    $saleRevenue = $package['trippal_price'];
                                                                    ?>
                                                                    <input type="radio" class="iCheck vin-room-pick" name="package[<?= $i ?>]" data-package-code="<?= $package['rateAvailablity']['ratePlanCode'] ?>" data-revenue="<?= $revenue ?>" data-sale-revenue="<?= $saleRevenue ?>" data-package-id="<?= $package['rateAvailablity']['propertyId'] ?>" data-rateplan-id="<?= $package['ratePlanID'] ?>" data-room-index="<?= $i ?>" data-room-key="<?= $k ?>"
                                                                           data-package-pice="<?= number_format($price) ?>"></i>
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-24">
                                                                <?php
                                                                $arrText = explode('-', $package['rateAvailablity']['ratePlan']['name']);
                                                                $packageName = '';
                                                                foreach ($arrText as $kText => $text) {
                                                                    $text = trim($text);
                                                                    $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                                                    $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                                                                }
                                                                ?>
                                                                <p class="fs18" style="text-decoration: underline"><?= $packageName ?></p>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <p class="fs18 pull-right"><?= number_format($price) ?> VNĐ</p>
                                                                <p class="fs14 pull-right">/đêm</p>
                                                            </div>
                                                            <div class="col-sm-offset-2 col-sm-25">
                                                                <p><?= $package['rateAvailablity']['ratePlan']['description'] ?></p>
                                                            </div>
                                                            <div class="col-sm-offset-2 col-sm-25">
                                                                <p>Hoàn/hủy theo chính sách khách sạn</p>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <?php
                                                                $dataPackage = [];
                                                                $dataPackage['name'] = $packageName;
                                                                $dataPackage['code'] = isset($package['rateAvailablity']['ratePlan']['rateCode']) ? $package['rateAvailablity']['ratePlan']['rateCode'] : '';
                                                                $dataPackage['description'] = isset($package['rateAvailablity']['ratePlan']['rateCode']) ? $package['rateAvailablity']['ratePlan']['rateCode'] : '';
                                                                $dataPackage['cancelPolicy'] = isset($package['rateAvailablity']['ratePlan']['cancelPolicy']['description']) ? $package['rateAvailablity']['ratePlan']['cancelPolicy']['description'] : '';
                                                                $dataPackage['guaranteePolicy'] = isset($package['rateAvailablity']['ratePlan']['guaranteePolicy']['description']) ? $package['rateAvailablity']['ratePlan']['guaranteePolicy']['description'] : '';
                                                                $dataPackage = json_encode($dataPackage);
                                                                ?>
                                                                <input type="hidden" name="package_<?= $packageKey ?>_<?= $k ?>" value='<?= $dataPackage ?>'>
                                                                <a class="text-main-blue pull-right" onclick="Frontend.checkDectailVinPackage('package_<?= $packageKey ?>_<?= $k ?>')" data-toggle="modal" data-target="#detailPackageModal">Xem chi tiết</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="bg-white">
                            <div class="p10">
                                <div class="information-header">
                                    <p class="text-main-blue">Thông tin đặt phòng</p>
                                    <p class="semi-bold mt10">Vinpearl Resort & Spa Hạ Long</p>
                                    <p class="w100 mt10 fs12"><span>12.04.2021</span> - <span>15.04.2021</span> <span class="pull-right semi-bold"><?= $dateDiff->days + 1 ?> ngày <?= $dateDiff->days ?> đêm</span></p>
                                    <p class="mt05 fs12"><?= $numAdult ?> Người lớn, <?= $numKid ?> trẻ em, <?= $numChild ?> em bé</p>
                                    <p class="mt05 fs12"><?= $numRoom ?> phòng</p>
                                </div>
                                <div class="detail-room-information mt10">
                                    <div class="panel-group" id="accordion-term">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed semi-bold fs16" data-toggle="collapse" data-parent="#accordion-term" href="#collapseTerm">
                                                        Thông tin phòng
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTerm" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <?php for ($i = 0; $i < $numRoom; $i++): ?>
                                                            <div class="single-room-detail" data-vinroom-price="0" data-vinroom-revenue="0" id="vin-room-<?= $i ?>" data-room-number="<?= $i ?>">

                                                            </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-room-information row">
                                    <div class="col-sm-36">
                                        <p class="pull-right">
                                            Tổng cộng
                                        </p>
                                    </div>
                                    <div class="col-sm-36">
                                        <p class="pull-right text-orange fs24 semi-bold" id="">
                                            <span id="totalVinBookingPrice">0</span> VNĐ
                                        </p>
                                    </div>
                                    <?php if ($this->request->session()->read('Auth.User.role_id') == 3): ?>
                                        <div class="col-sm-36">
                                            <p class="pull-right">
                                                Chênh lệch với giá bán lẻ của Mustgo
                                            </p>
                                        </div>
                                        <div class="col-sm-36">
                                            <p class="pull-right text-orange fs24 semi-bold" id="">
                                                <span id="totalVinBookingRevenue">0</span> VNĐ
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-sm-36">
                                        <p class="pull-right fs13">(Giá đã bao gồm phí dịch vụ và thuế GTGT)</p>
                                    </div>
                                    <?= $this->Form->create(null, ['id' => 'vinBookingRoom', 'method' => 'post', 'url' => '/sale-dat-hang-vin/' . $hotel->slug . '/booking/']) ?>
                                    <div class="list-booking-vin-room">
                                        <input type="hidden" name="num_adult" value="<?= $numAdult ?>">
                                        <input type="hidden" name="num_child" value="<?= $numChild ?>">
                                        <input type="hidden" name="num_kid" value="<?= $numKid ?>">
                                        <input type="hidden" name="num_room" value="<?= $numRoom ?>">
                                        <input type="hidden" name="start_date" value="<?= $startDate ?>">
                                        <input type="hidden" name="end_date" value="<?= $endDate ?>">
                                        <?php for ($indexRoom = 0; $indexRoom < $numRoom; $indexRoom++): ?>
                                            <div class="single-booking-vin-room-<?= $indexRoom ?> vin-bk-room">

                                            </div>
                                        <?php endfor; ?>
                                        <?php foreach ($dataRoom as $k => $roomData): ?>
                                            <input type="hidden" name="vin_room[<?= $k ?>][num_adult]" value="<?= $roomData['num_adult'] ?>">
                                            <input type="hidden" name="vin_room[<?= $k ?>][num_kid]" value="<?= $roomData['num_kid'] ?>">
                                            <input type="hidden" name="vin_room[<?= $k ?>][num_child]" value="<?= $roomData['num_child'] ?>">
                                        <?php endforeach; ?>
                                    </div>
                                    <?= $this->Form->end() ?>
                                    <div class="col-sm-36 mt10">
                                        <button class="btn btn-request text-white full-width full-height btnVinBooking" data-num-room="<?= count($dataRoom) ?>">
                                            <span class="semi-bold fs16">GỬI YÊU CẦU</span>
                                            <br/>
                                            <span class="fs12">MUSTGO sẽ liên hệ lại trong 30 phút</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
