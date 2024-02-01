var jsonDataUrl = '../public/assets/json/task_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#task_reports_data')) {
            $('#task_reports_data').DataTable().destroy();
        }
        var table = $('#task_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                { data: 'TaskName' },
                { data: 'StartDate' },
                { data: 'EndDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="dropdown action-label">
                        <a href="#" class="btn btn-white btn-sm btn-rounded"><i
                                class="fa-regular fa-circle-dot text-success"></i> ${data.Status} </a>

                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<ul class="team-members">
                        <li>
                            <a href="#" data-bs-toggle="tooltip" title=""
                                data-original-title="${data.name}"><img alt=""
                                    src="${data.image}"></a>
                        </li>
                    </ul>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: true,
            searching: false,
            info: true,
            // scrollX: false,
            // scrollY: false

        });
    })
    .catch(error => {
        console.error('Error:', error);
    });