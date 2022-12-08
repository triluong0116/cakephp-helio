<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking[]|\Cake\Collection\CollectionInterface $bookings
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
                        <th scope="col"><?= $this->Paginator->sort('#') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('combo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('gender') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('full_name') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $key => $booking): ?>
                        <tr>
                            <td><?= ++$key ?></td>
                            <td><?= $booking->has('combo') ? $this->Html->link($booking->combo->name, ['controller' => 'Combos', 'action' => 'view', $booking->combo->id]) : '' ?></td>
                            <td><?php if($booking->gender == 1){ echo 'Nam';} else { echo 'Nữ';} ?></td>
                            <td><?= h($booking->full_name) ?></td>
                            <td><?= h($booking->email) ?></td>
                            <td><?= h($booking->phone) ?></td>
                            <td><?= $this->Number->format($booking->status) ?></td>
                            <td><?= h($booking->created) ?></td>
                           <td class="actions">
                                <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'edit', $booking->id]) ?>">Sửa</a>
                                <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $booking->id], ['confirm' => __('Bạn có chắc muốn xóa Booking: {0}?', $booking->name), 'class' => 'btn btn-xs btn-danger']); ?>
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