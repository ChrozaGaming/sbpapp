var jsonDataUrl = '../public/assets/json/project_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
            item.Avatar_1 = `../public/assets/img/profiles/${item.Avatar_1}`;
            item.Avatar_2 = `../public/assets/img/profiles/${item.Avatar_2}`;
            item.Avatar_3 = `../public/assets/img/profiles/${item.Avatar_3}`;
            item.Avatar_4 = `../public/assets/img/profiles/${item.Avatar_4}`;
        });
        if ($.fn.DataTable.isDataTable('#project_reports_data')) {
            $('#project_reports_data').DataTable().destroy();
        }

        var table = $('#project_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="project-view">${data.ProjectTitle}</a>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <h2 class="table-avatar">
                                    <a href="client-profile" class="avatar"><img
                                            src="${data.image}" alt=""></a>
                                    <a href="client-profile">${data.ClientName}</a>
                        </h2>
            `;
                        return AvatarHtml;
                    }
                },
                { data: 'StartDate' },
                { data: 'EndDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Active') {
                            return `  <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded"><i
                                    class="fa-regular fa-circle-dot text-success"></i> ${data.Status} </a>

                        </div>`;
                        } else {
                            return `  <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded"><i
                                    class="fa-regular fa-circle-dot text-danger"></i> ${data.Status} </a>

                        </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <ul class="team-members">
                        <li>
                            <a href="#" title="" data-bs-toggle="tooltip"
                                data-original-title="${data.Name_1}"><img alt=""
                                    src="${data.Avatar_1}"></a>
                        </li>
                        <li>
                            <a href="#" title="" data-bs-toggle="tooltip"
                                data-original-title="${data.Name_2}"><img alt=""
                                    src="${data.Avatar_2}"></a>
                        </li>
                        <li>
                            <a href="#" title="" data-bs-toggle="tooltip"
                                data-original-title="${data.Name_3}"><img alt=""
                                    src="${data.Avatar_3}"></a>
                        </li>
                        <li>
                            <a href="#" title="" data-bs-toggle="tooltip"
                                data-original-title="${data.Name_4}"><img alt=""
                                    src="${data.Avatar_4}"></a>
                        </li>
                        <li>
                            <a href="#" class="all-users">${data.Name_5}</a>
                        </li>
                    </ul>
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