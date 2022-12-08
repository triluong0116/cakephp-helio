<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive">
        <tr>
            <th scope="row"><?= __('Tên khách sạn') ?></th>
            <td><?= h($hotel->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Địa điểm') ?></th>
            <td><?= $hotel->has('location') ? $this->Html->link($hotel->location->name, ['controller' => 'Locations', 'action' => 'view', $hotel->location->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Địa chỉ cụ thể') ?></th>
            <td><?= $hotel->map ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh đại diện') ?></th>
            <td>
                <?= ($hotel->thumbnail) ? $this->Html->image('/' . $hotel->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-7']) : "" ?>
            </td>
        </tr>
        <?php if ($hotel->album): ?>
            <tr>
                <th scope="row"><?= __('Album') ?></th>
                <td>
                    <?php
                    $albums = json_decode($hotel->album);
                    foreach ($albums as $image) {
                        if ($image)
                            echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                    }
                    ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <th scope="row"><?= __('Hotline') ?></th>
            <td><?= h($hotel->hotline) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Đánh giá trung bình') ?></th>
            <td><?= $this->Number->format($hotel->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mô tả') ?></th>
            <td>
                <?= $hotel->caption ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Chính sách') ?></th>
            <td>
                <?= $hotel->term ?>
            </td>
        </tr>
    </table>
    <div class="related">
        <?php if (!empty($hotel->categories)): ?>
            <h4><?= __('Danh mục liên quan') ?></h4>
            <table class="table">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                </tr>
                <?php foreach ($hotel->categories as $key => $categories): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($categories->name) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <?php if (!empty($hotel->channelrooms)): ?>
            <h4><?= __('Các hạng mục phòng') ?></h4>
            <table class="table">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Hạng mục phòng') ?></th>
                    <th scope="col"><?= __('Mã phòng') ?></th>
                    <th scope="col"><?= __('Mô tả') ?></th>
                    <th scope="col"><?= __('Diện tích') ?></th>
                    <th scope="col"><?= __('Hướng phòng') ?></th>
                    <th scope="col"><?= __('Ảnh phòng') ?></th>
                </tr>
                <?php foreach ($hotel->channelrooms as $key => $singleRoom): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($singleRoom->name) ?></td>
                        <td><?= h($singleRoom->room_code) ?></td>
                        <td><?= h($singleRoom->description) ?></td>
                        <td><?= h($singleRoom->area) ?></td>
                        <td><?= h($singleRoom->view_type) ?></td>
                        <td><a type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#<?= $singleRoom->room_code ?>">Xem</a>
                    </tr>
                    <div class="modal fade" id="<?= $singleRoom->room_code ?>" tabindex="-1" role="dialog"
                         aria-labelledby="<?= $singleRoom->room_code ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Danh sách ảnh</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $list_images = json_decode($singleRoom->media, true);
                                    ?>
                                    <?php if ($list_images): ?>
                                        <div class="row row-eq-height mt30">
                                            <div class="col-sm-36 col-xs-36 ">
                                                <div class="combo-slider">
                                                    <div class="box-image">
                                                        <div class="imgs_gird grid_6_small">
                                                            <div id="customer-pay-photo" class="lightgallery2">
                                                                <?php
                                                                $other = count($list_images) - 4;
                                                                ?>
                                                                <?php if ($list_images): ?>
                                                                    <?php foreach ($list_images as $key => $image): ?>
                                                                        <?php
                                                                        $class = '';
                                                                        if ($key <= 3) {
                                                                            $class = 'img item_' . $key;
                                                                            $class .= ' medium-small';
                                                                            if ($key == 3) {
                                                                                $class .= ' end';
                                                                            }
                                                                        } else {
                                                                            $class = 'hide';
                                                                        }
                                                                        ?>
                                                                        <div class="<?= $class ?> "
                                                                             data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                                            <img class="img-responsive"
                                                                                 src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                                            <?php if ($key > 2): ?>
                                                                                <span
                                                                                    class="other-small">+<?= $other ?></span>
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
