<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room[]|\Cake\Collection\CollectionInterface $rooms
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <a href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'add', $hotel->id]) ?>" class="btn btn-success"><i class="fa fa-plus"></i> Thêm Mới</a>
</div>
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
                        Mô tả
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
                        <td><?= $singleRoom->description ?></td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'view', $singleRoom->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'edit', $singleRoom->id]) ?>">Sửa</a>
                            <?php
                            echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $singleRoom->id], ['confirm' => __('Bạn có chắc muốn xóa Danh mục: {0}?', $singleRoom->name), 'class' => 'btn btn-xs btn-danger']);
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
