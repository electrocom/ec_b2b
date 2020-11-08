jQuery(document).ready(function(){
    $('#newFieldContainerID').prependTo('#delivery');
});


$(document).ready(function()
{
    $("#fileuploader").uploadFile({
        url:'http://localhost/module/ec_b2b/uploadfile?action=Uploadfile&var3=Dupa',
        fileName:"myfile",
        maxFileCount:1,
        multiple:false,
        showDelete: true
    });
});