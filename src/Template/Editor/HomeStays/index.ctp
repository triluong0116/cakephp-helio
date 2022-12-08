<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách Homestay</h2>
            <?= $this->element('Backend/searchv3') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="groupAction hidden">
                    <button class="btn btn-success" onclick="setFeatured('<?= $this->Url->build(['controller' => 'HomeStays', 'action' => 'setFeatured', 'prefix' => 'sale'], true) ?>');">
                        <i class="fa fa-map-marker"></i> Chọn Homestay Phổ biến
                    </button>
                    <button class="btn btn-warning" onclick="unsetFeatured('<?= $this->Url->build(['controller' => 'HomeStays', 'action' => 'unsetFeatured', 'prefix' => 'sale'], true) ?>');">
                        <i class="fa fa-minus-circle"></i> Bỏ Homestay Phổ biến
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">
                                    Tên Homestay
                                </th>
                                <th scope="col">
                                    Địa điểm
                                </th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($homestays as $key => $hotel): ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td><?= h($hotel->name) ?></td>
                                    <td><?= h($hotel->location->name) ?></td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'HomeStays', 'action' => 'view', $hotel->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'HomeStays', 'action' => 'edit', $hotel->id]) ?>">Sửa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
