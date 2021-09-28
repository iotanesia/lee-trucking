<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BniDashBoadrd extends CompModel
{
    protected $table = 'bni_dashboard_dummy';

    static function getSlChart() {
        try {
            return BniDashBoadrd::select('unit', DB::raw('COUNT(unit)'))->groupBy('unit')->orderBy(DB::raw('COUNT(unit)'), 'DESC')->limit(10)->get()->toArray();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function getSlAllChart() {
        try {
            return BniDashBoadrd::select('unit', DB::raw('COUNT(unit)'))->groupBy('unit')->orderBy(DB::raw('COUNT(unit)'), 'DESC')->get()->toArray();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function getSlProdukChart() {
        try {
            return BniDashBoadrd::select('unit', 'produk', DB::raw('COUNT(produk)'))->where('produk', '<>', '')->groupBy('unit', 'produk')->orderBy(DB::raw('COUNT(unit)'), 'DESC')->limit(5)->get()->toArray();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function mstUnit() {
        try {
            return BniDashBoadrd::select('unit')->groupBy('unit')->get()->toArray();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function mstKol() {
        try {
            return BniDashBoadrd::select('kol')->groupBy('kol')->get()->toArray();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function mstflagCovid() {
        try {
            return BniDashBoadrd::select('flag_covid')->groupBy('flag_covid')->get()->toArray();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function mstflag() {
        try {
            return BniDashBoadrd::select('flag')->groupBy('flag')->get()->toArray();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function mstProduk() {
        try {
            return BniDashBoadrd::select('produk')->groupBy('produk')->get()->toArray();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
