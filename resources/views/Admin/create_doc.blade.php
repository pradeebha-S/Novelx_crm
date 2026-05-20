@extends('Admin.layout')
<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f3f4f6;
}

/* HEADER */
.topbar {
  padding: 15px;
  border-bottom: 1px solid #ddd;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.topbar h2 {
  margin: 0;
}

/* CONTAINER */
.container {
  max-width: 900px;
  margin: 30px auto;
  padding: 20px;
  border-radius: 8px;
  border: 1px solid #ddd;
}

/* TITLE */
input.title {
  width: 100%;
  font-size: 22px;
  padding: 10px;
  border: 1px solid #ddd;
  margin-bottom: 15px;
}

/* EDITOR */
.editor {
  min-height: 350px;
  border: 1px solid #ddd;
  padding: 15px;
  outline: none;
}

.footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 15px;
}

/* Default desktop: row layout */
.buttons {
  display: flex;
  gap: 10px;
}

/* 🔥 Mobile (Bootstrap sm breakpoint ~576px) */
@media (max-width: 576px) {

  .footer {
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
  }

  .buttons {
    flex-direction: column;
    width: 100%;
  }

  .buttons button {
    width: 100%;
  }

  .status {
    text-align: center;
    width: 100%;
  }
}

.buttons button {
  padding: 8px 14px;
  border: none;
  margin-left: 8px;
  cursor: pointer;
  border-radius: 4px;
}

.save {
  background: #2563eb;
  color: white;
}

/* .draft {
  background: #e5e7eb;
} */

.email {
  background: green;
  color: white;
}

/* STATUS */
.status {
  font-size: 13px;
  color: gray;
}

</style>

@section('content')
 <div class="row d-flex justify-content-between">
        <div class="col-auto">
            <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('view_doc') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M15 6l-6 6l6 6"></path>
                        </svg>
                    </a>
                </button>
               Create Document
            </h5>
        </div>


    </div>
<!-- TOP BAR -->
<div class="card topbar d-flex flex-row justify-content-between align-items-center p-4">

  <div>
    <h6 class="mb-0">Document Editor</h6>
  </div>



</div>
<div class="col-lg-4 mb-4">
    <label class="form-label">Choose Project</label>

    <select name="project_id" id="project_id" class="form-select">

        <option value="">Select Project</option>

        @foreach ($projects as $proj)
            <option value="{{ $proj->id }}"
                {{ (isset($project) && $project->id == $proj->id) ? 'selected' : '' }}>
                {{ $proj->project_name }}
            </option>
        @endforeach

    </select>
</div>
<!-- MAIN -->
<div class="container card">

  <input class="title" id="title" placeholder="Enter Document Title"/>

  <div class="editor" contenteditable="true" id="editor">
    Start writing your document here...
  </div>
<div class="text-center mt-3">
      <button class="btn btn-primary" onclick="saveDoc()">Save</button>
    </div>

</div>


<script>

let documentData = {
  title: "",
  content: "",
  is_email: false
};

/* SAVE DOC */
function saveDoc() {
  documentData.title = document.getElementById("title").value;
  documentData.content = document.getElementById("editor").innerHTML;

  alert("Document Saved");
}


/* EMAIL MODAL */
function openEmail() {
  document.getElementById("emailModal").style.display = "flex";

  const title = document.getElementById("title").value || "Untitled Document";

  document.getElementById("subject").value = "Regarding " + title;
  document.getElementById("message").value = "Please find the attached document.";

  document.getElementById("attachFile").innerText = title + ".pdf";
}

function closeEmail() {
  document.getElementById("emailModal").style.display = "none";
}

/* SEND EMAIL */
function sendEmail() {
  documentData.is_email = true;

  document.getElementById("emailStatus").innerText = "Email: Sent";
  alert("Email Sent Successfully");

  closeEmail();
}

</script>
@endsection