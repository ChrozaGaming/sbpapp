var jsonDataUrl = '../public/assets/json/payments_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#payments_reports_data')) {
            $('#payments_reports_data').DataTable().destroy();
        }
        var table = $('#payments_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                { data: 'transactionID' },
                { data: 'date' },
                { data: 'clientName' },
                { data: 'paymentMethod' },
                { data: 'invoice' },
                { data: 'amount' },
            ],
            paging: true,
            searching: false,
            info: true,
            order:[],
            // scrollX:false,
            // scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });