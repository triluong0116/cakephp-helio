<!--message-->
<?php if ($this->request->getSession()->read('Auth.User.role_id') == 3): ?>
    <div class="">
        <div class="message-icon">
            <div class="bg-blue">
                <a id="message-custom" data-value="<?= $chatRoomId ?>" data-id="<?= $userId ?>">
                    <i class="fas fa-comment-dots text-white">
                        <span><i id="icon-notify" class="fa fa-exclamation-circle d-none" aria-hidden="true"></i></span>
                    </i>
                </a>
            </div>
        </div>
        <div class="body-message d-none">
            <div class="box-message">
                <div class="header-message flex">
                    <?php if (!empty($saleAdmin['avatar'])): ?>
                        <img src="/<?= $saleAdmin['avatar'] ?>" alt="" width="100px">
                    <?php else: $avatar = $this->Url->assetUrl('/frontend/img/noavatar.jpg'); ?>
                        <img src="<?= $avatar ?>" alt="" width="100px">
                    <?php endif; ?>
                    <p><?= $saleAdmin ? $saleAdmin->screen_name : '' ?></p>
                    <a id="close-message" href="#" onclick="" class="text-white"><i class="fas fa-times"></i></a>
                </div>
                <div class="content-message list-chat">
                    <div class="row">
                        <?php $count = count($listMessage); foreach ($listMessage as $k => $message): ?>
                            <?php  $img = json_decode($message['img']) ?>
                            <?php  if (($count-1) > $k): ?>
                                <?php if ($message['user_id'] == $this->request->getSession()->read('Auth.User.id')): ?>
                                    <div class="col-sm-36" id="<?= $message['id'] ?>">
                                        <div class="message-guest">
                                            <?php if ($message['type'] == 2): ?>
                                                <?php if (!empty($img))  : ?>
                                                    <img src="/<?= $img[0] ?>" alt="No Image" width="100%" >
                                                <?php else:?>
                                                    <p><?= $message['msg'] ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-sm-36" id="<?= $message['id'] ?>">
                                        <div class="message-admin">
                                            <?php if ($message['type'] == 1): ?>
                                                <?php if (!empty($img)): ?>
                                                    <img src="/<?= $img[0] ?>" alt="No Image" width="100%" >
                                                <?php else:?>
                                                    <p><?= $message['msg'] ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="footer-message">
                    <div class="flex">
                        <input type="text" name="text-message" id="text-message" placeholder="Nhập tin nhắn ..." >
                        <div class="message-file">
                            <label for="file-input"><i class="fas fa-paperclip fs20 mt10"></i></label>
                            <input class="d-none" name="image-message" id="file-input" type="file" multiple accept='image/*'>
                        </div>
                        <button type="button" class="btn btn-primary" id="btn-send-message">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!--message end-->

