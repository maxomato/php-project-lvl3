@extends('layouts.app')

@section('title', 'PageAnalyzer')

@section('content')
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Created</th>
            <th>Updated</th>
        </tr>
        <tr>
            <td>
                {{ $domain->name }}
            </td>
            <td>
                {{ $domain->created_at }}
            </td>
            <td>
                {{ $domain->updated_at }}
            </td>
        </tr>
    </table>
@endsection
