var jsonDataUrl = '../public/assets/json/salary.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#salary_data')) {
            $('#salary_data').DataTable().destroy();
        }
        var table = $('#salary_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar"><img alt=""
                                src="${data.image}"></a>
                        <a href="profile">${data.Employee} <span>${data.Role}</span></a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'EmployeeID' },
                { data: 'Email' },
                { data: 'JoinDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `  <div class="dropdown">
                        <a href="" class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">${data.Role} </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Software Engineer</a>
                            <a class="dropdown-item" href="#">Software Tester</a>
                            <a class="dropdown-item" href="#">Frontend Developer</a>
                            <a class="dropdown-item" href="#">UI/UX Developer</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
                { data: 'Salary' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a class="btn btn-sm btn-primary" href="salary-view">${data.Payslip}</a>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_salary"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_salary"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
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