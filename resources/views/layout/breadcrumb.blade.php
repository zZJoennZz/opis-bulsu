<div class="mb-3">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
            @for ($i = 0; $i < count($breadcrumbs); $i++)
                @php
                    $hrefValue = "#";
                    if (isset($breadcrumbs[$i]['route'])) {
                        if ($breadcrumbs[$i]['route'] !== "#") {
                            $hrefValue = route($breadcrumbs[$i]['route']);
                        }
                    }
                    if (isset($breadcrumbs[$i]['url'])) {
                        $hrefValue = $breadcrumbs[$i]['url'];
                    }
                    if (isset($breadcrumbs[$i]['routeWithParam'])) {
                        $hrefValue = $breadcrumbs[$i]['routeWithParam'];
                    }
                @endphp
                @if ($i === count($breadcrumbs) - 1)
                    <li class="breadcrumb-item active" aria-current="page">{!! $breadcrumbs[$i]['name'] !!}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $hrefValue }}">{!! $breadcrumbs[$i]['name'] !!}</a></li>
                @endif
            @endfor
        </ol>
    </nav>
</div>
<hr />