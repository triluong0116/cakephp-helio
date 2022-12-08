<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\RequestVoucher $requestVoucher
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $requestVoucher->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $requestVoucher->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Request Vouchers'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="requestVouchers form large-9 medium-8 columns content">
    <?= $this->Form->create($requestVoucher) ?>
    <fieldset>
        <legend><?= __('Edit Request Voucher') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('time');
            echo $this->Form->control('price');
            echo $this->Form->control('full_name');
            echo $this->Form->control('phone');
            echo $this->Form->control('email');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
