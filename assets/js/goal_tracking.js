var jsonDataUrl = '../public/assets/json/goal_tracking.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#goal_tracking_data')) {
            $('#goal_tracking_data').DataTable().destroy();
        }
        var table = $('#goal_tracking_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                { data: 'GoalType' },
                { data: 'Subject' },
                { data: 'TargetAchievement' },
                { data: 'StartDate' },
                { data: 'EndDate' },
                { data: 'Description' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Active') {
                            return `<div class="dropdown action-label">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-success"></i>  ${data.Status}
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                            </div>
                        </div>`;
                        } else {
                            return `
                            <div class="dropdown action-label">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status}
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                            </div>
                        </div>
                    `;

                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <p class="mb-1">${data.Progress.Label}</p>
                        <div class="progress" style="height:5px">
                            <div class="progress-bar bg-primary progress-sm" style="width:${data.Progress.Percentage}%;height:10px;"></div>
                        </div>
            `;
                        return AvatarHtml;
                    }
            },
                {
                        data: null,
                        render: function (data, type, row) {
                            var AvatarHtml = `
                            <div class="dropdown dropdown-action text-end">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit_goal"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_goal"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                    </div>
                                </div>
                `;
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