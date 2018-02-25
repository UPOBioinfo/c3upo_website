
function displayNotice(message, status){
    $('#'+status).html(message);
    $('#'+status).css('display', 'block');
    setTimeout(function(){
        $('#successSection').html('');
        $('#successSection').css('display', 'none');
    }, 12000);
}


/**
 * To update the table. 
 * 
 * @returns {undefined}
 */
function updateJpTable(tableElement){
    $('#'+tableElement).bootstrapTable('refresh');
}

function displayCommand(textCommand){
    if(textCommand != ''){
        $('#extraInfoContent').html(textCommand);
        $("#extraInfoModal").modal();
    }
}