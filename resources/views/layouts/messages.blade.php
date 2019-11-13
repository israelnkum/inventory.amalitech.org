@if(count($errors)>0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger  alert-dismissible fade show" style="background: darkred;border: none;  border-radius: 0;  color: #ffffff;" role="alert">
            <strong>Error!</strong> {{$error}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="alert alert-success text-white text-center bg-success alert-dismissible fade show" style="background: darkgreen;border: none; border-radius: 0; color: #ffffff;" role="alert">
        <strong>Success!</strong>   {{session('success')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

@endif

@if(session('error'))
    <div class="alert alert-danger bg-danger text-white text-center alert-dismissible fade show" style="background: darkred;border: none;  border-radius: 0; color: #ffffff;" role="alert">
        <strong>Error!</strong> {{session('error')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning bg-warning text-dark text-center alert-dismissible fade show" style="background: darkred;border: none;  border-radius: 0; color: #ffffff;" role="alert">
        <strong>Error!</strong> {{session('warning')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
