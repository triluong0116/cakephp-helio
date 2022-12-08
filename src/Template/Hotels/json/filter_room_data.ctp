<?php foreach ($rooms as $key => $room): ?>
    <?php
    $room_result = $hotel->name . ', hạng phòng ' . $room->name . ', check in ' . $startDate . ' check out ' . $endDate . ', ' . $numb_room . ' phòng ngủ, ' . $num_adult . ' người lớn, ' . $num_children . ' trẻ em.';
    ?>
    <div class="panel" data-room-id="<?= $room->id ?>" data-result="<?= $room_result ?>"
         data-price="<?= $room['price'] ? number_format($room['price']) . 'VNĐ' : 'Chưa cập nhật' ?>"
         data-profit="<?= $room['profit'] ? number_format($room['profit']) . 'VNĐ' : 'Chưa cập nhật' ?>"
         data-final-price="<?= $room['final_price'] ? number_format($room['final_price']) . 'VNĐ' : 'Chưa cập nhật' ?>"
         onclick="Frontend.filterHighlight(this, true);">
        <div class="pc">
            <div class="row pb10 pt10 no-mar-left no-mar-right panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                <div class="col-xs-18 col-sm-24 no-mar-left no-mar-right panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                    <div class="row-xs-36">
                        <div class="col-xs-36 col-sm-24">
                            <p class="text-left text-filter"><?= $room->name ?></p>
                        </div>
                        <div class="col-xs-36 col-sm-12">
                            <p class="text-left text-filter fs13-sp"><?= $room['start_date_price'] ? number_format($room['start_date_price']) . 'VNĐ' : 'Chưa cập nhật' ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-9 col-sm-6">
                    <p class=" text-red fs13-sp"><i class="icon mustgo-room-available"> </i> <?= ($room['available_count'] === null) ? 'Chưa cập nhật' : 'Còn ' . $room['available_count'] . ' phòng' ?></p>
                </div>
                <div class="col-xs-9 col-sm-6">
                    <a class="detail text-right collapsed fs13-sp" data-toggle="collapse" data-target="#<?= $room->slug . '-' . $room->hotel_id ?>" data-parent="#filter_result">
                        Xem ảnh chi tiết <span class="filter-dropdown-arrow"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="sp">
            <div class="row pb10 pt10 pb05-sp vertical-center pt05-sp no-mar-left no-mar-right panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                <div class="col-xs-18 no-pad-right panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                    <p class="text-left text-filter"><?= $room->name ?></p>
                </div>
                <div class="col-xs-12">
                    <p class="text-center fs13-sp text-filter"><?= $room['start_date_price'] ? number_format($room['start_date_price']) . 'VNĐ' : 'Chưa cập nhật' ?> </p>
                </div>
                <div class="col-xs-6 text-right sp">
                    <a class="detail collapsed fs16-sp" data-toggle="collapse" data-target="#<?= $room->slug . '-' . $room->hotel_id ?>" data-parent="#filter_result">
                        <span class="filter-dropdown-arrow"></span>
                    </a>
                </div>
            </div>
        </div>
        <div id="<?= $room->slug . '-' . $room->hotel_id ?>" class="collapse filter-accordion-content">
            <div class="pl30 pr30 pt15 pb05 pl15-sp pr15-sp">
                <div class="row">
                    <div class="col-sm-12">
                        <span class="fs16 fs13-sp room-icon"><i class="icon mustgo-area fs18"></i>  Diện tích: <?= $room->area ?>  </span>
                    </div>
                    <div class="col-sm-12">
                        <span class="fs16 fs13-sp room-icon"><i class="icon mustgo-bed fs18 "></i>  Giường: <?= $room->num_bed ?></span>
                    </div>
                    <div class="col-sm-12">
                        <span class="fs16 fs13-sp room-icon"><i class="icon mustgo-people fs18"></i>  Số người: <?= $room->num_adult ?>NL + <?= $room->num_children ?>TE dưới <?= $room->standard_child_age + 1 ?>T </span>
                    </div>
                </div>
                <div class="row mt10 pc">
                    <div class="col-sm-12">
                        <span class="fs16 fs13-sp room-icon"><i class="icon mustgo-view fs18"></i>  View: <?= $room->view_type ?></span>
                    </div>
                    <div class="col-sm-12">
                        <span class="fs16 fs13-sp room-icon"><i class="icon mustgo-meal fs18"></i>  Bữa sáng: <?= $room->have_breakfast ? 'Có bữa sáng' : 'Không bữa sáng' ?></span>
                    </div>
                </div>
                <div class="row sp">
                    <div class="col-xs-36">
                        <span class="fs16 fs13-sp room-icon"><i class="icon mustgo-view fs18"></i>  View: <?= $room->view_type ?></span>
                    </div>
                    <div class="col-xs-36">
                        <span class="fs16 fs13-sp room-icon"><i class="icon mustgo-meal fs18"></i>  Bữa sáng: <?= $room->have_breakfast ? 'Có bữa sáng' : 'Không bữa sáng' ?></span>
                    </div>
                    <div class="col-xs-36">
                        <p class="mb10-sp sp">Tình trạng phòng: <span class="text-red fs13-sp"><i class="icon mustgo-room-available"> </i> <?= ($room['available_count'] === null) ? 'Chưa cập nhật' : 'Còn ' . $room['available_count'] . ' phòng' ?></span></p>
                    </div>
                </div>
                <?php
                $list_images = json_decode($room->media, true);
                ?>
                <?php if ($list_images): ?>
                    <div class="row row-eq-height mt10-sp mt30">
                        <div class="col-sm-36 col-xs-36 ">
                            <div class="combo-slider">
                                <div class="box-image">
                                    <div class="imgs_gird grid_6_small pc">
                                        <div class="lightgallery2">
                                            <?php

                                            $other = count($list_images) - 6;
                                            ?>
                                            <?php if ($list_images): ?>
                                                <?php foreach ($list_images as $key => $image): ?>
                                                    <?php
                                                    $class = '';

                                                    if ($key <= 5) {
                                                        $class = 'img item_' . $key;
                                                        $class .= ' super-small';
                                                        if ($key == 5) {
                                                            $class .= ' end';
                                                        }
                                                    } else {
                                                        $class = 'hide';
                                                    }
                                                    ?>
                                                    <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                        <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                        <?php if ($key > 4): ?>
                                                            <span class="other-small">+<?= $other ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="imgs_gird grid_6_small_sp sp">
                                        <div class="lightgallery2">
                                            <?php

                                            $other = count($list_images) - 3;
                                            ?>
                                            <?php if ($list_images): ?>
                                                <?php foreach ($list_images as $key => $image): ?>
                                                    <?php
                                                    $class = '';

                                                    if ($key <= 2) {
                                                        $class = 'img item_' . $key;
                                                        $class .= ' medium-small';
                                                        if ($key == 2) {
                                                            $class .= ' end';
                                                        }
                                                    } else {
                                                        $class = 'hide';
                                                    }
                                                    ?>
                                                    <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                        <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                        <?php if ($key > 1): ?>
                                                            <span class="other-small-sp">+<?= $other ?></span>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
