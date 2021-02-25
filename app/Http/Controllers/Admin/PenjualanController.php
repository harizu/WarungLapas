<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPenjualanRequest;
use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('penjualan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Penjualan::with(['product'])->select(sprintf('%s.*', (new Penjualan)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'penjualan_show';
                $editGate      = 'penjualan_edit';
                $deleteGate    = 'penjualan_delete';
                $crudRoutePart = 'penjualans';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('trx_no', function ($row) {
                return $row->trx_no ? $row->trx_no : "";
            });
            $table->addColumn('product_nama_produk', function ($row) {
                return $row->product ? $row->product->nama_produk : '';
            });

            $table->editColumn('qty', function ($row) {
                return $row->qty ? $row->qty : "";
            });
            $table->editColumn('total_price', function ($row) {
                return $row->total_price ? $row->total_price : "";
            });

            $table->rawColumns(['actions', 'placeholder', 'product']);

            return $table->make(true);
        }

        return view('admin.penjualans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('penjualan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Produk::all()->pluck('nama_produk', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.penjualans.create', compact('products'));
    }

    public function store(StorePenjualanRequest $request)
    {
        $penjualan = Penjualan::create($request->all());

        return redirect()->route('admin.penjualans.index');
    }

    public function edit(Penjualan $penjualan)
    {
        abort_if(Gate::denies('penjualan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Produk::all()->pluck('nama_produk', 'id')->prepend(trans('global.pleaseSelect'), '');

        $penjualan->load('product');

        return view('admin.penjualans.edit', compact('products', 'penjualan'));
    }

    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan)
    {
        $penjualan->update($request->all());

        return redirect()->route('admin.penjualans.index');
    }

    public function show(Penjualan $penjualan)
    {
        abort_if(Gate::denies('penjualan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $penjualan->load('product');

        return view('admin.penjualans.show', compact('penjualan'));
    }

    public function destroy(Penjualan $penjualan)
    {
        abort_if(Gate::denies('penjualan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $penjualan->delete();

        return back();
    }

    public function massDestroy(MassDestroyPenjualanRequest $request)
    {
        Penjualan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
