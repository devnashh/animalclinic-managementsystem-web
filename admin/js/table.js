
    $('#user-table').DataTable({
        dom:
            "<'row'<'col-sm-3'l><'col-sm-5'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        responsive: true,   
        ordering: true,
        searching: true,
        paging: true,
        buttons: [
            {
                extend: "copyHtml5",
                className: "btn btn-outline-secondary btn-sm",
                text: ' Copy',
            },
            {
                extend: "csvHtml5",
                className: "btn btn-outline-secondary btn-sm",
                text: '  CSV',
            },
            {
                extend: "excel",
                className: "btn btn-outline-secondary btn-sm",
                text: '  Excel',
            },
            {
                extend: "pdfHtml5",
                className: "btn btn-outline-secondary btn-sm",
                text: '  PDF',
            },
            {
                extend: "print",
                className: "btn btn-outline-secondary btn-sm",
                text: '  Print',
            },
        ],
    });

