var jsonDataUrl = '../public/assets/json/shortlist_candidates.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.avatar = `../public/assets/img/profiles/${item.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#shortlist_candidates_data')) {
            $('#shortlist_candidates_data').DataTable().destroy();
        }
        var table = $('#shortlist_candidates_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<h2 class="table-avatar">
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
                        var AvatarHtml = `<a href="job-details">${data.jobTitle}</a>`;
                        return AvatarHtml;
                    }
                },
                { data: 'department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="action-label text-center">
                        <a class="btn btn-white btn-sm btn-rounded" href="#">
                            <i class="fa-regular fa-circle-dot text-danger"></i> Offered
                        </a>
                    </div>`;
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