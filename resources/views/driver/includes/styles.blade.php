<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link rel="icon" type="image/webp" href="{{asset('favicon.png')}}"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
<link href="{{asset('assets/dash/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/dash/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/dash/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/dash/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">
<script src="https://unpkg.com/notie"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
{{--<link rel="stylesheet" type="text/css" href="https://designreset.com/cork/html/src/assets/css/light/elements/alert.css">--}}
<style>
    .aside-dark .menu .menu-item .menu-link .menu-icon, .aside-dark .menu .menu-item .menu-link .menu-icon .svg-icon, .aside-dark .menu .menu-item .menu-link .menu-icon i {
        color: #d1d1d1;
    }

    .dataTables_info{
        display: none !important;
    }
    .table-hover>tbody>tr:hover>* {
        --bs-table-accent-bg:transparent !important;
    }
    .cke_notifications_area{
        display: none !important;
    }
    .page-item.active .page-link{
        color: white !important;
    }
    .dataTables_empty{
        text-align: center !important;
    }
    .close{
        display: inline-block;
        font-weight: 500;
        line-height: 1.5;
        color: #181c32;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        font-size: 1.1rem;
        border-radius: .475rem;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        font-size: 25px;
    }
    #google_translate_element img {
        display: none !important;
    }
    .goog-te-gadget span a {
        color: transparent !important;
    }
    .goog-te-gadget {
        color: transparent !important;
    }
    .goog-te-gadget .goog-te-combo {
        background-color: transparent;
        color: #5e6d88;
        border: transparent;
    }
    .VIpgJd-ZVi9od-ORHb-OEVmcd{
        display: none;
    }
    iframe.skiptranslate {
        visibility: hidden !important;
    }
    body {
        top: 0px !important;
    }
    .goog-te-spinner-pos {
        display: none !important;
    }
    .goog-te-gadget-icon {
        display: none !important;
    }

    #google_translate_element{width:300px;float:right;text-align:right;display:block}
    .goog-te-banner-frame.skiptranslate { display: none !important;}
    body { top: 0px !important; }
    #goog-gt-tt{display: none !important; top: 0px !important; }
    .goog-tooltip .skiptranslate{display: none !important; top: 0px !important; }
    .activity-root { display: hide !important;}
    .status-message { display: hide !important;}
    .started-activity-container { display: hide !important;}
    .VIpgJd-yAWNEb-VIpgJd-fmcmS-sn54Q {
        background-color: transparent !important;
        box-shadow: none !important; /* Remove any existing box shadow */
    }
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf {
        display: none !important;
    }
    .cke_notification{
        display: none !important;
    }
    .card-shadow:hover {
        box-shadow: 0 0.25rem 1rem rgba(165, 163, 174, 0.45) !important;
    }
    .card-shadow {
        box-shadow: 0 0.25rem 1.125rem rgba(75, 70, 92, 0.1) !important;
    }
</style>
