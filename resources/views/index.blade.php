@extends('layouts.app')

@section('title', 'PageAnalyzer')

@section('content')
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Created</th>
            <th>Updated</th>
        </tr>
        @foreach ($domains as $domain)
            <tr>
                <td>
                    <a href="{!! route('domains.show', ['id' => $domain->id]) !!}"
                       target="_blank"
                    >
                        {{ $domain->name }}
                    </a>
                </td>
                <td>
                    {{ $domain->created_at }}
                </td>
                <td>
                    {{ $domain->updated_at }}
                </td>
            </tr>
        @endforeach
    </table>

    {!! $domains->render() !!}
@endsection
