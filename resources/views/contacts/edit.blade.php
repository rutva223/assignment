<form class="g-3" action="{{ route('contacts.update', [$contacts->id]) }}" method="post" id="update_add_frm" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mt-2">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $contacts->name }}">
            </div>

            <div class="col-md-12">
                <label for="contact_no" class="form-label">Contact No</label>
                <input type="number" class="form-control" id="contact_no" name="contact_no" value="{{ $contacts->contact_no }}" required>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</form>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $('#update_add_frm').validate({
            errorElement: 'div',
            errorClass: 'text-danger',
            errorPlacement: function(error, element) {
                // error.insertAfter(element);
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            rules: {
                name: {
                    required: true
                },
                contact_no: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 12
                }
            },
            messages: {
                name: {
                    required: "Please enter name."
                },
                contact_no: {
                    required: "Contact number is required",
                    digits: "Only numbers are allowed",
                    minlength: "Must be at least 10 digits",
                    maxlength: "Cannot exceed 12 digits"
                }
            },
            success: function(label, element) {
                $(element).removeClass('is-invalid');
                $(label).remove();
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
