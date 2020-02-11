@extends('layouts.app')

@section('title', 'PageAnalyzer')

@section('content')
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Response Status</th>
            <th>Content Length</th>
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
            <td>
                {{ $domain->state . ($domain->status ? (': ' . $domain->status) : '') }}
            </td>
            <td>
                {{ $domain->content_length }}
            </td>
        </tr>
    </table>
@endsection
