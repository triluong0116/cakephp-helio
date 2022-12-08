<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
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
                        Địa điểm
                    </th>
                    <th scope="col">
                        Ảnh đại diện
                    </th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($hotels as $key => $hotel): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($hotel->name) ?></td>
                        <td><?= $hotel->has('location') ? $this->Html->link($hotel->location->name, ['controller' => 'Locations', 'action' => 'view', $hotel->location->id]) : '' ?></td>
                        <td>
                            <?= ($hotel->thumbnail) ? $this->Html->image('/' . $hotel->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-7']) : "" ?>
                        </td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'view', $hotel->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'edit', $hotel->id]) ?>">Sửa</a>
                            <?php
                            echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $hotel->id], ['confirm' => __('Bạn có chắc muốn xóa Danh mục: {0}?', $hotel->name), 'class' => 'btn btn-xs btn-danger']);
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
