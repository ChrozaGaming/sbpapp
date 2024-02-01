var jsonDataUrl = '../public/assets/json/apptitude_result.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.profiles_image = `../public/assets/img/profiles/${item.profiles_image}`;
        });
        if ($.fn.DataTable.isDataTable('#apptitude_result_data')) {
            $('#apptitude_result_data').DataTable().destroy();
        }
        var table = $('#apptitude_result_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <h2 class="table-avatar">
                                    <a href="profile" class="avatar"><img alt=""
                                            src="${data.profiles_image}"></a>
                                    <a href="profile">${data.name} <span>${data.jobTitle} </span></a>
                                </h2>

            `;
                        return imageHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <a href="job-details">${data.jobTitle}</a>

            `;
                        return imageHtml;
                    }
                },
                { data: 'department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        html - <b>${data.categoryWiseMark.html}</b><br>Level1 - <b>${data.categoryWiseMark.Level1}</b><br>

            `;
                        return imageHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                       <div class="text-center">${data.totalMark}
                       </div>
            `;
                        return imageHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="dropdown action-label text-center">
                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-circle-dot text-danger"></i> Action pending </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                Resume selected</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-danger"></i>
                                Resume Rejected</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-success"></i>
                                Aptitude Selected</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-danger"></i>
                                Aptitude rejected</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-success"></i>
                                video call selected</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-danger"></i>
                                Video call rejected</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-success"></i>
                                Offered</a>
                        </div>
                    </div>
            `;
                        return imageHtml;
                    }
                }
            ],
            paging: true,
            searching: false,
            info: true,
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });