<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Hotel'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Rooms'), ['controller' => 'Rooms', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Room'), ['controller' => 'Rooms', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Price Hotels'), ['controller' => 'PriceHotels', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Price Hotel'), ['controller' => 'PriceHotels', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Combos'), ['controller' => 'Combos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Combo'), ['controller' => 'Combos', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="hotels index large-9 medium-8 columns content">
    <h3><?= __('Hotels') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('slug') ?></th>
                <th scope="col"><?= $this->Paginator->sort('location_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thumbnail') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rating') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('hotline') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hotels as $hotel): ?>
            <tr>
                <td><?= $this->Number->format($hotel->id) ?></td>
                <td><?= h($hotel->name) ?></td>
                <td><?= h($hotel->slug) ?></td>
                <td><?= $hotel->has('location') ? $this->Html->link($hotel->location->name, ['controller' => 'Locations', 'action' => 'view', $hotel->location->id]) : '' ?></td>
                <td><?= h($hotel->thumbnail) ?></td>
                <td><?= $this->Number->format($hotel->rating) ?></td>
                <td><?= h($hotel->address) ?></td>
                <td><?= h($hotel->hotline) ?></td>
                <td><?= h($hotel->created) ?></td>
                <td><?= h($hotel->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $hotel->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $hotel->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $hotel->id], ['confirm' => __('Are you sure you want to delete # {0}?', $hotel->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
