@extends('layouts.main')

@section('page-title')
{{$book->name}}
@endsection

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/book.css') }}">
@endsection

@section('page-content')
<div class="book-detail-container">
	
	<div class="row">
		<div class="col-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">
					<i class="fas fa-home"></i>
				</a></li>
				<li class="breadcrumb-item"><a href="{{ route('category', $book->categories->id) }}">{{$book->categories->name}}</a></li>
				<li class="breadcrumb-item active">{{$book->name}}</li>
			</ol>
		</div>
		<div class="col-5">
			<div class="book-cover">
				<img src="{{$book->img}}">
			</div>
		</div>
		<div class="col-7">
			<div class="book-info-container">
				<div class="book-name">
					<b>{{$book->name}}</b>
				</div>

				<hr>

				<div class="book-author-detail">
					<div class="container">
						<div class="row">
							<div class="col-6 text-center">
								Author: {{$book->author}}
							</div>

							<div class="col-6 text-center">
								Published: {{$book->published_year}}
							</div>
						</div>
					</div>
				</div>

				<hr>
				
				<div class="book-author-detail">
					<div class="container">
						<div class="row">
							<div class="col-6 text-center">
								Price: <b><span style="color: #e74c3c">{{number_format($book->price)}}</span></b> VND
							</div>

							<div class="col-6 text-center" id="quantity-field">
								Remaining: {{$book->quantity}} books
							</div>
						</div>
					</div>
				</div>
				
				<hr>

				<div class="d-flex justify-content-center" style="width: 100%">
					<button class="get-book-btt" data-book-id="{{$book->id}}">Get it now</button>
				</div>

				<hr>
				
				<div class="description-section">
					Book Description:
					<div>{{$book->describes}}</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<hr>

