<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Hotels'), ['controller' => 'Hotels', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Hotel'), ['controller' => 'Hotels', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Uers'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Uer'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Comments'), ['controller' => 'Comments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Fanpages'), ['controller' => 'Fanpages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Fanpage'), ['controller' => 'Fanpages', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List User Transactions'), ['controller' => 'UserTransactions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Transaction'), ['controller' => 'UserTransactions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List User Sessions'), ['controller' => 'UserSessions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Session'), ['controller' => 'UserSessions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Child Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Child User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Booking'), ['controller' => 'Bookings', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Role') ?></th>
            <td><?= $user->has('role') ? $this->Html->link($user->role->name, ['controller' => 'Roles', 'action' => 'view', $user->role->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Parent Uer') ?></th>
            <td><?= $user->has('parent_uer') ? $this->Html->link($user->parent_uer->id, ['controller' => 'Users', 'action' => 'view', $user->parent_uer->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Screen Name') ?></th>
            <td><?= h($user->screen_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email Access Code') ?></th>
            <td><?= h($user->email_access_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Access Token') ?></th>
            <td><?= h($user->access_token) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Avatar') ?></th>
            <td><?= h($user->avatar) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fbid') ?></th>
            <td><?= h($user->fbid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Zalo') ?></th>
            <td><?= h($user->zalo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bank Code') ?></th>
            <td><?= h($user->bank_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bank Name') ?></th>
            <td><?= h($user->bank_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bank Master') ?></th>
            <td><?= h($user->bank_master) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ref Code') ?></th>
            <td><?= h($user->ref_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Score Test') ?></th>
            <td><?= h($user->score_test) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Landtour Parent Id') ?></th>
            <td><?= $this->Number->format($user->landtour_parent_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($user->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Revenue') ?></th>
            <td><?= $this->Number->format($user->revenue) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Share Fb Count') ?></th>
            <td><?= $this->Number->format($user->share_fb_count) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Share Zalo Count') ?></th>
            <td><?= $this->Number->format($user->share_zalo_count) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($user->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($user->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Phone') ?></h4>
        <?= $this->Text->autoParagraph(h($user->phone)); ?>
    </div>
    <div class="row">
        <h4><?= __('Bank') ?></h4>
        <?= $this->Text->autoParagraph(h($user->bank)); ?>
    </div>
    <div class="row">
        <h4><?= __('Signature') ?></h4>
        <?= $this->Text->autoParagraph(h($user->signature)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Bookings') ?></h4>
        <?php if (!empty($user->bookings)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Code') ?></th>
                <th scope="col"><?= __('Sale Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Client Id') ?></th>
                <th scope="col"><?= __('Item Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Booking Type') ?></th>
                <th scope="col"><?= __('Object Name') ?></th>
                <th scope="col"><?= __('Full Name') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Amount') ?></th>
                <th scope="col"><?= __('Hotel Code') ?></th>
                <th scope="col"><?= __('Room Level') ?></th>
                <th scope="col"><?= __('People Amount') ?></th>
                <th scope="col"><?= __('Adult Fee') ?></th>
                <th scope="col"><?= __('Children Fee') ?></th>
                <th scope="col"><?= __('Holiday Fee') ?></th>
                <th scope="col"><?= __('Other Fee') ?></th>
                <th scope="col"><?= __('Note') ?></th>
                <th scope="col"><?= __('Note Agency') ?></th>
                <th scope="col"><?= __('Car') ?></th>
                <th scope="col"><?= __('Service') ?></th>
                <th scope="col"><?= __('Start Date') ?></th>
                <th scope="col"><?= __('End Date') ?></th>
                <th scope="col"><?= __('Complete Date') ?></th>
                <th scope="col"><?= __('Price') ?></th>
                <th scope="col"><?= __('Sale Revenue') ?></th>
                <th scope="col"><?= __('Revenue') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Other') ?></th>
                <th scope="col"><?= __('Customer Deposit') ?></th>
                <th scope="col"><?= __('Mustgo Deposit') ?></th>
                <th scope="col"><?= __('Agency Pay') ?></th>
                <th scope="col"><?= __('Pay Hotel') ?></th>
                <th scope="col"><?= __('Confirm Agency Pay') ?></th>
                <th scope="col"><?= __('Sale Discount') ?></th>
                <th scope="col"><?= __('Agency Discount') ?></th>
                <th scope="col"><?= __('Information') ?></th>
                <th scope="col"><?= __('Payment Content') ?></th>
                <th scope="col"><?= __('Payment Content Agency') ?></th>
                <th scope="col"><?= __('Payment Deadline') ?></th>
                <th scope="col"><?= __('Payment Method') ?></th>
                <th scope="col"><?= __('Is Paid') ?></th>
                <th scope="col"><?= __('Is Send Notice') ?></th>
                <th scope="col"><?= __('Note For Hotel Payment') ?></th>
                <th scope="col"><?= __('Landtour Pickup Id') ?></th>
                <th scope="col"><?= __('Landtour Drop Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->bookings as $bookings): ?>
            <tr>
                <td><?= h($bookings->id) ?></td>
                <td><?= h($bookings->code) ?></td>
                <td><?= h($bookings->sale_id) ?></td>
                <td><?= h($bookings->user_id) ?></td>
                <td><?= h($bookings->client_id) ?></td>
                <td><?= h($bookings->item_id) ?></td>
                <td><?= h($bookings->type) ?></td>
                <td><?= h($bookings->booking_type) ?></td>
                <td><?= h($bookings->object_name) ?></td>
                <td><?= h($bookings->full_name) ?></td>
                <td><?= h($bookings->phone) ?></td>
                <td><?= h($bookings->email) ?></td>
                <td><?= h($bookings->amount) ?></td>
                <td><?= h($bookings->hotel_code) ?></td>
                <td><?= h($bookings->room_level) ?></td>
                <td><?= h($bookings->people_amount) ?></td>
                <td><?= h($bookings->adult_fee) ?></td>
                <td><?= h($bookings->children_fee) ?></td>
                <td><?= h($bookings->holiday_fee) ?></td>
                <td><?= h($bookings->other_fee) ?></td>
                <td><?= h($bookings->note) ?></td>
                <td><?= h($bookings->note_agency) ?></td>
                <td><?= h($bookings->car) ?></td>
                <td><?= h($bookings->service) ?></td>
                <td><?= h($bookings->start_date) ?></td>
                <td><?= h($bookings->end_date) ?></td>
                <td><?= h($bookings->complete_date) ?></td>
                <td><?= h($bookings->price) ?></td>
                <td><?= h($bookings->sale_revenue) ?></td>
                <td><?= h($bookings->revenue) ?></td>
                <td><?= h($bookings->status) ?></td>
                <td><?= h($bookings->other) ?></td>
                <td><?= h($bookings->customer_deposit) ?></td>
                <td><?= h($bookings->mustgo_deposit) ?></td>
                <td><?= h($bookings->agency_pay) ?></td>
                <td><?= h($bookings->pay_hotel) ?></td>
                <td><?= h($bookings->confirm_agency_pay) ?></td>
                <td><?= h($bookings->sale_discount) ?></td>
                <td><?= h($bookings->agency_discount) ?></td>
                <td><?= h($bookings->information) ?></td>
                <td><?= h($bookings->payment_content) ?></td>
                <td><?= h($bookings->payment_content_agency) ?></td>
                <td><?= h($bookings->payment_deadline) ?></td>
                <td><?= h($bookings->payment_method) ?></td>
                <td><?= h($bookings->is_paid) ?></td>
                <td><?= h($bookings->is_send_notice) ?></td>
                <td><?= h($bookings->note_for_hotel_payment) ?></td>
                <td><?= h($bookings->landtour_pickup_id) ?></td>
                <td><?= h($bookings->landtour_drop_id) ?></td>
                <td><?= h($bookings->created) ?></td>
                <td><?= h($bookings->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Bookings', 'action' => 'view', $bookings->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Bookings', 'action' => 'edit', $bookings->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bookings', 'action' => 'delete', $bookings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookings->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Comments') ?></h4>
        <?php if (!empty($user->comments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Object Type') ?></th>
                <th scope="col"><?= __('Object Id') ?></th>
                <th scope="col"><?= __('Content') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->comments as $comments): ?>
            <tr>
                <td><?= h($comments->id) ?></td>
                <td><?= h($comments->parent_id) ?></td>
                <td><?= h($comments->user_id) ?></td>
                <td><?= h($comments->object_type) ?></td>
                <td><?= h($comments->object_id) ?></td>
                <td><?= h($comments->content) ?></td>
                <td><?= h($comments->created) ?></td>
                <td><?= h($comments->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Comments', 'action' => 'view', $comments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Comments', 'action' => 'edit', $comments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Comments', 'action' => 'delete', $comments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comments->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Fanpages') ?></h4>
        <?php if (!empty($user->fanpages)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Page Id') ?></th>
                <th scope="col"><?= __('Access Token') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->fanpages as $fanpages): ?>
            <tr>
                <td><?= h($fanpages->id) ?></td>
                <td><?= h($fanpages->user_id) ?></td>
                <td><?= h($fanpages->name) ?></td>
                <td><?= h($fanpages->page_id) ?></td>
                <td><?= h($fanpages->access_token) ?></td>
                <td><?= h($fanpages->created) ?></td>
                <td><?= h($fanpages->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Fanpages', 'action' => 'view', $fanpages->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Fanpages', 'action' => 'edit', $fanpages->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Fanpages', 'action' => 'delete', $fanpages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $fanpages->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related User Transactions') ?></h4>
        <?php if (!empty($user->user_transactions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Booking Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Customer Name') ?></th>
                <th scope="col"><?= __('Revenue') ?></th>
                <th scope="col"><?= __('Reason') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->user_transactions as $userTransactions): ?>
            <tr>
                <td><?= h($userTransactions->id) ?></td>
                <td><?= h($userTransactions->booking_id) ?></td>
                <td><?= h($userTransactions->user_id) ?></td>
                <td><?= h($userTransactions->customer_name) ?></td>
                <td><?= h($userTransactions->revenue) ?></td>
                <td><?= h($userTransactions->reason) ?></td>
                <td><?= h($userTransactions->created) ?></td>
                <td><?= h($userTransactions->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UserTransactions', 'action' => 'view', $userTransactions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'UserTransactions', 'action' => 'edit', $userTransactions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UserTransactions', 'action' => 'delete', $userTransactions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userTransactions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related User Sessions') ?></h4>
        <?php if (!empty($user->user_sessions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Data') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Expires') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->user_sessions as $userSessions): ?>
            <tr>
                <td><?= h($userSessions->id) ?></td>
                <td><?= h($userSessions->data) ?></td>
                <td><?= h($userSessions->user_id) ?></td>
                <td><?= h($userSessions->expires) ?></td>
                <td><?= h($userSessions->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UserSessions', 'action' => 'view', $userSessions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'UserSessions', 'action' => 'edit', $userSessions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UserSessions', 'action' => 'delete', $userSessions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userSessions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($user->child_users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Role Id') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Landtour Parent Id') ?></th>
                <th scope="col"><?= __('Username') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Screen Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Email Access Code') ?></th>
                <th scope="col"><?= __('Access Token') ?></th>
                <th scope="col"><?= __('Avatar') ?></th>
                <th scope="col"><?= __('Fbid') ?></th>
                <th scope="col"><?= __('Zalo') ?></th>
                <th scope="col"><?= __('Bank Code') ?></th>
                <th scope="col"><?= __('Bank Name') ?></th>
                <th scope="col"><?= __('Bank Master') ?></th>
                <th scope="col"><?= __('Bank') ?></th>
                <th scope="col"><?= __('Signature') ?></th>
                <th scope="col"><?= __('Is Active') ?></th>
                <th scope="col"><?= __('Ref Code') ?></th>
                <th scope="col"><?= __('Score Test') ?></th>
                <th scope="col"><?= __('Revenue') ?></th>
                <th scope="col"><?= __('Share Fb Count') ?></th>
                <th scope="col"><?= __('Share Zalo Count') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->child_users as $childUsers): ?>
            <tr>
                <td><?= h($childUsers->id) ?></td>
                <td><?= h($childUsers->role_id) ?></td>
                <td><?= h($childUsers->parent_id) ?></td>
                <td><?= h($childUsers->landtour_parent_id) ?></td>
                <td><?= h($childUsers->username) ?></td>
                <td><?= h($childUsers->password) ?></td>
                <td><?= h($childUsers->screen_name) ?></td>
                <td><?= h($childUsers->email) ?></td>
                <td><?= h($childUsers->phone) ?></td>
                <td><?= h($childUsers->email_access_code) ?></td>
                <td><?= h($childUsers->access_token) ?></td>
                <td><?= h($childUsers->avatar) ?></td>
                <td><?= h($childUsers->fbid) ?></td>
                <td><?= h($childUsers->zalo) ?></td>
                <td><?= h($childUsers->bank_code) ?></td>
                <td><?= h($childUsers->bank_name) ?></td>
                <td><?= h($childUsers->bank_master) ?></td>
                <td><?= h($childUsers->bank) ?></td>
                <td><?= h($childUsers->signature) ?></td>
                <td><?= h($childUsers->is_active) ?></td>
                <td><?= h($childUsers->ref_code) ?></td>
                <td><?= h($childUsers->score_test) ?></td>
                <td><?= h($childUsers->revenue) ?></td>
                <td><?= h($childUsers->share_fb_count) ?></td>
                <td><?= h($childUsers->share_zalo_count) ?></td>
                <td><?= h($childUsers->created) ?></td>
                <td><?= h($childUsers->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $childUsers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $childUsers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $childUsers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childUsers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Clients') ?></h4>
        <?php if (!empty($user->clients)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('ClientId') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Expo Push Token') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Api Token Login') ?></th>
                <th scope="col"><?= __('Login Expire') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->clients as $clients): ?>
            <tr>
                <td><?= h($clients->id) ?></td>
                <td><?= h($clients->clientId) ?></td>
                <td><?= h($clients->user_id) ?></td>
                <td><?= h($clients->expo_push_token) ?></td>
                <td><?= h($clients->name) ?></td>
                <td><?= h($clients->email) ?></td>
                <td><?= h($clients->phone) ?></td>
                <td><?= h($clients->api_token_login) ?></td>
                <td><?= h($clients->login_expire) ?></td>
                <td><?= h($clients->created) ?></td>
                <td><?= h($clients->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Clients', 'action' => 'view', $clients->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Clients', 'action' => 'edit', $clients->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Clients', 'action' => 'delete', $clients->id], ['confirm' => __('Are you sure you want to delete # {0}?', $clients->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
