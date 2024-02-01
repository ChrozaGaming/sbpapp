var jsonDataUrl = '../public/assets/json/roles_permissions.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#roles_permissions_data')) {
            $('#roles_permissions_data').DataTable().destroy();
        }
        var table = $('#roles_permissions_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                { data: 'ModulePermission' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="text-center">
                        <input type="checkbox" checked="">
                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="text-center">
                        <input type="checkbox" checked="">
                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="text-center">
                        <input type="checkbox" checked="">
                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="text-center">
                        <input type="checkbox" checked="">
                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="text-center">
                        <input type="checkbox" checked="">
                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="text-center">
                        <input type="checkbox" checked="">
                    </div>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: false,
            searching: false,
            info: false,
            order:[],
            // scrollX:false,
            // scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });