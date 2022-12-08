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
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên voucher</th>
                     <th scope="col">Thời gian</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Người bán</th>
                    <th scope="col">Số điện thoại</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vouchers as $key => $voucher): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($voucher->title) ?></td>
                        <td><?= h($voucher->time) ?></td>
                        <td><?= h($voucher->price) ?></td>
                        <td><?= h($voucher->full_name) ?></td>
                        <td><?= h($voucher->phone) ?></td>
                        <td><?= h($voucher->email) ?></td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'view', $voucher->id]) ?>">Xem</a>
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
