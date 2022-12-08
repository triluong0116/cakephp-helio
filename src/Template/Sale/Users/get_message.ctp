<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<!--Content message-->
<div class="message-content">
    <div class="title-content">
        <div class="row">
            <div class="col-sm-5 avatar-message">
                <div class="avatar-message mb20">
                    <?php if (!empty($data['user']['avatar'])): ?>
                        <img src="/<?= $data['user']['avatar'] ?>" alt="" width="100px">
                    <?php else: $avatar = $this->Url->assetUrl('/frontend/img/noavatar.jpg'); ?>
                        <img src="<?= $avatar ?>" alt="" width="100px">
                    <?php endif; ?>
                </div>
                <div>
                    <p><?= $data['user']['screen_name'] ?> - <?= $data['user']['phone'] ?></p>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <div class="body-content">
        <div class="user-<?= $data['roomId'] ?>">
            <?php $count = count($data['dataMessage']);
            foreach ($data['dataMessage'] as $k => $value): ?>
            <?php  $img = json_decode($value['img']) ?>
                <?php if (($count) > $k): ?>
                    <?php if ($value['user_id'] == $this->request->getSession()->read('Auth.User.id')): ?>
                        <div class="row">
                            <div class="w-100-custom">
                                <div class="message-guest">
                                    <?php if ($value['type'] == 1): ?>
                                        <?php if (!empty($img)): ?>
                                            <img src="/<?= $img[0] ?>" alt="No Image" width="560px" >
                                        <?php else:?>
                                            <p><?= $value['msg'] ?></p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row" id="<?= $value['id'] ?>">
                            <div class="w-100-custom">
                                <div class="message-admin">
                                    <?php if ($value['type'] == 2): ?>
                                        <?php if (!empty($img)): ?>
                                            <img src="/<?= $img[0] ?>" alt="No Image" width="560px" >
                                        <?php else:?>
                                            <p><?= $value['msg'] ?></p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="footer-content">
        <div class="footer-message">
            <input type="text" placeholder="Nhập tin nhắn ..." name="text-message" id="text-message" required onclick="checkImg()">
            <input type="hidden" name="agency_id" value="<?= $data['user']['id'] ?>">
            <div class="message-file">
                <label for="file-input"><i class="fa fa-paperclip" aria-hidden="true"></i></label>
                <input class="d-none" id="file-input" name="images" type="file" accept='image/*'>
<!--                <input class="" id="file-input" name="images" type="file">-->
            </div>
            <button type="button" class="btn btn-primary" onclick="sendFirebaseMessage(<?= $data['user']['parent_id'] ?>)">Gửi</button>
        </div>
    </div>
</div>

