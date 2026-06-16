@extends('layouts.app')
@section('title','Laporan Laba Rugi')
@section('page-title','Laporan Laba Rugi')

@section('content')
<div class="card mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Bulan</label>
        <select name="bulan" class="form-select">
          @for($m=1;$m<=12;$m++)
          <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
          @endfor
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Tahun</label>
        <select name="tahun" class="form-select">
          @for($y=date('Y');$y>=date('Y')-3;$y--)
          <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
        </select>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
      </div>
    </form>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header text-center">
        <strong>LAPORAN LABA RUGI</strong><br>
        <small class="text-muted">Periode: {{ date('F', mktime(0,0,0,$bulan,1)) }} {{ $tahun }}</small>
      </div>
      <div class="card-body">
        <table class="table table-borderless mb-0">
          <tr>
            <td class="fw-semibold">Total Pendapatan (Penjualan)</td>
            <td class="text-end fw-bold text-success">Rp {{ number_format($pendapatan,0,',','.') }}</td>
          </tr>
          <tr>
            <td class="ps-4 text-muted">Harga Pokok Penjualan (HPP)</td>
            <td class="text-end text-muted">(Rp {{ number_format($hpp,0,',','.') }})</td>
          </tr>
          <tr style="border-top:1px solid #e5e7eb">
            <td class="fw-semibold">Laba Kotor</td>
            <td class="text-end fw-bold">Rp {{ number_format($labaKotor,0,',','.') }}</td>
          </tr>
          <tr>
            <td class="ps-4 text-muted">Total Pengeluaran Operasional</td>
            <td class="text-end text-danger">(Rp {{ number_format($pengeluaran,0,',','.') }})</td>
          </tr>
          <tr style="border-top:2px solid #1e1b4b">
            <td class="fw-bold fs-5">Laba Bersih</td>
            <td class="text-end fw-bold fs-5 {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
              Rp {{ number_format($labaBersih,0,',','.') }}
            </td>
          </tr>
        </table>

        @if($labaBersih < 0)
        <div class="alert alert-warning mt-3 mb-0">
          <i class="bi bi-exclamation-triangle me-2"></i>Toko mengalami kerugian pada periode ini. Pengeluaran lebih besar dari pendapatan.
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
