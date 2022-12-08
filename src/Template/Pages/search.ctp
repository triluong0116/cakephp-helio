<!-- Home Location -->
<div class="home-location mb50">
    <div class="home-location-title mt50 text-center">
        <span class="semi-bold box-underline-center fs24 pb20">TÌM KIẾM</span>
    </div>
    <div class="container mt50">
        <?php if (isset($hotels) && !empty($hotels)): ?>
            <div class="list-location mb30 bg-white">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">KHÁCH SẠN</span>
                        </div>
                        <div class="p15">
                            <?php
                            $numPeople = "1 Phòng-1NL-0TE-0EB";
                            $dataVinRoom[] = [
                                'num_adult' => 1,
                                'num_child' => 0,
                                'num_kid' => 0
                            ];
                            $sDate = date('d-m-Y', strtotime('today'));
                            $eDate = date('d-m-Y', strtotime('tomorrow'));
                            $fromDate = str_replace('-', '/', $sDate) . " - " . str_replace('-', '/', $eDate);
                            ?>
                            <?php foreach ($hotels as $indexHotel => $hotel): ?>
                                <?php
                                $url = "";
                                if ($hotel->is_vinhms == 1) {
                                    $url = \Cake\Routing\Router::url(['_name' => 'hotel.viewVinpearl', 'slug' => $hotel->slug, 'num_people' => $numPeople, 'date' => $fromDate, 'vin_room' => $dataVinRoom]);
                                } else {
                                    $url = \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotel->slug]);
                                }
                                ?>
                                <?php if ($indexHotel % 4 == 0): ?>
                                    <div class="row row-eq-height">
                                <?php endif; ?>
                                <div class="col-sm-9 mb15">
                                    <div class="combo-item border-bottom-blue">
                                        <div class="wrap-content rectangle-image-v2">
                                            <a href="<?= $url ?>">
                                                <?php if (file_exists($hotel->thumbnail)): ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('/' . $hotel->thumbnail) ?>"/>
                                                <?php else: ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                <?php endif; ?>
                                            </a>
                                            <div class="top-left text-white">
                                                <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $hotel->location_name ?></p>
                                            </div>
                                            <div class="middle-button">
                                                <div class="button-text text-center ">
                                                    <a class="fs12" href="<?= $url ?>">XEM NGAY</a>
                                                </div>
                                            </div>
                                            <div class="image-bottom-content combo-rating fs13 pl05">
                                                <div class="row">
                                                    <div class="col-sm-22 col-xs-25">
                                                        <p class="pc"><?= $this->System->splitByWords($hotel->name, 15) ?></p>
                                                        <p class="sp"><?= $this->System->splitByWords($hotel->name, 25) ?></p>
                                                    </div>
                                                    <div class="col-sm-14 col-xs-11">
                                                        <p class="star-rating text-right" data-point="<?= $hotel->rating ?>"></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="combo-detail overhidden pb10">
                                            <div class="semi-bold voucher-name">
                                                <a class="semi-bold text-super-dark" href="<?= $url ?>"><?= $hotel->name ?></a>
                                            </div>
                                            <div class="row">
                                                <div class="pl05 pr05">
                                                    <div class="col-sm-17 col-xs-17">
                                                        <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                    </div>
                                                    <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                        <?php if ($hotel->price_day): ?>
                                                            <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($hotel->price_day) ?></strong> đ/phòng</span>
                                                        <?php else: ?>
                                                            <span class="fs12 text-grey"><strong class="text-orange">Chưa cập nhập</strong></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($indexHotel % 4 == 3 || $indexHotel == (count($hotels) - 1)): ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">KHÁCH SẠN NGANG GIÁ</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($samePriceHotels as $hotel): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotel->slug]) ?>">
                                                    <?php if (file_exists($hotel->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $hotel->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $hotel->location_name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotel->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($hotel->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($hotel->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $hotel->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotel->slug]) ?>"><?= $hotel->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <?php if ($hotel->price_day): ?>
                                                                <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($hotel->price_day) ?></strong> đ/phòng</span>
                                                            <?php else: ?>
                                                                <span class="fs12 text-grey"><strong class="text-orange">Chưa cập nhập</strong></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">KHÁCH SẠN GẦN ĐẤY</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($sameLocationHotels as $hotel): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotel->slug]) ?>">
                                                    <?php if (file_exists($hotel->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $hotel->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $hotel->location_name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotel->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($hotel->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($hotel->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $hotel->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotel->slug]) ?>"><?= $hotel->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <?php if ($hotel->price_day): ?>
                                                                <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($hotel->price_day) ?></strong> đ/phòng</span>
                                                            <?php else: ?>
                                                                <span class="fs12 text-grey"><strong class="text-orange">Chưa cập nhập</strong></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($homestays) && !empty($homestays)): ?>
            <div class="list-location bg-white mt30 mb30">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">HOMESTAY</span>
                        </div>
                        <div class="p15">
                            <?php foreach ($homestays as $indexHomestay => $homestay): ?>
                                <?php if ($indexHomestay % 4 == 0): ?>
                                    <div class="row row-eq-height">
                                <?php endif; ?>
                                <div class="col-sm-9 mb15">
                                    <div class="combo-item border-bottom-blue">
                                        <div class="wrap-content rectangle-image-v2">
                                            <a href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>">
                                                <?php if (file_exists($homestay->thumbnail)): ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('/' . $homestay->thumbnail) ?>"/>
                                                <?php else: ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                <?php endif; ?>
                                            </a>
                                            <div class="top-left text-white">
                                                <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $homestay->location->name ?></p>
                                            </div>
                                            <div class="middle-button">
                                                <div class="button-text text-center ">
                                                    <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>">XEM NGAY</a>
                                                </div>
                                            </div>
                                            <div class="image-bottom-content combo-rating fs13 pl05">
                                                <div class="row">
                                                    <div class="col-sm-22 col-xs-25">
                                                        <p class="pc"><?= $this->System->splitByWords($homestay->name, 15) ?></p>
                                                        <p class="sp"><?= $this->System->splitByWords($homestay->name, 25) ?></p>
                                                    </div>
                                                    <div class="col-sm-14 col-xs-11">
                                                        <p class="star-rating text-right" data-point="<?= $homestay->rating ?>"></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="combo-detail overhidden pb10">
                                            <div class="semi-bold voucher-name">
                                                <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>"><?= $homestay->name ?></a>
                                            </div>
                                            <div class="row">
                                                <div class="pl05 pr05">
                                                    <div class="col-sm-17 col-xs-17">
                                                        <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                    </div>
                                                    <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                        <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($this->System->countingHomeStayPrice($currentDate, $homestay)) ?></strong>đ</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($indexHomestay % 4 == 3 || $indexHomestay == (count($homestays) - 1)): ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">HOMESTAY NGANG GIÁ</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($samePriceHomestays as $homestay): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>">
                                                    <?php if (file_exists($homestay->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $homestay->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $homestay->location->name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($homestay->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($homestay->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $homestay->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>"><?= $homestay->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($this->System->countingHomeStayPrice($currentDate, $homestay)) ?></strong>đ/nguời</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">HOMESTAY GẦN ĐẤY</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($sameLocationHomestays as $homestay): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>">
                                                    <?php if (file_exists($homestay->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $homestay->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $homestay->location->name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($homestay->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($homestay->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $homestay->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'homestay.view', 'slug' => $homestay->slug]) ?>"><?= $homestay->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($this->System->countingHomeStayPrice($currentDate, $homestay)) ?></strong>đ/nguời</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($landtours) && !empty($landtours)): ?>
            <div class="list-location bg-white mt30 mb30">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">LANDTOUR</span>
                        </div>
                        <div class="p15">
                            <?php foreach ($landtours as $indexLandtour => $landtour): ?>
                                <?php if ($indexLandtour % 4 == 0): ?>
                                    <div class="row row-eq-height">
                                <?php endif; ?>
                                <div class="col-sm-9 mb15">
                                    <div class="combo-item border-bottom-blue">
                                        <div class="wrap-content rectangle-image-v2">
                                            <a href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>">
                                                <?php if (file_exists($landtour->thumbnail)): ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('/' . $landtour->thumbnail) ?>"/>
                                                <?php else: ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                <?php endif; ?>
                                            </a>
                                            <div class="top-left text-white">
                                                <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $landtour->destination->name ?></p>
                                            </div>
                                            <div class="middle-button">
                                                <div class="button-text text-center ">
                                                    <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>">XEM NGAY</a>
                                                </div>
                                            </div>
                                            <div class="image-bottom-content combo-rating fs13 pl05">
                                                <div class="row">
                                                    <div class="col-sm-22 col-xs-25">
                                                        <p class="pc"><?= $this->System->splitByWords($landtour->name, 15) ?></p>
                                                        <p class="sp"><?= $this->System->splitByWords($landtour->name, 25) ?></p>
                                                    </div>
                                                    <div class="col-sm-14 col-xs-11">
                                                        <p class="star-rating text-right" data-point="<?= $landtour->rating ?>"></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="combo-detail overhidden pb10">
                                            <div class="semi-bold voucher-name">
                                                <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>"><?= $landtour->name ?></a>
                                            </div>
                                            <div class="row">
                                                <div class="pl05 pr05">
                                                    <div class="col-sm-17 col-xs-17">
                                                        <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                    </div>
                                                    <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                        <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($landtour->totalPrice) ?></strong>đ/người</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($indexLandtour % 4 == 3 || $indexLandtour == (count($landtours) - 1)): ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">LANDTOUR NGANG GIÁ</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($samePriceLandtours as $landtour): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>">
                                                    <?php if (file_exists($landtour->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $landtour->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $landtour->destination->name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($landtour->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($landtour->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $landtour->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>"><?= $landtour->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($landtour->totalPrice) ?></strong>đ</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">LANDTOUR GẦN ĐẤY</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($sameLocationLandtours as $landtour): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>">
                                                    <?php if (file_exists($landtour->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $landtour->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $landtour->destination->name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($landtour->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($landtour->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $landtour->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $landtour->slug]) ?>"><?= $landtour->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($landtour->totalPrice) ?></strong>đ</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($vouchers) && !empty($vouchers)): ?>
            <div class="list-location bg-white mt30 mb30">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">VOUCHER</span>
                        </div>
                        <div class="p15">
                            <?php foreach ($vouchers as $indexVoucher => $voucher): ?>
                                <?php if ($indexVoucher % 4 == 0): ?>
                                    <div class="row row-eq-height">
                                <?php endif; ?>
                                <div class="col-sm-9 mb15">
                                    <div class="combo-item border-bottom-blue">
                                        <div class="wrap-content rectangle-image-v2">
                                            <a href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>">
                                                <?php if (file_exists($voucher->thumbnail)): ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('/' . $voucher->thumbnail) ?>"/>
                                                <?php else: ?>
                                                    <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                <?php endif; ?>
                                            </a>
                                            <div class="top-left text-white">
                                                <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $voucher->destination->name ?></p>
                                            </div>
                                            <div class="middle-button">
                                                <div class="button-text text-center ">
                                                    <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>">XEM NGAY</a>
                                                </div>
                                            </div>
                                            <div class="image-bottom-content combo-rating fs13 pl05">
                                                <div class="row">
                                                    <div class="col-sm-22 col-xs-25">
                                                        <p class="pc"><?= $this->System->splitByWords($voucher->name, 15) ?></p>
                                                        <p class="sp"><?= $this->System->splitByWords($voucher->name, 25) ?></p>
                                                    </div>
                                                    <div class="col-sm-14 col-xs-11">
                                                        <p class="star-rating text-right" data-point="<?= $voucher->rating ?>"></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="combo-detail overhidden pb10">
                                            <div class="semi-bold voucher-name">
                                                <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>"><?= $voucher->name ?></a>
                                            </div>
                                            <div class="row">
                                                <div class="pl05 pr05">
                                                    <div class="col-sm-17 col-xs-17">
                                                        <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                    </div>
                                                    <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                        <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($voucher->price + $voucher->trippal_price + $voucher->customer_price) ?></strong>đ/voucher</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($indexVoucher % 4 == 3 || $indexVoucher == (count($vouchers) - 1)): ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">VOUCHER NGANG GIÁ</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($samePriceVouchers as $voucher): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>">
                                                    <?php if (file_exists($voucher->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $voucher->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $voucher->destination->name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($voucher->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($voucher->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $voucher->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>"><?= $voucher->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($voucher->price + $voucher->trippal_price + $voucher->customer_price) ?></strong>đ/voucher</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">VOUCHER GẦN ĐẤY</span>
                        </div>
                        <div class="p15">
                            <div class="row row-eq-height">
                                <?php foreach ($sameLocationVouchers as $voucher): ?>
                                    <div class="col-sm-9 mb15">
                                        <div class="combo-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>">
                                                    <?php if (file_exists($voucher->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $voucher->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $voucher->destination->name ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="image-bottom-content combo-rating fs13 pl05">
                                                    <div class="row">
                                                        <div class="col-sm-22 col-xs-25">
                                                            <p class="pc"><?= $this->System->splitByWords($voucher->name, 15) ?></p>
                                                            <p class="sp"><?= $this->System->splitByWords($voucher->name, 25) ?></p>
                                                        </div>
                                                        <div class="col-sm-14 col-xs-11">
                                                            <p class="star-rating text-right" data-point="<?= $voucher->rating ?>"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="combo-detail overhidden pb10">
                                                <div class="semi-bold voucher-name">
                                                    <a class="semi-bold text-super-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $voucher->slug]) ?>"><?= $voucher->name ?></a>
                                                </div>
                                                <div class="row">
                                                    <div class="pl05 pr05">
                                                        <div class="col-sm-17 col-xs-17">
                                                            <span class="fs12 text-dark"><i class="fas fa-calendar-alt fs15 text-light-blue">  </i>  <?= $currentDate = date('d-m-Y') ?></span>
                                                        </div>
                                                        <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                            <span class="fs12 text-grey"><strong class="text-orange"><?= number_format($voucher->price + $voucher->trippal_price + $voucher->customer_price) ?></strong>đ/voucher</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!(isset($hotels) && !empty($hotels)) && !(isset($homestays) && !empty($homestays)) && !(isset($landtours) && !empty($landtours)) && !(isset($vouchers) && !empty($vouchers))): ?>
            <div class="list-location p10 bg-white">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="mt20 pb30 text-center">
                            <span class="semi-bold fs24 box-underline-center pb05">Tìm kiếm</span>
                        </div>
                        <div class="p15">
                            <p class="text-center fs20">Không tìm thấy kết quả nào phù hợp cho cụm từ "<span class="text-light-blue semi-bold"><?= $keyword ?></span>"</p>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- End Home Location -->
