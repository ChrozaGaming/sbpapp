var jsonDataUrl = '../public/assets/json/interviewing.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#interviewing_data')) {
            $('#interviewing_data').DataTable().destroy();
        }
        
        if ($.fn.DataTable.isDataTable('#interviewing_data_2')) {
            $('#interviewing_data_2').DataTable().destroy();
        }
        var table1Data = data.slice(0, 3); 
        var table2Data = data.slice(3); 
        var table = $('#interviewing_data').DataTable({
            ordering: true,
            data: table1Data,
            columns: [
                { data: '#' },
                { data: 'JobTitle' },
                { data: 'Department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.JobType === 'Full Time') {
                            return ` <div class="action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="#">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.JobType}
                            </a>
                        </div>`;
                        } else {
                            return `
                            <div class="action-label text-center">
                                <a class="btn btn-white btn-sm btn-rounded" href="#">
                                    <i class="fa-regular fa-circle-dot text-warning"></i> ${data.JobType}
                                </a>
                            </div>
                    `;

                        }
                    }
                },
                {
                        data: null,
                        render: function (data, type, row) {
                            var AvatarHtml = `
                            <a href="job-aptitude" class="btn btn-primary aptitude-btn"><span> ${data.AptitudeTest}
                            </span></a>
                `;
                            return AvatarHtml;
                        }
                },

            ],
            paging: true,
            searching: false,
            info: true,
            scrollX:false,
            scrollY:false
        });

        var table = $('#interviewing_data_2').DataTable({
            ordering: true,
            data: table2Data,
            columns: [
                { data: '#' },
                { data: 'JobTitle' },
                { data: 'Department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.JobType === 'Full Time') {
                            return ` <div class="action-label">
                            <a class="btn btn-white btn-sm btn-rounded" href="#">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.JobType}
                            </a>
                        </div>`;
                        } else {
                            return `
                            <div class="action-label">
                                <a class="btn btn-white btn-sm btn-rounded" href="#">
                                    <i class="fa-regular fa-circle-dot text-warning"></i> ${data.JobType}
                                </a>
                            </div>
                    `;

                        }
                    }
                },
                {
                        data: null,
                        render: function (data, type, row) {
                            var AvatarHtml = `
                            <a href="javascript:void(0);"
                               class="btn btn-primary disabled"><span>Selected</span></a>
                `;
                            return AvatarHtml;
                        }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="javascript:void(0);" class="btn btn-primary aptitude-btn"
                            data-bs-toggle="modal" data-bs-target="#selectMyTime"><span>Select
                                Your Time Here</span></a>
            `;
                        return AvatarHtml;
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