$(function(){

    if($("#clientsTable").length){

        $("#clientsTable").DataTable({
            pageLength:25,
            responsive:true
        });

    }

});