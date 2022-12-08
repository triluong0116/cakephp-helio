<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher[]|\Cake\Collection\CollectionInterface $vouchers
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Vouchers</h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('#') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('price') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('trippal_price') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('customer_price') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('departure') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('destination') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('days') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('rating') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('thumbnail') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vouchers as $voucher): ?>
                        <tr>
                            <td><?= $this->Number->format($voucher->id) ?></td>
                            <td><?= h($voucher->name) ?></td>
                            <td><?= $this->Number->format($voucher->price) ?></td>
                            <td><?= $this->Number->format($voucher->trippal_price) ?></td>
                            <td><?= $this->Number->format($voucher->customer_price) ?></td>
                            <td><?= $voucher->has('departure') ? $this->Html->link($voucher->departure->name, ['controller' => 'Locations', 'action' => 'view', $voucher->departure->id]) : '' ?></td>
                            <td><?= $voucher->has('destination') ? $this->Html->link($voucher->destination->name, ['controller' => 'Locations', 'action' => 'view', $voucher->destination->id]) : '' ?></td>
                            <td><?= $this->Number->format($voucher->days) ?></td>
                            <td><?= $this->Number->format($voucher->rating) ?></td>
                            <td><?php
                                $this->Html->image('/' . $voucher->thumbnail, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                                ?>
                            <td><?= h($voucher->start_date) ?></td>
                            <td><?= h($voucher->end_date) ?></td>
                            <td><?= $this->Number->format($voucher->status) ?></td>
                            <td><?= h($voucher->created) ?></td>
                            <td class="actions">
                                <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Vouchers', 'action' => 'view', $voucher->id]) ?>">Xem</a>
                                <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Vouchers', 'action' => 'edit', $voucher->id]) ?>">Sửa</a>
                                <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $voucher->id], ['confirm' => __('Bạn có chắc muốn xóa Voucher: {0}?', $voucher->name), 'class' => 'btn btn-xs btn-danger']); ?>
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

