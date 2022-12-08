<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Blog $blog
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Blogs'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="blogs form large-9 medium-8 columns content">
    <?= $this->Form->create($blog) ?>
    <fieldset>
        <legend><?= __('Add Blog') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('title');
            echo $this->Form->control('slug');
            echo $this->Form->control('caption');
            echo $this->Form->control('description');
            echo $this->Form->control('thumbnail');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
