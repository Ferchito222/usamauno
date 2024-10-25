/* ------------------------------------------------------------------------------
*
*  # Fixed Columns extension for Datatables
*
*  Specific JS code additions for datatable_extension_fixed_columns.html page
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {


    // Table setup
    // ------------------------------

    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        columnDefs: [{ 
            orderable: false,
            width: '100px',
            targets: [ 5 ]
        }],
        dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });


    // Left fixed column example
    $('.datatable-fixed-left').DataTable({
        columnDefs: [
            { 
                orderable: false,
                targets: [5]
            },
            { 
                width: "200px",
                targets: [0]
            },
            { 
                width: "300px",
                targets: [1]
            },
            { 
                width: "200px",
                targets: [5, 6]
            },
            { 
                width: "100px",
                targets: [4]
            }
        ],
        scrollX: true,
        scrollY: '500px',
        scrollCollapse: false,
        searching: false,
        lengthChange: false,
        iDisplayLength: 30,
        //bSort:false,
        
        //scrollCollapse: true,
        fixedColumns: true,
        oLanguage: {
                    sEmptyTable: "No hay registros disponibles",
                    sInfo: "Hay _TOTAL_ registros. Mostrando de (_START_ a _END_)",
                    sLoadingRecords: "Por favor espera - Cargando...",
                    sSearch: "Filtro:",
                    sLengthMenu: "Mostrar _MENU_",
                    oPaginate: {
                        sLast: "Última página",
                        sFirst: "Primera",
                        sNext: "Siguiente",
                        sPrevious: "Ante    rior"
                    }
                }
    });
    // Left fixed column example
    $('.datatable-fixed-left_order_false').DataTable({
        columnDefs: [
            { 
                orderable: false,
                targets: [5]
            },
            { 
                width: "200px",
                targets: [0]
            },
            { 
                width: "300px",
                targets: [1]
            },
            { 
                width: "200px",
                targets: [5, 6]
            },
            { 
                width: "100px",
                targets: [4]
            }
        ],
        scrollX: true,
        scrollY: '500px',
        scrollCollapse: false,
        searching: false,
        lengthChange: false,
        iDisplayLength: 30,
        bSort:false,
        
        //scrollCollapse: true,
        fixedColumns: true,
        oLanguage: {
                    sEmptyTable: "No hay registros disponibles",
                    sInfo: "Hay _TOTAL_ registros. Mostrando de (_START_ a _END_)",
                    sLoadingRecords: "Por favor espera - Cargando...",
                    sSearch: "Filtro:",
                    sLengthMenu: "Mostrar _MENU_",
                    oPaginate: {
                        sLast: "Última página",
                        sFirst: "Primera",
                        sNext: "Siguiente",
                        sPrevious: "Ante    rior"
                    }
                }
    });


    // Right fixed column example
    $('.datatable-fixed-right').DataTable({
        columnDefs: [
            { 
                orderable: false,
                targets: [5]
            },
            { 
                width: "300px",
                targets: [0]
            },
            { 
                width: "300px",
                targets: [1]
            },
            { 
                width: "200px",
                targets: [5, 6]
            },
            { 
                width: "100px",
                targets: [3, 4]
            }
        ],
        scrollX: true,
        scrollY: '350px',
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 1
        }
    });
    // Right fixed column example inbox
    $('.datatable_inbox-fixed-right').DataTable({
        columnDefs: [
            { 
                orderable: false,
                targets: [5]
            },
            { 
                //width: "300px",
                targets: [0]
            },
            { 
                //width: "300px",
                targets: [1]
            },
            { 
                //width: "200px",
                targets: [5, 6]
            },
            { 
               // width: "100px",
                targets: [3, 4]
            }
        ],
        scrollX: true,
        scrollY: false,
        scrollCollapse: true,
        searching: false,
        lengthChange: false,
        //iDisplayLength: 100,
        fixedColumns: true,
        paginate: false,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 1
        }
    });


    // Left and right fixed columns
    $('.datatable-fixed-both').DataTable({
        columnDefs: [
            { 
                orderable: false,
                targets: [ 5 ]
            },
            { 
                width: "200px",
                targets: [0]
            },
            { 
                width: "100px",
                targets: [1]
            },
            { 
                width: "200px",
                targets: [5, 6]
            },
            { 
                width: "100px",
                targets: [4]
            }
        ],
        scrollX: true,
        //scrollY: '350px',
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
        }
    });


    // Fixed column with complex headers
    $('.datatable-fixed-complex').DataTable({
        columnDefs: [
            { 
                orderable: false,
                targets: [5]
            },
            { 
                width: "250px",
                targets: [0]
            },
            { 
                width: "250px",
                targets: [1]
            },
            { 
                width: "200px",
                targets: [5, 6]
            },
            { 
                width: "100px",
                targets: [4]
            }
        ],
        scrollX: true,
        scrollY: '350px',
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 1,
            rightColumns: 0
        }
    });



    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: "-1"
    });
    
});
