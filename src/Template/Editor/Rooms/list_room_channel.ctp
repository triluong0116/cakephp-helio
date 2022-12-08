<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room[]|\Cake\Collection\CollectionInterface $rooms
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12 mt10">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách phòng của <?= $hotel->name ?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th scope="col">
                        Tên phòng
                    </th>
                    <th scope="col">
                        Mã phòng
                    </th>
                    <th scope="col">
                        Mô tả
                    </th>
                    <th scope="col">
                        Diện tích
                    </th>
                    <th scope="col">
                        View type
                    </th>
                    <th scope="col">
                        Media
                    </th>
                    <th scope="col" class="actions">
                        <?= __('Actions') ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rooms as $key => $singleRoom): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= $singleRoom->name ?></td>
                        <td><?= $singleRoom->room_code ?></td>
                        <td><?= $singleRoom->description ?></td>
                        <td><?= $singleRoom->area ?></td>
                        <td><?= $singleRoom->view_type ?></td>
                        <td><a type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#<?= $singleRoom->room_code ?>">Xem</a>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'editChannel', $singleRoom->id]) ?>">Sửa</a>
                            <a type="button" class="btn btn-xs btn-success" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'rateplaneChannel', $singleRoom->id]) ?>">Danh sách các gói khách sạn</a>
                        </td>
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
                </tbody>
            </table>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
