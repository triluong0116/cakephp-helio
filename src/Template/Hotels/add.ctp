<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Hotels'), ['action' => 'index']) ?></li>
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
<div class="hotels form large-9 medium-8 columns content">
    <?= $this->Form->create($hotel) ?>
    <fieldset>
        <legend><?= __('Add Hotel') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('slug');
            echo $this->Form->control('description');
            echo $this->Form->control('location_id', ['options' => $locations]);
            echo $this->Form->control('thumbnail');
            echo $this->Form->control('media');
            echo $this->Form->control('rating');
            echo $this->Form->control('address');
            echo $this->Form->control('map');
            echo $this->Form->control('hotline');
            echo $this->Form->control('term');
            echo $this->Form->control('categories._ids', ['options' => $categories]);
            echo $this->Form->control('combos._ids', ['options' => $combos]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
