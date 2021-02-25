<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProdukRequest;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Produk;
use App\Models\Seller;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProdukController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('produk_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $produks = Produk::with(['seller', 'media'])->get();

        return view('admin.produks.index', compact('produks'));
    }

    public function create()
    {
        abort_if(Gate::denies('produk_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sellers = Seller::all()->pluck('nama_seller', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.produks.create', compact('sellers'));
    }

    public function store(StoreProdukRequest $request)
    {
        $produk = Produk::create($request->all());

        foreach ($request->input('foto_produk', []) as $file) {
            $produk->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('foto_produk');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $produk->id]);
        }

        return redirect()->route('admin.produks.index');
    }

    public function edit(Produk $produk)
    {
        abort_if(Gate::denies('produk_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sellers = Seller::all()->pluck('nama_seller', 'id')->prepend(trans('global.pleaseSelect'), '');

        $produk->load('seller');

        return view('admin.produks.edit', compact('sellers', 'produk'));
    }

    public function update(UpdateProdukRequest $request, Produk $produk)
    {
        $produk->update($request->all());

        if (count($produk->foto_produk) > 0) {
            foreach ($produk->foto_produk as $media) {
                if (!in_array($media->file_name, $request->input('foto_produk', []))) {
                    $media->delete();
                }
            }
        }

        $media = $produk->foto_produk->pluck('file_name')->toArray();

        foreach ($request->input('foto_produk', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $produk->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('foto_produk');
            }
        }

        return redirect()->route('admin.produks.index');
    }

    public function show(Produk $produk)
    {
        abort_if(Gate::denies('produk_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $produk->load('seller');

        return view('admin.produks.show', compact('produk'));
    }

    public function destroy(Produk $produk)
    {
        abort_if(Gate::denies('produk_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $produk->delete();

        return back();
    }

    public function massDestroy(MassDestroyProdukRequest $request)
    {
        Produk::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('produk_create') && Gate::denies('produk_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Produk();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
