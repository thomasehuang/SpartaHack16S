$(document).ready(function() {
	var count = 1;
	var max_count = 10;
	var count_limit = max_count;
	if ($("#id_num_statements").val() < max_count) {
		count_limit = $("#id_num_statements").val();
	}
	var myVar = window.setInterval(function() {
		if (count == count_limit) count = 0;
		$(".flow_text").each(function() {
			$(this).css("display", "none");
		})
		$("#flow_text" + count.toString()).css("display", "inline-block");
		count += 1;
	}, 2000);

    $("#help_button").click(function(){
        $("#main_wrap").addClass("help_pushed");
        $("#help_wrap").addClass("help_pushed");
    })
    $("#help_return").click(function(){
        $("#main_wrap").removeClass("help_pushed");
        $("#help_wrap").removeClass("help_pushed");
    })

    $("#write_own").click(function(){
        $("#create_new").css("visibility", "visible");
    })
    $("#close_create_new").click(function(){
        $("#create_new").css("visibility", "hidden");
    })
})
