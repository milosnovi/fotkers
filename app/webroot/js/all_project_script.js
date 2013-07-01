$(document).ready(function(){
    console.log($("#ImageSearchActionForm"));
    $("#ImageSearchActionForm").submit(function() {
        var searchvalue = $("#ImageSearchTitle").attr('value');
        if (0 < searchvalue.length && searchvalue != 'Input text first!') {
            return true;
        } else {
            $("#ImageSearchTitle").attr({value: 'Input text first!'});
            $("#ImageSearchTitle").select();
            console.log('false');
            return false;
        }
    });
});