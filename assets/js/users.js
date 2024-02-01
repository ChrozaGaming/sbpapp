var jsonDataUrl = '../public/assets/json/users.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#users_data')) {
            $('#users_data').DataTable().destroy();
        }
        var table = $('#users_data').DataTable({
            ordering: true,
            data: data,
            columns: [
               
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
                { data: 'email' },
                { data: 'company' },
                { data: 'created_date' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.role === 'Employee') {
                            return ` <span class="badge bg-inverse-success">${data.role}</span>`;
                        }else if (data.role === 'Admin') {
                            return ` <span class="badge bg-inverse-danger">${data.role}</span>`;
                        }else{
                            return ` <span class="badge bg-inverse-info">${data.role}</span>`;
                        }
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
                                data-bs-target="#edit_user"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_user"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
                
            ],
            paging: true,
            searching: false,
            info: true,
            order: [],
        });
        
    })
    .catch(error => {
        console.error('Error:', error);
    });