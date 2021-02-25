<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyWargaBinaanRequest;
use App\Http\Requests\StoreWargaBinaanRequest;
use App\Http\Requests\UpdateWargaBinaanRequest;
use App\Models\WargaBinaan;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class WargaBinaanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('warga_binaan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wargaBinaans = WargaBinaan::with(['media'])->get();

        return view('admin.wargaBinaans.index', compact('wargaBinaans'));
    }

    public function create()
    {
        abort_if(Gate::denies('warga_binaan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wargaBinaans.create');
    }

    public function store(StoreWargaBinaanRequest $request)
    {
        $wargaBinaan = WargaBinaan::create($request->all());

        if ($request->input('foto', false)) {
            $wargaBinaan->addMedia(storage_path('tmp/uploads/' . $request->input('foto')))->toMediaCollection('foto');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $wargaBinaan->id]);
        }

        return redirect()->route('admin.warga-binaans.index');
    }

    public function edit(WargaBinaan $wargaBinaan)
    {
        abort_if(Gate::denies('warga_binaan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wargaBinaans.edit', compact('wargaBinaan'));
    }

    public function update(UpdateWargaBinaanRequest $request, WargaBinaan $wargaBinaan)
    {
        $wargaBinaan->update($request->all());

        if ($request->input('foto', false)) {
            if (!$wargaBinaan->foto || $request->input('foto') !== $wargaBinaan->foto->file_name) {
                if ($wargaBinaan->foto) {
                    $wargaBinaan->foto->delete();
                }

                $wargaBinaan->addMedia(storage_path('tmp/uploads/' . $request->input('foto')))->toMediaCollection('foto');
            }
        } elseif ($wargaBinaan->foto) {
            $wargaBinaan->foto->delete();
        }

        return redirect()->route('admin.warga-binaans.index');
    }

    public function show(WargaBinaan $wargaBinaan)
    {
        abort_if(Gate::denies('warga_binaan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.wargaBinaans.show', compact('wargaBinaan'));
    }

    public function destroy(WargaBinaan $wargaBinaan)
    {
        abort_if(Gate::denies('warga_binaan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wargaBinaan->delete();

        return back();
    }

    public function massDestroy(MassDestroyWargaBinaanRequest $request)
    {
        WargaBinaan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('warga_binaan_create') && Gate::denies('warga_binaan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new WargaBinaan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
