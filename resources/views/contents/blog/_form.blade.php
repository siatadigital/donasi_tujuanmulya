@include('partials.alert-error')
<header class="page-header text-center">
	{!! Form::text('title', null, ['class'=>'form-control input-lg input-title-blog','required','placeholder'=>'Title here ..','autocomplete'=>'off']) !!}
	{{-- <p>By : <a href="">My Mother Is Hero</a></p> --}}
</header>

<div class="form-group">
	<label for="cover">Sampul Blog (max: 1 MB)</label>
	<div class="input-group">
		{!! Form::text('cover', null, ['class'=>'form-control','required','placeholder'=>'Foto Cover Blog','readonly','id'=>'cover']) !!}

		<span class="input-group-btn">
			<button type="button" id="btn-browse-cover-project" class="btn btn-default">
				Browse Image
			</button>
		</span>
	</div>
</div>

<div class="progress" style="display:none" id="progress_cover">
	<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
	</div>
</div>

<div class="hide">
	<input type="file" id="browse-cover-project">
</div>
<div class="cover thumbnail-project" id="cover-preview" style="background-image:url({{ media(@$blog['cover'], 'small') }});width: 100%;height: 260px;margin: 10px auto 20px;"></div>
<section>
	{!! Form::textarea('content', null, ['class'=>'summernote']) !!}
</section>
<section>
	
	@if(Route::current()->getName() == "blog.getCreate")
		<label for="slug">Custom Slug <small>(opsional)</small></label>
		<input type="text" name="slug" class="form-control" id="custom_slug" onkeyup="custom_slugs(this)">
	@else
		<label for="slug">Slug</label>
		<input type="text" name="slug" value="{{ $blog->slug }}" class="form-control" readonly>
		<br>
		<label for="slug">Edit Custom Slug <small>(opsional)</small></label>
		<input type="text" name="edit_slug" class="form-control" id="custom_slug" onkeyup="custom_slugs(this)">
	@endif
</section>
<br>
<section>
	<label for="kategori">Pilih Kategori</label>
	<select name="blog_categories_id" id="kategori" class="form-control" required>
		@if(Route::current()->getName() == "blog.getCreate")
			@foreach($kategori as $item)
				<option value="{{ $item->id }}">{{ $item->title }}</option>
			@endforeach
		@elseif(Route::current()->getName() == "blog.getEdit")
			@foreach($kategori as $item)
				@if($blog->blog_categories_id == $item->id)
					<option value="{{ $item->id }}" selected>{{ $item->title }}</option>
				@else
					<option value="{{ $item->id }}">{{ $item->title }}</option>
				@endif
			@endforeach
		@endif
	</select>
</section>
<br>
<section>
	<label for="status">Status Publikasi</label>
	<select name="status" id="status" class="form-control">
		@if (Route::current()->getName() == "blog.getCreate")
			<option value="publish">Publikasikan</option>
			<option value="draft">Draft</option>
		@elseif (Route::current()->getName() == "blog.getEdit")
			<option value="publish" {{ $blog->status === 'publish' ? 'selected' : '' }}>Publikasikan</option>
			<option value="draft" {{ $blog->status === 'draft' ? 'selected' : '' }}>Draft</option>
		@endif
	</select>
</section>
<br><br>
<p class="text-center">
	{!! Form::submit('Save & Publish',['class'=>'btn btn-primary btn-lg']) !!}
</p>
