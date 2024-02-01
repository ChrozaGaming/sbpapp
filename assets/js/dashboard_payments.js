var jsonDataUrl = '../public/assets/json/dashboard_payments.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        var table = $('#dashboard_payments_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                {
                    data: 'invoice_id',
                    render: function (data, type, row) {
                        return '<a href="invoice-view">' + data + '</a>';
                    }
                },
                {
                    data: 'client',
                    render: function (data, type, row) {
                        return '<h2><a href="#">' + data + '</a></h2>';
                    }
                },
                { data: 'payment_type' },
                { data: 'paid_date' },
                {
                    data: 'paid_amount',
                }
            ],
            paging: false,
            searching: false,
            info: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });