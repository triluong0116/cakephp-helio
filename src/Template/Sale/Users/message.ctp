<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<!--Content message-->
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="col-sm-3 border-message">
            <div class="message-box">
                <div class="row">
                    <div class="search-message">
                        <input id="search-message"  type="search" width="80%" placeholder="Search ..." name="search-user">
                        <span><button type="submit" class="btn btn-primary" onclick="searchMessage()" ><i class="fa fa-search" aria-hidden="true"></i></button></span>
                    </div>
                </div>
                <div class="row">
                    <div class="list-chat" id="chatList">
                        <?php foreach ($chatRoomIds as $chatRoomId): ?>
                            <div class="custom-lc"  href="#"  onclick="getMessage(this)" id="<?= $chatRoomId['roomId']?>" data-value="<?= $chatRoomId['roomId']?>">
                                <div class="row">
                                    <div class="col-sm-3 avatar-message mb20">
                                        <?php if (!empty($chatRoomId['user_avatar'])): ?>
                                            <img src="/<?= $chatRoomId['user_avatar'] ?>" alt="" width="100px">
                                        <?php else: $avatar = $this->Url->assetUrl('/frontend/img/noavatar.jpg'); ?>
                                            <img src="<?= $avatar ?>" alt="" width="100px">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-9">
                                        <a href="#" class="r-message">
                                            <p class="name-message"><?= $chatRoomId['user_name'] ?> - <?= $chatRoomId['user_phone'] ?></p>
                                            <P class="" id="new-chat-<?= $chatRoomId['roomId'] ?>"><?= ''//$chatRoomId['msg'] ?></P>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 ">
            <div class="message-content">
                <div class="title-content d-none">
                    <div class="row">
                        <div class="col-sm-5 avatar-message">
                            <div class="avatar-message mb20">
                                <?php if (!empty($data['user']['avatar'])): ?>
                                    <img src="/<?= $data['user']['avatar'] ?>" alt="" width="100px">
                                <?php else: $avatar = $this->Url->assetUrl('/frontend/img/noavatar.jpg'); ?>
                                    <img src="/<?= $avatar ?>" alt="" width="100px">
                                <?php endif; ?>
                            </div>
                            <div>
                                <p><?= $data['user']['screen_name'] ?> - <?= $data['user']['phone'] ?></p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="body-content d-none">
                    <div class="user-<?= $data['user']['id']  ?>">
                        <?php foreach ($data['dataMessage'] as $value): ?>
                            <?php if ($value['user_id'] == $this->request->getSession()->read('Auth.User.id')): ?>
                                <div class="row">
                                    <div class="w-100-custom">
                                        <div class="message-guest">
                                            <?php if ($value['type'] == 1): ?>
                                                <?php if ($value['img'] != ""): ?>
                                                        <img src="/<?= $value['img'] ?>" alt="No Image" >
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
                                                <?php if ($value['img'] != ""): ?>
                                                    <img src="/<?= $value['img'] ?>" alt="No Image" >
                                                <?php else:?>
                                                    <p><?= $value['msg'] ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="footer-content d-none">
                    <div class="footer-message">
                        <input type="text" placeholder="Nhập tin nhắn ..." name="text-message" required>
                        <input type="hidden" name="agency_id" value="<?= $data['user']['id'] ?>">
                        <div class="message-file d-none">
                            <label for="file-input"><i class="fa fa-paperclip" aria-hidden="true"></i></label>
                            <input class="d-none" id="file-input" type="file" name="images" multiple accept='image/*'>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="sendFirebaseMessage(<?= $data['user']['id']  ?>)">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
