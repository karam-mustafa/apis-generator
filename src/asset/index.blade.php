@extends('ApisGenerator.layouts._layouts')
@section('content')
    <div class="apis_area">
        <div class="table_area">
            <div class="row my-4">
                <div class="col-md-12"><h3 class="text-center">Made It Easy <span class="exclamation"> &nbsp;!</span>
                    </h3>
                </div>
            </div>
            <hr>
            <div class="row">
                <table class="table overflow-hidden table-hover table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Api</th>
                        <th scope="col">Url</th>
                        <th scope="col">Route controller</th>
                        <th scope="col">type</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\KMLaravel\ApiGenerator\Facade\KMFileHelper::getCredentialJsonFileAsJson() as $index  => $item)
                        <tr>
                            <td>{{$index}}</td>
                            <td>{{$item->title}}</td>
                            <td>/api/{{$item->url}}</td>
                            <td>{{$item->route}}</td>
                            <td>{{$item->type}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
