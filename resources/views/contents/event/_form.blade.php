<p>
	<button type="button" id="btn-poster" class="btn btn-primary btn-block">
		Upload Poster (optional)
	</button>
	<div class="text-center">
		<img src="{{ media(@$event['cover'], 'small') }}" style="margin: 10px auto 20px;" alt="" id="poster-preview">
	</div>
</p>
<div class="form-group">
	<label>Title</label>
	{!! Form::text('title',null,['class'=>'form-control','placeholder'=>'What the event ?','required']) !!}
</div>

<div class="form-grup">
	<label>Schedule</label>
	{!! Form::text('schedule',null,['class'=>'form-control','id'=>'date','required']) !!}
</div>
<br>
<div class="form-group">
	<label>Ticket</label>
	{!! Form::number('htm',null,['class'=>'form-control','placeholder'=>'Harga tiket masuk','required']) !!}
</div>
<div class="form-group">
	<label>Tell about details event</label>
	{!! Form::textarea('description', null, ['class'=>'summernote','required']) !!}
</div>
<div class="form-group">
	<label>Location Name</label>
	{!! Form::text('location',null,['class'=>'form-control','placeholder'=>'Nama lokasi contoh : kantor Yukdonasi.org','required']) !!}
</div>
<div class="form-group">
	<label>Mark location on maps. (optional)</label>
	<input id="pac-input" class="controls" type="text"
        placeholder="Enter a location">
    <div id="map"></div>
</div>
{!! Form::hidden('lng',null,['id'=>'lng']) !!}
{!! Form::hidden('lat',null,['id'=>'lat']) !!}
{!! Form::hidden('cover',null,['id'=>'poster']) !!}
<div class="form-group">
	<button id="btnSave" type="button" class="btn btn-primary btn-lg">
		Save Event
	</button>
</div>
