@extends('layouts.frontend')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">中国省市县数据</div>

                    <div class="panel-body">
                        <a class="btn btn-success" href="{{ route('frontend.download',['type'=>'pcc']) }}"><code>JSON</code>格式下载</a>
                        <a class="btn btn-danger" href="{{ route('frontend.download',['type'=>'pcc','data_type'=>'sql']) }}"><code>SQL</code>格式下载</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">中国城市地铁数据</div>

                    <div class="panel-body">
                        <a class="btn btn-success" href="{{ route('frontend.download',['type'=>'metro']) }}"><code>JSON</code>格式下载</a>
                        <a class="btn btn-danger" href="{{ route('frontend.download',['type'=>'metro','data_type'=>'sql']) }}"><code>SQL</code>格式下载</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection