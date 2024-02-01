var jsonDataUrl = '../public/assets/json/timesheet.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#timesheet_data')) {
            $('#timesheet_data').DataTable().destroy();
        }
        var table = $('#timesheet_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<h2 class="table-avatar">
                            <a href="${data.profile_link}" class="avatar"><img alt=""
                                    src="${data.image}"></a>
                            <a href="${data.profile_link}">${data.name}</a> <span class="role">${data.role}</span>
                        </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'Date' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2>${data.Projects}</h2>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="text-center">${data.AssignedHours}</div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="text-center">${data.Hours}</div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="d-none d-sm-table-cell col-md-4">${data.Description}</div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="dropdown dropdown-action text-end">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#edit_todaywork"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#delete_workdetail"><i class="fa-regular fa-trash-can m-r-5"></i>
                                    Delete</a>
                            </div>
                        </div>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: true,
            searching: false,
            info: true,
            order: []
        });
        
    })
    .catch(error => {
        console.error('Error:', error);
    });