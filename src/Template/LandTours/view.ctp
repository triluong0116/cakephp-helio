<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $combo
 */
?>
<!-- Header Combo -->
<div class="combo-header">
    <div class="container ">
        <div class="breadcrumb">
            <ul class="breadcrumb-menu">
                <li><a href="/">Trang chủ</a></li>
                <li><a href="#"><?= $combo->destination->name ?></a></li>
                <li><a href="#">Landtour tại <?= $combo->destination->name ?></a></li>
                <li><?= $combo->name ?></li>
            </ul>
        </div>
        <div class="clearfix"></div>

        <div class="row mb20 mt30">
            <div class="col-sm-36">
                <div class="col-sm-24 no-pad-left">
                    <p class="text-white fs22"><?= $combo->name ?></p>
                    <div class="mb10">
                        <p class="fs16 text-white"><i
                                class="fas fa-map-marker-alt pr15 text-red"></i><?= $combo->destination->name ?></p>
                    </div>
                </div>
                <div class="col-sm-12 text-right no-pad-right pc">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $combo->rating ?>"></p>
                    </div>
                </div>
                <div class="col-sm-12 text-left mt05 sp">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $combo->rating ?>"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-eq-height mb30">
            <div class="col-sm-36 no-pad-right-pc col-xs-36 mb15-sp">
                <div class="combo-slider">
                    <div class="box-image">
                        <div id="lightgallery" class="imgs_gird grid_6">
                            <?php
                            $list_images = json_decode($combo->media, true);
                            $other = count($list_images) - 6;
                            ?>
                            <?php if ($list_images) : ?>
                                <?php foreach ($list_images as $key => $image) : ?>
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
                                        <?php if ($other > 0) : ?>
                                            <span class="other">+<?= $other ?></span>
                                        <?php endif; ?>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Combo -->
