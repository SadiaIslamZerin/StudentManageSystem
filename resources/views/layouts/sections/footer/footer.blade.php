@php
    $containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<!-- Footer -->
<section id="basic-footer">
    <footer class="footer bg-lighter">
        <div
            class="container-fluid d-flex flex-md-row flex-column justify-content-between align-items-md-center gap-1 container-p-x py-4">
            <div>
                <a href="{{ config('variables.livePreview') }}" target="_blank"
                    class="footer-text fw-bold">{{ config('variables.templateName') }}</a> ©
            </div>
            <div class="d-flex flex-column flex-sm-row">
                <a href="{{ config('variables.licenseUrl') }}" class="footer-link me-6" target="_blank">License</a>
                <a href="javascript:void(0)" class="footer-link me-6">Help</a>
                <a href="javascript:void(0)" class="footer-link me-6">Contact</a>
                <a href="javascript:void(0)" class="footer-link">Terms &amp; Conditions</a>
            </div>
        </div>
    </footer>
</section>
<!--/ Footer -->
