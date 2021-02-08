@php
    $modelName = get_class($item);
@endphp
@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
    'name' => 'description',
    'label' => 'Description',
    'maxlength' => 100
    ])

    @formField('block_editor', [
    'blocks' => ['text', 'image','header', 'hero', 'footer', 'aside']
    ])

    @include('admin._partials.block_validator')
@stop
