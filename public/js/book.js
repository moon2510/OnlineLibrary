$("#submit-comment").on('click',function(e){
	let rating = $("input[name='star_number']:checked").val();
	let comment = $("#comment-text-content").val();
	console.log(comment.length);
	if(!rating){
		alert("Please rate for this book");
		e.preventDefault();
		return;
	}
	if(comment.length > 1000){
		alert("The comment must be less than 1000 characters");
		e.preventDefault();
		return;
	}
});

function getRatingPaginate(number_comment,number_star){
	let token = $("meta[name='csrf-token']").attr("content");
	let book_id = $("#book-id").val();

	$.ajax({
		type:'POST',
		url: '/book/' + book_id,
		data:{
			'book_id':book_id,
			'number_comment':number_comment,
			'number_star':number_star,
			'_token':token
		},
		success:function(data){
			$("#user-comment-container").empty().append($(data).hide().fadeIn(500));
			InitPageItem();
		},
		error:function(jqXHR,exception){
			console.log(jqXHR);
		}
	});
}

function InitPageItem(){
	$(".page-item").on('click',function(e){
		e.preventDefault();
		let number_comment = $("select#number-comment-select").val();
		let number_star = $("#rating-number-select").val();
		window.location.href = "?page=" + $(this).text() + "&num_comment=" + number_comment + "&num_star=" + number_star;
	});
}

InitPageItem();

$("#number-comment-select").change(function(){
	let number_comment = $(this).val();
	let number_star = $("#rating-number-select").val();
	getRatingPaginate(number_comment,number_star);
});

$("#rating-number-select").change(function(){
	let number_comment = $("#number-comment-select").val();
	let number_star = $(this).val();
	getRatingPaginate(number_comment,number_star);
});

