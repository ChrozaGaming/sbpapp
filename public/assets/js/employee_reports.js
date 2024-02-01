var jsonDataUrl = '../public/assets/json/employee_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.Image = `../public/assets/img/profiles/${item.Image}`;
        });
        if ($.fn.DataTable.isDataTable('#employee_reports_data')) {
            $('#employee_reports_data').DataTable().destroy();
        }
        var table = $('#employee_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <h2 class="table-avatar">
                            <a href="profile" class="avatar"><img alt=""
                                            src="${data.Image}"></a>
                            <a href="profile" class="text-primary">${data.EmployeeName} <span>${data.Number}</span></a>
                        </h2>

            `;
                        return AvatarHtml;
                    }
                },
                { data: 'EmployeeType' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <div class="text-info">${data.Email}</div>
            `;
                        return AvatarHtml;
                    }
                },
                { data: 'Department' },
                { data: 'Designation' },
                { data: 'JoiningDate' },
                { data: 'DOB' },
                { data: 'MartialStatus' },
                { data: 'Gender' },
                { data: 'TerminatedDate' },
                { data: 'RelievingDate' },
                { data: 'Salary' },
                { data: 'Address' },
                { data: 'ContactNumber' },
                { data: 'EmercencyContactDetails' },
                { data: 'Experience' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <button class="btn btn-outline-success btn-sm">${data.Status}</button>
            `;
                        return AvatarHtml;
                    }
                },
            ],
            paging: true,
            searching: false,
            info: true,
            order:[],
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });