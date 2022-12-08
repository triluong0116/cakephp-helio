<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room $room
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Room'), ['action' => 'edit', $room->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Room'), ['action' => 'delete', $room->id], ['confirm' => __('Are you sure you want to delete # {0}?', $room->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Rooms'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Room'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Hotels'), ['controller' => 'Hotels', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Hotel'), ['controller' => 'Hotels', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Combos'), ['controller' => 'Combos', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Combo'), ['controller' => 'Combos', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="rooms view large-9 medium-8 columns content">
    <h3><?= h($room->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Hotel') ?></th>
            <td><?= $room->has('hotel') ? $this->Html->link($room->hotel->name, ['controller' => 'Hotels', 'action' => 'view', $room->hotel->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($room->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Slug') ?></th>
            <td><?= h($room->slug) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thumbnail') ?></th>
            <td><?= h($room->thumbnail) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($room->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Area') ?></th>
            <td><?= $this->Number->format($room->area) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Num Bed') ?></th>
            <td><?= $this->Number->format($room->num_bed) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->format($room->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Date') ?></th>
            <td><?= h($room->start_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('End Date') ?></th>
            <td><?= h($room->end_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($room->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($room->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Media') ?></h4>
        <?= $this->Text->autoParagraph(h($room->media)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Combos') ?></h4>
        <?php if (!empty($room->combos)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Slug') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Price') ?></th>
                <th scope="col"><?= __('Trippal Price') ?></th>
                <th scope="col"><?= __('Customer Price') ?></th>
                <th scope="col"><?= __('Promote') ?></th>
                <th scope="col"><?= __('Departure Id') ?></th>
                <th scope="col"><?= __('Destination Id') ?></th>
                <th scope="col"><?= __('Days') ?></th>
                <th scope="col"><?= __('Rating') ?></th>
                <th scope="col"><?= __('Thumbnail') ?></th>
                <th scope="col"><?= __('Media') ?></th>
                <th scope="col"><?= __('Date Start') ?></th>
                <th scope="col"><?= __('Date End') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($room->combos as $combos): ?>
            <tr>
                <td><?= h($combos->id) ?></td>
                <td><?= h($combos->name) ?></td>
                <td><?= h($combos->slug) ?></td>
                <td><?= h($combos->description) ?></td>
                <td><?= h($combos->price) ?></td>
                <td><?= h($combos->trippal_price) ?></td>
                <td><?= h($combos->customer_price) ?></td>
                <td><?= h($combos->promote) ?></td>
                <td><?= h($combos->departure_id) ?></td>
                <td><?= h($combos->destination_id) ?></td>
                <td><?= h($combos->days) ?></td>
                <td><?= h($combos->rating) ?></td>
                <td><?= h($combos->thumbnail) ?></td>
                <td><?= h($combos->media) ?></td>
                <td><?= h($combos->date_start) ?></td>
                <td><?= h($combos->date_end) ?></td>
                <td><?= h($combos->created) ?></td>
                <td><?= h($combos->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Combos', 'action' => 'view', $combos->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Combos', 'action' => 'edit', $combos->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Combos', 'action' => 'delete', $combos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $combos->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Categories') ?></h4>
        <?php if (!empty($room->categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Slug') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($room->categories as $categories): ?>
            <tr>
                <td><?= h($categories->id) ?></td>
                <td><?= h($categories->parent_id) ?></td>
                <td><?= h($categories->name) ?></td>
                <td><?= h($categories->slug) ?></td>
                <td><?= h($categories->created) ?></td>
                <td><?= h($categories->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Categories', 'action' => 'view', $categories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Categories', 'action' => 'edit', $categories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Categories', 'action' => 'delete', $categories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
