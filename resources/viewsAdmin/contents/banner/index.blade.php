@extends('admin::layouts.default')

@section('content')

  <div class="nav-tabs-custom">
    <div class="tab-content">
      <header>
        <h2>Banner Foto</h2>
      </header>
      <hr>
        @if (isPermitted('admin.banner.postBanner'))
        {!! Form::open(['route' => ['admin.banner.postBanner'], 'method' => 'post']) !!}
          <div class="row">
            <div class="col-md-4">
              <label for="link">Link</label>
              <input id="link" name="link" type="text" class="form-control" placeholder="Wajib ada http:// (contoh: http://tujuanmulia.id/) " required>
            </div>
            <div class="col-md-4">
              <label for="photo">Foto</label>
              <div class="input-group">
                {!! Form::text('photo', null, ['class'=>'form-control','required','placeholder'=>'Foto Banner','readonly','id'=>'cover']) !!}
                <span class="input-group-btn">
                  <button type="button" id="btn-browse-cover-project" class="btn btn-default">
                    Browse Image
                  </button>
                </span>
              </div>
              <div class="progress" style="display:none" id="progress_cover">
                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                </div>
              </div>
              <div class="hide">
                <input type="file" id="browse-cover-project">
              </div>
            </div>
            <div class="col-md-4">
              <label>&nbsp;</label>
              <div>
                <button type="submit" class="btn btn-primary">Add new banner</button>
              </div>
            </div>
          </div>
        {!! Form::close() !!}
        @endif
      <hr>
      <!-- { $supporters->render() } -->
      <!-- dont forget render pagination -->

      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Link</th>
            <th> </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($banner as $key => $value)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>
			  	      <img src="{{ media($value['photo'],'medium') }}" width="200">
              </td>              
              <td>
                <a href="{{ $value['link'] }}" target="_blank">
                  {{ $value['link'] }}
                </a>
              </td>              
              
              <td>
                @if (isPermitted('admin.banner.deleteBanner'))
                {!! Form::open(['route' => ['admin.banner.deleteBanner', $value->id], 'method' => 'delete']) !!}
                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                {!! Form::close() !!}
                @endif
                @if ($value['is_modal_popup'] == "0")
                  @if (isPermitted('admin.banner.setModalPopup'))
                  <a href="{{ route('admin.banner.setModalPopup', $value['id']) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-search"></i>
                    Set Modal Popup
                  </a>
                  @endif
                @else
                  @if (isPermitted('admin.banner.removeModalPopup'))
                  <a href="{{ route('admin.banner.removeModalPopup', $value['id']) }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-search"></i>
                    Remove Modal Popup
                  </a>
                  @endif
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <!-- { $supporters->render() } -->
      <!-- dont forget render pagination -->
    </div><!-- /.tab-content -->
  </div><!-- /.nav-tabs-custom -->

@stop
@section('scripts')
<script src="{{ asset('js/project-create.js') }}"></script>
@stop
