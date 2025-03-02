@if (session('successToast'))
    <div class="bs-toast toast toast-placement-ex m-2 fade bg-primary top-0 start-50 translate-middle-x show"
        role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
        <div class="toast-header">

            <div class="me-auto fw-semibold"></div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">{!! session('successToast') !!}</div>
    </div>
@endif

{{-- @if ($errors->any())
    @foreach ($errors->all() as $item)
        <div class="bs-toast toast toast-placement-ex m-2 fade bg-danger top-0 start-50 translate-middle-x show"
            role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
            <div class="toast-header">
                <i class="bx bx-edit me-2"></i>
                <div class="me-auto fw-semibold"></div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ $item }}</div>
        </div>
    @endforeach
@endif --}}
