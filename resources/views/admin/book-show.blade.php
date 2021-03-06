@extends('layouts.admin.master')


@section('content')

  @include('layouts.version')

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"></h3>
          
          {{-- breadcramps --}}
          <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.books') }}">Manage Books</a></li>
            <li><a href="{{ route('admin.books') }}">Book Lists</a></li>
            <li class="@active('admin/book/show/*', 'active')">{{ $book['title'] }}</li>
          </ol>
          {{-- / brandcramps --}}

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">

              @include('flash::message')

              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">{{ $book['title'] }}</h3>
                </div>
                <div class="panel-body">
                    

                      {{-- button page --}}
                      <a class="btn btn-default" data-toggle="modal" href='#modal-page'>
                        <i class="fa fa-plus-circle"></i> Add Page
                      </a>

                      @if(count($pages) > 0)
                        <div class="pull-right">
                          <div class="btn-group">
                            <a href="#modal-page-index"  data-toggle="modal" class="btn btn-default"><i class="fa fa-plus"></i> Index</a>
                            <a href="#modal-indices"  data-toggle="modal" class="btn btn-default"><i class="fa fa-eye"></i> Indices</a>
                            <a href="#modal-page-update"  data-toggle="modal" class="btn btn-default"><i class="fa fa-edit"></i>Edit</a>
                            {{-- delete --}}
                            <button id="btn-delete-page" class="btn btn-danger"><i class="fa fa-trash"></i>Delete</button>
                          </div>
                        </div>
                        <hr>
                      @endif

                      {{-- pagination --}}
                      <center>
                        {{ $pages->links() }}
                      </center>
                      {{-- / pagination --}}

                      <div class="container-fluid">
                        
                        @foreach($pages as $page)
                            <img src="{{ url($page->page) }}" class="img-responsive img-thumbnail" alt="Image">
                        @endforeach

                      </div>

                      {{-- pagination --}}
                      <center>
                        {{ $pages->links() }}
                      </center>
                      {{-- / pagination --}}

                </div>
              </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          {{-- Footer --}}
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->

    {{-- modal page --}}
    <div class="modal fade" id="modal-page">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Add New Page</h4>
          </div>
          <div class="modal-body">
              {{-- form --}}
              <form class="form-horizontal" action="{{ url('/admin/page/store') }}" method="POST" id="page-form" enctype="multipart/form-data">
                 {{ csrf_field() }}
                 <input type="hidden" name="type" value="add">
                {{-- file --}}
                <div class="form-group">
                  <label class="control-label col-sm-2" for="file">File:</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control" name="file" id="file" required>
                  </div>
                </div>
              {{-- / file --}}

              <input type="hidden" id="slug" name="slug" value="{{ $book['slug'] }}">

          </div>
          <div class="modal-footer">
            <button id="submit-page" type="button" class="btn btn-default" data-dismiss="modal">Close <i class="fa fa-close"></i></button>
            <button type="submit" class="btn btn-primary">Save<i class="fa fa-check"></i></button>
              </form>
              {{-- / form --}}
          </div>
        </div>
      </div>
    </div>
    {{-- / modal page --}}


    {{-- update modal page --}}
     <div class="modal fade" id="modal-page-update">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Edit Page</h4>
          </div>
          <div class="modal-body">
              {{-- form --}}
              <form class="form-horizontal" action="{{ url('/admin/page/update') }}" method="POST" id="page-form" enctype="multipart/form-data">
                 {{ csrf_field() }}
                 {{ method_field('PATCH') }}
                {{-- file --}}
                <div class="form-group">
                  <label class="control-label col-sm-2" for="file">File:</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control" name="file" id="file" required>
                  </div>
                </div>
              {{-- / file --}}

              <input type="hidden" id="id" name="id" value="{{ isset($pages[0]->id) ? $pages[0]->id : '' }}">

          </div>
          <div class="modal-footer">
            <button id="submit-page" type="button" class="btn btn-default" data-dismiss="modal">Close <i class="fa fa-close"></i></button>
            <button type="submit" class="btn btn-primary">Update <i class="fa fa-check"></i></button>
              </form>
              {{-- / form --}}
          </div>
        </div>
      </div>
    </div>
  {{-- / update modal page --}}

{{-- delete page--}}
    @if(count($pages) > 0)
      {{-- confirm delete modal --}}
      <div class="modal fade" id="modal-confirm-delete">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">System Message</h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete this page <strong>permanently</strong>?</p>
            

               <form method="POST" action="{{ url('admin/page/delete/'.$page->id) }}">
                  {{ csrf_field() }}
                  <input type="hidden" name="_method" value="DELETE">
                  <input type="hidden" name="slug" id="slug" value="{{ $book->slug }}">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close
                <i class="fa fa-remove"></i>
              </button>
              <button id="submit-modal-page" type="submit" class="btn btn-danger">Confirm
                <i class="fa fa-check" aria-hidden="true"></i>
              </button>
            
              </form>
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
    @endif
{{-- / delete page --}}
@endsection


{{-- index --}}
<div class="modal fade" id="modal-page-index">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Index</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" method="POST" action="{{ route('index.store') }}">
           
            {{ csrf_field() }}

            <input type="hidden" name="book_id" value="{{ $book['id'] }}">
            <input type="hidden" name="page" value="{{ $pages->currentPage() }}">

            <div class="form-group">
              <label class="control-label col-sm-2" for="index">Description:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter index description" required>
              </div>
            </div>
            <div class="form-group"> 
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Submit</button>
              </div>
            </div>

          </form>

      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

{{-- indices --}}
<div class="modal fade" id="modal-indices">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Indices</h4>
      </div>
      <div class="modal-body">
        <table id="table-indices" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Page</th>
                <th><center>Action</center></th>
              </tr>
              </thead>

              <tbody>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>




@include('partials.confirm-delete')


@section('script')

<script type="text/javascript">
    
    $(function() {
        var table = $('#table-indices').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('index.all', ['id' => $book['id']]) }}',
            columnDefs: [
              { "width": "50%", "targets": 2 }
            ],
            columns: [
                {data: 'id'},
                {data: 'description'},
                {data: 'page'},
                {data: 'action'},
            ]
        });
    });

    var id;
    function deleteIndex(row) {
      $('#modal-confirm-delete .modal-title').html('System Message');
      $('#modal-confirm-delete .modal-body p').html('Are you sure you want to delete index <strong>' + row.description + '</strong>?');
      $('#modal-confirm-delete').modal();

      id = row.id;
  }
  $('#btn-confirm-delete').click(function(event) {
        /* Act on the event */
        $.ajax({
            type: "DELETE",
            url: '/admin/index/' + id,
            // data: {
            //   id : id
            // },
            success: function (data) {
                
                console.log(data);

                $('#modal-confirm-delete').modal('hide');
                dataTableRefresh('#table-indices');
                printSuccessMsg(data.title, 'Deleted');

            },
            error: function (data) {
                console.log('Error:', data);
            }

        });

  });


 @if(count($pages) > 0)
  $('#btn-delete-page').click(function(event) {
    /* Act on the event */
      $('#modal-confirm-delete-page .modal-title').html('System Message');
      $('#modal-confirm-delete-page .modal-body p').html('Are you sure you want to delete this <strong>page</strong>?');
      $('#modal-confirm-delete-page').modal();
  });

   $('#btn-confirm-delete-page').click(function(event) {
     /* Act on the event */
      window.location = "{{ route('page.delete', [$page->id, $book->slug]) }}";
   });
 @endif

  
</script>

@endsection

