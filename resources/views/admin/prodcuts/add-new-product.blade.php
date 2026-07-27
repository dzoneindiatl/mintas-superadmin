@extends('admin.layout.master')
@section('content')
<div class="card-header">
    <div class="card-title">
        <h5>Add Product </h5>
    </div>
</div>
<div class="tab-container">
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif



    <form id="finalProductForm" method="post" action="{{ route('admin-product-save-product') }}"
        enctype="multipart/form-data">
        @csrf


        <input type="hidden" value="" name="draf" id="draf">
       
        <ul class="nav nav-tabs mb-4" id="formTabs">
            <li class="nav-item">
                <a class="nav-link active" data-tab="tab1" href="javascript:void(0)">Basic Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-tab="tab2" href="javascript:void(0)">Advance Details</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" data-tab="tab3" href="javascript:void(0)">Advance Details</a>
            </li> --}}
            {{-- <li class="nav-item">
                <a class="nav-link" data-tab="tab4" href="javascript:void(0)">SEO Feature</a>
            </li> --}}
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                @include('modals.products.create_product_combined', ['categories' =>$categories,'product'=>$product])
            </div>
            <div class="tab-pane" id="tab2">
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let $form = $("#finalProductForm");
$form.validate({
    errorClass: 'error',
    highlight: function(element) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function(element) {
        $(element).removeClass("is-invalid");
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element);
    }
});

$(".nextBtn").click(function() {
    let $currentTab = $(this).closest(".tab-pane");
    let $fields = $currentTab.find("input, select, textarea");

    let valid = true;
    $fields.each(function() {
        if (!$(this).valid()) {
            valid = false;
        }
    });

    if (valid) {
        let currentTabId = $currentTab.attr("id");
        let nextTab = $currentTab.next(".tab-pane").attr("id");
        // Check if moving from Tab2 to Tab3 (variant -> advance feature)
        
            // Normal tab switch
            switchTab(nextTab);
        
    }
});


$(".prevBtn").click(function() {
    let $currentTab = $(this).closest(".tab-pane");
    let prevTab = $currentTab.prev(".tab-pane").attr("id");
    if (prevTab) {
        switchTab(prevTab);
    }
});

function switchTab(targetId) {
    $(".tab-pane").removeClass("active");
    $("#" + targetId).addClass("active");

    $(".nav-link").removeClass("active");
    $('.nav-link[data-tab="' + targetId + '"]').addClass("active");
}

</script>
@endpush