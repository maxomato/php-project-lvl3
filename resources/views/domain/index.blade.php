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
            <th>H1</th>
            <th>Keywords</th>
            <th>Description</th>
        </tr>
        @foreach ($domains as $domain)
            <tr>
                <td>
                    <a href="{!! route('domain.show', ['id' => $domain->id]) !!}"
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
                <td>
                    {{ $domain->state . ($domain->status ? (': ' . $domain->status) : '') }}
                </td>
                <td>
                    {{ $domain->content_length }}
                </td>
                <td>
                    {{ $domain->h1 }}
                </td>
                <td>
                    {{ $domain->keywords }}
                </td>
                <td>
                    {{ $domain->description }}
                </td>
            </tr>
        @endforeach
    </table>

    {!! $domains->render() !!}
@endsection
