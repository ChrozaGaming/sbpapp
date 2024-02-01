var jsonDataUrl = '../public/assets/json/tickets.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#tickets_data')) {
            $('#tickets_data').DataTable().destroy();
        }
        var table = $('#tickets_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a href="ticket-view">${data.TicketId}</a>`;
                        return AvatarHtml;
                    }
                },
                { data: 'TicketSubject' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<h2 class="table-avatar">
                        <a href="${data.profile_link}" class="avatar avatar-xs"><img alt=""
                                src="${data.image}"></a>
                        <a href="${data.profile_link}">${data.name}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'CreatedDate' },
                { data: 'LastReply' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Priority === 'High') {
                            return `<div class="dropdown action-label">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                    class="fa-regular fa-circle-dot text-danger"></i> ${data.Priority} </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> High</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-warning"></i> Medium</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Low</a>
                            </div>
                        </div>`;
                        } else{
                           return `<div class="dropdown action-label">
                           <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                               data-bs-toggle="dropdown" aria-expanded="false"><i
                                   class="fa-regular fa-circle-dot text-warning"></i> ${data.Priority} </a>
                           <div class="dropdown-menu dropdown-menu-right">
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-danger"></i> High</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-warning"></i> Medium</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-success"></i> Low</a>
                           </div>
                       </div>`;
                        }
                    }
                }, 
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'New') {
                            return `<div class="dropdown action-label">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-info"></i> Open</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-info"></i> Reopened</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> On Hold</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Closed</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> In Progress</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Cancelled</a>
                            </div>
                        </div>`;
                        } else{
                           return `<div class="dropdown action-label">
                           <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                               data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fa-regular fa-circle-dot text-info"></i> ${data.Status}
                           </a>
                           <div class="dropdown-menu dropdown-menu-right">
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-info"></i> Open</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-info"></i> Reopened</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-danger"></i> On Hold</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-success"></i> Closed</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-success"></i> In Progress</a>
                               <a class="dropdown-item" href="#"><i
                                       class="fa-regular fa-circle-dot text-danger"></i> Cancelled</a>
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
                                data-bs-target="#edit_ticket"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_ticket"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
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