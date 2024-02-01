var jsonDataUrl = '../public/assets/json/attendance.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });

        if ($.fn.DataTable.isDataTable('#attendance_data')) {
            $('#attendance_data').DataTable().destroy();
        }
        var table = $('#attendance_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                            <h2 class="table-avatar">
                                <a class="avatar avatar-xs" href="profile"><img alt="" src="${data.image}"></a>
                                <a href="profile">${data.name}</a>
                            </h2>
                        `;
                        return imageHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var attendanceHtml = '';
                        data.attendance.forEach(att => {
                            if (att === true) {
                                attendanceHtml += `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#attendance_info"><i class="fa fa-check text-success"></i></a> `;
                            } else {
                                attendanceHtml += `<i class="fa fa-close text-danger"></i> `;
                            }
                        });
                        return attendanceHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var attendanceHtml = '';
                        data.attendance.forEach(att => {
                            if (att === true) {
                                attendanceHtml += `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#attendance_info"><i class="fa fa-check text-success"></i></a> `;
                            } else {
                                attendanceHtml += `<i class="fa fa-close text-danger"></i> `;
                            }
                        });
                        return attendanceHtml;
                    }
                }
            ],
            paging: true,
            searching: true,
            info: true,
            scrollX: false,
            scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });
