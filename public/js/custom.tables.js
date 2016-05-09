/*
* Author: William Syms
* Website: williamsyms.com
* Date: 2013-05-08
*/

/* See datatables.net for
* the full documentation on how
* to use the plug-in
*/

var oTable;

$(document).ready(function(){
    
    /* Add a click handler to the rows - this could be used as a callback */
    $(".pagination-table tbody tr, .pagination-table-audit tbody tr").click( function( e ) {
        if ( $(this).hasClass('row_selected') ) {
            $(this).removeClass('row_selected');
        }
        else {
            oTable.$('tr.row_selected').removeClass('row_selected');
            $(this).addClass('row_selected');
        }
    });
     
    /* Add a click handler for the delete row */
    $('#delete').click( function() {
        var anSelected = fnGetSelected( oTable );
        if ( anSelected.length !== 0 ) {
            oTable.fnDeleteRow( anSelected[0] );
        }
    } );
     
    /* Init the table */
    oTable = $('.pagination-table').dataTable({
        "sPaginationType": "full_numbers",         
    });   
    $('.pagination-table-audit').dataTable({
        "sPaginationType": "full_numbers", 
         //"scrollX": true,
         "aaSorting": [[ 0, "desc" ]],         
    });
    $('.custom-datatable').dataTable({
        bFilter: false, 
        bInfo: false,
        paging: false
    });
    
    
    $("#table-popup-search").dataTable({             
        /*"bSort":true,
        "bFilter": true,
        "bSearchable":true,
        "bPaginate":false,
        "bInfo":false,  */
        "sPaginationType": "full_numbers",
    });
    
});

/* Get the rows which are currently selected */
function fnGetSelected( oTableLocal )
{
    return oTableLocal.$('tr.row_selected');
}