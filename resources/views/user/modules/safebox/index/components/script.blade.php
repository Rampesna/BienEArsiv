<script>

    var safeboxes = $('#safeboxes');

    var page = $('#page');
    var pageUpButton = $('#pageUp');
    var pageDownButton = $('#pageDown');
    var pageSizeSelector = $('#pageSize');

    var create_safebox_type_id = $('#create_safebox_type_id');
    var filter_safebox_type = $('#filter_safebox_type');

    var CreateSafeboxButton = $('#CreateSafeboxButton');

    function createSafebox() {
        create_safebox_type_id.val('').select2();
        $('#create_safebox_name').val('');
        $('#create_safebox_account_number').val('');
        $('#create_safebox_branch').val('');
        $('#create_safebox_iban').val('');
        $('#CreateSafeboxModal').modal('show');
    }

    function changePage(newPage) {
        if (newPage === 1) {
            pageDownButton.attr('disabled', true);
        } else {
            pageDownButton.attr('disabled', false);
        }

        page.html(newPage);
        getSafeboxes();
    }

    function getSafeboxTypes() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safeboxType.getAll') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                create_safebox_type_id.empty();
                filter_safebox_type.empty();
                create_safebox_type_id.append(`<option value="" selected hidden>Tümü</option>`);
                filter_safebox_type.append(`<optgroup label=""><option value="0">Tümü</option></optgroup>`);
                $.each(response.response, function (i, safeboxType) {
                    create_safebox_type_id.append(`<option value="${safeboxType.id}">${safeboxType.name}</option>`);
                    filter_safebox_type.append(`<option value="${safeboxType.id}">${safeboxType.name}</option>`);
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getSafeboxes() {
        var pageIndex = parseInt(page.html()) - 1;
        var pageSize = pageSizeSelector.val();
        var keyword = $('#filter_safebox_keyword').val();
        var safeboxType = filter_safebox_type.val();

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.index') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                pageIndex: pageIndex,
                pageSize: pageSize,
                keyword: keyword,
                safeboxType: safeboxType
            },
            success: function (response) {
                safeboxes.empty();
                $.each(response.response.safeboxes, function (i, safebox) {
                    safeboxes.append(`
                    <tr class="cursor-pointer" onclick="goToDetail(${safebox.id})">
                        <th class="w-10px pe-2">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 19.725V18.725C20 18.125 19.6 17.725 19 17.725H5C4.4 17.725 4 18.125 4 18.725V19.725H3C2.4 19.725 2 20.125 2 20.725V21.725H22V20.725C22 20.125 21.6 19.725 21 19.725H20Z" fill="black"/>
                                    <path opacity="0.3" d="M22 6.725V7.725C22 8.325 21.6 8.725 21 8.725H18C18.6 8.725 19 9.125 19 9.725C19 10.325 18.6 10.725 18 10.725V15.725C18.6 15.725 19 16.125 19 16.725V17.725H15V16.725C15 16.125 15.4 15.725 16 15.725V10.725C15.4 10.725 15 10.325 15 9.725C15 9.125 15.4 8.725 16 8.725H13C13.6 8.725 14 9.125 14 9.725C14 10.325 13.6 10.725 13 10.725V15.725C13.6 15.725 14 16.125 14 16.725V17.725H10V16.725C10 16.125 10.4 15.725 11 15.725V10.725C10.4 10.725 10 10.325 10 9.725C10 9.125 10.4 8.725 11 8.725H8C8.6 8.725 9 9.125 9 9.725C9 10.325 8.6 10.725 8 10.725V15.725C8.6 15.725 9 16.125 9 16.725V17.725H5V16.725C5 16.125 5.4 15.725 6 15.725V10.725C5.4 10.725 5 10.325 5 9.725C5 9.125 5.4 8.725 6 8.725H3C2.4 8.725 2 8.325 2 7.725V6.725L11 2.225C11.6 1.925 12.4 1.925 13.1 2.225L22 6.725ZM12 3.725C11.2 3.725 10.5 4.425 10.5 5.225C10.5 6.025 11.2 6.725 12 6.725C12.8 6.725 13.5 6.025 13.5 5.225C13.5 4.425 12.8 3.725 12 3.725Z" fill="black"/>
                                </svg>
                            </span>
                        </th>
                        <td>
                            ${safebox.name}
                        </td>
                        <td>
                            ${safebox.type ? safebox.type.name : ''}
                        </td>
                        <td class="text-end">${reformatNumberToMoney(safebox.balance)} ₺</td>
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

    function goToDetail(companyId) {
        window.location.href = `{{ route('web.user.safebox.detail') }}/${companyId}`;
    }

    getSafeboxTypes();
    getSafeboxes();

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

    $(document).delegate('.filterInput', 'change keyup', function () {
        getSafeboxes();
    });

    CreateSafeboxButton.click(function () {
        var typeId = create_safebox_type_id.val();
        var name = $('#create_safebox_name').val();
        var accountNumber = $('#create_safebox_account_number').val();
        var branch = $('#create_safebox_branch').val();
        var iban = $('#create_safebox_iban').val();

        if (!typeId) {
            toastr.warning('Hesap Türü Seçmediniz!');
        } else if (!name) {
            toastr.warning('Hesap Tanımı Girmediniz!');
        } else {
            $.ajax({
                type: 'post',
                url: '{{ route('api.user.safebox.create') }}',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                data: {
                    typeId: typeId,
                    name: name,
                    accountNumber: accountNumber,
                    branch: branch,
                    iban: iban
                },
                success: function () {
                    $('#CreateSafeboxModal').modal('hide');
                    toastr.success('Hesap Başarıyla Oluşturuldu!');
                    changePage(1);
                },
                error: function (error) {
                    console.log(error);
                    toastr.error('Hesap Oluşturulurken Serviste Bir Hata Oluştu!');
                }
            });
        }
    });

</script>
