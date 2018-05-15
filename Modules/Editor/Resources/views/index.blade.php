@extends('editor::layouts.master')

@section('content')
    <iframe src="{{ $href }}" id="editor-iframe"></iframe>
@stop
