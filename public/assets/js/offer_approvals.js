var jsonDataUrl = '../public/assets/json/offer_approvals.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.avatar = `../public/assets/img/profiles/${item.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#offer_approvals_data')) {
            $('#offer_approvals_data').DataTable().destroy();
        }
        var table = $('#offer_approvals_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar"><img alt=""
                                src="${data.avatar}"></a>
                        <a href="profile">${data.name} <span>${data.role}</span></a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <a href="job-details">${data.jobTitle} </a>`;
                        return AvatarHtml;
                    }
                },
                { data: 'jobType' },
                { data: 'pay' },
                { data: 'annualIP' },
                { data: 'longTermIP' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Requested') {
                            return ` <label class="badge bg-inverse-warning"
                            style="display: inline-block;min-width: 90px;">${data.status}</label>`;
                        } else if (data.status === 'Rejected') {
                            return ` <label class="badge bg-inverse-danger"
                            style="display: inline-block;min-width: 90px;">${data.status}</label>`;
                        } else{
                           return `<label class="badge bg-inverse-success"
                           style="display: inline-block;min-width: 90px;">${data.status}</label>`;
                        }
                    }
                }, 
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="dropdown dropdown-action text-center">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="fa-regular fa-thumbs-up m-r-5"></i>
                                Approved</a>
                            <a class="dropdown-item" href="#"><i class="fa fa-ban m-r-5"></i> Rejected</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                }, 
            ],
            paging: true,
            searching: false,
            info: true,
            order:[],
            scrollX:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });