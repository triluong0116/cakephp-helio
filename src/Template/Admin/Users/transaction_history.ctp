<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="card card-custom">
    <!--begin: modal add edit xem thông tin-->
    <div class="" id="modal-detail-user">
        <!-- Modal-->
    </div>

    <!--end: modal add edit xem thông tin-->
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Lịch sử giao dịch</h3>
            <input type="hidden" name="id_user" id="id_user" value="<?= $id ?>" />
        </div>
        <div class="card-toolbar">
        </div>
        <!--begin::Search Form-->
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-lg-12 col-xl-12">
                    <div class="row align-items-center">
                        <div class="col-md-12 my-2 my-md-0">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Search..."
                                       id="kt_datatable_search_query"/>
                                <span><i class="flaticon2-search-1 text-muted"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Search Form-->
    </div>
    <div class="card-body">
        <!--begin: Datatable-->
        <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
        <!--end: Datatable-->
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script type="text/javascript">
    "use strict";
    // Class definition
    console.log('123123123');
    var userId = $('#id_user').val();
    console.log(userId);
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
                            url: baseUrl + 'admin/users/transaction-history-datatable/'+ userId,
                            // sample custom headers
                            headers: {'X-CSRF-TOKEN': csrfToken},
                            map: function (raw) {
                                // sample data mapping
                                var dataSet = raw;
                                if (typeof raw.data !== 'undefined') {
                                    dataSet = raw.data;
                                }
                                return dataSet;
                            },
                        },
                    },
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: false,
                    footer: false,
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#kt_datatable_search_query'),
                    key: 'generalSearch',
                    delay: 1000,
                },

                // columns definition
                columns: [
                    {
                        field: '#',
                        title: '#',
                        autoHide: false,
                        width: 50,
                        template: function (row, key) {
                            return key + 1;
                        }
                    },
                    {
                        field: 'code',
                        title: 'Mã giao dịch',
                        autoHide: false,
                    }, {
                        field: 'title',
                        title: 'Tiêu đề',
                        autoHide: false,
                    }, {
                        field: 'amount',
                        title: 'Số tiền nạp',
                        autoHide: false,
                        template: function (row) {
                            return String(row.amount).replace(/(.)(?=(\d{3})+$)/g,'$1,')+'<sup> đ</sup>';
                        },
                    }, {
                        field: 'type',
                        title: 'Loại giao dịch',
                        autoHide: false,
                        template: function (row){
                            var types = {
                                1: {
                                    'title': 'Nạp tiền',
                                },
                                2: {
                                    'title': 'Thanh toán đơn hàng'
                                },
                            };
                            return types[row.type].title;
                        }
                    }, {
                        field: 'created',
                        title: 'Thời gian giao dịch',
                        autoHide: false,
                        template: function (row){
                            var cDate = new Date(row.created);
                            return cDate.getDate() + "/" + cDate.getMonth() + "/" + cDate.getFullYear() ;
                        }
                    },{
                        field: 'status',
                        title: 'Trạng thái',
                        autoHide: false,
                        template: function (row){
                            var type = {
                                0 : {
                                    'title' : 'Đã hủy'
                                },
                                1 : {
                                    'title' : 'Đã duyệt'
                                },
                                2 : {
                                    'title' : 'Đang chờ'
                                },
                            }
                            return type[row.type].title;
                        }
                    }, {
                        field: 'balance',
                        title: 'Số dư',
                        autoHide: false,
                        template: function (row) {
                            if (row.balance > 0 ){
                                return String(row.balance).replace(/(.)(?=(\d{3})+$)/g,'$1,')+'<sup> đ</sup>';
                            }
                            else {

                                return 'Chờ xác nhận số dư';
                            }

                        },
                    }, {
                        field: 'image',
                        title: 'Ảnh UNC',
                        autoHide: false,
                        overflow: 'visible',
                        sortable: false,
                        template: function (row) {
                            return '\
                            <a class="btn btn-default-outline thumbnail" >\
                                <img src="'+ baseUrl + row.image +'" style="height: 100px;  width : 100px ">\
                            </a>\
                            ';
                        },
                    }, {
                        field: '',
                        title: '',
                        autoHide: false,
                        overflow: 'visible',
                        sortable: false,
                        width: 10,
                    }
                ],
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

    function deleteuser(id) {
        console.log(id);
        if (!confirm("Bạn có muốn xóa người dùng " + id + " ?")) {
            return false;
        }
        $("form#delete-user-" + id).submit();
    }
</script>
<?php $this->end() ?>
