<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive">
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= $user->username ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Screen name') ?></th>
            <td><?= $user->screen_name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= $user->email ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email access code') ?></th>
            <td><?= $user->email_access_code ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh đại diện') ?></th>
            <td>
                <?= ($user->avatar) ? $this->Html->image('/' . $user->avatar, ['alt' => 'avatar', 'class' => 'img-responsive col-xs-7']) : "" ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Facebook id') ?></th>
            <td><?= $user->fbid ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Zalo') ?></th>
            <td><?= $user->zalo ?></td>
        </tr>
    </table>
</div>
