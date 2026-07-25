@extends('errors.layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('Access Forbidden'))
@section('description', __('You do not have the required permissions to access this page.'))
