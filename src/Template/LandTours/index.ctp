<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LandTour[]|\Cake\Collection\CollectionInterface $landTours
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Land Tour'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Departures'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Departure'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="landTours index large-9 medium-8 columns content">
    <h3><?= __('Land Tours') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('slug') ?></th>
                <th scope="col"><?= $this->Paginator->sort('price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('trippal_price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('customer_price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('promote') ?></th>
                <th scope="col"><?= $this->Paginator->sort('departure_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('destination_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('days') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rating') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thumbnail') ?></th>
                <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($landTours as $landTour): ?>
            <tr>
                <td><?= $this->Number->format($landTour->id) ?></td>
                <td><?= $landTour->has('user') ? $this->Html->link($landTour->user->id, ['controller' => 'Users', 'action' => 'view', $landTour->user->id]) : '' ?></td>
                <td><?= h($landTour->name) ?></td>
                <td><?= h($landTour->slug) ?></td>
                <td><?= $this->Number->format($landTour->price) ?></td>
                <td><?= $this->Number->format($landTour->trippal_price) ?></td>
                <td><?= $this->Number->format($landTour->customer_price) ?></td>
                <td><?= $this->Number->format($landTour->promote) ?></td>
                <td><?= $landTour->has('departure') ? $this->Html->link($landTour->departure->name, ['controller' => 'Locations', 'action' => 'view', $landTour->departure->id]) : '' ?></td>
                <td><?= $landTour->has('destination') ? $this->Html->link($landTour->destination->name, ['controller' => 'Locations', 'action' => 'view', $landTour->destination->id]) : '' ?></td>
                <td><?= $this->Number->format($landTour->days) ?></td>
                <td><?= $this->Number->format($landTour->rating) ?></td>
                <td><?= h($landTour->thumbnail) ?></td>
                <td><?= h($landTour->start_date) ?></td>
                <td><?= h($landTour->end_date) ?></td>
                <td><?= $this->Number->format($landTour->status) ?></td>
                <td><?= h($landTour->created) ?></td>
                <td><?= h($landTour->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $landTour->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $landTour->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $landTour->id], ['confirm' => __('Are you sure you want to delete # {0}?', $landTour->id)]) ?>
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
