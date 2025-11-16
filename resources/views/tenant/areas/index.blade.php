@extends('tenant.layouts.admin')

@section('title', 'Areas')
@section('page-title', 'Areas')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Area List</h3>
        <a href="{{ route_include_subdirectory('areas.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Area</a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 12px; font-size: 12px;">
            {{ session('success') }}
        </div>
    @endif

    @if($areas->count() > 0)
        <table class="table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="font-size: 11px; padding: 8px;">ID</th>
                    <th style="font-size: 11px; padding: 8px;">Name</th>
                    <th style="font-size: 11px; padding: 8px;">Created At</th>
                    <th style="font-size: 11px; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areas as $area)
                <tr>
                    <td style="padding: 8px;">{{ $area->id }}</td>
                    <td style="padding: 8px;">{{ $area->name }}</td>
                    <td style="padding: 8px;">{{ $area->created_at->format('M d, Y') }}</td>
                    <td style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('areas.show', ['subdomain' => request()->route('subdomain'), 'area' => $area->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                        <a href="{{ route_include_subdirectory('areas.edit', ['subdomain' => request()->route('subdomain'), 'area' => $area->id]) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                        <form method="POST" action="{{ route_include_subdirectory('areas.destroy', ['subdomain' => request()->route('subdomain'), 'area' => $area->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this area?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px; font-size: 12px;">
            {{ $areas->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No areas found. <a href="{{ route_include_subdirectory('areas.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first area</a></p>
        </div>
    @endif
</div>
@endsection

