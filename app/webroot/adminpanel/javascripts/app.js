/**** fancy box *****/
$(document).ready(function() {
	$(".pop_box").fancybox();

    $(".pop_box_val_eng_refresh").fancybox({
        afterShow: function (ob) {
            $("div.fancybox-opened .form-horizontal").validationEngine();
        }
    })
});

(function($){
	$(window).load(function(){
		$("#scrollAdd").mCustomScrollbar({
			scrollButtons:{
				enable:true
			}
		});
		$("#scrollAdd").hover(function(){
			$(document).data({"keyboard-input":"enabled"});
			$(this).addClass("keyboard-input");
		},function(){
			$(document).data({"keyboard-input":"disabled"});
			$(this).removeClass("keyboard-input");
		});
		$(document).keydown(function(e){
			if($(this).data("keyboard-input")==="enabled"){
				var activeElem=$(".keyboard-input"),
					activeElemPos=Math.abs($(".keyboard-input .mCSB_container").position().top),
					pixelsToScroll=60;
				if(e.which===38){ //scroll up
					e.preventDefault();
					if(pixelsToScroll>activeElemPos){
						activeElem.mCustomScrollbar("scrollTo","top");
					}else{
						activeElem.mCustomScrollbar("scrollTo",(activeElemPos-pixelsToScroll),{scrollInertia:400,scrollEasing:"easeOutCirc"});
					}
				}else if(e.which===40){ //scroll down
					e.preventDefault();
					activeElem.mCustomScrollbar("scrollTo",(activeElemPos+pixelsToScroll),{scrollInertia:400,scrollEasing:"easeOutCirc"});
				}
			}
		});
	});
})(jQuery);

//eBuilders actions
$(document).ready(function(){
	
		//Open links in new tab
		$('.f_help').find('a').attr('target','_blank');
		$('a.new_tab').attr('target','_blank');
		
		// tool tip
		$('.view').tooltip();
		$('.edit').tooltip();
		$('.delete').tooltip();
		
		//date picker
		$('.datepicker').datepicker({ dateFormat: "yy-mm-dd" });
		
		//date and time picker
    $(".datetimepicker").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
		
		//choosen
		$(".chosen").chosen();
		
		/*tinymce.init({
		    selector: "textarea",
		    theme: "modern",
		    width: "51%",
		    height: 200,
		    plugins: [
		         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		         "save table contextmenu directionality emoticons template paste textcolor"
		   ],
		   content_css: "css/content.css",
		   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
		   style_formats: [
		        {title: 'Bold text', inline: 'b'},
		        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
		        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
		        {title: 'Example 1', inline: 'span', classes: 'example1'},
		        {title: 'Example 2', inline: 'span', classes: 'example2'},
		        {title: 'Table styles'},
		        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
		    ]

		 });*/
		
		
		
		// form validation
		$(".form-horizontal").validationEngine();
		
		// timepicker		
		//$('.timepicker').timepicker();	
		
		// select tag 		
		//$('.selecttags').select2({'tags':['clever','is','better','clevertech'],'placeholder':'type clever, or is, or just type!','width':'40%','tokenSeparators':[',',' ']});
		
		// Tag input
		//$(".tagsinput").tagsinput();
		
});
$(document).ready(function(){
	$('.chzn-container').css("width", "51.1%");
});
/*** tool tipsy ***/
$(document).ready(function(){
	$('.tipsy').tipsy();
});

$(document).ready(function(){
	$('.datatable').dataTable();
});

$(document).ready(function() {
    $("#user_campaign_datatable, #user_purchase_datatable").dataTable({
        "columnDefs": [
            {
                targets: [4,5],
                render: function(data) {
                    return $.formatDateTime("M d, yy g:ii a", new Date(data));
                }
            }
        ]
    });

});