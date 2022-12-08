<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room[]|\Cake\Collection\CollectionInterface $rooms
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
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th scope="col">
                        Tên khách sạn
                    </th>
                    <th scope="col">
                        Tên phòng
                    </th>
                    <th scope="col">
                        Diện tích (m2)
                    </th>
                    <th scope="col">
                        Số giường
                    </th>
                    <th scope="col">
                        Giá
                    </th>
                    <th scope="col" class="actions">
                        <?= __('Actions') ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rooms as $key => $room): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= $room->has('hotel') ? $this->Html->link($room->hotel->name, ['controller' => 'Hotels', 'action' => 'view', $room->hotel->id]) : '' ?></td>
                        <td><?= h($room->name) ?></td>
                        <td><?= $room->area ?></td>
                        <td><?= $room->num_bed ?></td>
                        <td><?= $this->Number->currency($room->price, "VND") ?></td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'view', $room->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'edit', $room->id]) ?>">Sửa</a>
                            <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $room->id], ['confirm' => __('Bạn có chắc muốn xóa Room: {0}?', $room->name), 'class' => 'btn btn-xs btn-danger']);?>
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
