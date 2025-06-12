<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('assets/dash/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/scripts.bundle.js')}}"></script>
<script src="{{asset('assets/dash/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/custom/apps/user-management/users/list/table.js')}}"></script>

<script src="{{asset('assets/dash/assets/js/widgets.bundle.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/custom/widgets.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/custom/apps/chat/chat.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/custom/utilities/modals/create-app.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/custom/utilities/modals/users-search.js')}}"></script>

<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    function googleTranslateElementInit() {
        document.documentElement.lang = 'en'; // Ensure the language is set before Google Translate initializes
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,es,fr,de,it,ar,ja,zh-CN,pt,nl,pl,ru,sw,ur,hi,bn,fa,ta,ml', // Include all the languages
            autoDisplay: false
        }, 'google_translate_element');
    }

    function triggerGoogleTranslate(languageCode) {
        var googleFrame = document.querySelector('.goog-te-combo');
        if (googleFrame) {
            googleFrame.value = languageCode;
            googleFrame.dispatchEvent(new Event('change'));
        }
    }

    document.getElementById('languageSelect').addEventListener('change', function () {
        var languageCode = this.value;
        console.log(languageCode);
        triggerGoogleTranslate(languageCode);
    });
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