<!-- Start content -->
<div class="combo-detail mb50 mb30-sp">
    <div class="container no-pad-left no-pad-right">
        <div class="combo-detail-title mt50 mt30-sp text-center">
            <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN ĐẶT LANDTOUR</span>
        </div>
        <div class="bg-grey">
            <div class="mt60">
                <form id="landTourSelection" method="post"
                      action="<?= \Cake\Routing\Router::url(['_name' => 'landtour.booking', 'slug' => $combo->slug]) ?>">
                    <div class="row">
                        <div class="col-sm-36">
                            <div class="pt10">
                                <input type="hidden" name="_csrfToken"
                                       value="<?= $this->request->getParam('_csrfToken') ?>">
                                <input type="hidden" name="landtour_id" value="<?= $combo->id ?>"/>
                                <div class="col-sm-12">
                                    <span class="text-center">Check in</span>
                                    <div class='input-group date datepicker'>
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-alt main-color"></span>
                                    </span>
                                        <input type='text' name="fromDate"
                                               class="form-control popup-voucher border-blue"
                                               placeholder="Thời gian đi" value="<?= date('d-m-Y') ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-center">Số người lớn</span>
                                    <select class="form-control popup-voucher select-no-arrow" name="num_adult">
                                        <?php for ($i = 1; $i <= 50; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> Người lớn</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-center">Số trẻ em</span>
                                    <select class="form-control popup-voucher select-no-arrow" name="num_children">
                                        <?php for ($i = 0; $i <= 50; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> Trẻ em</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-center">Số em bé</span>
                                    <select class="form-control popup-voucher select-no-arrow" name="num_kid">
                                        <?php for ($i = 0; $i <= 50; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> Trẻ em</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-center">Giá/Khách</span>
                                    <input type='text' disabled="disabled"
                                           class="form-control popup-voucher border-blue" placeholder="Số người" name="price"
                                           value=""/>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="m10">
                        <div class="bg-white p20 m10">
                            <?php if (count($combo->land_tour_accessories) > 0): ?>
                                <?php if (count($combo->land_tour_accessories) == 3): ?>
                                    <div class="row">
                                        <div class="col-sm-6"></div>
                                        <?php foreach ($combo->land_tour_accessories as $k => $accessory): ?>
                                            <div class="col-sm-8">
                                                <p class="fs16 mb15 text-light-blue text-center">
                                                    <input type="checkbox" class="iCheck" name="accessory[]" checked value="<?= $accessory->id; ?>">
                                                    <?= $accessory->name ?></i></p>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="col-sm-6"></div>
                                    </div>
                                <?php endif; ?>

                                <?php if (count($combo->land_tour_accessories) == 2): ?>
                                    <div class="row">
                                        <div class="col-sm-10"></div>
                                        <?php foreach ($combo->land_tour_accessories as $k => $accessory): ?>
                                            <div class="col-sm-8">
                                                <p class="fs16 mb15 text-light-blue text-center">
                                                    <input type="checkbox" class="iCheck" name="accessory[]" checked value="<?= $accessory->id; ?>">
                                                    <?= $accessory->name ?></i></p>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="col-sm-10"></div>
                                    </div>
                                <?php endif; ?>

                                <?php if (count($combo->land_tour_accessories) == 1): ?>
                                    <div class="row">
                                        <div class="col-sm-14"></div>
                                        <?php foreach ($combo->land_tour_accessories as $k => $accessory): ?>
                                            <div class="col-sm-8">
                                                <p class="fs16 mb15 text-light-blue text-center">
                                                    <input type="checkbox" class="iCheck" name="accessory[]" checked value="<?= $accessory->id; ?>">
                                                    <?= $accessory->name ?></i></p>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="col-sm-14"></div>
                                    </div>
                                <?php endif; ?>

                                <?php if (count($combo->land_tour_accessories) >= 4): ?>
                                    <?php foreach ($combo->land_tour_accessories as $k => $accessory): ?>
                                        <?php if ($k % 4 == 0): ?>
                                            <div class="row">
                                            <div class="col-sm-3"></div>
                                        <?php endif; ?>
                                        <div class="col-sm-8">
                                            <p class="fs16 mb15 text-light-blue">
                                                <input type="checkbox" class="iCheck" name="accessory[]" checked value="<?= $accessory->id; ?>">
                                                <?= $accessory->name ?></i></p>

                                        </div>
                                        <?php if ($k % 4 == 3 || $k == count($combo->land_tour_accessories) - 1): ?>
                                            <div class="col-sm-1"></div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
                <div class="pl20 pr20">
                    <div class="block-2 bg-white ">
                        <div class="description fs16">
                            <?php
                            $json = json_decode($combo->people_description, true);
                            if(!$json){
                                $json = [];
                            }
                            isset($json['description_type']) ? true : $json['description_type'] = "";
                            isset($json['adult_description']) ?  true : $json['adult_description'] = "";
                            isset($json['child_description']) ? true : $json['child_description'] = "";
                            isset($json['kid_description']) ? true : $json['kid_description'] = "";
                            ?>
                            <div id="filter_result" class="accordion filter-accordion">
                                <div class="panel" onclick="Frontend.filterHighlightLandtour(this, true);">
                                    <div class="pc">
                                        <div class="row pb10 pt10 no-mar-left no-mar-right panel-row panel-bg-blue">
                                            <div
                                                class="col-xs-18 col-sm-30 no-mar-left no-mar-right panel-row panel-bg-blue">
                                                <div class="row-xs-36">
                                                    <div class="col-xs-36 col-sm-24">
                                                        <p class="text-left text-filter">Người Lớn</p>
                                                    </div>
                                                    <div class="col-xs-36 col-sm-12 ">
                                                        <p class="text-left text-filter fs13-sp landtour-adult-price"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-9 col-sm-6">
                                                <a class="detail text-right collapsed fs13-sp"
                                                   data-toggle="collapse" data-target="#adult-description"
                                                   data-parent="#filter_result">
                                                    Xem chi tiết <span class="filter-dropdown-arrow"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sp">
                                        <div class="row pb10 pt10 pb05-sp vertical-center pt05-sp no-mar-left no-mar-right panel-row panel-bg-blue">
                                            <div class="col-xs-18 no-pad-right panel-row panel-bg-blue">
                                                <p class="text-left text-filter">Người Lớn</p>
                                            </div>
                                            <div class="col-xs-12">
                                                <p class="text-center fs13-sp text-filter landtour-adult-price"></p>
                                            </div>
                                            <div class="col-xs-6 text-right sp">
                                                <a class="detail collapsed fs16-sp" data-toggle="collapse"
                                                   data-target="#adult-description" data-parent="#filter_result">
                                                    <span class="filter-dropdown-arrow"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="adult-description" class="collapse filter-accordion-content">
                                        <div class="pl30 pr30 pt15 pb05 pl15-sp pr15-sp">
                                            <?= $json['description_type'] == "age" ? "<i class='fas fa-child'></i> Theo tuổi:" : ""?>
                                            <?= $json['description_type'] == "height" ? "<i class='fas fa-ruler-vertical'></i> Theo chiều cao:" : ""?>
                                            <?= $json['adult_description'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel" onclick="Frontend.filterHighlightLandtour(this, true);">
                                    <div class="pc">
                                        <div class="row pb10 pt10 no-mar-left no-mar-right panel-row">
                                            <div
                                                class="col-xs-18 col-sm-30 no-mar-left no-mar-right panel-row">
                                                <div class="row-xs-36">
                                                    <div class="col-xs-36 col-sm-24">
                                                        <p class="text-left text-filter">Trẻ em</p>
                                                    </div>
                                                    <div class="col-xs-36 col-sm-12">
                                                        <p class="text-left text-filter fs13-sp landtour-child-price"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-9 col-sm-6">
                                                <a class="detail text-right collapsed fs13-sp"
                                                   data-toggle="collapse" data-target="#child-description"
                                                   data-parent="#filter_result">
                                                    Xem chi tiết <span class="filter-dropdown-arrow"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sp">
                                        <div class="row pb10 pt10 pb05-sp vertical-center pt05-sp no-mar-left no-mar-right panel-row">
                                            <div class="col-xs-18 no-pad-right panel-row">
                                                <p class="text-left text-filter">Trẻ em</p>
                                            </div>
                                            <div class="col-xs-12">
                                                <p class="text-center fs13-sp text-filter landtour-child-price"></p>
                                            </div>
                                            <div class="col-xs-6 text-right sp">
                                                <a class="detail collapsed fs16-sp" data-toggle="collapse"
                                                   data-target="#child-description" data-parent="#filter_result">
                                                    <span class="filter-dropdown-arrow"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="child-description" class="collapse filter-accordion-content">
                                        <div class="pl30 pr30 pt15 pb05 pl15-sp pr15-sp">
                                            <?= $json['description_type'] == "age" ? "<i class='fas fa-child'></i> Theo tuổi:" : ""?>
                                            <?= $json['description_type'] == "height" ? "<i class='fas fa-ruler-vertical'></i> Theo chiều cao:" : ""?>
                                            <?= $json['child_description'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel" onclick="Frontend.filterHighlightLandtour(this, true);">
                                    <div class="pc">
                                        <div class="row pb10 pt10 no-mar-left no-mar-right panel-row">
                                            <div class="col-xs-18 col-sm-30 no-mar-left no-mar-right panel-row">
                                                <div class="row-xs-36">
                                                    <div class="col-xs-36 col-sm-24">
                                                        <p class="text-left text-filter">Em bé</p>
                                                    </div>
                                                    <div class="col-xs-36 col-sm-12">
                                                        <p class="text-left text-filter fs13-sp landtour-kid-price"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-9 col-sm-6">
                                                <a class="detail text-right collapsed fs13-sp"
                                                   data-toggle="collapse" data-target="#kid-description"
                                                   data-parent="#filter_result">
                                                    Xem chi tiết <span class="filter-dropdown-arrow"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sp">
                                        <div class="row pb10 pt10 pb05-sp vertical-center pt05-sp no-mar-left no-mar-right panel-row">
                                            <div class="col-xs-18 no-pad-right panel-row">
                                                <p class="text-left text-filter">Em bé</p>
                                            </div>
                                            <div class="col-xs-12 ">
                                                <p class="text-center fs13-sp text-filter landtour-kid-price"></p>
                                            </div>
                                            <div class="col-xs-6 text-right sp">
                                                <a class="detail collapsed fs16-sp" data-toggle="collapse"
                                                   data-target="#kid-description" data-parent="#filter_result">
                                                    <span class="filter-dropdown-arrow"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="kid-description" class="collapse filter-accordion-content">
                                        <div class="pl30 pr30 pt15 pb05 pl15-sp pr15-sp">
                                            <?= $json['description_type'] == "age" ? "<i class='fas fa-child'></i> Theo tuổi:" : ""?>
                                            <?= $json['description_type'] == "height" ? "<i class='fas fa-ruler-vertical'></i> Theo chiều cao:" : ""?>
                                            <?= $json['kid_description'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt20-pc mb20-pc"
            <?php if (($this->request->getSession()->read('Auth.User.role_id') == 3 || $this->request->getSession()->read('Auth.User.role_id') == 2) && $this->request->getSession()->read('Auth.User.is_active') == 1) : ?>
                <div class="mb30">
                    <div class="row">
                        <div class="row-sm-36">
                            <div class="pl30 pr30 pb25 mt20">
                                <div class="row row-eq-height">
                                    <div class="col-xs-36 col-sm-12 mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="fs16" id="filter_result_str"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-36 col-sm-6 mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="grp-filter-price">
                                                <p class="text-center fs16">Giá tiền</p>
                                                <div class="text-center semi-bold fs16"
                                                     id="filter_result_price"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-36 col-sm-6 mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="grp-filter-price">
                                                <p class="text-center fs16">Giá tiền lãi</p>
                                                <div class="text-center semi-bold fs16"
                                                     id="filter_result_profit"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-36  col-sm-12 mb10-sp">
                                        <button <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?>
                                            class="btn btn-request text-white full-width full-height btnGoBooking <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?>"
                                            data-form-id="#landTourSelection">
                                            <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                            <br/>
                                            <span class="fs16">MUSTGO sẽ liên hệ lại trong 30 phút</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="mb30">
                    <div class="row">
                        <div class="row-sm-36">
                            <div class="pl30 pr30 pb25 mt20">
                                <div class="row row-eq-height">
                                    <div class="col-xs-36 col-sm-18 mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="fs16" id="filter_result_str"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-36 col-sm-6 mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="grp-filter-price mt05">
                                                <p class="text-center fs16">Giá tiền</p>
                                                <div class="text-center semi-bold fs16"
                                                     id="filter_result_price"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-36 col-sm-12 mb10-sp">
                                        <button
                                            class="btn btn-request text-white full-width full-height btnGoBooking"
                                            data-form-id="#landTourSelection">
                                            <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                            <br/>
                                            <span class="fs16">MUSTGO sẽ liên hệ lại trong 30 phút</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="combo-detail mb50">
    <div class="combo-detail-title mt50 text-center" id="detail">
        <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN VỀ LAND TOUR</span>
    </div>
    <div class="container mt50 info-combo no-pad-left no-pad-right">
        <div class="row">
            <div class="col-sm-36">
                <div class="bg-grey p20">
                    <div class="row">
                        <div class="col-sm-36 fs16 mb20">
                            <div class="p10 bg-white">
                                <div class="basic-info">
                                    <div class="col-sm-offset-3 col-sm-30 col-xs-36">
                                        <div class=" bold name-hotel text-blue mt10">
                                            <span class="fs20 fs16-sp"><i class="fa fa-check text-blue"
                                                                          aria-hidden="true"></i> ĐƠN VỊ TỔ CHỨC: <?= $combo->organizer ?></span>
                                        </div>
                                        <div class="rating-hotel rating-inline bold name-hotel mt10">
                                            <span class="fs20 fs16-sp text-blue"><i class="fa fa-check text-blue"
                                                                                    aria-hidden="true"></i> TÊN LANDTOUR:  <?= $combo->name ?> </span>
                                            <div class="combo-rating fs22 mt05">
                                                <p class="star-rating" data-point="<?= $combo->rating ?>"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="short-info mt15 mb20">
                                        <div class="col-sm-offset-3 col-sm-30 mt15 col-xs-36 mt0-sp">
                                            <div class="text-left bold">
                                                <span class="fs20 fs16-sp text-blue text-uppercase"><i
                                                        class="fa fa-check"
                                                        aria-hidden="true"></i> LỊCH TRÌNH TOUR: </span>
                                            </div>
                                        </div>
                                        <?php
                                        $list_captions = json_decode($combo->caption, true);
                                        ?>
                                        <?php if ($list_captions) : ?>
                                            <?php foreach ($list_captions as $capKey => $caption) : ?>
                                                <?php if (is_array($caption)) : ?>
                                                    <div class="col-sm-offset-3 col-sm-30 mt15 col-xs-36 mt0-sp">
                                                        <span id="caption-content"
                                                              class="fs18 fs14-sp"><?= $caption['content'] ?></span>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="col-sm-offset-3 col-sm-30 mt15 col-xs-36 mt0-sp">
                                                        <span id="caption-content"
                                                              class="fs18 fs14-sp"> <?= $caption ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container term no-pad-left-pc no-pad-right-pc">
        <div class="vertical-center mt30">
            <div class="combo-detail-title box-underline-center text-center pb20 mb20 pb05-sp">
                <span class="semi-bold fs24">ĐIỀU KHOẢN QUY ĐỊNH, CHÍNH SÁCH HOÀN HỦY</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-36">
                <?php
                $terms = json_decode($combo->term, true);
                ?>
                <?php if ($terms): ?>
                    <div class="panel-group" id="accordion-term">
                        <?php foreach ($terms as $key => $term): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle collapsed text-uppercase semi-bold"
                                           data-toggle="collapse" data-parent="#accordion-term"
                                           href="#collapseTerm-<?= $key ?>">
                                            <?= $term['name'] ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTerm-<?= $key ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?= $term['content'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!--    <div class="container term no-pad-left-pc no-pad-right-pc">-->
<!--        <div class="vertical-center mt30">-->
<!--            <div class="combo-detail-title box-underline-center text-center pb20 mb20">-->
<!--                <span class="semi-bold fs24">ĐIỀU KHOẢN QUY ĐỊNH, CHÍNH SÁCH HOÀN HỦY</span>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-sm-36">-->
<!--                --><?php
//                $terms = json_decode($combo->term, true);
//                ?>
<!--                --><?php //if ($terms) : ?>
<!--                    <div class="panel-group" id="accordion-term">-->
<!--                        --><?php //foreach ($terms as $key => $term) : ?>
<!--                            <div class="panel panel-default">-->
<!--                                <div class="panel-heading">-->
<!--                                    <h4 class="panel-title">-->
<!--                                        <a class="accordion-toggle collapsed text-uppercase semi-bold" data-toggle="collapse" data-parent="#accordion-term" href="#collapseTerm--->
<? //= $key ?><!--">-->
<!--                                            --><? //= $term['name'] ?>
<!--                                        </a>-->
<!--                                    </h4>-->
<!--                                </div>-->
<!--                                <div id="collapseTerm---><? //= $key ?><!--" class="panel-collapse collapse">-->
<!--                                    <div class="panel-body">-->
<!--                                        --><? //= $term['content'] ?>
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        --><?php //endforeach; ?>
<!--                    </div>-->
<!--                --><?php //endif; ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</div>
<!-- End content -->
<?= $this->element('/Front/Popup/bookinglantour') ?>
