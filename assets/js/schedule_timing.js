var jsonDataUrl = '../public/assets/json/schedule_timing.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#schedule_timing_data')) {
            $('#schedule_timing_data').DataTable().destroy();
        }
        var table = $('#schedule_timing_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'number' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar"><img alt=""
                                src="${data.image}"></a>
                        <a href="profile">${data.name}<span>${data.role}</span></a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a href="job-details">${data.jobTitle}</a>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var timingsHtml = '';
                        data.userAvailableTimings.forEach(function (timing) {
                            timingsHtml += `<b>${timing.date}</b> - ${timing.time}<br>`;
                        });
                        return timingsHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="action-label text-center">
                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit_job"
                            href="#">
                            Schedule Time
                        </a>
                    </div>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: true,
            searching: false,
            info: true,
            // scrollX:false,
            // scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });