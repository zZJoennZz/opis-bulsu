<div class="mb-3">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
            @for ($i = 0; $i < count($breadcrumb); $i++)
                @php
                    $hrefValue = "#";
                    if (isset($breadcrumb[$i]['route'])) {
                        if ($breadcrumb[$i]['route'] !== "#") {
                            $hrefValue = route($breadcrumb[$i]['route']);
                        }
                    }
                    if (isset($breadcrumb[$i]['url'])) {
                        $hrefValue = $breadcrumb[$i]['url'];
                    }
                    if (isset($breadcrumb[$i]['routeWithParam'])) {
                        $hrefValue = $breadcrumb[$i]['routeWithParam'];
                    }
                @endphp
                @if ($i === count($breadcrumb) - 1)
                    <li class="breadcrumb-item active" aria-current="page">{!! $breadcrumb[$i]['name'] !!}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $hrefValue }}">{!! $breadcrumb[$i]['name'] !!}</a></li>
                @endif
            @endfor
        </ol>
    </nav>
</div>
<hr />