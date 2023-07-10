<script>

    function getTotalBalanceForCase() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.getTotalBalance') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                typeId: 1
            },
            success: function (response) {
                $('#totalBalanceForCase').html(`${reformatNumberToMoney(response.response)} ₺`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getTotalBalanceForBank() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.safebox.getTotalBalance') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                typeId: 2
            },
            success: function (response) {
                $('#totalBalanceForBank').html(`${reformatNumberToMoney(response.response)} ₺`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getThisMonthTransactions() {
        var date = new Date();
        var datetimeStart = reformatDatetime(new Date(date.getFullYear(), date.getMonth(), 1));
        var datetimeEnd = reformatDatetime(new Date(date.getFullYear(), date.getMonth() + 1, 0, 23, 59, 59));

        $.ajax({
            type: 'get',
            url: '{{ route('api.user.transaction.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {
                datetimeStart: datetimeStart,
                datetimeEnd: datetimeEnd
            },
            success: function (response) {
                var totalEarnings = 0;
                var totalExpenses = 0;

                $.each(response.response, function (i, transaction) {
                    if (transaction.direction === 0) {
                        totalEarnings += transaction.amount;
                    } else {
                        totalExpenses += transaction.amount;
                    }
                });

                $('#totalEarnings').html(`${reformatNumberToMoney(totalEarnings)} ₺`);
                $('#totalExpenses').html(`${reformatNumberToMoney(totalExpenses)} ₺`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getCompanies() {
        $.ajax({
            type: 'get',
            url: '{{ route('api.user.company.all') }}',
            headers: {
                'Accept': 'application/json',
                'Authorization': token
            },
            data: {},
            success: function (response) {
                var totalCredit = 0;
                var totalDebit = 0;

                $.each(response.response, function (i, company) {
                    if (company.balance > 0) {
                        totalDebit += company.balance;
                    } else {
                        totalCredit += company.balance * -1;
                    }
                });

                $('#totalCredit').html(`${reformatNumberToMoney(totalCredit)} ₺`);
                $('#totalDebit').html(`${reformatNumberToMoney(totalDebit)} ₺`);
                $('#totalBalance').html(`${reformatNumberToMoney(totalDebit - totalCredit)} ₺`);
                $('#balanceStatus').html(`${totalDebit - totalCredit > 0 ? 'Borçluyuz' : 'Alacaklıyız'}`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function initializeDashboard() {
        getTotalBalanceForCase();
        getTotalBalanceForBank();
        getThisMonthTransactions();
        getCompanies();
    }

    initializeDashboard();

    $(document).ready(function () {
        $('#VatAnnouncementModal').modal('show');
    });

</script>
