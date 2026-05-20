<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\ProjectDocuments;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
class ProjectServiceController extends Controller
{

public function store_document(Request $request)
{
    DB::beginTransaction();

    try {

        // 🔥 1. VALIDATOR
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'document_name' => 'required|string|max:255',
            'content' => 'required',
        ], [
            'project_id.required' => 'Project is required',
            'document_name.required' => 'Document title is required',
            'content.required' => 'Content is required',
        ]);

        if ($validator->fails()) {

            Log::warning('Document validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // 🔥 2. SAVE DOCUMENT
        $document = Document::create([
            'project_id' => $request->project_id,
            'document_name' => $request->document_name,
            'content' => $request->content,
            'status' => 'created'
        ]);

        // 🔥 3. GENERATE PDF
        $pdf = Pdf::loadView('pdf.document', [
            'title' => $request->document_name,
            'content' => $request->content
        ]);

        $fileName = 'documents/' . time() . '_' . $document->id . '.pdf';

        Storage::disk('public')->put($fileName, $pdf->output());

        // 🔥 4. UPDATE DOCUMENT
        $document->update([
            'pdf_file' => $fileName
        ]);

        DB::commit();

        Log::info('Document created successfully', [
            'document_id' => $document->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Document saved successfully',
            'file' => $fileName
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('Document store failed', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}
    public function store_document(Request $request)
{
    try {


        $tempName = $request->input('temp_image') ?? session('temp_image');


        if ($request->hasFile('image')) {


            $file = $request->file('image');

            $tempName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = public_path('uploads/documents');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                Log::info('📁 Folder created');
            }

            $file->move($path, $tempName);

        }

        $validator = Validator::make($request->all(), [
            'project_id'     => 'required|exists:projects,id',
            'document_name'  => 'required|string|max:255',
            'image'          => 'nullable',
        ]);

        if ($validator->fails()) {

          

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('temp_image', $tempName);
        }


        // 🔥 STEP 4: final save
        Document::create([
            'project_id'    => $request->project_id,
            'document_name' => $request->document_name,
            'file'          => $tempName
        ]);


       return redirect()->route('view_doc', $request->project_id)
    ->with('success', 'Uploaded Successfully');

    } catch (\Exception $e) {

        

        return redirect()->back()
            ->withInput()
            ->with('temp_image', $tempName ?? null)
            ->with('error', 'Something went wrong');
    }
}


  public function store_credential(Request $request)
{
    Log::info('🟡 Credential Store Started', ['request' => $request->all()]);

    $validator = Validator::make($request->all(), [
        'project_id' => 'required|exists:projects,id',
        'platform'      => 'required|string|max:255',
        'user_id'   => 'required|string|max:255',
        'password'   => 'required|string|max:255',
        'document'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
    ]);

    if ($validator->fails()) {
        Log::error('❌ Validation Failed', [
            'errors' => $validator->errors()->toArray()
        ]);
        return back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
        $filePath = null;

        // 📁 FILE UPLOAD
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('uploads/credentials');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $fileName);
            $filePath = 'uploads/credentials/' . $fileName;
        }

        // 🔐 ENCRYPT ALL SENSITIVE DATA
        $credential = ProjectDocuments::create([
            'project_id'    => $request->project_id,
            'platform'      => Crypt::encryptString($request->platform),
            'user_id'       => Crypt::encryptString($request->user_id),
            'password'      => Crypt::encryptString($request->password),
            'password_hint' => $request->password,
            'document'      => $filePath,
        ]);

        DB::commit();

      return redirect()->route('view_credentials', $request->project_id)
    ->with('success', 'Credential stored securely');

    } catch (\Exception $e) {
        DB::rollback();

        Log::error('🔥 Credential Store Failed', [
            'error' => $e->getMessage(),
        ]);

        return back()->withInput()->with('error', 'Something went wrong!');
    }
}
public function credentialsData(Request $request)
{
    $query = ProjectDocuments::latest();

    return DataTables::of($query)
        ->addIndexColumn()

       ->addColumn('date', function ($row) {
    return $row->created_at
        ? $row->created_at->format('d/m/Y')
        : '-';
})

        // 🔐 PASSWORD (AUTO DECRYPTED FROM MODEL)
        ->addColumn('password', function ($row) {

            $realPassword = $row->password_hint ?? '';

            return '
            <div class="password-wrap d-flex align-items-center gap-2">
                <span class="password-text"
                      data-password="'.$realPassword.'"
                      data-visible="0">
                    ••••••••
                </span>
                <button type="button"
                    class="btn btn-sm eye-toggle border-0 bg-transparent p-0">
                    <i class="ti tabler-eye" style="font-size:20px;"></i>
                </button>
            </div>';
        })

        ->addColumn('document', function ($row) {
            if (!$row->document) return '-';

            $file = asset($row->document);

            return '
            <div class="d-flex align-items-center gap-2">
                <a href="'.$file.'" target="_blank">
                    <i class="ti tabler-pdf" style="font-size:22px;"></i>
                </a>
                <a href="'.$file.'" download>
                    <i class="ti tabler-download" style="font-size:22px;"></i>
                </a>
            </div>';
        })

        ->addColumn('action', function ($row) {
            return '
            <div class="d-flex gap-3 align-items-center">

                <a href="'.route('edit_credential', $row->id).'">
                    <i class="ti tabler-edit text-primary"
                       style="font-size:22px"></i>
                </a>

                <i class="ti tabler-trash text-danger deleteBtn"
                   style="font-size:22px"
                   data-id="'.$row->id.'"
                   data-bs-toggle="modal"
                   data-bs-target="#delete"></i>

            </div>';
        })

        ->rawColumns(['password', 'document', 'action'])
        ->make(true);
}
 public function edit_credential($id)
{
    $project = ProjectDocuments::findOrFail($id);
    return view('Admin.edit_credentials', compact('project'));
}

public function update_credential(Request $request, $id)
{
    Log::info('🟡 Credential Update Started', ['id' => $id]);

    $validator = Validator::make($request->all(), [
        'project_id' => 'required|exists:projects,id',
        'platform'   => 'required|string|max:255',
        'user_id'    => 'required|string|max:255',
        'password'   => 'required|string|max:255',
        'document'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {

        $credential = ProjectDocuments::findOrFail($id);

        $filePath = $credential->document;

        // 📁 FILE UPDATE
        if ($request->hasFile('document')) {

            if ($credential->document && file_exists(public_path($credential->document))) {
                unlink(public_path($credential->document));
            }

            $file = $request->file('document');
            $fileName = time().'_'.$file->getClientOriginalName();

            $destination = public_path('uploads/credentials');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $fileName);

            $filePath = 'uploads/credentials/'.$fileName;
        }

        // 🔐 ENCRYPT EVERYTHING
        $credential->update([
            'platform' => Crypt::encryptString($request->platform),
            'user_id'  => Crypt::encryptString($request->user_id),
            'password' => Crypt::encryptString($request->password),
            'document' => $filePath,
        ]);

        DB::commit();

        return redirect()->route('view_credentials', $request->project_id)
            ->with('success', 'Credential updated securely');

    } catch (\Exception $e) {

        DB::rollback();

        Log::error('🔥 Update Failed', [
            'error' => $e->getMessage()
        ]);

        return back()->with('error', 'Something went wrong');
    }
}

  public function delete_credentials(Request $request)
{
    try {

        Log::info('🟡 Credential delete request', [
            'id' => $request->id,
            'user' => auth()->id()
        ]);

        $project = ProjectDocuments::find($request->id);

        if (!$project) {
            Log::warning('❌ Credential not found', ['id' => $request->id]);

            return redirect()->back()->with('error', 'Record not found');
        }

        // ✅ DELETE FILE (PDF / IMAGE)
        if ($project->document && file_exists(public_path($project->document))) {

            unlink(public_path($project->document));

            Log::info('📁 File deleted', [
                'path' => $project->document
            ]);
        }

        // ✅ DELETE DB
        $project->delete();

        Log::info('🗑️ Credential deleted successfully', [
            'id' => $request->id
        ]);

        return redirect()->back()->with('success', 'Deleted successfully');

    } catch (\Exception $e) {

        Log::error('🔥 Delete failed', [
            'id' => $request->id,
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ]);

        return redirect()->back()->with('error', 'Something went wrong');
    }
}

public function project_documents($id = null)
{
    \Log::info('📄 Fetching project documents', ['project_id' => $id]);

    $documents = Document::when($id, function ($q) use ($id) {
        $q->where('project_id', $id);
    })->orderBy('id', 'desc')->get();

    return response()->json([
        'data' => $documents
    ]);
}
public function delete_document(Request $request)
{
    try {

        $document = Document::where('id', $request->id)->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Record not found');
        }

        // delete file
        if ($document->document && file_exists(public_path($document->document))) {
            unlink(public_path($document->document));
        }

        // delete db
        $document->delete();

        return redirect()->back()->with('success', 'Deleted successfully');

    } catch (\Exception $e) {

        Log::error('Delete error', [
            'error' => $e->getMessage()
        ]);

        return redirect()->back()->with('error', 'Something went wrong');
    }
}


}
