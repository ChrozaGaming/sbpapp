var jsonDataUrl = '../public/assets/json/profile.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#profile_data')) {
            $('#profile_data').DataTable().destroy();
        }
        var table = $('#profile_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                { data: 'name' },
                { data: 'relationship' },
                { data: 'dateOfBirth' },
                { data: 'phone' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="dropdown dropdown-action text-end">
                        <a aria-expanded="false" data-bs-toggle="dropdown"
                            class="action-icon dropdown-toggle" href="#"><i
                                class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item"><i
                                    class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a href="#" class="dropdown-item"><i
                                    class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: false,
            searching: false,
            info: false,
            order:[],
            scrollX:false,
            scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });