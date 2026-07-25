@extends('errors.layout')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized Access'))
@section('description', __('You are not authorized to view this page. Please log in to continue.'))
