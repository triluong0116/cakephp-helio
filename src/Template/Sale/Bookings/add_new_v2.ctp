<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div>
    <?= $this->Form->create($booking, ['class' => '', 'data-parsley-validate', 'id' => 'form-booking-system', 'type' => 'file']) ?>
    <?php
    $this->Form->setTemplates([
        'formStart' => '<form class="" {{attrs}}>',
        'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
        'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
        'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
        'textarea' => '<div class="col-md-8 col-sm-8 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
        'checkContainer' => ''
    ]) ?>
    <div class="">
        <div class="card card-custom mb-5 center">
            <div class="card-header border-0 pt-6 pb-0">
                <div class="card-title m-auto">
                    <div class="">
                        <div class="row mb-10">
                            <div class="m-auto row">
                                <div class="mr-5">
                                    <button type="button" class="btn btn-primary">
                                        <h4>MUSTGO</h4>
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-default">
                                        <h4>VINPEARL</h4>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <p>Bạn đang tạo Booking mới thuộc hệ thống <span class="text-primary">MOSTGO.VN</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <input type="hidden" value="1" name="booking_type">
                    <input type="hidden" value="1" name="creator_type">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="user_id">
                                    <h5 class="">Chọn cộng tác viên (<span class="text-danger">*</span>)</h5>
                                </label>
                                <select class="form-control select2" name="user_id" id="user_id">
                                    <option value="">Chọn cộng tác viên</option>
                                    <option value="12">Khách lẻ</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user->id ?>"
                                                data-name="<?= $user->screen_name ?>"
                                                data-phone="<?= isset($user->phone) ? $user->phone : '' ?>"
                                                data-email="<?= isset($user->email) ? $user->email : '' ?>">
                                            <?= $user->screen_name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="error-user-id"></span>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="booking-hotel-select">
                                    <h5 class="fs18">Dánh sách khánh sạn(<span class="text-danger">*</span>)</h5>
                                </label>
                                <select class="form-control select2" name="item_id" id="booking-hotel-select"
                                        onchange="bookingChangeHotelV2(this)" data-booking-id="">
                                    <option value="">Chọn khách sạn</option>
                                    <?php foreach ($hotels as $hotel): ?>
                                        <option value="<?= $hotel->id ?>"><?= $hotel->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="error-booking-hotel-select"></span>
                            </div>
                            <div class="form-group d-none">
                                <select name="type" class="form-control text-left select2 select2-hidden-accessible"
                                        id="choose-type-booking-system"
                                        aria-hidden="true">
                                    <option value="4">Hotel</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="row" id="hotelBookingForm">
            <div class="col-sm-8">
                <div class="card carrd-custom" id="setName">
                    <div class="accordion accordion-solid accordion-panel accordion-svg-toggle" id="accordionExample8">
                        <div class="card">
                            <div class="card-header" id="headingOne8">
                                <div class="card-title" data-toggle="collapse" data-target="#collapseOne8">
                                    <div class="card-label">Hạng phòng</div>
                                    <span class="svg-icon">
                             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                  width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                               <polygon points="0 0 24 0 24 24 0 24"></polygon>
                               <path
                                   d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                   fill="#000000" fill-rule="nonzero"></path>
                               <path
                                   d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                   fill="#000000" fill-rule="nonzero" opacity="0.3"
                                   transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                              </g>
                             </svg>
                            </span>
                                </div>
                            </div>
                            <div id="collapseOne8" class="collapse show" data-parent="#accordionExample8">
                                <div class="card-body pl-12">
                                    <div id="list-hotel-item" class="rooms">
                                        <div class="card-body">
                                            <div id="kt_repeater_1">
                                                <div class="form-group row " id="kt_repeater_1">
                                                    <div class="separator separator-dashed my-8"></div>
                                                    <div data-repeater-list="" class="">
                                                        <div data-repeater-item
                                                             class="form-group row align-items-center">
                                                            <div class="col-sm-6 form-group booking_rooms">
                                                                <label for="">
                                                                    <p class="fs18">Chọn hạng phòng(<span
                                                                            class="text-danger">*</span>)</p>
                                                                </label>
                                                                <select class="form-control option-room"
                                                                        name="room_id"
                                                                        id="room_id"
                                                                        tabindex="-1"
                                                                        aria-hidden="true">
                                                                    <option value="0">Chọn hạng phòng</option>
                                                                </select>
                                                                <span class="error_room_id"></span>
                                                            </div>

                                                            <div class="form-group col-sm-6 booking_rooms">
                                                                <label class="col-form-label text-right ">Check In(<span
                                                                        class="text-danger">*</span>)</label>
                                                                <div class="">
                                                                    <div class="input-group date">
                                                                        <input type="text"
                                                                               class="form-control kt_datepicker_3"
                                                                               name="start_date"
                                                                               readonly value="<?= date('d/m/Y') ?>"
                                                                               id=""/>
                                                                        <div class="input-group-append">
                                                                   <span class="input-group-text">
                                                                    <i class="la la-calendar"></i>
                                                                    </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6 form-group booking_rooms">
                                                                <label for="num_room">
                                                                    <p class="fs18">Số lượng phòng(<span
                                                                            class="text-danger">*</span>)</p>
                                                                </label>
                                                                <input name="num_room"
                                                                       id="num_room" type="text"
                                                                       class="form-control"
                                                                       placeholder=""/>
                                                                <span class="error_num_room"></span>
                                                            </div>
                                                            <div class="form-group col-sm-6 booking_rooms">
                                                                <label class="col-form-label text-right ">Check
                                                                    Out(<span
                                                                        class="text-danger">*</span>)</label>
                                                                <div class="">
                                                                    <div class="input-group date">
                                                                        <input type="text"
                                                                               class="form-control kt_datepicker_3"
                                                                               name="end_date"
                                                                               readonly value="<?= date('d/m/Y') ?>"
                                                                               id=""/>
                                                                        <div class="input-group-append">
                                                                   <span class="input-group-text">
                                                                    <i class="la la-calendar"></i>
                                                                    </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6 form-group booking_rooms" >
                                                                <label for="num_adult">
                                                                    <p class="fs18">Số người lớn(<span
                                                                            class="text-danger">*</span>)</p>
                                                                </label>
                                                                <input name="num_adult"
                                                                       id="num_adult" type="text"
                                                                       class="form-control"
                                                                       placeholder=""/>
                                                                <span class="error_num_adult"></span>
                                                            </div>

                                                            <div class="col-sm-6 form-group booking_rooms">
                                                                <label for="hotel">
                                                                    <p class="fs18">Giá phòng</p>
                                                                </label>
                                                                <input name="room_single_price" type="text"
                                                                       class="form-control"
                                                                       style="background-color: lightgrey "
                                                                       placeholder="" value=""
                                                                       readonly="readonly"/>
                                                            </div>

                                                            <div class="col-sm-6 form-group booking_rooms" >
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>
                                                                            <p class="fs18">trẻ em 0-6 tuổi(<span
                                                                                    class="text-danger">*</span>)</p>
                                                                        </label>
                                                                        <input type="text"
                                                                               name="num_children_0_6"
                                                                               class="form-control" placeholder=""/>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>
                                                                            <p class="fs18">trẻ em 7-12 tuổi(<span
                                                                                    class="text-danger">*</span>)</p>
                                                                        </label>
                                                                        <input type="text"
                                                                               name="num_children_7_12"
                                                                               class="form-control" placeholder=""/>
                                                                    </div>
                                                                    <input type="hidden" name="num_children" value="0">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 form-group booking_rooms">
                                                                <label for="hotel">
                                                                    <p class="fs18">Tổng giá các phòng</p>
                                                                </label>
                                                                <input name="room_total_price" type="text"
                                                                       class="form-control "
                                                                       style="background-color: lightgrey "
                                                                       placeholder=""
                                                                       readonly="readonly"/>
                                                            </div>

                                                            <div class="booking_rooms">
                                                                <input type="hidden" name="num_people">
                                                                <p id="error_booking_rooms_0_num_people"
                                                                   class="error-message"></p>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <a href="javascript:;" data-repeater-delete=""
                                                                   class="btn btn-sm font-weight-bolder btn-light-danger">
                                                                    <i class="la la-trash-o"></i>Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div>
                                                        <span id="error-choose-room"></span>
                                                    </div>
                                                    <div class="">
                                                        <a href="javascript:;" data-repeater-create=""
                                                           class="btn btn-sm font-weight-bolder btn-light-primary"
                                                           onclick="addHotelRoomV2('#list-hotel-item', false)">
                                                            <i class="la la-plus"></i>Thêm
                                                            hạng phòng
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion accordion-solid accordion-panel accordion-svg-toggle" id="accordionExample8">
                    <div class="card">
                        <div class="card-header" id="headingOne8">
                            <div class="card-title" data-toggle="collapse" data-target="#collapseTow8">
                                <div class="card-label">Phụ thu</div>
                                <span class="svg-icon">
                             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                  width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                               <polygon points="0 0 24 0 24 24 0 24"></polygon>
                               <path
                                   d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                   fill="#000000" fill-rule="nonzero"></path>
                               <path
                                   d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                   fill="#000000" fill-rule="nonzero" opacity="0.3"
                                   transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                              </g>
                             </svg>
                            </span>
                            </div>
                        </div>
                        <div id="collapseTow8" class="collapse show" data-parent="#accordionExample8">
                            <div class="card-body pl-12">
                                <div id="hotel-list-surcharges">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion accordion-solid accordion-panel accordion-svg-toggle" id="accordionExample8">
                    <div class="card">
                        <div class="card-header" id="headingOne8">
                            <div class="card-title" data-toggle="collapse" data-target="#collapseOne8">
                                <div class="card-label">Bổ sung thông tin</div>
                                <span class="svg-icon">
                             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                  width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                               <polygon points="0 0 24 0 24 24 0 24"></polygon>
                               <path
                                   d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                   fill="#000000" fill-rule="nonzero"></path>
                               <path
                                   d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                   fill="#000000" fill-rule="nonzero" opacity="0.3"
                                   transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                              </g>
                             </svg>
                            </span>
                            </div>
                        </div>
                        <div id="collapseOne8" class="collapse show" data-parent="#accordionExample8">
                            <div class="card-body">
                                <div class="card-body pl-12">
                                    <div class="row">
                                        <div class="col-sm-6 ">
                                            <div class="form-group">
                                                <label for="full_name">
                                                    <p class="fs18">Họ tên Khách hàng(<span class="text-danger">*</span>)
                                                    </p>
                                                </label>
                                                <input name="full_name" id="full_name" type="text" class="form-control"
                                                       placeholder="Họ và Tên"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="example-tel-input">
                                                    <p class="fs18">Số điện thoại(<span class="text-danger">*</span>)
                                                    </p>
                                                </label>
                                                <input class="form-control" type="tel" name="phone"
                                                       placeholder="(+84)999-999-999"
                                                       id="example-tel-input"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="other">
                                                    <p class="fs18">Yếu cầu thêm</p>
                                                </label>
                                                <textarea class="form-control" name="other" id="other"
                                                          rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" id="email" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="hotel-code">Mã Booking khánh sạn</label>
                                                <input type="text" name="hotel-code" id="hotel-code"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="information">
                                                    <p class="fs18">Danh sách đoàn</p>
                                                </label>
                                                <textarea class="form-control" name="information" id="information"
                                                          rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="note">
                                                    <p class="fs18">Lưu ý cho khách sạn</p>
                                                </label>
                                                <textarea class="form-control" name="note" id="note"
                                                          rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="note-agency">
                                                    <p class="fs18">Lưu ý gửi đại lý</p>
                                                </label>
                                                <textarea class="form-control" name="note-agency" id="note-agency"
                                                          rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="paymentDeadline">Hạn ngày thanh toán</label>
                                                <div class="input-group date">
                                                    <input type="text"
                                                           class="form-control kt_datepicker_3"
                                                           name="paymentDeadline"
                                                           value="<?= isset($booking->payment_deadline) ? $booking->payment_deadline->format('d/m/Y') : date('d/m/Y') ?>"
                                                           id="paymentDeadline"/>
                                                    <div class="input-group-append">
                                                                   <span class="input-group-text">
                                                                    <i class="la la-calendar"></i>
                                                                    </span>
                                                    </div>
                                                    <input type="hidden" name="payment_deadline"
                                                           value="<?= date('d/m/Y') ?>">
                                                    <input type="hidden" name="pay_hotel_text" value=" ">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="customer_deposit" value="0">
                                        <input type="hidden" name="agency_pay" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card card-custom mb-10">
                    <div class="card-header">
                        <h3 class="card-title text-primary">
                            Chi phí
                        </h3>
                    </div>
                    <!--begin::Form-->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="price">
                                <p class="fs18">Giá vốn</p>
                            </label>
                            <input type="text" name="price" readonly="readonly"
                                   class="form-control " style="background-color: lightgrey"
                                   id="price">
                        </div>
                        <div class="form-group">
                            <label for="hotel">
                                <p class="fs18">Giá bán</p>
                            </label>
                            <input type="text" name="room_single_price" class="form-control "
                                   id="booking-rooms-0-room-single-price" value="">
                        </div>
                        <div class="form-group">
                            <label for="hotel">
                                <p class="fs18">Lợi nhuận</p>
                            </label>
                            <input type="text" name="room_single_price" readonly="readonly"
                                   class="form-control "
                                   id="booking-rooms-0-room-single-price">
                        </div>
                    </div>
                </div>
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title text-primary">
                            đại lý thanh toán
                        </h3>
                    </div>
                    <!--begin::Form-->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="payment_type">
                                <p class="fs18">Phương thúc thanh toán</p>
                            </label>
                            <select name="payment_type" class="form-control iCheck payment-check" id="payment_type">
                                <option value="1">Chuyển khoản nhân hàng</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="radio-inline">
                                <label class="radio radio-square">
                                    <input type="radio" class="iCheck invoice-check" name="payment_invoice" value="0" onclick="invoiceCheck(0, 'info_payment_invoice')"
                                           data-field-id="no-invoice" style="position: absolute; opacity: 0;">
                                    <span></span>
                                    Không xuất hóa đơn
                                </label>
                                <label class="radio radio-square">
                                    <input type="radio" class="iCheck invoice-check" name="payment_invoice" value="1" onclick="invoiceCheck(1, 'info_payment_invoice')"
                                           data-field-id="has-invoice" style="position: absolute; opacity: 0;">
                                    <span></span>
                                    Xuất hóa đơn VAT
                                </label>
                            </div>
                            <div class="ml15 mr15 mb15 mt15 d-none" id="info_payment_invoice">
                                <p class="fs14 mb10">Quý khách vui lòng chuyển khoản vào tài khoản dưới đây và điền địa
                                    chỉ thông tin chi tiết để mustgo xuất và gửi hóa đơn thanh toán</p>
                                <p class="error-messages" id="error_invoice_information"></p>
                                <div class="">
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Thông tin xuất hóa đơn..."
                                                  name="payment_invoice_information" rows="5"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="deligate-payment">
                                <p class="">Ảnh hóa đơn thanh toán</p>
                                <p class="error-messages" id="error_images"></p>
                                <div class=" text-center">
                                    <div id="dropzone-upload" class="dropzone">
                                    </div>
                                    <input type="hidden" name="media"
                                           value='<?= $list_images ?>'/>
                                    <input type="hidden" name="list_image"
                                           value='<?= $list_images ?>'/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title text-primary">
                            Thanh toán cho Khách sạn / Đối tác
                        </h3>
                    </div>
                    <!--begin::Form-->
                    <div class="card-body">
                        <div class="form-group">
                            <label>Chọn đối tượng thanh toán</label>
                            <div class="radio-inline">
                                <label class="radio radio-square">
                                    <input type="radio" class="iCheck payment-for-hotel"  name="pay_object" value="0" onclick="invoiceCheck(0, 'pay_object')"
                                           data-field-id="pay-for-hotel" style="position: absolute; opacity: 0;">
                                    <span></span>
                                    Thanh toán cho khách sạn
                                </label>
                                <label class="radio radio-square">
                                    <input type="radio" class="iCheck payment-for-hotel"  name="pay_object" value="1" onclick="invoiceCheck(1, 'pay_object')"
                                           data-field-id="pay-for-hotel" style="position: absolute; opacity: 0;">
                                    <span></span>
                                    Thanh toán cho đối tác
                                </label>
                            </div>
                        </div>
                        <div class="d-none" id="pay_object_0">
                            <div class="form-group">
                                <label>Chọn đối tượng thanh toán</label>
                                <div class="radio-inline">
                                    <label class="radio radio-square">
                                        <input type="radio" class="iCheck payment-for-hotel" name="check_type" value="1"
                                               data-field-id="pay-for-hotel" style="position: absolute; opacity: 0;">
                                        <span></span>
                                        Không xuất hóa đơn
                                    </label>
                                    <label class="radio radio-square">
                                        <input type="radio" class="iCheck payment-for-hotel" name="check_type" value="2"
                                               data-field-id="pay-for-hotel" style="position: absolute; opacity: 0;">
                                        <span></span>
                                        Xuất hóa đơn VAT
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-none" id="pay_object_1">
                            <div class="form-group">
                                <label for="partner_name">Tên tài khoản(<span class="text-danger">*</span>)</label>
                                <input type="text" class="form-control" name="partner_name" id="partner_name">
                            </div>
                            <div class="form-group">
                                <label for="partner_number">Số tài khoản(<span class="text-danger">*</span>)</label>
                                <input type="text" class="form-control" name="partner_number" id="partner_number">
                            </div>
                            <div class="form-group">
                                <label for="partner_bank">Chi nhánh ngân hàng(<span
                                        class="text-danger">*</span>)</label>
                                <input type="text" class="form-control" name="partner_bank" id="partner_bank">
                            </div>
                            <div class="form-group">
                                <label for="partner_email">Email(<span class="text-danger">*</span>)</label>
                                <input type="text" class="form-control" name="partner_email" id="partner_email">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button style="position: absolute; top: 16%; right: 2%;" type="button" class="btn btn-success" onclick="checkVadidateV2(this)"
                     data-role="sale" data-title="2"
                    data-id="<?= isset($booking) ? $booking->id : "" ?>" data-code="<?= isset($booking) ? $booking->code : "" ?>">Lưu</button>
        </div>
    </div>

    <?= $this->Form->end() ?>
