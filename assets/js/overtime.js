var jsonDataUrl = '../public/assets/json/overtime.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.avatar = `../public/assets/img/profiles/${item.avatar}`;
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#overtime_data')) {
            $('#overtime_data').DataTable().destroy();
        }
        var table = $('#overtime_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar blue-link">
                        <a href="profile" class="avatar"><img alt=""
                                src="${data.avatar}"></a>
                        <a href="profile">${data.name}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'otDate' },
                {
                    data: 'otHours',
                    className: 'text-center' 
                },
                { data: 'otType' },
                { data: 'description' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'New') {
                            return ` <div class="action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                <i class="fa-regular fa-circle-dot text-purple"></i> ${data.status}
                            </a>
                        </div>`;
                        } else{
                           return `<div class="action-label">
                           <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                               <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                           </a>
                       </div>`;
                        }
                    }
                }, 
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar avatar-xs"><img
                                src="${data.image}" alt=""></a>
                        <a href="#">${data.approvedBy}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_overtime"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_overtime"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    },
                    
                }, 
            ],
            paging: true,
            searching: false,
            info: true,
            order:[],
            scrollX:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });