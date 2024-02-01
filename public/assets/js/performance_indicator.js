var jsonDataUrl = '../public/assets/json/performance_indicator.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.addedBy.avatar = `../public/assets/img/profiles/${item.addedBy.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#performance_indicator_data')) {
            $('#performance_indicator_data').DataTable().destroy();
        }
        var table = $('#performance_indicator_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                { data: 'designation' },
                { data: 'department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="${data.addedBy.profileUrl}" class="avatar"><img alt=""
                                src="${data.addedBy.avatar}"></a>
                        <a href="${data.addedBy.profileUrl}">${data.addedBy.name}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'createdAt' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="dropdown action-label">
                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-success"></i> Active</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_indicator"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_indicator"><i class="fa-regular fa-trash-can m-r-5"></i>
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
            order:[],
            // scrollX:false,
            // scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });