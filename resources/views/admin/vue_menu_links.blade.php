@extends('admin.layout.master')

@section('styles')
    <link rel="stylesheet" href="/css/admin-app.css">
@endsection

@section('content')
    <div id="app">
        <router-view></router-view>
    </div>
@endsection

@section('scripts')
    <script>
        window.lroutes = {!! json_encode(getRoutes()) !!};
    </script>
    <script src="/js/frontcommons.js"></script>
    <script src="/js/menu_links.js"></script>
@endsection