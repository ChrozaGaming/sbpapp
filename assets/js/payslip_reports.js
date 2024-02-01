var jsonDataUrl = '../public/assets/json/payslip_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.avatar = `../public/assets/img/profiles/${item.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#payslip_reports_data')) {
            $('#payslip_reports_data').DataTable().destroy();
        }
        var table = $('#payslip_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar"><img alt=""
                                src="${data.avatar}"></a>
                        <a href="profile">${data.employeeName} <span>${data.role}r</span></a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'paidAmount' },
                { data: 'paymentMonth' },
                { data: 'paymentYear' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <a href="#" class="btn btn-sm btn-primary">PDF</a>`;
                        return AvatarHtml;
                    }
                },
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