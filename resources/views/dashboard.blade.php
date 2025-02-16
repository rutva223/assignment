<style>
    [type=file] {
        background: bottom !important;
        border-color: #e5e7eb !important;
        border-width: 1px !important;
        border-radius: 5px !important;
        padding: 6px !important;
        font-size: initial !important;
        line-height: unset !important;
    }

</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contacts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <div class="col-6 text-start">
                </div>
                <div class="col-6 text-end mb-3">
                    <a href="javascript:;" data-size="md" data-title="Add Contact" class="btn btn-primary"
                        data-url="{{ route('contacts.create') }}" data-ajax-popup="true">
                        <i class="bx bx-plus"></i> Add contact
                    </a>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#importXMLModal">
                        <i class="bx bx-upload"></i> Import XML
                    </button>
                </div>
            </div>

            <div class="modal fade" id="importXMLModal" tabindex="-1" aria-labelledby="importXMLModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importXMLModalLabel">Import Contacts (XML)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <a href="{{ route('download-sample-xml') }}" class="btn btn-success">
                                    <i class="bx bx-download"></i> Download Sample XML
                                </a>
                            </div>
                            <hr>
                            <form id="importXMLForm" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group  col-md-12">
                                    <label for="file" class="col-form-label">Upload XML File</label>
                                    <input class="form-control" required="required" id="xmlFile" accept=".xml"
                                        name="xmlFile" type="file">
                                    <img id="image" class="mt-2" style="width:25%;">
                                </div>

                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="users-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>contact No</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            let table = $('#users-table').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: "{{ route('contacts.get') }}",
                                columns: [{
                                        data: 'id',
                                        name: 'id'
                                    },
                                    {
                                        data: 'name',
                                        name: 'name'
                                    },
                                    {
                                        data: 'contact_no',
                                        name: 'contact_no'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                    {
                                        data: 'action',
                                        name: 'action',
                                        orderable: false,
                                        searchable: false
                                    }
                                ]
                            });
                        });

                        $(document).ready(function() {
                            // Delete button click event
                            $(document).on('click', '.delete-button', function(e) {
                                e.preventDefault();

                                var deleteUrl = $(this).data('url');
                                var row = $(this).closest('tr');

                                Swal.fire({
                                    title: "Are you sure?",
                                    text: "This record will be permanently deleted!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Yes, delete it!"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: deleteUrl,
                                            type: "POST",
                                            data: {
                                                _method: "DELETE",
                                                _token: "{{ csrf_token() }}"
                                            },
                                            success: function(response) {
                                                Swal.fire("Deleted!", "The record has been deleted.",
                                                    "success");
                                                row.remove();
                                            },
                                            error: function(xhr) {
                                                Swal.fire("Error!", "Something went wrong.", "error");
                                            }
                                        });
                                    }
                                });
                            });
                        });

                        $(document).ready(function() {
                            $("#importXMLForm").on("submit", function(e) {
                                e.preventDefault();

                                var formData = new FormData(this);

                                $.ajax({
                                    url: "{{ route('contacts.import.xml') }}",
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        Swal.fire("Success!", "Contacts imported successfully.", "success");
                                        $("#importXMLModal").modal("hide");
                                        $("#users-table").DataTable().ajax.reload();
                                    },
                                    error: function(xhr) {
                                        // Swal.fire("Error!", "Failed to import contacts.", "error");
                                    }
                                });
                            });
                        });

                        $(document).ready(function() {
                            $('#importXMLForm').validate({
                                errorElement: 'div',
                                errorClass: 'text-danger',
                                errorPlacement: function(error, element) {
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
                                    xmlFile: {
                                        required: true,
                                        extension: "xml"
                                    }
                                },
                                messages: {
                                    xmlFile: {
                                        required: "Please select an XML file.",
                                        extension: "Only XML files are allowed."
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
