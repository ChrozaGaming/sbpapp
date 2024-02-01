var jsonDataUrl = '../public/assets/json/payments.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#payments_data')) {
            $('#payments_data').DataTable().destroy();
        }
        var table = $('#payments_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <a href="invoice-view">${data.invoiceID}</a>`;
                        return AvatarHtml;
                    }
                },
                { data: 'client' },
                { data: 'paymentType' },
                { data: 'paidDate' },
                { data: 'paidAmount' },
            ],
            paging: true,
            searching: false,
            info: true,
            order:[],
            scrollX:false,
            scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });