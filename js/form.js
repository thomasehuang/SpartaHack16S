$(document).ready(function() {
	$(document.body).on('click', '.vote_up' ,function() {
		$("#id_id").attr("value", $(this).parent().attr("value"));
		$("#id_name").attr("value", $(this).attr("value"));
		$("#id_score").attr("value", $(this).parent().siblings(".vote_score").html());
		$("#id_up").attr("value", 1);
		$.post("submit.php", $("#form_vote").serialize())
            .done(function (data) {
            $("#id_vote_boxes").html(data);
        });
	})

	$(document.body).on('click', '.vote_down' ,function() {
		$("#id_id").attr("value", $(this).parent().attr("value"));
		$("#id_name").attr("value", $(this).attr("value"));
		$("#id_score").attr("value", $(this).parent().siblings(".vote_score").html());
		$("#id_up").attr("value", 0);
		$.post("submit.php", $("#form_vote").serialize())
            .done(function (data) {
            $("#id_vote_boxes").html(data);
        });
	})
})