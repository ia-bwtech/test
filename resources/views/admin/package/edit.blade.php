@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12 ">
                <!-- general form elements -->
                <!-- /.card -->
                <!-- Form Element sizes -->
                <!-- /.card -->
                <!-- /.card -->
                <!-- Input addon -->
                <!-- /.card -->
                <!-- Horizontal Form -->
                <div class="card card-info black">
                    <div class="card-header">
                        <h3 class="card-title">Add package</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="col-md-8 ">
                        <form action="{{ route($last[1] . '.packages.update', $package->id) }}" method="POST"
                            class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body">

                                @if ($package->is_admin != 1)
                                    @if (auth()->user()->is_admin == 1)
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">User</label>
                                            <div class="col-sm-6">
                                                <select required class="form-control" name="user_id" id="user_id">
                                                    @foreach ($users as $item)
                                                        <option @if ($item->id == $package->user_id) selected @endif
                                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    @else
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    @endif
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Title</label>
                                        <div class="col-sm-6">
                                            <input value="{{ $package->name }}" required type="text" name="name"
                                                class="form-control">
                                        </div>

                                    </div>


                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Price</label>
                                        <div class="col-sm-6">
                                            <input value="{{ $package->price }}" required type="number" name="price"
                                                class="form-control">
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Duration</label>
                                        <div class="col-sm-6">
                                            <select required class="form-control" name="duration" id="duration">
                                                <option value="">Select</option>
                                                <option @if ($package->duration == 'daily') selected @endif value="daily">
                                                    daily
                                                </option>
                                                <option @if ($package->duration == 'weekly') selected @endif value="weekly">
                                                    weekly
                                                </option>
                                                <option @if ($package->duration == 'monthly') selected @endif value="monthly">
                                                    monthly</option>


                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Description</label>
                                        <div class="col-sm-6">
                                            <textarea required class="form-control" name="description" id="" cols="30" rows="8">{{ $package->description }}</textarea>
                                        </div>

                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Visible</label>
                                    <div class="col-sm-6">
                                        <select required class="form-control" name="status" id="status">
                                            <option value="">Select</option>
                                            <option @if ($package->status == 0) selected @endif value="0">No
                                            </option>
                                            <option @if ($package->status == 1) selected @endif value="1">Yes
                                            </option>


                                        </select>
                                    </div>

                                </div>




                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            {{-- <div class="card-footer">
                                <button type="submit" class="btn btn-default float-right">Cancel</button>
                            </div>
                            <!-- /.card-footer --> --}}
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div>
@endsection
