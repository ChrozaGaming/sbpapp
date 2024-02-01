var jsonDataUrl = '../public/assets/json/user_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#user_reports_data')) {
            $('#user_reports_data').DataTable().destroy();
        }
        var table = $('#user_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar"><img
                                src="${data.image}" alt=""></a>
                        <a href="profile">${data.name}</a> <span>${data.position}</span>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'company' },
                { data: 'email' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.role === 'Client') {
                            return ` <span class="badge bg-inverse-info">${data.role}</span>`;
                        }else{
                            return ` <span class="badge bg-inverse-danger">${data.role}</span>`;
                        }
                    }
                },
                { data: 'designation' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Active') {
                            return ` <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded"><i
                                    class="fa-regular fa-circle-dot text-success"></i> ${data.status} </a>
                        </div>`;
                        }else{
                            return ` <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded"><i
                                    class="fa-regular fa-circle-dot text-danger"></i> ${data.status} </a>
                        </div>`;
                        }
                    }
                },
                
            ],
            paging: true,
            searching: false,
            info: true,
            order: [[0, 'asc']],
        });
        
    })
    .catch(error => {
        console.error('Error:', error);
    });