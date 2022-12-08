<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <h3><?= h($booking->title) ?></h3>
    <table class=" table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Combo') ?></th>
            <td><?= $booking->has('combo') ? $this->Html->link($booking->combo->name, ['controller' => 'Combos', 'action' => 'view', $booking->combo->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Full Name') ?></th>
            <td><?= h($booking->full_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($booking->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone') ?></th>
            <td><?= h($booking->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hạng phòng') ?></th>
            <td><?= h($booking->room_level) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tổng số tiền booking') ?></th>
            <td><?= number_format($booking->price + $booking->adult_fee + $booking->children_fee + $booking->holiday_fee + $booking->other_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Số lượng người') ?></th>
            <td><?= h($booking->people_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu người lớn') ?></th>
            <td><?= number_format($booking->adult_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu trẻ em') ?></th>
            <td><?= number_format($booking->children_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu ngày lễ') ?></th>
            <td><?= number_format($booking->holiday_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu khác') ?></th>
            <td><?= number_format($booking->other_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lưu ý gửi khách sạn') ?></th>
            <td><?= h($booking->note) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hãng xe') ?></th>
            <td><?= h($booking->car) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lưu ý gửi CTV') ?></th>
            <td><?= h($booking->service) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gender') ?></th>
            <td><?= $this->Number->format($booking->gender) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($booking->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($booking->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Other') ?></h4>
        <?= $this->Text->autoParagraph(h($booking->other)); ?>
    </div>
</div>
