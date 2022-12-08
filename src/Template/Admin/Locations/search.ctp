<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location[]|\Cake\Collection\CollectionInterface $locations
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Có <?= $number ?> kết quả được tìm thấy với từ khóa: <?= $data ?></h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Tên địa điểm</th>
                    <th>Mô tả</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($locations as $key => $location): ?>
                    <tr>
                        <th scope="row"><?= $key + 1 ?></th>
                        <td><?= $location->name ?></td>
                        <td><?= $location->description ?></td>
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
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
