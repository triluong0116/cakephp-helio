<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\HomeStay $homeStay
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $homeStay->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $homeStay->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Home Stays'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Price Home Stays'), ['controller' => 'PriceHomeStays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Price Home Stay'), ['controller' => 'PriceHomeStays', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="homeStays form large-9 medium-8 columns content">
    <?= $this->Form->create($homeStay) ?>
    <fieldset>
        <legend><?= __('Edit Home Stay') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('slug');
            echo $this->Form->control('location_id', ['options' => $locations]);
            echo $this->Form->control('address');
            echo $this->Form->control('description');
            echo $this->Form->control('rating');
            echo $this->Form->control('email');
            echo $this->Form->control('fb_content');
            echo $this->Form->control('thumbnail');
            echo $this->Form->control('media');
            echo $this->Form->control('hotline');
            echo $this->Form->control('term');
            echo $this->Form->control('homestay_type');
            echo $this->Form->control('room_type');
            echo $this->Form->control('num_bed_room');
            echo $this->Form->control('num_guest');
            echo $this->Form->control('num_bed');
            echo $this->Form->control('num_bath_room');
            echo $this->Form->control('price_agency');
            echo $this->Form->control('price_customer');
            echo $this->Form->control('categories._ids', ['options' => $categories]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
