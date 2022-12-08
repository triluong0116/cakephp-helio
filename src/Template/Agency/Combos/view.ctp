<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $combo
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Combo'), ['action' => 'edit', $combo->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Combo'), ['action' => 'delete', $combo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $combo->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Combos'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Combo'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Departures'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Departure'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Destinations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Destination'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Booking'), ['controller' => 'Bookings', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Rooms'), ['controller' => 'Rooms', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Room'), ['controller' => 'Rooms', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="combos view large-9 medium-8 columns content">
    <h3><?= h($combo->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($combo->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Slug') ?></th>
            <td><?= h($combo->slug) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Departure') ?></th>
            <td><?= $combo->has('departure') ? $this->Html->link($combo->departure->name, ['controller' => 'Locations', 'action' => 'view', $combo->departure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Destination') ?></th>
            <td><?= $combo->has('destination') ? $this->Html->link($combo->destination->name, ['controller' => 'Locations', 'action' => 'view', $combo->destination->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thumbnail') ?></th>
            <td><?= h($combo->thumbnail) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($combo->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->format($combo->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Trippal Price') ?></th>
            <td><?= $this->Number->format($combo->trippal_price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer Price') ?></th>
            <td><?= $this->Number->format($combo->customer_price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Promote') ?></th>
            <td><?= $this->Number->format($combo->promote) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Days') ?></th>
            <td><?= $this->Number->format($combo->days) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rating') ?></th>
            <td><?= $this->Number->format($combo->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date Start') ?></th>
            <td><?= h($combo->date_start) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date End') ?></th>
            <td><?= h($combo->date_end) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($combo->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($combo->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Caption') ?></h4>
        <?= $this->Text->autoParagraph(h($combo->caption)); ?>
    </div>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($combo->description)); ?>
    </div>
    <div class="row">
        <h4><?= __('Media') ?></h4>
        <?= $this->Text->autoParagraph(h($combo->media)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Rooms') ?></h4>
        <?php if (!empty($combo->rooms)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Hotel Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Slug') ?></th>
                <th scope="col"><?= __('Area') ?></th>
                <th scope="col"><?= __('Num Bed') ?></th>
                <th scope="col"><?= __('Thumbnail') ?></th>
                <th scope="col"><?= __('Media') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($combo->rooms as $rooms): ?>
            <tr>
                <td><?= h($rooms->id) ?></td>
                <td><?= h($rooms->hotel_id) ?></td>
                <td><?= h($rooms->name) ?></td>
                <td><?= h($rooms->slug) ?></td>
                <td><?= h($rooms->area) ?></td>
                <td><?= h($rooms->num_bed) ?></td>
                <td><?= h($rooms->thumbnail) ?></td>
                <td><?= h($rooms->media) ?></td>
                <td><?= h($rooms->created) ?></td>
                <td><?= h($rooms->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Rooms', 'action' => 'view', $rooms->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Rooms', 'action' => 'edit', $rooms->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Rooms', 'action' => 'delete', $rooms->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rooms->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Bookings') ?></h4>
        <?php if (!empty($combo->bookings)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Combo Id') ?></th>
                <th scope="col"><?= __('Gender') ?></th>
                <th scope="col"><?= __('Full Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Other') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($combo->bookings as $bookings): ?>
            <tr>
                <td><?= h($bookings->id) ?></td>
                <td><?= h($bookings->user_id) ?></td>
                <td><?= h($bookings->combo_id) ?></td>
                <td><?= h($bookings->gender) ?></td>
                <td><?= h($bookings->full_name) ?></td>
                <td><?= h($bookings->email) ?></td>
                <td><?= h($bookings->phone) ?></td>
                <td><?= h($bookings->status) ?></td>
                <td><?= h($bookings->other) ?></td>
                <td><?= h($bookings->created) ?></td>
                <td><?= h($bookings->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Bookings', 'action' => 'view', $bookings->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Bookings', 'action' => 'edit', $bookings->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bookings', 'action' => 'delete', $bookings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookings->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
