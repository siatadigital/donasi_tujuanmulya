@extends('admin::layouts.default')

@section('content')

  <div class="nav-tabs-custom">
    <div class="tab-content">
      <header>
        <h2>Kategori Project/Campaign</h2>
      </header>
      <hr>
        @if (isPermitted('admin.page.postCategories'))
        {!! Form::open(['route' => ['admin.page.postCategories'], 'method' => 'post']) !!}
          <div class="input-group">
            <input name="category" type="text" class="form-control input-lg" placeholder="Category Name" required>
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary btn-lg">Add new category</button>
            </span>
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
            <th>Name</th>
            <th style="width: 15%;"> </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($category as $key => $value)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $value['category_name'] }}</td>              
              
              @if (isPermitted('admin.page.deleteCategories'))
              <td>
                {!! Form::open(['route' => ['admin.page.deleteCategories', $value->id], 'method' => 'delete']) !!}
                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                {!! Form::close() !!}
              </td>
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>

      <!-- { $supporters->render() } -->
      <!-- dont forget render pagination -->
    </div><!-- /.tab-content -->
  </div><!-- /.nav-tabs-custom -->

@stop
