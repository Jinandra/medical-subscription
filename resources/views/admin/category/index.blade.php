@include('admin.partial.header')
@include('admin.partial.sidebar')
@include('admin.partial.topnav')

<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left">
      @if (Session::has('message'))
        <div role="alert" class="alert alert-success">
          {{ Session::get('message') }}
        </div>
      @endif
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Category</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float:right"><a href="javascript:void(0)" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        @include('admin.searchBar', [
          'placeholder' => 'Search for name...'
        ])
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
          <tr class="headings">
            <th class="column-title" width="25%">Name</th>
            <th class="column-title" width="35%">Description</th>
            <th class="column-title" width="10%" style="text-align:right;"># Media</th>
            <th class="column-title" width="15%" style="text-align:right;">Last Modified</th>
            <th class="column-title no-link last" width="15%"><span class="nobr">Action</span></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
            <tr class="pointer">
              <td width="25%">{{ $category->name }}</td>
              <td width="35%"><span style="font-size: smaller;">{{ $category->description }}</span></td>
              <td width="10%" style="text-align:right;">{{ $category->media->count() }}</td>
              <td width="15%" style="text-align:right;">{{ time_ago($category->updated_at) }}</td>
              <td class="last" width="15%">
                <div class="btn-group">
                  <button type="button" class="btn btn-info">Action</button>
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">                                         
                    <li><a href="{{ route('admin::categories::media', ['id' => $category->id]) }}"><i class='fa fa-pencil'></i> Edit Media</a></li>
                    <li><a href="{{ route('admin::categories::edit', ['id' => $category->id]) }}"><i class='fa fa-pencil-square-o'></i> Edit Category</a></li>
                    <li><a href="{{ route('admin::categories::delete', ['id' => $category->id]) }}" data-method="delete" data-alert="confirm" data-alert-text="Are you sure you want to delete '{{ $category->name }}'?" data-alert-title="Confirmation"><i class='fa fa-remove'></i> Delete</a></li>
                  </ul>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
        </table>
        <?php
        /*
        <div align="center">
          {!! $categories->appends(['s' => Input::get('s')])->render() !!}
        </div>
        */
        ?>
      </div>
    </div>
  </div>
</div>

@include('admin.partial.footerjs')