<div class="commented-container">
	<div class="evalate-container">
		<div class="row">
			<div class="col-lg-8 col-md-12">
				<div class="evalate-data">
					<div class="row">
						<div class="col-md-4">
							<div class="average-evalate">
								<div style="text-align: center;">
									<div style="font-size: 1.3em;">
										Rating
									</div>
									<div style="font-size: 2.5em;color: #e74c3c;">
										{{ $average_evalate }} / 5
									</div>
									<div>
										@php
										$remain = 5  - $average_evalate;
										@endphp

										@for($i = 0; $i < $average_evalate;$i++)
										<i class="fas fa-star" style="color: #f1c40f;font-size: 15px"></i>
										@endfor

										@for($i = 0; $i < $remain;$i++)
										<i class="fas fa-star" style="font-size: 15px;color: darkgray"></i>
										@endfor
									</div>
									<br>
									<div style="font-size: 1.1em;font-weight: bold">
										{{ $count_ratings }} rated
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="evalate-on-percent">
								<div style="width: 100%">
									@php
									$percent_of_rating_decode = json_decode($percent_of_ratings);
									@endphp
									@for($i = 1; $i <= 5;$i++)
									<div class="percent-container">
										<div class="row">
											<div class="col-lg-2 col-2 text-center">
												{{ $i }} <i class="fas fa-star"></i>
											</div>
											<div class="col-lg-8 col-8">
												<div class="percent-outer">
													<div class="percent-inner" style="width: {{ $percent_of_rating_decode[$i - 1] }}%"></div>
												</div>
											</div>
											<div class="col-lg-2 col-2 text-center">
												{{ $percent_of_rating_decode[$i - 1] }} %
											</div>
										</div>
									</div>
									@endfor
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-12">
				<div style="width: 100%;height: 100%;display: flex;justify-content: center;align-items: center">
					@if(!Auth::check())
					<a class="btn btn-warning" href="/login">Login to rating !</a>
					@elseif ($ratings !== null)
					<button class="btn btn-success" data-toggle="collapse" data-target="#comment-section">You are commented on this book , click here to edit!</button>
					@else
					<button data-toggle="collapse" data-target="#comment-section" id="comment-btn">Write Comment</button>
					@endif
				</div>
			</div>
		</div>
	</div>
	@if(Auth::check())

	<form action="{{  route('add_rating') }}" method="POST">
		{{ csrf_field() }}
		<div id="comment-section" class="collapse">
			<div class="row">
				<div class="col-3">
					<div class="user-info">
						<div style="width: 100%;">
							<div style="width: 100%" class="d-flex justify-content-center">
								@if(Auth::user()->image)
								<img class="user-avatar" src="{{ Auth::user()->image }}">
								@else
								<img class="user-avatar" src="{{ asset('images/default.png') }}">
								@endif
							</div>
							<br>
							<div class="user-detail">
								<div>{{  Auth::user()->firstname }}</div>		
							</div>
						</div>
					</div>
				</div>
				<div class="col-9">
					<div class="rating-section">
						<table class="table">
							<thead>
								<tr>
									<th>Angry</th>
									<th>Disappointed</th>
									<th>Neutral</th>
									<th>Good</th>
									<th>Excellent</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									@for($i = 1;$i <= 5;$i++)
									@if($user_rating && $user_rating->star_number == $i)
									<td>
										<input type="radio" name="star_number" value="{{ $i }}" id="rating-star-{{ $i }}" checked>
										<label for="rating-star-{{ $i }}" class="rating-label"><i class="fas fa-star"></i></label>
									</td>
									@else
									<td>
										<input type="radio" name="star_number" value="{{ $i }}" id="rating-star-{{ $i }}">
										<label for="rating-star-{{ $i }}" class="rating-label"><i class="fas fa-star"></i></label>
									</td>
									@endif

									@endfor			
								</tr>
							</tbody>
						</table>
					</div>

					<div class="comment-textarea">
						<textarea name="comment" placeholder="Bình luận cuốn sách này" id="comment-text-content" required>{{ ($user_rating == null ) ? '' : $user_rating->comment }} </textarea>
					</div>

					<br>

					<input type="label" value="{{ $book->id }}" name="book_id" id="book-id" style="display: none;">
					<input type="label" value="{{ Auth::user()->id }}" name="user_id" id="book-id" style="display: none;">

					<div style="width: 100%:height: 80px">

						@if($ratings !== null)
						<button type="submit" class="btn btn-success float-right mr-5"><b>Update</b></button>
						<!-- <form action="{{ route('delete_rating') }}" method="POST">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="_method" value="DELETE">
							<input type="hidden" name="id" value="{{ $book->id }}">
						</form> -->
						@else
						<button type="submit" class="btn btn-success float-right mr-5"><b>Send</b></button>
						@endif
					</div>
				</div>
			</div>
		</div>
	</form>
	
	@endif
	<br>

	<div class="filter-option">
		<div class="row">
			<div class="col-4">
				<div class="mt-2 text-center" style="font-size: 1.1em">
					Comments
				</div>
			</div>
			<div class="col-4">
				<select class="browser-default custom-select custom-select-md" style="display: inline-block;" id="number-comment-select">
					<option value="0" {{ ($num_comment == 0) ? 'selected' : "" }}>Show all comment</option>
					<option value="5" {{ ($num_comment == 5) ? 'selected' : "" }}>5 comment</option>
					<option value="10" {{ ($num_comment == 10) ? 'selected' : "" }}>10 comment</option>
					<option value="15" {{ ($num_comment == 15) ? 'selected' : "" }}>15 comment</option>
				</select>
			</div>
			<div class="col-4">
				<select class="browser-default custom-select custom-select-md" style="display: inline-block;" id="rating-number-select">
					<option value="0" {{ ($num_star == 0) ? 'selected' : "" }}>Show all star</option>
					<option value="5" {{ ($num_star == 5) ? 'selected' : "" }}>5 star</option>
					<option value="4" {{ ($num_star == 4) ? 'selected' : "" }}>4 star</option>
					<option value="3" {{ ($num_star == 3) ? 'selected' : "" }}>3 star</option>
					<option value="2" {{ ($num_star == 2) ? 'selected' : "" }}>2 star</option>
					<option value="1" {{ ($num_star == 1) ? 'selected' : "" }}>1 star</option>
				</select>
			</div>
		</div>
	</div>
	
	<br>

	<div class="user-comment-container" id="user-comment-container">
		@foreach($ratings as $rating)
		<div class="other-user-comment">
			<div class="row">
				<div class="col-3">
					<div class="user-info">
						<div>
							<div>
								@if($rating->user->image)
								<img src="{{ $rating->user->image }}" class="user-avatar">
								@else
								<img src="{{ asset('images/default.png') }}" class="user-avatar">
								@endif
							</div>
							<div class="text-center" style="font-weight: bold;font-size: 1.1em">
								{{ $rating->user->firstname }}
							</div>
						</div>
					</div>
				</div>
				<div class="col-9">
					<div style="width: 100%;font-weight: bold" class="mt-2">
						{{ $rating->user->firstname }} commented at {{ $rating->created_at }} 
					</div>
					<div style="width: 100%;font-weight: bold" class="mt-2">
						@php
						$remaining_rating = 5 - $rating->star_number;
						@endphp

						@for($i = 1; $i <= $rating->star_number; $i++)
						<i class="fas fa-star" style="color: #f1c40f"></i>
						@endfor

						@for($i = 1; $i <= $remaining_rating; $i++)
						<i class="fas fa-star" style="color: darkgray"></i>
						@endfor

						@if($rating->star_number == 1) <b> Angry</b>
						@elseif ($rating->star_number == 2) <b> Disappointed</b>
						@elseif ($rating->star_number == 3) <b> Neutral</b>
						@elseif ($rating->star_number == 4) <b> Good</b>
						@elseif ($rating->star_number == 5) <b> Excellent</b>
						@endif					
					</div>

					<div style="width: 100%;font-weight: bold" class="mt-2">
						{{ $rating->comment }}
					</div>
				</div>
			</div>
		</div>
		<br>
		@endforeach

		<div style="width: 100%;display: flex;justify-content: center;align-items: center">
			{!! $ratings->appends(request()->query())->links() !!}
		</div>
	</div>
</div>
@endsection

@section('custom-js')
<script type="text/javascript" src="{{ asset('js/book.js') }}"></script>
@endsection