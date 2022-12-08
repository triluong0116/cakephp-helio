<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Hotels'), ['controller' => 'Hotels', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Hotel'), ['controller' => 'Hotels', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Parent Uers'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Parent Uer'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Comments'), ['controller' => 'Comments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Fanpages'), ['controller' => 'Fanpages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Fanpage'), ['controller' => 'Fanpages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List User Transactions'), ['controller' => 'UserTransactions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User Transaction'), ['controller' => 'UserTransactions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List User Sessions'), ['controller' => 'UserSessions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User Session'), ['controller' => 'UserSessions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Booking'), ['controller' => 'Bookings', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Form->control('role_id', ['options' => $roles]);
            echo $this->Form->control('parent_id', ['options' => $parentUers]);
            echo $this->Form->control('landtour_parent_id');
            echo $this->Form->control('username');
            echo $this->Form->control('password');
            echo $this->Form->control('screen_name');
            echo $this->Form->control('email');
            echo $this->Form->control('phone');
            echo $this->Form->control('email_access_code');
            echo $this->Form->control('access_token');
            echo $this->Form->control('avatar');
            echo $this->Form->control('fbid');
            echo $this->Form->control('zalo');
            echo $this->Form->control('bank_code');
            echo $this->Form->control('bank_name');
            echo $this->Form->control('bank_master');
            echo $this->Form->control('bank');
            echo $this->Form->control('signature');
            echo $this->Form->control('is_active');
            echo $this->Form->control('ref_code');
            echo $this->Form->control('score_test');
            echo $this->Form->control('revenue');
            echo $this->Form->control('share_fb_count');
            echo $this->Form->control('share_zalo_count');
            echo $this->Form->control('bookings._ids', ['options' => $bookings]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
