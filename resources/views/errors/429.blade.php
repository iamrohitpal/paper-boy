@extends('errors.layout')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))
@section('description', __('You have made too many requests to our servers recently. Please slow down and try again later.'))
