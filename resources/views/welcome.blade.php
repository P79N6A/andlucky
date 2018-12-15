<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="api-token" content="{{ $login_token }}">
        <title>连运网|www.andlucky.com</title>
        <meta content="资讯、小游戏、热点、案件、搞笑、感悟、记录、美女、自媒体平台、连运网、lucky" name="Keywords">
        <meta content="连运网致力于打造独特自媒体平台，按篇、按阅读量核算发文收益和阅读收益，简单、透明，还有新颖小游戏。" name="description">
        <link rel="stylesheet" type="text/css" href="{{asset('css/sm.css')}}">
        <link rel="stylesheet" type="text/css" href="{{mix('/css/app.css')}}">
        <link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_420960_zrsekepyv2ikke29.css">
        <!-- Fonts -->

        <!-- Styles -->
        <style>
            .page-group .page {
                display: block;
            }
            .card {
                margin:0rem;
            }
            #uploadAdv input {
                overflow:hidden;position:fixed;width:1px;height:1px;z-index:-1;opacity:0;
            }
        </style>
        <script type="text/javascript">
            window.imUser = "{!! data_get( auth()->user() , 'name') !!}";
            window.blogCate = {!! json_encode( $cate )!!}
        </script>
    </head>
    <body class="theme-coffee">
        <audio id="tipAudio" controls>
            <source src="{{asset('images/dog.mp3')}}" type="audio/mp3" />
            Your browser does not support the audio tag.
        </audio>
        <div class="page-group" id="app-3">
            <keep-alive>
                <router-view v-if="$route.meta.keepAlive"></router-view>
            </keep-alive>
            <router-view v-if="!$route.meta.keepAlive"></router-view>
        </div>
    </body>
    <script type="text/javascript" src="{{asset('vendor/im/strophe-1.2.8.js')}}"></script>
    <script type="text/javascript" src="{{mix('/js/napp.js')}}"></script>
</html>
