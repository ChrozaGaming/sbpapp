var jsonDataUrl = '../public/assets/json/termination.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#termination_data')) {
            $('#termination_data').DataTable().destroy();
        }
        var table = $('#termination_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<h2 class="table-avatar blue-link">
                        <a href="${data.profile_link}" class="avatar"><img alt=""
                                src="${data.image}"></a>
                        <a href="${data.profile_link}">${data.name}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'Department' },
                { data: 'TerminationType' },
                { data: 'TerminationDate' },
                { data: 'Reason' },
                { data: 'NoticeDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_termination"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_termination"><i class="fa-regular fa-trash-can m-r-5"></i>
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
           
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });