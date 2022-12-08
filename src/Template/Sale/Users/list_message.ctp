<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<!--Content message-->
<div class="list-chat">
    <?php foreach ($chatRoomIds as $chatRoomId): ?>
        <div class="custom-lc"  href="#"  onclick="getMessage(this)" id="<?= $chatRoomId['user_id']?>" data-value="<?= $chatRoomId['roomId']?>">
            <div class="row">
                <div class="col-sm-3 avatar-message mb20">
                    <?php if (!empty($chatRoomId['user_avatar'])): ?>
                        <img src="<?= $chatRoomId['user_avatar'] ?>" alt="" width="100px">
                    <?php else: $avatar = $this->Url->assetUrl('/frontend/img/noavatar.jpg'); ?>
                        <img src="<?= $avatar ?>" alt="" width="100px">
                    <?php endif; ?>
                </div>
                <div class="col-sm-9">
                    <a href="#" class="r-message">
                        <p class="name-message"><?= $chatRoomId['user_name'] ?> - <?= $chatRoomId['user_phone'] ?></p>
                        <P class="title-message" id="new-chat-<?= $chatRoomId['roomId'] ?>"><?= ''//$chatRoomId['msg'] ?></P>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php //dd(1); ?>
