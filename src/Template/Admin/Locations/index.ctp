<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location[]|\Cake\Collection\CollectionInterface $locations
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách địa điểm</h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="groupAction hidden">
                    <button class="btn btn-success" onclick="setFeatured('<?= $this->Url->build(['controller' => 'locations', 'action' => 'setFeatured', 'prefix' => 'admin'], true) ?>');">
                        <i class="fa fa-map-marker"></i> Chọn Địa điểm Phổ biến
                    </button>
                    <button class="btn btn-warning" onclick="unsetFeatured('<?= $this->Url->build(['controller' => 'locations', 'action' => 'unsetFeatured', 'prefix' => 'admin'], true) ?>');">
                        <i class="fa fa-minus-circle"></i> Bỏ Địa điểm Phổ biến
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" class="flat" id="checkAll"></th>
                                <th>Tên địa điểm</th>
                                <th>Mô tả</th>
                                <th>Phổ biến</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($locations as $key => $location): ?>
                                <tr>
                                    <th scope="row"><?= $key + 1 ?></th>
                                    <td><input type="checkbox" class="check flat" data-id="<?= $location->id ?>"></td>
                                    <td><?= $location->name ?></td>
                                    <td><?= substr($location->description,0,20) . '...' ?></td>
                                    <td>
                                        <?php if ($location->is_featured): ?>
                                            <i class="fa fa-check-circle text-success"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Locations', 'action' => 'view', $location->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Locations', 'action' => 'edit', $location->id]) ?>">Sửa</a>
                                        <?php
                                        echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $location->id], ['confirm' => __('Bạn có chắc muốn xóa Địa điểm # {0}?', $location->id), 'class' => 'btn btn-xs btn-danger']);
                                        ?>
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
