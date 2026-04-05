@props(['title' => null, 'action' => null, 'actionUrl' => null])

<div class="card">
    @if($title)
        <div class="card-head">
            <span class="card-title">{{ $title }}</span>
            @if($action)
                <span class="card-action" @if($actionUrl) onclick="showPage('{{ $actionUrl }}')" @endif>{{ $action }}</span>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
