var jsonDataUrl = '../public/assets/json/dashboard_clients.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.profiles_image = `../public/assets/img/profiles/${item.profiles_image}`;
        });
        var table = $('#dashboard_clients_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <h2 class="table-avatar">
                        <a href="#" class="avatar"><img alt=""
                        src="${data.profiles_image}"></a>
                        <a href="client-profile">${data.name}<span>${data.speciality}</span></a>
                        </h2>

            `;
                        return imageHtml;
                    }
                },
                { data: 'email' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Active') {
                            return `
                                <div class="dropdown action-label">
                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-circle-dot text-success"></i> Active
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i
                                            class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                        <a class="dropdown-item" href="#"><i
                                            class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                    </div>
                                </div>
                            `;
                        } else {
                            return `
                                <div class="dropdown action-label">
                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-circle-dot text-danger"></i> Inactive
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i
                                            class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                        <a class="dropdown-item" href="#"><i
                                            class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }
                ,                
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0)"><i
                        class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i
                        class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                        </div>
            `;
                        return imageHtml;
                    }
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