</div>




<?php $this->start('scriptBottom'); ?>

<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
<script src="assets/plugins/global/plugins.bundle.js"></script>

<script type="text/javascript">
    "use strict";
    // Class definition
    var KTBootstrapDatepicker = function () {
        var arrows;
        if (KTUtil.isRTL()) {
            arrows = {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            }
        } else {
            arrows = {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }
        // Private functions
        var datePick = function () {
            // enable clear button
            $('.kt_datepicker_3').datepicker({
                rtl: KTUtil.isRTL(),
                todayBtn: "linked",
                format: 'dd/mm/yyyy',
                clearBtn: true,
                todayHighlight: true,
                templates: arrows
            });
        }
        return {
            // public functions
            init: function () {
                datePick();
            }
        };
    }();
    jQuery(document).ready(function () {
        KTBootstrapDatepicker.init();
    });
    // Class definition

    var KTBootstrapTimepicker = function () {

        // Private functions
        var time = function () {
            // minimum setup
            $('#kt_timepicker_2, #kt_timepicker_2_modal').timepicker({
                minuteStep: 1,
                defaultTime: '',
                showSeconds: true,
                showMeridian: false,
                snapToStep: true
            });
        }
        return {
            // public functions
            init: function () {
                time();
            }
        };
    }();
    jQuery(document).ready(function () {
        KTBootstrapTimepicker.init();
    });

    var KTFormRepeater = function () {
        var arrows;
        if (KTUtil.isRTL()) {
            arrows = {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            }
        } else {
            arrows = {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }
        // Private functions
        var room_id = function () {
            $('#kt_repeater_1').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                    $('.kt_datepicker_3').datepicker({
                        rtl: KTUtil.isRTL(),
                        todayBtn: "linked",
                        clearBtn: true,
                        todayHighlight: true,
                        templates: arrows
                    });
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }
        return {
            // public functions
            init: function () {
                // enable clear button
                room_id();
            }
        };
    }();
    jQuery(document).ready(function () {
        KTFormRepeater.init();
    });


    var formatNumber = Intl.NumberFormat('en-US');
    // var KTDatatableRemoteAjaxDemo = function () {
    //     // Private functions
    //
    //     // basic demo
    //     var booking = function () {
    //
    //         var datatable = $('#kt_datatable').KTDatatable({
    //             // datasource definition
    //             data: {
    //                 type: 'remote',
    //                 source: {
    //                     read: {
    //                         url: baseUrl + 'sale/bookings/index-booking-datatable',
    //                         // sample custom headers
    //                         headers: {'X-CSRF-TOKEN': csrfToken},
    //                         map: function (raw) {
    //                             // sample data mapping
    //                             var dataSet = raw;
    //                             if (typeof raw.data !== 'undefined') {
    //                                 dataSet = raw.data;
    //                             }
    //                             console.log(dataSet);
    //                             return dataSet;
    //                         },
    //                     },
    //                 },
    //                 pageSize: 10,
    //                 serverPaging: true,
    //                 serverFiltering: true,
    //                 serverSorting: true,
    //             },
    //
    //             // layout definition
    //             layout: {
    //                 scroll: true,
    //                 footer: false,
    //             },
    //
    //             // column sorting
    //             sortable: true,
    //
    //             pagination: true,
    //
    //             search: {
    //                 input: $('#kt_datatable_search_query'),
    //                 key: 'generalSearch'
    //             },
    //
    //             // columns definition
    //             columns: [
    //                 {
    //                     field: 'agency',
    //                     title: 'Đại lý',
    //                     width: 110,
    //                     autoHide: false,
    //                     template: function (row) {
    //                         return row.user.screen_name;
    //                     },
    //                 }, {
    //                     field: 'created',
    //                     title: 'Ngày tạo',
    //                     autoHide: false,
    //                     width: 80,
    //                 }, {
    //                     field: 'code',
    //                     title: 'Mã Booking',
    //                     autoHide: false,
    //                     width: 90,
    //                 }, {
    //                     field: 'hotel_code',
    //                     title: 'Mã phòng',
    //                     autoHide: false,
    //                     width: 90,
    //                 },
    //                 {
    //                     field: 'location',
    //                     title: 'Địa phương',
    //                     width: 100,
    //                     autoHide: false,
    //                     template: function (row) {
    //                         return row.hotels.location.name;
    //                     },
    //                 },
    //                 {
    //                     field: 'type',
    //                     title: 'Loại hình',
    //                     width: 70,
    //                     autoHide: false,
    //                     // callback function support for column rendering
    //                     template: function (row) {
    //                         var types = {
    //                             4: {
    //                                 'title': 'Khách sạn',
    //                             },
    //                             2: {
    //                                 'title': 'Voucher',
    //                             },
    //                             3: {
    //                                 'title': 'Landtour'
    //                             },
    //                             5: {
    //                                 'title': 'Homestay',
    //                             },
    //                         };
    //                         return types[row.type].title;
    //                     },
    //                 }, {
    //                     field: 'type_name',
    //                     title: 'Tên Loại hình',
    //                     autoHide: false,
    //                     template: function (row) {
    //                         return row.hotels.name;
    //                     },
    //                 }, {
    //                     field: 'full_name',
    //                     title: 'Khách hàng',
    //                     autoHide: false,
    //                 }, {
    //                     field: 'check_in_out',
    //                     title: 'Check In/Out',
    //                     width: 80,
    //                     autoHide: false,
    //                     template: function (row) {
    //                         var sDate = new Date(row.start_date);
    //                         var eDate = new Date(row.end_date);
    //                         return sDate.getDate() + "/" + sDate.getMonth() + "/" + sDate.getFullYear() + "<br>" + eDate.getDate() + "/" + eDate.getMonth() + "/" + eDate.getFullYear();
    //                     },
    //                 }, {
    //                     field: 'priceall',
    //                     title: 'Giá Vốn <br> Giá Bán',
    //                     width: 80,
    //                     autoHide: false,
    //                     template: function (row) {
    //                         var originP = row.price - row.sale_revenue - row.revenue;
    //                         var saleP = row.price - row.revenue;
    //                         return formatNumber.format(originP) + "<br>" + formatNumber.format(saleP);
    //                     },
    //                 }, {
    //                     field: 'revenue',
    //                     title: 'Lợi nhuận',
    //                     width: 80,
    //                     autoHide: false,
    //                     template: function (row) {
    //                         return formatNumber.format(row.revenue);
    //                     },
    //                 }, {
    //                     field: 'status',
    //                     title: 'Trạng thái',
    //                     width: 140,
    //                     autoHide: false,
    //                     // callback function support for column rendering
    //                     template: function (row) {
    //                         return '<span class="label font-weight-bold label-lg ' + row.statuscls + ' label-inline">' + row.statustr + '</span>';
    //                     },
    //                 }, {
    //                     field: 'Actions',
    //                     title: 'Actions',
    //                     sortable: false,
    //                     width: 100,
    //                     overflow: 'visible',
    //                     autoHide: false,
    //                     template: function (row) {
    //                         var btnText1 = "";
    //                         var btnid = "";
    //                         // var btnText2 = "";
    //                         if (row.status != -1 && row.loginID == 2 ){
    //                             btnText1 = "Gửi mail đặt phòng KS";
    //                             btnid = 22;
    //                             if (row.status >= 1){
    //                                 btnText1 = "Gửi mail thanh toán và mail xác nhận đặt phòng";
    //                                 btnid = 21;
    //                             }
    //                         } else if (row.status < 3 && row.loginID == 5) {
    //                             btnText1 = "Gửi mail đặt Landtour và thanh toán";
    //                             btnid = 22;
    //                         }
    //                         var sl = 'd-none'
    //                         if(row.sale_id == 0) { var sl = '' }
    //                         console.log(btnText1, btnid);
    //
    //                         return '\
    //                     <div class="dropdown dropdown-inline">\
    //                         <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
    //                             <span class="svg-icon svg-icon-md">\
    //                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
    //                                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
    //                                         <rect x="0" y="0" width="24" height="24"/>\
    //                                         <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>\
    //                                     </g>\
    //                                 </svg>\
    //                             </span>\
    //                         </a>\
    //                         <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
    //                             <ul class="navi flex-column navi-hover py-2">\
    //                                 <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
    //                                     Choose an action:\
    //                                 </li>\
    //                                 <li>\
    //                                 <a href="#">\
    //                                     <button href="javascript:;"  type="button" class=" navi-text btn btn-xs btn-warning '+ sl +'" onclick="getBooking(this, '+ row.id +')"> <i class="fa fa-spin fa-spinner d-none"></i>&nbsp; >\
    //                                         <i class="fa fa-envelope"></i>\
    //                                         <b> LẤY BOOKING NÀY</b>\
    //                                     </button>\
    //                                 </a>\
    //                                 </li>\
    //                                 <li class="navi-item">\
    //                                     <a href="javascript:;" class="navi-link "   ="sendEmailV2(this, '+ row.id +' ,'+ btnid +')">\
    //                                         <span class="navi-text btn btn-xs btn-success">'+ btnText1 +'</span>\
    //                                     </a>\
    //                                 </li>\
    //                                 <li class="navi-item">\
    //                                     <a href="/trippal/sale/bookings/view/' + row.id + '" class="navi-link ">\
    //                                         <span class="navi-text btn btn-xs btn-primary">View</span>\
    //                                     </a>\
    //                                 </li>\
    //                                 <li class="navi-item">\
    //                                     <a href="/trippal/sale/bookings/edit/' + row.id + '" class="navi-link ">\
    //                                         <span class="navi-text btn btn-xs btn-warning">Sửa</span>\
    //                                     </a>\
    //                                 </li>\
    //                                 <li class="navi-item">\
    //                                     <a href="javascript:;" id="kt_delete_row" class="navi-link kt_delete_row delete-detail">\
    //                                         <span class="navi-text btn btn-xs btn-warning">Xóa</span>\
    //                                     </a>\
    //                                 </li>\
    //                             </ul>\
    //                         </div>\
    //                     </div>\
    //                 ';
    //                     },
    //                 }],
    //             autoColumns: true,
    //             autoHide: false,
    //
    //         });
    //
    //         console.log('tesst js');
    //         // $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
    //
    //         $('#kt_datatable_search_status').on('change', function () {
    //             datatable.search($(this).val().toLowerCase(), 'statusStr');
    //         });
    //
    //         $('#kt_datatable_search_type').on('change', function () {
    //             datatable.search($(this).val().toLowerCase(), 'Type');
    //         });
    //         $('#kt_datatable tbody').on('click', '#kt_delete_row',  function (e) {
    //             // $('#kt_datatable').KTDatatable().row($(this).delete());
    //             console.log('test button remote');
    //             $('.delete-detail')
    //                 .off()
    //                 .each(function () {
    //                     let delButton = $(this);
    //                     delButton.on('click', function (){
    //                         console.log('test remote');
    //                         Swal.fire({
    //                             title: 'Are you sure?',
    //                             text: "This can't be returned.",
    //                             type: 'warning',
    //                             showCancelButton: true,
    //                             confirmButtonColor: '#3085d6',
    //                             cancelButtonColor: '#d33',
    //                             confirmButtonText: 'Yeah i am sure!'
    //                         }).then((result) => {
    //                             if (result.value) {
    //                                 $.ajax({
    //                                     url: delButton.data('url'),
    //                                     dataType: 'json',
    //                                     type: 'POST',
    //                                     success: function () {
    //                                         table.row(delButton.parents('tr')).remove().draw();
    //                                     },
    //                                 });
    //                             }
    //                         });
    //                     });
    //                 });
    //         });
    //
    //
    //
    //     };
    //
    //     return {
    //         // public functions
    //         init: function () {
    //             booking();
    //         },
    //     };
    // }();

    // jQuery(document).ready(function () {
    //     KTDatatableRemoteAjaxDemo.init();
    // });
</script>

<?php $this->end() ?>
