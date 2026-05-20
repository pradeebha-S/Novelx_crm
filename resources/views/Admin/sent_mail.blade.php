@extends('Admin.layout')
<style>

:root{
    --primary-color:#6366f1;
}


.mail-card{
    animation:fadeIn .4s ease;
}

/* RECIPIENTS */
.recipient-wrapper{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    min-height:56px;
    align-items:center;
    transition:.3s ease;
}

.recipient-wrapper:focus-within{
    border-color:var(--primary-color);
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

.recipient-input{
    outline:none;
    min-width:200px;
    background:transparent;
}

/* CHIP */
.recipient-chip{
    background:rgba(99,102,241,.12);
    color:var(--primary-color);
    border-radius:50px;
    padding:6px 12px;
    display:flex;
    align-items:center;
    gap:8px;
    font-size:14px;
    animation:fadeIn .3s ease;
}

.recipient-chip i{
    cursor:pointer;
}

/* TEXTAREA */
.custom-textarea{
    min-height:250px;
    resize:none;
    transition:.3s ease;
}

.custom-textarea:focus,
.form-control:focus{
    border-color:var(--primary-color);
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

/* UPLOAD AREA */
.upload-area{
    border:2px dashed #d6d9e0;
    background:#fafbff;
    transition:.3s ease;
    cursor:pointer;
}

.upload-area:hover{
    border-color:var(--primary-color);
    background:#f5f5ff;
}

.upload-icon{
    color:var(--primary-color);
}

/* FILE ITEM */
.file-item{
    background:#fff;
    border:1px solid #eee;
    border-radius:16px;
    padding:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
    transition:.3s ease;
    animation:fadeIn .3s ease;
}

.file-item:hover{
    transform:translateY(-2px);
    box-shadow:0 4px 15px rgba(0,0,0,.06);
}

.file-left{
    display:flex;
    align-items:center;
    gap:14px;
}

.file-icon{
    width:45px;
    height:45px;
    border-radius:12px;
    background:#eef2ff;
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--primary-color);
}

.remove-btn{
    border:none;
    background:none;
    color:#dc3545;
    font-size:20px;
}

/* BUTTON */
.send-btn{
    transition:.3s ease;
    background:var(--primary-color);
    border:none;
}

.send-btn:hover{
    transform:translateY(-2px);
    opacity:.95;
}

/* ANIMATION */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* MOBILE */
@media(max-width:576px){

    .card{
        padding:20px !important;
    }

    .recipient-input{
        width:100%;
    }

    .send-btn{
        width:100%;
    }

}

</style>
@section('content')
<div class="row d-flex justify-content-between">
        <div class="col-auto">
            <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ url()->previous() }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M15 6l-6 6l6 6"></path>
                        </svg>
                    </a>
                </button>
             Compose Mail
            </h5>
        </div>


    </div>

    <div class="card p-4 rounded-4 shadow-sm border-0 mail-card">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold">Compose Mail</h4>
                <p class="text-muted mb-0 small">
                    Create and send professional emails
                </p>
            </div>

            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                <i class="ti tabler-mail me-1"></i> Mail Composer
            </span>
        </div>

        <!-- FORM -->
        <form id="mailForm">

            <!-- TO FIELD -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    <i class="ti tabler-user me-1"></i> To
                </label>

                <div class="recipient-wrapper form-control rounded-4 p-2">

                    <div id="recipientChips" class="d-flex flex-wrap gap-2"></div>

                    <input
                        type="email"
                        id="recipientInput"
                        class="border-0 flex-grow-1 recipient-input"
                        placeholder="Enter recipient email addresses"
                    >

                </div>

                <div class="form-text">
                    Press Enter to add multiple recipients
                </div>

            </div>

            <!-- SUBJECT -->
            <div class="mb-4">

                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control rounded-4"
                        id="subject"
                        placeholder="Enter mail subject"
                        value="Invoice #INV-2026-1001"
                    >

                    <label for="subject">
                        Enter mail subject
                    </label>
                </div>

            </div>

            <!-- CONTENT -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    <i class="ti tabler-message me-1"></i> Message
                </label>

                <textarea
                    id="content"
                    class="form-control rounded-4 custom-textarea"
                    placeholder="Write your message here..." rows="10"
                >Dear Client,

Please find the attached invoice document.

Thank you.</textarea>

            </div>

            <!-- ATTACHMENTS -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    <i class="ti tabler-paperclip me-1"></i> Attachments
                </label>

                <!-- Upload Area -->
                <div
                    class="upload-area rounded-4 p-4 text-center position-relative"
                    id="uploadArea"
                >

                    <input
                        type="file"
                        id="fileInput"
                        multiple
                        hidden
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.jpeg,.png"
                    >

                    <div class="upload-content">

                        <div class="upload-icon mb-3">
                            <i class="ti tabler-upload fs-1"></i>
                        </div>

                        <h6 class="fw-semibold mb-2">
                            Drag & Drop files here
                        </h6>

                        <p class="text-muted small mb-3">
                            PDF, DOC, XLS, ZIP, JPG, PNG
                        </p>

                        <button
                            type="button"
                            class="btn btn-primary rounded-pill px-4"
                            id="browseBtn"
                        >
                            Browse Files
                        </button>

                    </div>

                </div>

                <!-- FILE LIST -->
                <div class="attachment-list mt-3" id="attachmentList"></div>

            </div>

            <!-- SEND BUTTON -->
            <div class="d-grid d-md-flex justify-content-md-center">

                <button
                    type="submit"
                    class="btn btn-primary rounded-pill px-5 py-3 send-btn"
                    id="sendBtn"
                >

                    <span class="btn-text">
                        <i class="ti tabler-send me-2"></i>
                        Send Email
                    </span>

                    <span class="spinner-border spinner-border-sm d-none"></span>

                </button>

            </div>

        </form>

    </div>





<script>

const recipientInput = document.getElementById('recipientInput');
const recipientChips = document.getElementById('recipientChips');

let recipients = [];

/* ADD RECIPIENT */
recipientInput.addEventListener('keydown', function(e){

    if(e.key === 'Enter'){

        e.preventDefault();

        const email = this.value.trim();

        if(validateEmail(email)){

            recipients.push(email);

            renderRecipients();

            this.value = '';

        }else{
            alert('Invalid email address');
        }
    }

});

function renderRecipients(){

    recipientChips.innerHTML = '';

    recipients.forEach((email,index)=>{

        recipientChips.innerHTML += `
            <div class="recipient-chip">
                ${email}
                <i class="ti tabler-x" onclick="removeRecipient(${index})"></i>
            </div>
        `;

    });

}

function removeRecipient(index){

    recipients.splice(index,1);

    renderRecipients();

}

function validateEmail(email){

    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

}

/* FILES */
const fileInput = document.getElementById('fileInput');
const attachmentList = document.getElementById('attachmentList');
const uploadArea = document.getElementById('uploadArea');
const browseBtn = document.getElementById('browseBtn');

let uploadedFiles = [];

/* AUTO PREFILL ATTACHMENT */
window.addEventListener('load', ()=>{

    const autoFile = {
        name:'Invoice_INV-2026-1001.pdf',
        size:'2.4 MB'
    };

    uploadedFiles.push(autoFile);

    renderFiles();

});

/* BROWSE */
browseBtn.addEventListener('click', ()=> fileInput.click());

/* FILE SELECT */
fileInput.addEventListener('change', function(){

    [...this.files].forEach(file=>{

        uploadedFiles.push(file);

    });

    renderFiles();

});

/* DRAG */
uploadArea.addEventListener('dragover', e=>{

    e.preventDefault();

    uploadArea.classList.add('border-primary');

});

uploadArea.addEventListener('dragleave', ()=>{

    uploadArea.classList.remove('border-primary');

});

uploadArea.addEventListener('drop', e=>{

    e.preventDefault();

    uploadArea.classList.remove('border-primary');

    [...e.dataTransfer.files].forEach(file=>{

        uploadedFiles.push(file);

    });

    renderFiles();

});

/* RENDER FILES */
function renderFiles(){

    attachmentList.innerHTML = '';

    uploadedFiles.forEach((file,index)=>{

        attachmentList.innerHTML += `

            <div class="file-item">

                <div class="file-left">

                    <div class="file-icon">
                        <i class="ti tabler-file"></i>
                    </div>

                    <div>
                        <div class="fw-semibold">
                            ${file.name}
                        </div>

                        <small class="text-muted">
                            ${file.size || ''}
                        </small>
                    </div>

                </div>

                <button
                    class="remove-btn"
                    onclick="removeFile(${index})"
                >
                    <i class="ti tabler-trash"></i>
                </button>

            </div>

        `;

    });

}

/* REMOVE FILE */
function removeFile(index){

    uploadedFiles.splice(index,1);

    renderFiles();

}

/* SEND */
document.getElementById('mailForm').addEventListener('submit', function(e){

    e.preventDefault();

    const btn = document.getElementById('sendBtn');

    btn.querySelector('.btn-text').classList.add('d-none');

    btn.querySelector('.spinner-border').classList.remove('d-none');

    setTimeout(()=>{

        btn.querySelector('.btn-text').classList.remove('d-none');

        btn.querySelector('.spinner-border').classList.add('d-none');

        alert('Email Sent Successfully');

    },2000);

});

</script>

@endsection