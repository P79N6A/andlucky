@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">系统通知</div>

    </header>
    <section class="aui-content-padded">
        {!! data_get( $data , 'message') !!}
    </section>

@endsection


@section('script')

@endsection