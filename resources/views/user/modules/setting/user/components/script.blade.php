<script>

    var users = $('#users');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var CreateUserButton = $('#CreateUserButton');
    var UpdateUserButton = $('#UpdateUserButton');
    var DeleteUserButton = $('#DeleteUserButton');

    function createUser() {
        $('#create_user_name').val('');
        $('#create_user_surname').val('');
        $('#create_user_email').val('');
        $('#create_user_phone').val('');
        $('#create_user_password').val('');
        $('#CreateUserModal').modal('show');
    }

    function updateUser(id) {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.getById') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function (response) {
                $('#update_user_id').val(response.response.id);
                $('#update_user_name').val(response.response.name);
                $('#update_user_surname').val(response.response.surname);
                $('#update_user_email').val(response.response.email);
                $('#update_user_phone').val(response.response.phone);
                $('#UpdateUserModal').modal('show');
            },
            error: function (error) {
                console.log(error);
                toastr.error('Kullanıcı Bilgileri Alınırken Serviste Bir Hata Oluştu.');
            }
        });
    }

    function deleteUser(id) {
        $('#delete_user_id').val(id);
        $('#DeleteUserModal').modal('show');
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getUsers();
    }

    function getUsers() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                pageIndex: pageIndex,
                pageSize: pageSize,
            },
            success: function (response) {
                users.empty();
                $.each(response.response.users, function (i, user) {
                    users.append(`
                    <tr>
                        <td>${user.name} ${user.surname}</td>
                        <td>${user.email}</td>
                        <td class="hideIfMobile">${user.phone ?? ''}</td>
                        <td class="text-end">
                            <button class="btn btn-primary btn-icon btn-sm" onclick="updateUser(${user.id})" title="Düzenle"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-icon btn-sm" onclick="deleteUser(${user.id})" title="Sil"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    `);
                });

                if (response.response.totalCount <= (pageIndex + 1) * pageSize) {
                    pageUpButton.attr('disabled', true);
                } else {
                    pageUpButton.attr('disabled', false);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    getUsers();

    pageUpButton.click(function () {
        changePage(parseInt(page.html()) + 1);
    });

    pageDownButton.click(function () {
        changePage(parseInt(page.html()) - 1);
    });

    pageSizeSelector.change(function () {
        changePage(1);
    });

    $('body').on('contextmenu', function (e) {
        var top = e.pageY - 10;
        var left = e.pageX - 10;

        $("#context-menu").css({
            display: "block",
            top: top,
            left: left
        });

        return false;
    }).on("click", function () {
        $("#context-menu").hide();
    }).on('focusout', function () {
        $("#context-menu").hide();
    });

    CreateUserButton.click(function () {
        var customerId = '{{ auth()->user()->getCustomerId() }}';
        var name = $('#create_user_name').val();
        var surname = $('#create_user_surname').val();
        var email = $('#create_user_email').val();
        var phone = $('#create_user_phone').val();
        var password = $('#create_user_password').val();

        if (!name) {
            toastr.warning('Ad Boş Olamaz!');
        } else if (!surname) {
            toastr.warning('Soyad Boş Olamaz!');
        } else if (!email) {
            toastr.warning('E-posta Adresi Boş Olamaz!');
        } else if (!password) {
            toastr.warning('Şifre Boş Olamaz!');
        } else if (password.length < 8) {
            toastr.warning('Şifre 8 Karakterden Küçük Olamaz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    customerId: customerId,
                    name: name,
                    surname: surname,
                    email: email,
                    phone: phone,
                    password: password,
                },
                success: function () {
                    $('#CreateUserModal').modal('hide');
                    toastr.success('Kullanıcı Başarıyla Oluşturuldu!');
                    changePage(1);
                },
                error: function (error) {
                    $.each(error.responseJSON.response.email, function (i, message) {
                        if (message === 'The email has already been taken.') {
                            toastr.warning('Bu E-posta Adresi Kullanılamaz!');
                        } else if (message === 'The email must be a valid email address.') {
                            toastr.warning('Geçerli Bir E-posta Adresi Girmediniz!');
                        }
                    });
                    console.log(error)
                }
            });
        }
    });

    UpdateUserButton.click(function () {
        var id = $('#update_user_id').val();
        var name = $('#update_user_name').val();
        var surname = $('#update_user_surname').val();
        var phone = $('#update_user_phone').val();

        if (!name) {
            toastr.warning('Ad Boş Olamaz!');
        } else if (!surname) {
            toastr.warning('Soyad Boş Olamaz!');
        } else {
            $.ajax({
                type: 'put',
                url: '{{ route('api.user.update') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    id: id,
                    name: name,
                    surname: surname,
                    phone: phone,
                },
                success: function () {
                    $('#UpdateUserModal').modal('hide');
                    toastr.success('Kullanıcı Başarıyla Güncellendi!');
                    changePage(1);
                },
                error: function (error) {
                    console.log(error)
                }
            });
        }
    });

    DeleteUserButton.click(function () {
        var id = $('#delete_user_id').val();

        $.ajax({
            type: 'delete',
            url: '{{ route('api.user.delete') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                id: id,
            },
            success: function () {
                $('#DeleteUserModal').modal('hide');
                toastr.success('Kullanıcı Başarıyla Silindi!');
                changePage(1);
            },
            error: function (error) {
                if (error.status === 403) {
                    toastr.error('Kendi Hesabınızı Silemezsiniz!');
                } else {
                    console.log(error);
                    toastr.error('Kullanıcı Silinirken Serviste Bir Hata Oluştu!');
                }
            }
        });
    });

</script>
