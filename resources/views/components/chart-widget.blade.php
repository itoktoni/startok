@props(['title' => 'Chart', 'chart' => null])

<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-4">
        <h3 class="text-sm font-bold mb-2">{{ $title }}</h3>
        {!! $chart->container() !!}
        {!! $chart->script() !!}
    </div>
</div>
