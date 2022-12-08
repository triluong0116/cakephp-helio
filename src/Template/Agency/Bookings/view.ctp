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
