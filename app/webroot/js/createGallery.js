$(document).ready(function() {
   $("#buttonCreateNewGallery").click(function () {
      $("#addGalleryForm").show();
    });
   
    $("#buttonCloseNewGallery").click(function () {
        $("#addGalleryForm").hide();
   })
   
   $("#deleteGalleryAction").click(function() {
       console.log(this);
//       var albumId = $(this).parent().parent().find('#albumId').attr('value');
       
	   $.ajax({
	        type: "POST",
	        url: "/albums/deleteAlbum",
            data: {albumId: 99},
            dataType: 'json',
	        success: function(response) {
                console.log(response);
	        }
       });
   });
   
 });
