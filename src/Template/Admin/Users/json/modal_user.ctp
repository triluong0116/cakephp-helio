<!-- Modal-->
<form method="POST" id="save-user-<?= $user->id ?>" action="users/editUser2/<?= $user->id ?>" enctype='multipart/form-data'>
    <input type="hidden" name="_csrfToken" value="<?= $this->request->getParam('_csrfToken'); ?>">
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-blue">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Thông tin người dùng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close text-white"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center mb10">
                            <div class="image-input image-input-outline image-input-circle" id="kt_image_3">
                                <div class="image-input-wrapper"
                                     style="background-image: url('<?= $this->Url->assetUrl('/' . $user->avatar) ?>') ; width: 200px; height: 200px"></div>
                                <label
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="change" data-toggle="tooltip" title=""
                                    data-original-title="Change avatar">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="profile_avatar"  accept=".png, .jpg, .jpeg"/>
                                    <input type="hidden" name="profile_avatar_remove"/>
                                    <input type="hidden" name="avatar" value="<?= $user->avatar ?>"/>
                                </label>
                                <span
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="cancel" data-toggle="tooltip" title="Cancel avatar"><i
                                        class="ki ki-bold-close icon-xs text-muted"></i></span>
                            </div>
                        </div>
                        <div class="col-12 mt15 mb10  ml30 mr25 text-center">
                            <div class="input-icon input-icon-right">
                                <input type="text" class="form-control input-border-bottom-1px text-center fs-2-rem "
                                       placeholder="Nhập tên hiển thị" name="screen_name"
                                       value="<?= $user->screen_name ?>" />
                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                            </div>
                            <h5 class="mt-5"><?php echo $user->username ?></h5>
                        </div>
                        <div class="col-12 mt15 mb10">
                            <table class="table table-bordered table-checkable dataTable no-footer dtr-inline">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-suitcase-rolling"></i></span>Vai
                                                trò:</label>
                                            <div class="input-icon input-icon-right">
                                                <select
                                                    class="form-select form-select-transparent form-control input-border-bottom-1px"
                                                    aria-label="Select example" name="role_id">
                                                    <?php foreach ($roles as $key_role => $role): ?>
                                                        <option value="<?= $key_role ?>" <?= $user->role_id == $key_role ? 'selected' : '' ?>><?= $role ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-suitcase-rolling"></i></span>Chọn Sale Phòng:</label>
                                            <div class="input-icon input-icon-right">
                                                <select
                                                    class="form-select form-select-transparent form-control input-border-bottom-1px"
                                                    aria-label="Select example" name="parent_id">
                                                    <option value="0" <?= $user->parent_id == 0 ? 'selected' : '' ?>>Chọn Sale phòng</option>
                                                    <?php foreach ($managers as  $key => $manager): ?>
                                                        <option
                                                            value="<?= $key ?>" <?= $user->parent_id == $key ? 'selected' : '' ?>><?= $manager ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-suitcase-rolling"></i></span>Chọn Sale Landtour:</label>
                                            <div class="input-icon input-icon-right">
                                                <select
                                                    class="form-select form-select-transparent form-control input-border-bottom-1px"
                                                    aria-label="Select example" name="landtour_parent_id">
                                                    <option value="0" <?= $user->landtour_parent_id == 0 ? 'selected' : '' ?>>Chọn Sale Landtour</option>
                                                    <?php foreach ($landtour_managers as $k => $landtour_manager): ?>
                                                        <option
                                                            value="<?= $k ?>" <?= $user->landtour_parent_id == $k ? 'selected' : '' ?>><?= $landtour_manager ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i
                                                        class="fab fa-facebook-square"></i></span>Facebook:</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="fbid"
                                                       value="<?= $user->fbid ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-university"></i></span>Ngân
                                                Hàng:</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="bank_name"
                                                       value="<?= $user->bank_name ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-university"></i></span>Zalo:</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="zalo"
                                                       value="<?= $user->zalo ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-money-check"></i></span>STK ngân hàng:</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="bank_code"
                                                       value="<?= $user->bank_code ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i
                                                        class="fad flaticon2-email"></i></span>Email:</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="email" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="email"
                                                       value="<?= $user->email ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-money-check"></i></span>Tên tài
                                                khoản ngân hàng:</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="bank_master"
                                                       value="<?= $user->bank_master ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fad flaticon2-email"></i></span>Email access
                                                code:</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="email_access_code"
                                                       value="<?= $user->email_access_code ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-university"></i></span>Chi
                                                nhánh</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="bank"
                                                       value="<?= $user->bank ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>

                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fas fa-check"></i></span>Kích hoạt</label>
                                            <div class="input-icon input-icon-right mr-3">
                                                <span class="switch switch-outline switch-icon switch-primary">
                                                    <label>
                                                        <input type="checkbox" <?= $user->is_active == 1 ? 'checked' : '' ?> name="is_active"/>
                                                        <span></span>
                                                    </label>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-3 ml-3">
                                            <label for="exampleFormControlInput1" class="required form-label"> <span
                                                    class="mr-2"><i class="fas fa-phone"></i></span>Số điện
                                                thoại</label>
                                            <div class="input-icon input-icon-right">
                                                <input type="text" class="form-control input-border-bottom-1px"
                                                       placeholder="Example input" name="phone"
                                                       value="<?= $user->phone ?>"/>
                                                <span><i class="fas fa-edit icon-md icon-blue"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var avatar3 = new KTImageInput('kt_image_3');
</script>
