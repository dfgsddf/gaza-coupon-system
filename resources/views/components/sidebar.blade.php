<div class="sidebar-fixed" id="sidebar">
    <div class="sidebar-header p-3 mb-3 border-bottom border-light">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-white mb-0 fw-bold">{{ $title ?? 'Dashboard' }}</h5>
            <button class="btn btn-sm btn-outline-light sidebar-toggle" id="sidebar-toggle" title="طي/إظهار الشريط الجانبي">
                <i class="fas fa-bars"></i>
            </button>
        </div>
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