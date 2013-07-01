function updateNumberOfFav(type) {
    var fav = parseInt($("#favNumber").text());
    fav = (type == 'add') ? fav+1 : fav-1;
    $("#favNumber").html(fav);
}

function updateNumberOfComments(type, id) {
     var comment = parseInt($("#commentNumber").text());
     if (type == 'remove') {
        $("#comment_"+id).animate({ opacity: "hide" }, 1000);
        comment -= 1;
     } else {
        comment += 1;
     }
    $("#commentNumber").html(comment);
}

$(document).ready(function() {
    $("a.dropDownMenu").click(function() { //When trigger is clicked...
        //Following events are applied to the subnav itself (moving subnav up and down)
        $(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click

        $(this).parent().hover(function() {}, function(){
            $(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
        });
        return false;
    });
    
    $("ul.subnav").find('a').click(function() {
        var url = window.location.pathname;
        var brokenstring = url.split('/');
        var userId = brokenstring[brokenstring.length -2];
        var imageId = brokenstring[brokenstring.length -1];
        var name =  $("#textfieldNewImageName").attr("value");
        var albumId = $(this).find('input').attr('value');
        var dataToSend = {
            albumId: albumId,
            userId: userId,
            imageId: imageId
        };

        $.ajax({
            type: 'post',
            url: '/albums/add_image',
            data: dataToSend,
            success: function(response) {
                console.log(response);
            }
        });
    }); 
    
   $("#addTagsLink").click(function () {
      $("#formAddTags").show();
      
       $("#TagTitle").attr({
            value: ''
        })
    });
    
    $("#buttonCloseNewTags").click(function(){
        $("#formAddTags").hide();
    });
    
    $("#imageName").hover(function(){
        $(this).css({'background-color' : '#FFFFD3', 'font-weight' : 'bolder'});
    }, function() {
		var cssObj = {
		    'background-color' : "#FFFFFF",
    		'font-weight' : ''
		}
		$(this).css(cssObj);
    });
    
    $("#imageName").click(editImageName);
    
    function editImageName() {
        var value = $(this).text();
        
        function createTextfield(value) {
	        var cssObject = ({
	            'float': 'left',
	            'font-size': 12,
	            'width': 100
	        });
	        var textfield = $('<input>');  
	        textfield.attr({
	            id: 'textfieldNewImageName',
	            name: '[Image][name]',
	            value: value
	        });
	        textfield.css(cssObject);
	        $("#labelNameImage").css({
	            'width': 25
	        });
	        return textfield;
	    }
        
        var textfield = createTextfield($(this).text());
        
        function createCloseButton() {
            var closeButton = $('<input>');
	        closeButton.attr({
	            value: "Cancel",
	            id: 'cancelAction',
	            type: 'submit'
	        });
            
	        var cssObject = ({
	            'float': 'left',
	            'left': 223,
	            'position': 'absolute',
	            'top': 29,
	            'font-size': 10
	        });
	        closeButton.css(cssObject);
            
	        closeButton.bind("click", function() {
                    createDIVImageName(value);
                });
	        return closeButton;
        }
        var closeButton = createCloseButton();
        
        function createOkButton() {
            var okButton = $('<input>');
	        okButton.attr({
	            value: "Ok",
	            id: 'okAction',
	            type: 'submit'
	        });
	        var cssObject = ({
	            'float': 'left',
	            'left': 187,
	            'position': 'absolute',
	            'top': 29,
	            'font-size': 10
	        });
	        okButton.css(cssObject);
            
            okButton.bind('click', function(){
                var url = window.location.pathname;
		        var brokenstring = url.split('/');
		        var userId = brokenstring[brokenstring.length -2];
		        var imageId = brokenstring[brokenstring.length -1];
                var name =  $("#textfieldNewImageName").attr("value");
                
                var objToSend = {
                    imageId: imageId,
                    userId: userId,
                    name: name
                };
                
                $.ajax ({
                    type: "POST",
                    url: "/images/saveChanges",
                    data: objToSend,
                    dataType: 'json',
                    success: function(response) {
                        if (true == response.data.success) {
                            createDIVImageName(name);
                        }
                    }
                });
            });
            return okButton;
        }
        var okButton = createOkButton();
        
        
        $(this.parentNode).append(okButton);
        $(this.parentNode).append(closeButton);
        $(this).replaceWith(textfield);
        textfield.select();
        
        function createDIVImageName(data) {
            var imageName = $('<div>');
            imageName.attr({
                "id": 'imageName'
            });
            var cssObj = ({
                'float': 'left',
                'width': 125
            });
            imageName.html(data);
            imageName.css(cssObj);
             $("#labelNameImage").css({
                'width': 80
            });
            imageName.bind("click", editImageName);
            
            okButton.remove();
            closeButton.remove();
            textfield.replaceWith(imageName);
        }
        
    }
    
     $("#imageDescription").hover(function(){
        $(this).css({'background-color' : '#FFFFD3', 'font-weight' : 'bolder'});
    }, function() {
        var cssObj = {
            'background-color' : "#FFFFFF",
            'font-weight' : ''
        }
        $(this).css(cssObj);
    });
    
  
	$("#imageDescription").click(function() {
        if ($("#textfieldNewImageDescription").length == 0) { // Check if textarea field exist
            createEditDescriptionForm();
        }
    });
    
    function createEditDescriptionForm() {
        var currentValue = $("#imageDescriptionValue").text();
        var textarea = $('<textarea>');  
        var cssObject = ({
            'float': 'left',
            'font-size': 12,
            'width': 230,
            'overflow-x': 'hidden',
            'overflow-y': 'scroll'
        });
        textarea.attr({
            id: 'textfieldNewImageDescription',
            name: '[Image][name]',
            value: currentValue,
            rows: 7
        });
        textarea.css(cssObject);
       
        var closeButton = $('<input>');
        closeButton.attr({
            value: "Cancel",
            id: 'cancelAction',
            type: 'submit'
        });
        
        var cssObject = ({
            'float': 'left',
            'font-size': 10
        });
        closeButton.css(cssObject);
        
        closeButton.bind("click", function() {
           var divEl = $('<div>');
           divEl.attr({
            'id': 'imageDescriptionValue'
           });
           
           var cssObj = ({
            'float': 'left',
            'width': 230
           }); 
           
           divEl.css(cssObj);
           divEl.html(currentValue);
           
           textarea.replaceWith(divEl);
           $("div#imageDescriptionValue").html(currentValue);
           okButton.remove();
           closeButton.remove();
        });
        
        var okButton = $('<input>');
        okButton.attr({
            value: "Save",
            id: 'saveAction',
            type: 'submit'
        });
        
        var cssObject = ({
            'float': 'left',
            'font-size': 10
        });
        okButton.css(cssObject);
        okButton.bind("click", function() {
            var url = window.location.pathname;
            var brokenstring = url.split('/');
            var userId = brokenstring[brokenstring.length -2];
            var imageId = brokenstring[brokenstring.length -1];
            var description =  $("#textfieldNewImageDescription").attr("value");
            
            var objToSend = {
                imageId: imageId,
                userId: userId,
                description: description
            };
            
            $.ajax ({
                type: "POST",
                url: "/images/saveChanges",
                data: objToSend,
                dataType: 'json',
                success: function(response) {
                    if (true == response.data.success) {
//                       var divEl = $('<div>');
                        var divEl = $('<div>');
			           divEl.attr({
			             'id': 'imageDescriptionValue'
			           });
			           
			           var cssObj = ({
				            'float': 'left',
				            'width': 230
			           }); 
			           
			           divEl.css(cssObj);
			           divEl.html(currentValue);
			           
			           textarea.replaceWith(divEl);
			           $("div#imageDescriptionValue").html(description);
			           okButton.remove();
			           closeButton.remove();
                   }
                }
            });
        });
        
        $("div#imageDescriptionValue").html('');
        $("div#imageDescriptionValue").append(textarea);
        $("div#imageDescriptionValue").append(okButton);
        $("div#imageDescriptionValue").append(closeButton);
        
       
       
//        textarea.select();
//      $(this).unbind('click', createEditDescriptionForm);
//      $(this).unbind();
    }
    
    
    $('#next_image').click(function() {
	    var url = window.location.pathname;
        var brokenstring = url.split('/');
        var userId = brokenstring[brokenstring.length -2];
        var objToserver = {
            imageId: $("#next_image_id").attr("value"),
            userId: userId,
            type: 'next'
        };
        
        $.ajax ({
            type: "POST",
            url: "/images/findNavigationImage",
            data: objToserver,
            dataType: 'json',
            success: function(data) {
                    $("#next_image_id").attr({value: data.data});
                    $("#nextPicture > a").attr({
                        href: '/images/full_image/' + userId + '/' + data.data
                    });
                    $("#nextPicture").find('#IMGnext').animate({left: 110}, {queue: false, duration: 300, complete: function() {
                        $("#IMGnext").attr({
                            src: "http://fotkers.comuf.com/files/" + userId + "/Thumbnail/" + data.data + "_square.jpg",
                            alt: "cover picture"
                        });
                    $('#IMGnext').animate({left: 5},{duration:500});
                 }});
            }
       });   
    });

    $('#previous_image').click(function(){
        var url = window.location.pathname;
        var brokenstring = url.split('/');
        var userId = brokenstring[brokenstring.length -2];
        var objToserver = {
            imageId: $("#previous_image_id").attr("value"),
            userId: userId,
            type: 'previous'
        };
        
        $.ajax ({
            type: "POST",
            url: "/images/findNavigationImage",
            data: objToserver,
            dataType: 'json',
            success: function(data) {
                    $("#previous_image_id").attr({value: data.data});// updaty hidden field
                    
                    $("#previousPicture > a").attr({
                        href: '/images/full_image/' + userId + '/' + data.data
                    });
                    
                     $("#previousPicture").find('#IMGprevious').animate({left: -90},{queue:false, duration:500, complete: function() {
                           $("#IMGprevious").attr({
                                src: "http://fotkers.comuf.com/files/" + userId + "/Thumbnail/" + data.data + "_square.jpg",
                                alt: "cover picture"
                            });
                            $('#IMGprevious').animate({left: 5},{duration:500});
                     }});
                        
                    /*$("#previousPicture").animate({opacity: 'hide'}, 100, false, function() {
                       $("#IMGprevious").attr({
                            src: "http://fotkers.comuf.com/files/" + userId + "/Thumbnail/" + data.data + "_square.jpg",
                            alt: "cover picture"
                        });
                        $("#previousPicture").animate({ opacity: 'show'}, "slow");
                    });*/
                }
         });   
    });
});

/*        $.post(
            "/cakePHP/images/test",{sendValue: str },
            function(data) {
                alert('1');
                $("#previousPicture").animate({ opacity: 'hide' }, "slow"); 
            }, "json" );
 */ 
    
//  Action save form Mozda bi moglo za komentare!    
//    $('#previous_image').click(function(){
//            $(this).ajaxSubmit({
//                success: function(responseText, responseCode) {
//                    $('#ajax-save-message').hide().html(responseText).fadeIn();
//                    setTimeout(function(){
//                        $('#ajax-save-message').fadeOut();
//                   }, 5000);
//                }
//            });
//            return false;
//        });


