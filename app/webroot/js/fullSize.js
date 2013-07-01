$(document).ready(function() {
   $("#changeBackgroundColor > img").mouseover(function() {
       document.bgColor = $(this).attr("id");
   });
   
   $("#close_action").click(function() {
        window.close();
   });
});