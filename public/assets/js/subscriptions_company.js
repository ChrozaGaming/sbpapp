var jsonDataUrl = '../public/assets/json/subscriptions_company.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#subscriptions_company_data')) {
            $('#subscriptions_company_data').DataTable().destroy();
        }
        var table = $('#subscriptions_company_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                { data: 'Plan' },
                { data: 'Users' },
                { data: 'PlanDuration' },
                { data: 'StartDate' },
                { data: 'EndDate' },
                { data: 'Amount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a class="btn btn-primary btn-sm" href="javascript:void(0);">${data.UpdatePlan}</a>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: false,
            searching: false,
            info: false,
            scrollX: false,
            scrollY: false

        });
    })
    .catch(error => {
        console.error('Error:', error);
    });