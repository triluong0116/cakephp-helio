<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $combo
 */
?>
<!-- Header Combo -->
<div class="combo-header">
    <div class="container">
        <div class="breadcrumb">
        </div>
        <div class="clearfix"></div>
        <div class="row mb20 mt30">
            <div class="col-sm-24">
                <div class="col-sm-24 col-xs-36 no-pad-left-pc">
                    <p class="text-white fs22"><?= $combo->name ?></p>
                    <div class="mb10">
                        <?php foreach ($combo->hotels as $address): ?>
                            <p class="fs16 text-white"><i
                                        class="fas fa-map-marker-alt pr15 text-red"></i><?= $address->address ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-sm-12 text-right no-pad-right pc">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $combo->rating ?>"></p>
                    </div>
                </div>
                <div class="col-xs-36 no-pad-right sp">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $combo->rating ?>"></p>
                    </div>
                </div>
            </div>

        </div>
        <div class="row row-eq-height mb30">
            <div class="col-sm-24 no-pad-right-pc col-xs-36 mb15-sp">
                <div class="combo-slider">
                    <div class="box-image">
                        <div id="lightgallery" class="imgs_gird grid_6">
                            <?php
                            $list_images = json_decode($combo->media, true);

                            if (count($list_images) == 0) {
                                $list_images = [];
                                foreach ($combo->hotels as $hotel) {
                                    if ($hotel->media) {
                                        $hotel_medias = json_decode($hotel->media, true);
                                    } else {
                                        $hotel_medias = [];
                                    }
                                    $list_images = array_merge($list_images, $hotel_medias);
                                }
                            }
                            $other = count($list_images) - 6;
                            ?>
                            <?php if ($list_images): ?>
                                <?php foreach ($list_images as $key => $image): ?>
                                    <?php
                                    $class = '';
                                    if ($key <= 5) {
                                        $class = 'img item_' . $key;
                                        if ($key == 0) {
                                            $class .= ' big';
                                        } else {
                                            $class .= ' small';
                                        }
                                        if ($key == 5) {
                                            $class .= ' end';
                                        }
                                    } else {
                                        $class = 'hide';
                                    }
                                    ?>
                                    <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                        <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                        <?php if ($other > 0): ?>
                                            <span class="other">+<?= $other ?></span>
                                        <?php endif; ?>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 no-pad-left-pc col-xs-36">
                <div class="combo-box-header text-center">
                    <div class="row vertical-center-pc">
                        <div class="col-sm-16 col-xs-36 no-pad-right-pc">
                            <div class="choose-month no-pad-left">
                                <div class='input-group date datepicker' id="choose-date-price"
                                     data-hotel-id="<?= $combo->id ?>">
                                    <span class="input-group-addon calendar-icon">
                                        <span class="far fa-calendar-alt main-color"></span>
                                    </span>
                                    <input type='text' name="chooseDate"
                                           class="form-control monthly-picker calendar-picker" placeholder=""
                                           value="<?= $today = date('d-m-Y') ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-20 col-xs-36 mb10-sp mt10-sp">
                            <?php
                            $fromDate = date('Y-m-d');
                            $comboDay = 0;
                            foreach ($combo->hotels as $hotel) {
                                $comboDay += $hotel->_joinData->days_attended;
                            }
                            $customerPrice = $this->System->countComboPrice($fromDate, $combo);
                            ?>
                            <p clas
                            <p class="text-white bold fs20"><span
                                        id="comboUpdate"><?= number_format(round(($customerPrice + $combo->addition_fee) / 2), -3) ?></span>
                                <span class="regular fs14">đ/người</span></p>
                            <p class="text-white regular">Giá cũ: <span class="text-line-through"
                                                                        id="comboUpdatePrice"><?= number_format(round((($customerPrice + $combo->addition_fee) / (100 - $combo->promote) * 100) / 2, -3)) ?> đ/người</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="combo-box-detail bg-white">
                    <div class="row pos-relative">
                        <div class="col-sm-18 col-xs-18 text-left semi-bold fs14 no-pad-right line-vertical">
                            <div class="p15 icon-list">
                                <p><i class="fas fa-map-marker-alt text-pink fs18"></i>
                                    Từ: <?= $combo->departure->name ?></p>
                                <p class="mt10"><i class="fas fa-chevron-circle-right main-color fs18"></i>
                                    Đến: <?= $combo->destination->name ?></p>
                            </div>
                        </div>
                        <div class="col-sm-18 col-xs-18 semi-bold fs14 text-left">
                            <div class="p15 text-right">
                                <p><i class="far fa-clock text-green fs18"></i> <?= $combo->days ?></p>
                                <p class="mt10">
                                    <?php
                                    if ($combo->icon_list) {
                                        $icon_lists = json_decode($combo->icon_list, true);
                                    } else {
                                        $icon_lists = [];
                                    }
                                    ?>
                                    <?php foreach ($icon_lists as $k => $icon): ?>
                                        <i class="<?= $icon ?> fs20"></i>
                                        <?= ($k < (count($icon_lists) - 1)) ? '&nbsp;+&nbsp;' : '' ?>
                                    <?php endforeach; ?>
                                </p>
                            </div>

                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-36">
                            <ul class="combo-caption pl15 pt05" style="min-height: 156px">
                                <?php
                                $list_captions = json_decode($combo->caption, true);
                                //                                dd($list_captions);
                                ?>
                                <?php if ($list_captions): ?>
                                    <?php foreach ($list_captions as $caption): ?>
                                        <?php if (is_array($caption)): ?>
                                            <li><i class="<?= $caption['icon'] ?> main-color fs20"></i>&nbsp;&nbsp;&nbsp;<?= $caption['content'] ?>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <i class="fas fa-check main-color fs20"></i>&nbsp;&nbsp;&nbsp;<?= $caption ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <hr/>
                    <div class="row pos-relative">
                        <div class="col-sm-36">
                            <ul class="combo-more-info p15">
                                <!--<li><i class="fas fa-user-check fs20 main-color"></i>&nbsp;&nbsp;&nbsp;122 Khách đặt</li>-->
                                <?php $count = count($combo->hotels) ?>
                                <?php if ($this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                                    <?php foreach ($combo->hotels as $key => $hotline): ?>
                                        <li><a class="btn btn-primary" href="tel: <?= $hotline->hotline ?>">Hotline
                                                khách sạn <?= $count == 2 ? $key + 1 : '' ?>
                                                : <?= $hotline->hotline ?></a></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>
                                        <i class="fas fa-phone fs20 main-color"></i>&nbsp;&nbsp;&nbsp;<span>Hotline: <?= $hotline ?></span>
                                    </li>
                                <?php endif; ?>
                                <li><i class="fas fa-info-circle fs20 main-color"></i>&nbsp;&nbsp;&nbsp;<a
                                            id="viewdetail" class="text-dark">Xem chi tiết Combo</a></li>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-36">
                            <div class="p15">
                                <button class="btn btn-request text-white full-width"
                                        onclick="Frontend.checkSessionUser(<?= $combo->id ?>, <?= $comboDay ?>, <?= COMBO ?>)">
                                    <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                    <br/>
                                    <span class="fs16">MUSTGO sẽ liên hệ lại trong 30 phút</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php if ($this->request->getSession()->read('Auth.User.role_id') == 3 && $this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                        <?php $refCode = $this->request->getSession()->read('Auth.User.ref_code'); ?>
                        <hr/>
                        <?php
                        $revenue = 0;
                        foreach ($combo->hotels as $hotel) {
                            $revenue += $hotel->price_customer;
                        }
                        ?>

                        <center><p class="text-dark semi-bold text-uppercase mt10 mb10 fs18">Cộng Tác Viên Chia sẻ
                            <p></center>
                        <div class="mt10 mb10 fs16 text-center">
                            <p class="text-orange">Số tiền lãi: <?= number_format($revenue) ?>đ/đêm</p>
                        </div>
                        <div class="row vertical-center">
                            <div class="col-sm-18 no-pad-right line-vertical text-center">
                                <div class="p15">
                                    <p class="btn btn-primary"
                                       onclick="Frontend.showModalPostFB(<?= COMBO ?>, <?= $combo->id ?>);">
                                        <i class="fab fa-facebook-f"></i>&nbsp;Facebook
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-18 text-center">
                                <div class="p15">
                                    <div class="zalo-share-button" data-object-type="<?= COMBO ?>"
                                         data-object-id="<?= $combo->id ?>" data-callback="shareZaloSuccess"
                                         data-href="<?= $this->Url->build(['_name' => 'combo.view', 'slug' => $combo->slug, 'ref' => $refCode], true) ?>"
                                         data-oaid="579745863508352884" data-layout="3" data-color="blue"
                                         data-customize="false">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="combo-detail-title mt50 text-center" id="detail">
    <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN VỀ COMBO</span>
</div>
<!-- End Header Combo -->
<?= $this->element('Front/Popup/find-agency') ?>
<?= $this->element('Front/Popup/bookingcombo') ?>
<?php if (count($combo->hotels) == 1): ?>
    <?= $this->element('Front/Combo/one-hotel') ?>
<?php else: ?>
    <?= $this->element('Front/Combo/two-hotel') ?>
<?php endif; ?>
