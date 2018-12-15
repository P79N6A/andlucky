@extends('layout')

@section('style')
<style>
    p {
        text-indent: 2em;
    }
</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">比大小帮助</div>

    </header>
    <section class="aui-content-padded">
        <p>
        {!! str_replace( PHP_EOL , '</p><p>' , $data ) !!}
        </p>
    </section>

@endsection


@section('script')

@endsection