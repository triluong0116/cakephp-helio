<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<div>
    <?= $this->Form->create($booking, ['class' => '', 'data-parsley-validate', 'id' => 'form-booking-system', 'type' => 'file']) ?>
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
                            <select class="form-control select2" name="user_id" id="user_id" default>
                                <option value="">Chọn cộng tác viên</option>
                                <option value="12">Khách lẻ</option>
                                <?php foreach ($users as $user): ?>
                                    <?php if( $user->id == $booking->user_id ) : ?>
                                        <option value="<?= $user->id ?>" selected="selected"
                                                data-name="<?= $user->screen_name ?>"
                                                data-phone="<?= isset($user->phone) ? $user->phone : '' ?>"
                                                data-email="<?= isset($user->email) ? $user->email : '' ?>">
                                            <?= $user->screen_name ?>
                                        </option>
                                    <?php else: ?>
                                        <option value="<?= $user->id ?>"
                                                data-name="<?= $user->screen_name ?>"
                                                data-phone="<?= isset($user->phone) ? $user->phone : '' ?>"
                                                data-email="<?= isset($user->email) ? $user->email : '' ?>">
                                            <?= $user->screen_name ?>
                                        </option>
                                    <?php endif; ?>
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
                                    <?php if( $hotel->id == $booking->item_id ) : ?>
                                        <option value="<?= $hotel->id ?>" selected="selected" ><?= $hotel->name ?></option>
                                    <?php else: ?>
                                        <option value="<?= $hotel->id ?>"><?= $hotel->name ?></option>
                                    <?php endif; ?>
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
                                                        <?php foreach ($rooms as $key => $room): ?>
                                                            <div data-repeater-item class="form-group row align-items-center">
                                                                <div class="col-sm-6 form-group booking_rooms">
                                                                    <label for="">
                                                                        <p class="fs18">Chọn hạng phòng(<span
                                                                                class="text-danger">*</span>)</p>
                                                                    </label>
                                                                    <select class="form-control option-room"
                                                                            name="booking_rooms[<?= $key ?>][room_id]"
                                                                            id="room_id"
                                                                            tabindex="-1"
                                                                            aria-hidden="true">
                                                                        <option value="0">Chọn hạng phòng</option>
                                                                        <?php foreach ($list_room as $r): ?>
                                                                            <?php  if ($r->id == $room->room_id): ?>
                                                                                <option value="<?= $r->id ?>" selected="selected"><?= $r->name ?></option>
                                                                            <?php else: ?>
                                                                                <option value="<?= $r->id ?>"><?= $r->name ?></option>
                                                                            <?php endif ?>
                                                                        <?php endforeach; ?>
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
                                                                                   name="booking_rooms[<?= $key ?>][start_date]"
                                                                                   readonly value="<?= date_format($room->start_date, 'd/m/Y') ?>"
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
                                                                    <input name="booking_rooms[<?= $key ?>][num_room]"
                                                                           id="num_room" type="text"
                                                                           class="form-control" value="<?= $room->num_room ?>"
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
                                                                                   name="booking_rooms[<?= $key ?>][end_date]"
                                                                                   readonly value="<?= date_format($room->end_date, 'd/m/Y') ?>"
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
                                                                    <input name="booking_rooms[<?= $key ?>][num_adult]"
                                                                           id="num_adult" type="text"
                                                                           class="form-control" value="<?= $room->num_adult ?>"
                                                                           placeholder=""/>
                                                                    <span class="error_num_adult"></span>
                                                                </div>

                                                                <div class="col-sm-6 form-group booking_rooms">
                                                                    <label for="hotel">
                                                                        <p class="fs18">Giá phòng</p>
                                                                    </label>
                                                                    <input name="booking_rooms[<?= $key ?>][room_single_price]" type="text"
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
                                                                                   name="booking_rooms[<?= $key ?>][num_children_0_6]"
                                                                                   class="form-control" placeholder=""/>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>
                                                                                <p class="fs18">trẻ em 7-12 tuổi(<span
                                                                                        class="text-danger">*</span>)</p>
                                                                            </label>
                                                                            <input type="text"
                                                                                   name="booking_rooms[<?= $key ?>][num_children_7_12]" value="<?= $room->num_children ?>"
                                                                                   class="form-control" placeholder=""/>
                                                                        </div>
                                                                        <input type="hidden" name="booking_rooms[<?= $key ?>][num_children]" value="0">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 form-group booking_rooms">
                                                                    <label for="hotel">
                                                                        <p class="fs18">Tổng giá các phòng</p>
                                                                    </label>
                                                                    <input name="booking_rooms[<?= $key ?>][room_total_price]" type="text"
                                                                           class="form-control "
                                                                           style="background-color: lightgrey "
                                                                           placeholder=""
                                                                           readonly="readonly"/>
                                                                </div>

                                                                <div class="booking_rooms">
                                                                    <input type="hidden" name="booking_rooms[<?= $key ?>][num_people]">
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
                                                        <?php endforeach; ?>
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
                                                       placeholder="Họ và Tên" value="<?= $booking->full_name ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="example-tel-input">
                                                    <p class="fs18">Số điện thoại(<span class="text-danger">*</span>)
                                                    </p>
                                                </label>
                                                <input class="form-control" type="tel" name="phone"
                                                       placeholder="(+84)999-999-999" value="<?= $booking->phone ?>"
                                                       id="example-tel-input"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="other">
                                                    <p class="fs18">Yếu cầu thêm</p>
                                                </label>
                                                <textarea class="form-control" name="other" id="other"
                                                          rows="5"><?= $booking->other ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" id="email" class="form-control" value="<?= $booking->email ?>" >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="hotel_code">Mã Booking khánh sạn</label>
                                                <input type="text" name="hotel_code" id="hotel_code" value="<?= $booking->hotel_code ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="information">
                                                    <p class="fs18">Danh sách đoàn</p>
                                                </label>
                                                <textarea class="form-control" name="information" id="information"
                                                          rows="5"><?= $booking->information ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="note">
                                                    <p class="fs18">Lưu ý cho khách sạn</p>
                                                </label>
                                                <textarea class="form-control" name="note" id="note" value="<?= $booking->note ?>"
                                                          rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="note_agency">
                                                    <p class="fs18">Lưu ý gửi đại lý</p>
                                                </label>
                                                <textarea class="form-control" name="note_agency" id="note_agency"
                                                          rows="5"><?= $booking->note_agency ?></textarea>
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
                                        <input type="hidden" name="agency_pay" value="<?= $booking->agency_pay ?>">
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
                                   id="price" value="<?= number_format($booking->price - $booking->revenue) ?>">
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
                                           value='<?=  !isset($list_images) ? $list_images : "" ?>'/>
                                    <input type="hidden" name="list_image"
                                           value='<?= !isset($list_images) ? $list_images : "" ?>'/>
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

    $('body').ready( function (){
        var data = "<input type='text' value='" +<?= $booking->item_id ?>+"' data-booking-id='"+ <?= $booking->id ?> +"' >";
        //data.hotel_id = <?//= $booking->item_id ?>
        //data.booking_id = <?//= $booking->id ?>
        bookingChangeHotelV2(data);
    })
</script>

<?php $this->end() ?>
