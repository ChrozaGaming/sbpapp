var jsonDataUrl = '../public/assets/json/subscribed_companies.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#subscribed_companies_data')) {
            $('#subscribed_companies_data').DataTable().destroy();
        }
        var table = $('#subscribed_companies_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<h2 class="table-avatar">
                        <a href="client-profile" class="avatar"><img
                                src="${data.image}" alt=""></a>
                        <a href="client-profile">${data.Client}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'Plan' },
                { data: 'Users' },
                { data: 'PlanDuration' },
                { data: 'StartDate' },
                { data: 'EndDate' },
                { data: 'Amount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.PlanStatus === 'Active') {
                            return `<span class="badge bg-inverse-success">${data.PlanStatus}</span>`;
                        } else{
                           return `<span class="badge bg-inverse-danger">${data.PlanStatus}</span>`;
                        }
                    }
                }, 
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a class="btn btn-primary btn-sm" href="javascript:void(0);" data-bs-toggle="modal"
                        data-bs-target="#upgrade_plan">Change Plan</a>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Active') {
                            return `<div class="status-toggle">
                            <input type="checkbox" id="${data.id_status}" class="check" checked>
                            <label for="${data.for_status}" class="checktoggle">${data.Status}</label>
                        </div>`;
                        } else{
                           return `<div class="status-toggle">
                           <input type="checkbox" id="${data.id_status}" class="check">
                           <label for="${data.for_status}" class="checktoggle">${data.Status}</label>
                       </div>`;
                        }
                    }
                },
            ],
            paging: true,
            searching: false,
            info: true,
            
            
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });