@extends('Staff.layout')
<style>
    .upload-box {
        border: 2px dashed #d9dee3;
        border-radius: 8px;
        background: #f8f9fa;
        cursor: pointer;
        /* transition: all .2s ease; */
    }

    .upload-box:hover {
        border-color: #7367f0;
        background: #f4f3ff;
    }

    .upload-box.dragover {
        border-color: #28c76f;
        background: #e9f8f1;
    }
</style>
@section('content')

    <div class="row align-items-center justify-content-between mb-1">

        <div class="col-auto">
            <h5 class="d-flex align-items-center">

                <button type="button" class="btn btn-icon bg-white me-2" style="box-shadow:0px 9px 12px -2px #66328E1F;"  onclick="history.back()">

                        <i class="ti tabler-chevron-left text-black"></i>

                </button>

                <i class="ti tabler-bug text-danger me-2"></i>
                Edit Bug

            </h5>
        </div>

    </div>


    <div class="card shadow-sm border-1 border-danger p-3">
        <div class="card-body p-4">

            <form action="#" method="POST" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-lg-8">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-user me-1 text-primary"></i>
                                    Identified By <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control bg-light" value="Username" readonly>


                            </div>


                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-layout-dashboard me-1 text-primary"></i>
                                    Panel <span class="text-danger">*</span>
                                </label>

                                <select class="form-select">
                                    <option>Select</option>
                                    <option selected>Admin</option>
                                    <option>User Web</option>
                                    <option>Website</option>
                                    <option>App</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-category me-1 text-primary"></i>
                                    Bug Type <span class="text-danger">*</span>
                                </label>

                                <select class="form-select">
                                    <option>Select</option>
                                    <option>Design</option>
                                    <option selected>Functionality</option>
                                    <option>Deploy</option>
                                    <option>App</option>
                                    <option>UI Issue</option>
                                </select>
                            </div>


                            <div class="col-lg-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="ti tabler-pencil me-1 text-primary"></i>
                                    Bug Title <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" placeholder="Enter Bug Title" value="Bug Title">
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="col-lg-12">

                            <label class="form-label fw-semibold">
                                <i class="ti tabler-photo me-1 text-primary"></i>
                                Screenshot / Attachment
                            </label>

                            <div class="upload-box text-center p-4" id="uploadBox">

                                <div id="uploadContent">

                                    <i class="ti tabler-cloud-upload fs-1 text-primary"></i>

                                    <p class="mb-1 mt-2 fw-semibold">
                                        Drag & Drop your screenshot here
                                    </p>

                                    <small class="text-muted">
                                        or click to browse files
                                    </small>

                                </div>

                                <input type="file" id="fileInput" name="attachment" class="d-none"
                                    accept="image/*,.pdf,.zip,.doc,.docx">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-2 mt-1">

                    <div class="col-lg-4 mb-2">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-box me-1 text-primary"></i>
                            Module <span class="text-danger">*</span>
                        </label>

                        <select class="form-select">
                            <option>Select</option>
                            <option selected>Module 1</option>
                            <option>Module 2</option>
                            <option>Module 3</option>
                        </select>
                    </div>


                    <div class="col-lg-4 mb-2">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-code me-1 text-primary"></i>
                            Debug By <span class="text-danger">*</span>
                        </label>

                        <select class="form-select">
                            <option>Select</option>
                            <option selected>Employee 1</option>
                            <option>Employee 2</option>
                            <option>Employee 3</option>
                        </select>
                    </div>


                    <div class="col-lg-4">

                        <label class="form-label fw-semibold d-block">
                            <i class="ti tabler-flag me-1 text-danger"></i>
                            Priority
                        </label>

                        <div class="d-flex gap-7 mt-2">

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" value="Low">
                                <label class="form-check-label">Low</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" value="Medium" checked>
                                <label class="form-check-label">Medium</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" value="High">
                                <label class="form-check-label text-danger fw-semibold">High</label>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-stethoscope me-1 text-danger"></i>
                            Testing Scenerio <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" rows="3" placeholder="Explain the Scenerio..?">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, ullam.</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-alert-triangle me-1 text-danger"></i>
                            Current Output <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" rows="3" placeholder="What is the Current output..?">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, ullam.</textarea>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            <i class="ti tabler-check me-1 text-success"></i>
                            Expected Output <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" rows="3" placeholder="What is the expected output..?">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, ullam.</textarea>
                    </div>





                </div>


                <div class="d-flex justify-content-center mt-4 gap-3">

                    <button type="reset" class="btn btn-label-secondary">
                        <i class="ti tabler-x me-1"></i> Discard
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="ti tabler-send me-1"></i>Update Bug
                    </button>

                </div>


            </form>

        </div>
    </div>
    <script>
        const uploadBox = document.getElementById('uploadBox');
        const fileInput = document.getElementById('fileInput');
        const uploadContent = document.getElementById('uploadContent');

        uploadBox.addEventListener('click', () => fileInput.click());

        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.classList.add('dragover');
        });

        uploadBox.addEventListener('dragleave', () => {
            uploadBox.classList.remove('dragover');
        });

        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.classList.remove('dragover');

            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        });

        fileInput.addEventListener('change', function () {
            showPreview(this.files[0]);
        });

        function showPreview(file) {

            uploadContent.style.display = "none"; // hide text

            if (file.type.startsWith('image')) {

                let img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxHeight = '90px';
                img.classList.add('img-fluid', 'rounded');

                uploadBox.appendChild(img);

            } else {

                let fileName = document.createElement('p');
                fileName.classList.add('text-success', 'mt-2', 'fw-semibold');
                fileName.innerText = file.name;

                uploadBox.appendChild(fileName);

            }

        }
    </script>
@endsection