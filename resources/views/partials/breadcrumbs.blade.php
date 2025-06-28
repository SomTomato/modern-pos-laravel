@if (isset($breadcrumbs))
    <nav aria-label="breadcrumb" style="margin-bottom: 20px;">
        <ol style="list-style: none; display: flex; padding: 0; font-size: 0.9em; color: #666;">
            @foreach ($breadcrumbs as $breadcrumb)
                <li style="display: flex; align-items: center;">
                    @if (!$loop->last)
                        <a href="{{ $breadcrumb['url'] }}" style="text-decoration: none; color: var(--primary-color);">{{ $breadcrumb['name'] }}</a>
                        <span style="margin: 0 8px;">/</span>
                    @else
                        <span style="color: #333; font-weight: 500;">{{ $breadcrumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
