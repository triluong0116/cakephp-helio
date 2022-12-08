<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room $room
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Rooms'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Hotels'), ['controller' => 'Hotels', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Hotel'), ['controller' => 'Hotels', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Combos'), ['controller' => 'Combos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Combo'), ['controller' => 'Combos', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="rooms form large-9 medium-8 columns content">
    <?= $this->Form->create($room) ?>
    <fieldset>
        <legend><?= __('Add Room') ?></legend>
        <?php
            echo $this->Form->control('hotel_id', ['options' => $hotels]);
            echo $this->Form->control('name');
            echo $this->Form->control('slug');
            echo $this->Form->control('area');
            echo $this->Form->control('num_bed');
            echo $this->Form->control('thumbnail');
            echo $this->Form->control('media');
            echo $this->Form->control('start_date');
            echo $this->Form->control('end_date');
            echo $this->Form->control('price');
            echo $this->Form->control('combos._ids', ['options' => $combos]);
            echo $this->Form->control('categories._ids', ['options' => $categories]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
