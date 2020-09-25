@extends('ApisGenerator.layouts._layouts')
@section('content')
    <div class="form py-4">
        <form class="form_body" action="{{route('apisGenerator.create.send')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12"><h3 class="text-center">Made It Easy <span class="exclamation"> &nbsp;!</span>
                    </h3></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 col-lg-6 title_area">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Api</h5>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="added_area">
                                <div class="form-group">
                                    <label>title</label>
                                    <input type="text" name="title" class="form-control">
                                </div>
                                <hr>
                                <h5>column in database</h5>
                                <div class="column_input_area w-100">
                                    <div class="form-group ">
                                        <input type="text" class="form-control column_name" placeholder="column name">
                                    </div>
                                    <div class="form-group w-50">
                                        <select class="form-control column_type">
                                            @foreach(config('ApisGenerator.columnType') as $item)
                                                <option value="{{$item}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="add_new_column hvr-shutter-in-vertical process_icon">+</div>
                                    </div>
                                </div>
                            </div>
                            <div class="error_message_area">
                                <p class="text-danger error_message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <h5>Options </h5>
                    <hr>
                    <div class="row">
                        @foreach(config('ApisGenerator.buildOptions') as $name => $option)
                            <div class="col-md-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="options[{{$option}}]"
                                           id="{{$option}}">
                                    <label class="form-check-label" for="{{$option}}">
                                        With {{$name}}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr>
            <div class="row api_details_area"></div>
            <button class="btn text-white btn-block mt-4 btn-grad">Save</button>
        </form>
    </div>
@endsection
