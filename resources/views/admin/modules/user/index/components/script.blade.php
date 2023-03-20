<script src="{{ asset('assets/jqwidgets/jqxcore.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxbuttons.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxscrollbar.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxlistbox.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxdropdownlist.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxmenu.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.selection.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.columnsreorder.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.columnsresize.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.filter.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.sort.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxdata.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.pager.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxnumberinput.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxwindow.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxdata.export.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.export.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxexport.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqxgrid.grouping.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/globalization/globalize.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jqgrid-localization.js') }}"></script>
<script src="{{ asset('assets/jqwidgets/jszip.min.js') }}"></script>
<script>

    $(document).ready(function () {
        $('#loader').hide();
    });

    var usersListDiv = $('#usersList');
    var selectedUserId = null;
    var TransactionsModal = $('#TransactionsModal');
    var EditUserModal = $('#EditUserModal');
    var CreateSubscriptionModal = $('#CreateSubscriptionModal');
    var subscriptionListInput = $('#subscriptionList');
    var subscriptionStartDateInput = $('#subscriptionStartDate');


    function getUsers() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.admin.user.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                var users = [];
                $.each(response.response, function (i, user) {

                    users.push({
                        id: user.id,
                        name: user.name + ' ' + user.surname,
                        email: user.email,
                        suspend:  user.suspend == 1 ? `<span class="badge badge-danger">Pasif</span>` : `<span class="badge badge-success">Aktif</span>`,
                        created_at: moment(user.created_at).format('DD.MM.YYYY HH:mm'),
                        subscription: user.subscription != null ? user.subscription.subscription.name : 'Yok',
                        subscription_start_date: user.subscription != null ? user.subscription.subscription_start_date : 'Yok',
                        subscription_expiry_date: user.subscription != null ? user.subscription.subscription_expiry_date : 'Yok',
                    });
                });
                users_count = users.length;
                var source = {
                    localdata: users,
                    datatype: "array",
                    datafields: [
                        {name: 'id', type: 'integer'},
                        {name: 'name', type: 'string'},
                        {name: 'email', type: 'string'},
                        {name: 'suspend', type: 'string'},
                        {name: 'subscription', type: 'string'},
                        {name: 'subscription_start_date', type: 'string'},
                        {name: 'subscription_expiry_date', type: 'string'},


                    ]
                };
                var dataAdapter = new $.jqx.dataAdapter(source);
                usersListDiv.jqxGrid({
                    width: '100%',
                    height: '600',
                    source: dataAdapter,
                    columnsresize: true,
                    groupable: true,
                    theme: jqxGridGlobalTheme,
                    filterable: true,
                    showfilterrow: true,
                    pageable: false,
                    sortable: true,
                    pagesizeoptions: ['10', '40', '50', '1000'],
                    localization: getLocalization('tr'),
                    columns: [
                        {
                            text: '#',
                            dataField: 'id',
                            columntype: 'textbox',
                        },
                        {
                            text: 'Kullanıcı',
                            dataField: 'name',
                            columntype: 'textbox',
                        },
                        {
                            text: 'Email',
                            dataField: 'email',
                            columntype: 'textbox',
                        },
                        {
                            text: 'Durum',
                            dataField: 'suspend',
                            columntype: 'textbox',
                        },
                        {
                            text: 'Abonelik',
                            dataField: 'subscription',
                            columntype: 'textbox',
                        },

                        {
                            text: 'Abonelik Başlangıç Tarihi',
                            dataField: 'subscription_start_date',
                            columntype: 'textbox',
                        },
                        {
                            text: 'Abonelik Bitiş Tarihi',
                            dataField: 'subscription_expiry_date',
                            columntype: 'textbox',
                        },


                    ],
                });

                usersListDiv.on('rowclick', function (event) {
                    usersListDiv.jqxGrid('selectrow', event.args.rowindex);
                    var rowindex = usersListDiv.jqxGrid('getselectedrowindex');
                    var dataRecord = usersListDiv.jqxGrid('getrowdata', rowindex);
                    selectedUserId = dataRecord.id;
                    return false;
                });
                usersListDiv.jqxGrid('sortby', 'id', 'desc');

            },
            error: function (error) {
                console.log(error);
                if (parseInt(error.status) === 422) {
                    $.each(error.responseJSON.response, function (i, error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error(error.responseJSON.message);
                }
            }
        });
    }

    function getByUserId() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.admin.user.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: selectedUserId
            },
            success: function (response) {
                TransactionsModal.modal('hide');
                EditUserModal.modal('show');
                $('#user_name').val(response.response.name);
                $('#user_surname').val(response.response.surname);
                $('#user_email').val(response.response.email);
                $('#user_title').val(response.response.title);
                $('#user_phone').val(response.response.phone);
                $('#user_suspend').prop('checked', response.response.suspend == 0 ? true : false);
            },
            error: function (error) {
                console.log(error);
                if (parseInt(error.status) === 422) {
                    $.each(error.responseJSON.response, function (i, error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error(error.responseJSON.message);
                }
            }
        });
    }

    function updateUser() {
        $.ajax({
            type: 'put',
            url: '{{ route('api.admin.user.update') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: selectedUserId,
                name: $('#user_name').val(),
                surname: $('#user_surname').val(),
                email: $('#user_email').val(),
                title: $('#user_title').val(),
                phone: $('#user_phone').val(),
                suspend: $('#user_suspend').prop('checked') === true ? 0 : 1,
            },
            success: function (response) {
                toastr.success(response.message);
                getUsers();
                EditUserModal.modal('hide');
                selectedUserId = null;
            },
            error: function (error) {
                console.log(error);
                if (parseInt(error.status) === 422) {
                    $.each(error.responseJSON.response, function (i, error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error(error.responseJSON.message);
                }
            }
        });
    }


    function getAllSubscription() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.admin.subscription.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {

            },
            success: function (response) {
                TransactionsModal.modal('hide');
                CreateSubscriptionModal.modal('show');
                subscriptionListInput.empty();
                response.response.forEach(function (item) {
                    subscriptionListInput.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            },
            error: function (error) {
                console.log(error);
                if (parseInt(error.status) === 422) {
                    $.each(error.responseJSON.response, function (i, error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error(error.responseJSON.message);
                }
            }
        });
    }

    function createSubscription() {
        $.ajax({
            type: 'post',
            url: '{{ route('api.admin.subscription.create') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                customerId: selectedUserId,
                subscriptionId: subscriptionListInput.val(),
                startDate: subscriptionStartDateInput.val(),

            },
            success: function (response) {
                toastr.success(response.message);
                getUsers();
                CreateSubscriptionModal.modal('hide');
                selectedUserId = null;
                subscriptionStartDateInput.val('');

            },
            error: function (error) {
                selectedUserId = null;
                if (parseInt(error.status) === 422) {
                    $.each(error.responseJSON.response, function (i, error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error(error.responseJSON.message);
                }
            }
        });
    }

    getUsers();

    $('body').on('contextmenu', function () {
        TransactionsModal.modal('show');
        return false;
    });

</script>
