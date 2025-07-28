<div class="sidebar-fixed">
    <div class="sidebar-header p-3 mb-3 border-bottom border-light">
        <h5 class="text-white mb-0 fw-bold">{{ $title ?? 'Dashboard' }}</h5>
    </div>
    
    <div class="sidebar-content">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
    <div class="sidebar-footer p-3 mt-auto border-top border-light">
        {{ $footer }}
    </div>
    @endif
</div> 