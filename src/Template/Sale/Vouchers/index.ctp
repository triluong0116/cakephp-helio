<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher[]|\Cake\Collection\CollectionInterface $vouchers
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <?php if ($this->request->is('get') && $this->request->getQuery('search')): ?>
                <h2>Có <?= $number ?> kết quả được tìm thấy với từ khóa: <?= $data ?></h2>
            <?php else: ?>
                <h2>Danh sách vouchers</h2>
            <?php endif; ?>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="groupAction hidden">
                    <button class="btn btn-success" onclick="setFeatured('<?= $this->Url->build(['controller' => 'vouchers', 'action' => 'setFeatured', 'prefix' => 'sale'], true) ?>');">
                        <i class="fa fa-heart"></i> Chọn Hot Voucher
                    </button>
                    <button class="btn btn-warning" onclick="unsetFeatured('<?= $this->Url->build(['controller' => 'vouchers', 'action' => 'unsetFeatured', 'prefix' => 'sale'], true) ?>');">
                        <i class="fa fa-minus-circle"></i> Bỏ Hot Voucher
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th><input type="checkbox" class="flat" id="checkAll"></th>
                                <th scope="col"><?= $this->Paginator->sort('Tên voucher') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Giá') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Giá Đại lý') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Giá Khách hàng') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Khuyến mãi') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Điểm đi') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Điểm đến') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Đánh giá') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Ngày bắt đầu') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Ngày kết thúc') ?></th>
                                <th scope="col">Phổ biến</th>
                                <th scope="col" class="actions"><?= __('Thực hiện') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($vouchers as $key => $voucher): ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td><input type="checkbox" class="check flat" data-id="<?= $voucher->id ?>"></td>
                                    <td><?= h($voucher->name) ?></td>
                                    <td><?= $this->Number->format($voucher->price) ?></td>
                                    <td><?= $this->Number->format($voucher->trippal_price) ?></td>
                                    <td><?= $this->Number->format($voucher->customer_price) ?></td>
                                    <td><?= $this->Number->format($voucher->promote) ?> %</td>
                                    <td><?= $voucher->has('departure') ? $this->Html->link($voucher->departure->name, ['controller' => 'Locations', 'action' => 'view', $voucher->departure->id]) : '' ?></td>
                                    <td><?= $voucher->has('destination') ? $this->Html->link($voucher->destination->name, ['controller' => 'Locations', 'action' => 'view', $voucher->destination->id]) : '' ?></td>
                                    <td><?= $this->Number->format($voucher->rating) ?></td>
                                    <td><?= h($voucher->start_date) ?></td>
                                    <td><?= h($voucher->end_date) ?></td>
                                    <td>
                                        <?php if ($voucher->is_featured): ?>
                                            <i class="fa fa-check-circle text-success"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'view', $voucher->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['action' => 'edit', $voucher->id]) ?>">Sửa</a>
                                        <!--                                --><?php //echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $voucher->id], ['confirm' => __('Bạn có chắc muốn xóa Voucher: {0}?', $voucher->name), 'class' => 'btn btn-xs btn-danger']); ?>
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
