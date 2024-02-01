var jsonDataUrl = '../public/assets/json/leave_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.employee.avatar = `../public/assets/img/profiles/${item.employee.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#leave_reports_data')) {
            $('#leave_reports_data').DataTable().destroy();
        }
        var table = $('#leave_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <h2 class="table-avatar">
                                    <a href="${data.employee.profileUrl}" class="avatar"><img alt=""
                                            src="${data.employee.avatar}"></a>
                                    <a href="${data.employee.profileUrl}">${data.employee.name} </a><span>${data.employee.id}</span>
                                </h2> `;
                        return AvatarHtml;
                    }
                },
                { data: 'date' },
                { data: 'department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.leaveType === 'Sick Leave') {
                            return ` <div class="text-center"> <button class="btn btn-outline-info btn-sm">${data.leaveType}</button> </div>`;
                        } else if (data.leaveType === 'Parenting Leave'){
                            return `<div class="text-center"> <button class="btn btn-outline-warning btn-sm">${data.leaveType}</button></div>`;
                        } else {
                            return `<div class="text-center"> <button class="btn btn-outline-danger btn-sm">${data.leaveType}</button> </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="text-center">
                        <span class="btn btn-danger btn-sm">${data.numberOfDays}</span></div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="text-center">
                        <span class="btn btn-warning btn-sm">${data.remainingLeave}</span></div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="text-center">
                        <span class="btn btn-success btn-sm">${data.totalLeaves}</span></div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="text-center">
                        ${data.totalLeaveTaken}</div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="text-center">
                        ${data.leaveCarryForward}</div>`;
                        return AvatarHtml;
                    }
                }
            ],
            paging: true,
            searching: false,
            info: true,
            order:[]
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });