<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\FileModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! isset($_GET['t'])) {
            return abort(Response::HTTP_FOUND);
        }
        if (! $request->user()->hasRole('admin')) {
            $files = FileModel::with('user')->where('user_id', auth()->id());
        } else {
            $files = FileModel::with('user')->whereNot('id', 0);
        }
        if ((int) $_GET['t'] === 0) {
            $files = $files->orderByDesc('id');
        } else {
            $files = $files->where(
                'type',
                'like',
                '%'.array_search((int) $_GET['t'], FileModel::$TYPES).'%'
            );
        }
        if (isset($_GET['s'])) {
            $files->where('title', 'like', "%{$_GET['s']}%");
        }
        $files = $files->latest()->paginate(10);

        return response($files);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException;
        }
        $save = $receiver->receive();
        if ($save->isFinished()) {
            return $this->saveFile($save->getFile(), $request->input('destination'));
        }
        $handler = $save->handler();

        return response()->json([
            'done' => $handler->getPercentageDone(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $static = [];
        if (! $request->user()->hasRole('admin')) {
            $files = FileModel::where('user_id', auth()->id());
        }
        if (isset($_GET['s'])) {
            $files = FileModel::search($_GET['s']);
            $static['search'] = $_GET['s'];
        }
        $files = isset($files) ? $files->latest()->paginate(30) : FileModel::latest()->paginate(30);

        return Inertia::render('FileManager', compact(['files', 'static']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response(FileModel::find($id), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'required|min:3'], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min.string'),
        ]);
        $fileItem = FileModel::find($id);
        $fileItem->update(['title' => $request->input('title')]);

        return response(['msg' => 'تغییرات ذخیره شد'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $item = FileModel::find($id);
        if ($item->src !== null) {
            $pathFile = public_path("store/$item->src");
            if (File::exists($pathFile)) {
                File::delete($pathFile);
            }
        }
        $item->delete();

        return response(['msg' => 'فایل حذف شد']);
    }

    public function download(int $id)
    {
        $fileItem = FileModel::find($id);
        $file = public_path("upload/$fileItem->src");
        $headers = ['Content-Type' => $fileItem->type];
        $fileExtension = explode('.', $fileItem->src);

        return response()->download(
            $file,
            time().'-'.$fileItem->title.'.'.$fileExtension[count($fileExtension) - 1],
            $headers
        );
    }

    protected function saveFile(UploadedFile $file, string $destination)
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = $destination.
            '/'.
            explode('/', $file->getMimeType())[0].
            's/'.
            Carbon::now()->format('Y').
            '/';
        $splitFileTitle = explode('.', $file->getClientOriginalName());
        $data = [
            'src' => $filePath.$fileName,
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'title' => $splitFileTitle[0],
            'user_id' => auth()->id(),
            'user' => auth()->user(),
        ];
        $fileItem[] = FileModel::create($data)->toArray();
        $file->move(public_path("upload/$filePath"), $fileName);

        if (array_search($splitFileTitle[count($splitFileTitle) - 1], ['jpg', 'png', 'gif', 'svg', 'webp'])) {
            ImageOptimizer::optimize(public_path("upload/$filePath$fileName"));
        }

        return response($fileItem, Response::HTTP_OK);
    }
}
