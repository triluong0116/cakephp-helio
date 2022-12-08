<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Danh sách booking Vinpearl
                <span class="d-block text-muted pt-2 font-size-sm">Chết độ hiển thị dành cho sale</span></h3>
        </div>
        <div class="card-toolbar">
        </div>
    </div>
    <div class="card-body">
        <!--begin: Search Form-->
        <!--begin::Search Form-->
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-lg-9 col-xl-8">
                    <div class="row align-items-center">
                        <div class="col-md-4 my-2 my-md-0">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Search..."
                                       id="kt_datatable_search_query"/>
                                <span><i class="flaticon2-search-1 text-muted"></i></span>
                            </div>
                        </div>
                        <div class="col-md-4 my-2 my-md-0">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0">Status:</label>
                                <select class="form-control" id="kt_datatable_search_status">
                                    <option value="">All</option>
                                    <option value="1">Pending</option>
                                    <option value="2">Delivered</option>
                                    <option value="3">Canceled</option>
                                    <option value="4">Success</option>
                                    <option value="5">Info</option>
                                    <option value="6">Danger</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 my-2 my-md-0">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0">Type:</label>
                                <select class="form-control" id="kt_datatable_search_type">
                                    <option value="">All</option>
                                    <option value="1">Online</option>
                                    <option value="2">Retail</option>
                                    <option value="3">Direct</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                    <a href="#" class="btn btn-light-primary px-6 font-weight-bold">Search</a>
                </div>
            </div>
        </div>
        <!--end::Search Form-->
        <!--end: Search Form-->
        <!--begin: Datatable-->
        <div class="datatable datatable-bordered datatable-head-custom dataTables_wrapper display nowrap"
             id="kt_datatable" style="width: 100%"></div>
        <!--end: Datatable-->
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script type="text/javascript">
    "use strict";
    // Class definition

    var formatNumber = Intl.NumberFormat('en-US');
    var KTDatatableRemoteAjaxDemo = function () {
        // Private functions

        // basic demo
        var booking = function () {

            var datatable = $('#kt_datatable').KTDatatable({
                // datasource definition
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: baseUrl + 'sale/bookings/index-booking-vin-datatable',
                            // sample custom headers
                            headers: {'X-CSRF-TOKEN': csrfToken},
                            map: function (raw) {
                                // sample data mapping
                                var dataSet = raw;
                                if (typeof raw.data !== 'undefined') {
                                    dataSet = raw.data;
                                }
                                console.log(dataSet);
                                return dataSet;
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    footer: false,
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#kt_datatable_search_query'),
                    key: 'generalSearch'
                },

                // columns definition
                columns: [
                    {
                        field: 'agency',
                        title: 'Đại lý',
                        width: 110,
                        autoHide: false,
                        template: function (row) {
                            return row.user.screen_name;
                        },
                    }, {
                        field: 'created',
                        title: 'Ngày tạo',
                        autoHide: false,
                        width: 80,
                    }, {
                        field: 'code',
                        title: 'Mã Booking',
                        autoHide: false,
                        width: 90,
                    },
                    // {
                    //     field: 'hotel_code',
                    //     title: 'Mã phòng',
                    //     autoHide: false,
                    //     width: 90,
                    // },
                    {
                        field: 'location',
                        title: 'Địa phương',
                        width: 100,
                        autoHide: false,
                        template: function (row) {
                            return row.hotel.location.name;
                        },
                    },
                    // {
                    //     field: 'type',
                    //     title: 'Loại hình',
                    //     width: 70,
                    //     autoHide: false,
                    //     // callback function support for column rendering
                    //     template: function (row) {
                    //         var types = {
                    //             4: {
                    //                 'title': 'Khách sạn',
                    //             },
                    //             2: {
                    //                 'title': 'Voucher',
                    //             },
                    //             3: {
                    //                 'title': 'Landtour'
                    //             },
                    //             5: {
                    //                 'title': 'Homestay',
                    //             },
                    //         };
                    //         return types[row.type].title;
                    //     },
                    // },
                {
                        field: 'type_name',
                        title: 'Tên Loại hình',
                        autoHide: false,
                        template: function (row) {
                            return row.hotel.name;
                        },
                    }, {
                        field: 'full_name',
                        title: 'Khách hàng',
                        autoHide: false,
                    }, {
                        field: 'check_in_out',
                        title: 'Check In/Out',
                        width: 80,
                        autoHide: false,
                        template: function (row) {
                            var sDate = new Date(row.start_date);
                            var eDate = new Date(row.end_date);
                            return sDate.getDate() + "/" + sDate.getMonth() + "/" + sDate.getFullYear() + "<br>" + eDate.getDate() + "/" + eDate.getMonth() + "/" + eDate.getFullYear();
                        },
                    }, {
                        field: 'priceall',
                        title: 'Giá Vốn <br> Giá Bán',
                        width: 80,
                        autoHide: false,
                        template: function (row) {
                            var originP = row.price - row.sale_revenue - row.revenue;
                            var saleP = row.price - row.revenue;
                            return formatNumber.format(originP) + "<br>" + formatNumber.format(saleP);
                        },
                    }, {
                        field: 'revenue',
                        title: 'Lợi nhuận',
                        width: 80,
                        autoHide: false,
                        template: function (row) {
                            return formatNumber.format(row.revenue);
                        },
                    }, {
                        field: 'status',
                        title: 'Trạng thái',
                        width: 140,
                        autoHide: false,
                        // callback function support for column rendering
                        template: function (row) {
                            return '<span class="label font-weight-bold label-lg ' + row.statuscls + ' label-inline">' + row.statustr + '</span>';
                        },
                    }, {
                        field: 'Actions',
                        title: 'Actions',
                        sortable: false,
                        width: 100,
                        overflow: 'visible',
                        autoHide: false,
                        template: function (row) {
                            var btnText1 = "";
                            var btnid = "";
                            // var btnText2 = "";
                            if (row.status != -1 && row.loginID == 2 ){
                                btnText1 = "Gửi mail đặt phòng KS";
                                btnid = 22;
                                if (row.status >= 1){
                                    btnText1 = "Gửi mail thanh toán và mail xác nhận đặt phòng";
                                    btnid = 21;
                                }
                            } else if (row.status < 3 && row.loginID == 5) {
                                btnText1 = "Gửi mail đặt Landtour và thanh toán";
                                btnid = 22;
                            }
                            var sl = 'd-none'
                            if(row.sale_id == 0) { var sl = '' }
                            console.log(btnText1, btnid);

                            return '\
                        <div class="dropdown dropdown-inline">\
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
                                <span class="svg-icon svg-icon-md">\
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                            <rect x="0" y="0" width="24" height="24"/>\
                                            <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>\
                                        </g>\
                                    </svg>\
                                </span>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
                                <ul class="navi flex-column navi-hover py-2">\
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
                                        Choose an action:\
                                    </li>\
                                    <li>\
                                    <a href="#">\
                                        <button href="#"  type="button" class=" navi-text btn btn-xs btn-warning '+ sl +'" onclick="getBooking(this, '+ row.id +')"> <i class="fa fa-spin fa-spinner d-none"></i>&nbsp; >\
                                            <i class="fa fa-envelope"></i>\
                                            <b> LẤY BOOKING NÀY</b>\
                                        </button>\
                                    </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" class="navi-link "   ="sendEmailV2(this, '+ row.id +' ,'+ btnid +')">\
                                            <span class="navi-text btn btn-xs btn-success">'+ btnText1 +'</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="/trippal/sale/bookings/edit-price-vin/' + row.id + '" class="navi-link ">\
                                            <span class="navi-text btn btn-xs btn-warning">Sửa giá</span>\
                                        </a>\
                                    </li>\ <li class="navi-item">\
                                        <a href="/trippal/sale/bookings/view-vin/' + row.id + '" class="navi-link ">\
                                            <span class="navi-text btn btn-xs btn-primary">View</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="/trippal/sale/bookings/edit-vinpearl/' + row.id + '" class="navi-link ">\
                                            <span class="navi-text btn btn-xs btn-warning">Sửa</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="/trippal/sale/bookings/edit-vinpearl/' + row.id + '" class="navi-link ">\
                                            <span class="navi-text btn btn-xs btn-danger">Xóa</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="/trippal/sale/bookings/edit-vinpearl/' + row.id + '" class="navi-link ">\
                                            <span class="navi-text btn btn-xs btn-danger">Hủy</span>\
                                        </a>\
                                    </li>\
                                </ul>\
                            </div>\
                        </div>\
                    ';
                        },
                    }],
                autoColumns: true,
                autoHide: false,

            });

            // $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();

            $('#kt_datatable_search_status').on('change', function () {
                datatable.search($(this).val().toLowerCase(), 'statusStr');
            });

            $('#kt_datatable_search_type').on('change', function () {
                datatable.search($(this).val().toLowerCase(), 'Type');
            });


        };

        return {
            // public functions
            init: function () {
                booking();
            },
        };
    }();

    jQuery(document).ready(function () {
        KTDatatableRemoteAjaxDemo.init();
    });
</script>
<?php $this->end() ?>
