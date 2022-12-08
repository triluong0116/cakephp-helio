<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="card card-custom mb-5 center">
    <div class="card-header border-0 pt-6 pb-0">
        <div class="card-title">
            <div class="mr-auto">
                <div class="row">
                    <div>
                        <button type="button" class="btn btn-primary">
                            <h3>MUSTGO</h3>
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default">
                            <h3>VINPEARL</h3>
                        </button>
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
            <form action="">
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="ctv">
                            <h5 class="">Chọn cộng tác viên (<span class="text-danger">*</span>)</h5>
                        </label>
                        <select class="form-control select2" name="ctv" id="ctv">
                            <option value="">Chọn cộng tác viên</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user->id ?>"><?= $user->screen_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="hotel">
                            <h5 class="fs18">Dánh sách khánh sạn(<span class="text-danger">*</span>)</h5>
                        </label>
                        <select class="form-control select2" name="hotel" id="hotel">
                            <option value="">Chọn khách sạn</option>
                            <?php foreach ($hotels as $hotel): ?>
                                <option value="<?= $hotel->id ?>"><?= $hotel->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            Textual HTML5 Inputs
        </h3>
    </div>
    <!--begin::Form-->
    <form>
        <div class="card-body">
            <div class="form-group mb-8">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Here are examples of <code>.form-control</code> applied to each textual HTML5 input type:
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label">Text</label>
                <div class="col-10">
                    <input class="form-control" type="text" value="Artisanal kale" id="example-text-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-search-input" class="col-2 col-form-label">Search</label>
                <div class="col-10">
                    <input class="form-control" type="search" value="How do I shoot web" id="example-search-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Email</label>
                <div class="col-10">
                    <input class="form-control" type="email" value="bootstrap@example.com" id="example-email-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-url-input" class="col-2 col-form-label">URL</label>
                <div class="col-10">
                    <input class="form-control" type="url" value="https://getbootstrap.com" id="example-url-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-tel-input" class="col-2 col-form-label">Telephone</label>
                <div class="col-10">
                    <input class="form-control" type="tel" value="1-(555)-555-5555" id="example-tel-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-password-input" class="col-2 col-form-label">Password</label>
                <div class="col-10">
                    <input class="form-control" type="password" value="hunter2" id="example-password-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-number-input" class="col-2 col-form-label">Number</label>
                <div class="col-10">
                    <input class="form-control" type="number" value="42" id="example-number-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-datetime-local-input" class="col-2 col-form-label">Date and time</label>
                <div class="col-10">
                    <input class="form-control" type="datetime-local" value="2011-08-19T13:45:00"
                           id="example-datetime-local-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-date-input" class="col-2 col-form-label">Date</label>
                <div class="col-10">
                    <input class="form-control" type="date" value="2011-08-19" id="example-date-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-month-input" class="col-2 col-form-label">Month</label>
                <div class="col-10">
                    <input class="form-control" type="month" value="2011-08" id="example-month-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-week-input" class="col-2 col-form-label">Week</label>
                <div class="col-10">
                    <input class="form-control" type="week" value="2011-W33" id="example-week-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-time-input" class="col-2 col-form-label">Time</label>
                <div class="col-10">
                    <input class="form-control" type="time" value="13:45:00" id="example-time-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-color-input" class="col-2 col-form-label">Color</label>
                <div class="col-10">
                    <input class="form-control" type="color" value="#563d7c" id="example-color-input"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Range</label>
                <div class="col-10">
                    <input class="form-control" type="range"/>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-2">
                </div>
                <div class="col-10">
                    <button type="reset" class="btn btn-success mr-2">Submit</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $this->start('scriptBottom'); ?>
<script type="text/javascript">
    "use strict";
    // Class definition

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
