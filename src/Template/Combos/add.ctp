<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $combo
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Combos'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Departures'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Departure'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Booking'), ['controller' => 'Bookings', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Rooms'), ['controller' => 'Rooms', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Room'), ['controller' => 'Rooms', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="combos form large-9 medium-8 columns content">
    <?= $this->Form->create($combo) ?>
    <fieldset>
        <legend><?= __('Add Combo') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('slug');
            echo $this->Form->control('caption');
            echo $this->Form->control('description');
            echo $this->Form->control('price');
            echo $this->Form->control('trippal_price');
            echo $this->Form->control('customer_price');
            echo $this->Form->control('promote');
            echo $this->Form->control('departure_id', ['options' => $departures]);
            echo $this->Form->control('destination_id', ['options' => $destinations]);
            echo $this->Form->control('days');
            echo $this->Form->control('rating');
            echo $this->Form->control('thumbnail');
            echo $this->Form->control('media');
            echo $this->Form->control('date_start');
            echo $this->Form->control('date_end');
            echo $this->Form->control('rooms._ids', ['options' => $rooms]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
