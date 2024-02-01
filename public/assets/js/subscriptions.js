var jsonDataUrl = '../public/assets/json/subscriptions.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#subscriptions_data')) {
            $('#subscriptions_data').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#subscriptions_data_2')) {
            $('#subscriptions_data_2').DataTable().destroy();
        }
        var table1Data = data.slice(0, 3); 
            var table2Data = data.slice(3);
        var table = $('#subscriptions_data').DataTable({
            ordering: false,
            data: table1Data,
            columns: [
                { data: 'Plan' },
                { data: 'PlanType' },
                { data: 'CreateDate' },
                { data: 'ModifiedDate' },
                { data: 'Amount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a class="btn btn-info btn-sm" href="subscribed-companies">${data.SubscribedUsers}</a>`;
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
        var table = $('#subscriptions_data_2').DataTable({
            ordering: false,
            data: table2Data,
            columns: [
                { data: 'Plan' },
                { data: 'PlanType' },
                { data: 'CreateDate' },
                { data: 'ModifiedDate' },
                { data: 'Amount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a class="btn btn-info btn-sm" href="subscribed-companies">${data.SubscribedUsers}</a>`;
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