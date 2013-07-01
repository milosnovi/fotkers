$(document).ready(function() {
    
   $("#photo > img").click(function () {
        $("#photo > img").css("border", "0px");
         $(this).css("border", "1px solid red");
         
         var currentId = $(this).attr("id");
         var current = currentId.split(".");
         var imageId = current[1];
         var userId = current[2];
         
         $("#cover_picture").attr({
            src: "http://fotkers.comuf.com/files/" + userId + "/Original/" + imageId + ".jpg",
            alt: "cover picture"
         });
        
        $("#albumCoverIdentifier").attr({
            value: imageId
        });
   });
   
//    $("#photo > img").hover(function() {
//        $(this).css("border", "1px blue solid");
//    }, function(){
//         $(this).css("border", "0px");
//    });
    
    var tabContainers = $('div.addPhotoToAlbum > div');
    tabContainers.hide().filter(':first').show();
    
    $('div.addPhotoToAlbum ul.tabNavigation  a').click(function () {
        tabContainers.hide().filter(this.hash).show();
        
        $('div.addPhotoToAlbum ul.tabNavigation li').removeClass('selected');
        
        $(this.parentNode).addClass('selected');
        return false;
    }).filter(':first').click();
    
 });
