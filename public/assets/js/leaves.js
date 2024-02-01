var jsonDataUrl = '../public/assets/json/leaves.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.avatar = `../public/assets/img/profiles/${item.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#leaves_data')) {
            $('#leaves_data').DataTable().destroy();
        }
        var table = $('#leaves_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar"><img alt=""
                                src="${data.avatar}"></a>
                        <a href="#">${data.name}</a> <span>${data.role}</span>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'leaveType' },
                { data: 'fromDate' },
                { data: 'toDate' },
                { data: 'noOfDays' },
                { data: 'reason' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'New') {
                            return ` <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-purple"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-purple"></i> New</a>
                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                    Pending</a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#approve_leave"><i class="fa-regular fa-circle-dot text-success"></i>
                                    Approved</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Declined</a>
                            </div>
                        </div>`;
                        } else if (data.status === 'Approved') {
                            return `<div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-purple"></i> New</a>
                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                    Pending</a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#approve_leave"><i class="fa-regular fa-circle-dot text-success"></i>
                                    Approved</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Declined</a>
                            </div>
                        </div>`;
                        } else {
                            return `<div class="dropdown action-label text-center">
                           <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                               data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                           </a>
                           <div class="dropdown-menu dropdown-menu-right">
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-purple"></i> New</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-info"></i> Pending</a>
                               <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                   data-bs-target="#approve_leave"><i
                                       class="fa-regular fa-circle-dot text-success"></i> Approved</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-danger"></i> Declined</a>
                           </div>
                       </div>`;
                        }
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
                                data-bs-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_approve"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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