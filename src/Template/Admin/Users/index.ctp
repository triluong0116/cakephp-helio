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
            <h3 class="card-label">Danh sách CTV</h3>
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
                            url: baseUrl + 'admin/users/index-datatable',
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
                        template: function (row, key) {
                            return key + 1;
                        }
                    },
                    {
                        field: 'username',
                        title: 'Tên tài khoản',
                        autoHide: false,
                    }, {
                        field: 'screen_name',
                        title: 'Tên hiển thị',
                        autoHide: false,
                    }, {
                        field: 'role_name',
                        title: 'Vai trò',
                        autoHide: false,
                        template: function (row) {
                            return row.Roles.name;
                        },
                    }, {
                        field: 'phone',
                        title: 'Số điện thoại',
                        autoHide: false,
                    }, {
                        field: 'email',
                        title: 'Số điện thoại',
                        autoHide: false,
                    }, {
                        field: 'balance',
                        title: 'Số dư',
                        autoHide: false,
                        template: function (row) {
                            return String(row.balance).replace(/(.)(?=(\d{3})+$)/g,'$1,')+'<sup> đ</sup>';
                        },
                    }, {
                        field: 'Actions',
                        title: 'Actions',
                        sortable: false,
                        width: 125,
                        overflow: 'visible',
                        autoHide: false,
                        template: function (row) {
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
                                    <span class="navi-icon"><i class="fas fa-cog"></i></i></span>\
                                            <span class="navi-text">Choose an action: </span>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" class="navi-link" onclick="showModalUser(' + row.id + ')">\
                                            <span class="navi-icon"><i class="fas fa-eye"></i></span>\
                                            <span class="navi-text">Xem hồ sơ</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="users/transactionHistory/' + row.id + '" class="navi-link">\
                                            <span class="navi-icon"><i class="fas fa-list-ul"></i></span>\
                                            <span class="navi-text">Lịch sử giao dịch</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                    <form action="' + baseUrl + 'admin/users/delete/' + row.id + '" method="post" id="delete-user-' + row.id + '">\
                                           <input type="hidden" name="_csrfToken" value="' + csrfToken + '" />\
                                        <a href="#" class="navi-link" onclick="deleteuser(' + row.id + ');">\
                                            <span class="navi-icon"><i class="fas fa-trash"></i></span>\
                                            <span class="navi-text">Xóa hồ sơ</span>\
                                        </a>\
                                        </form>\
                                    </li>\
                                    \
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

    function deleteuser(id) {
        console.log(id);
        if (!confirm("Bạn có muốn xóa người dùng " + id + " ?")) {
            return false;
        }
        $("form#delete-user-" + id).submit();
    }
</script>
<?php $this->end() ?>
