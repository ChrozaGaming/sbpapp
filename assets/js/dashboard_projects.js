var jsonDataUrl = '../public/assets/json/dashboard_projects.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.profiles_image = `../public/assets/img/profiles/${item.profiles_image}`;
        });
        var table = $('#dashboard_projects_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <h2><a href="project-view">${data.project_name}</a></h2>
                        <small class="block text-ellipsis">
                        <span>${data.open_tasks}</span> <span class="text-muted">open tasks, </span>
                        <span>${data.completed_tasks}</span> <span class="text-muted">tasks completed</span>
                        </small>

            `;
                        return imageHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="progress progress-xs progress-striped">
                        <div class="progress-bar" role="progressbar" data-bs-toggle="tooltip"
                            title="${data.progress}" style="width:${data.progress}%"></div>
                        </div>
            `;
                        return imageHtml;
                    }
                },
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