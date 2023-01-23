@extends('layouts.default')

@section('title', 'Karcis')

@section('content')
<div class="page-content flex-grow-1">
  <section class="row">
    <div class="col-lg-12">
      @if (session('ticket'))
        {{ session('ticket') }}
      @endif
    </div>

    {{-- <div class="col-lg-6">
      <div class="card">
        <div class="card-header pb-0">
          <h4 class="card-title">Parkir Masuk</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <form action="" method="post">
              @csrf
              <div class="form-group">
                <label for="roundText">Plat Kendaraan</label>
                <input name="plate_number"
                  type="text" 
                  id="roundText" 
                  class="form-control round" 
                  placeholder="Plat Kendaraan" 
                  required
                >
              </div>
  
              <div class="btn-group mb-3 w-100" role="group" aria-label="Basic example">
                @foreach ($rates as $rate)
                <input
                  type="radio"
                  class="btn-check"
                  name="rate_id"
                  id="rate-{{ $rate->id }}"
                  autocomplete="off"
                  {{ !$loop->index ? 'checked' : '' }}
                  value="{{ $rate->id }}"
                />
                <label
                  class="btn btn-outline-primary"
                  for="rate-{{ $rate->id }}"
                  >{{ $rate->type }}</label
                >
                @endforeach
              </div>
  
              <button class="btn btn-primary">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-header pb-0">
          <h4 class="card-title">Parkir Keluar</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <form action="{{ route('ticket.finishBySearch') }}" method="post">
              @csrf
              <div class="form-group">
                <label for="roundText">Kode Karcis</label>
                <input name="ticket_id"
                  type="text" 
                  id="ticket_id" 
                  class="form-control round" 
                  placeholder="Kode Karcis" 
                  required
                >
              </div>
  
              <button class="btn btn-primary">Proses</button>
            </form>
          </div>
        </div>
      </div>
    </div> --}}

    <div class="col-lg-12">
      <div class="card">
        <div class="card-header pb-0">
          <h4 class="card-title">Daftar Karcis</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="active-tab" data-bs-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">Aktif</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false" tabindex="-1">Semua</a>
              </li>
            </ul>

            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active show" id="active" role="tabpanel" aria-labelledby="active-tab">
                <div class="table-responsive">
                  <table class="table table-lg">
                    <thead>
                      <tr>
                        <th>Kode</th>
                        {{-- <th>Plat Nomor</th> --}}
                        <th>Jenis</th>
                        {{-- <th>Lama Parkir</th>
                        <th>Total</th> --}}
                        <th>Jam Masuk</th>
                        {{-- <th>Jam Keluar</th> --}}
                        {{-- <th>Aksi</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tickets['active'] as $ticket)
                      <tr>
                        <td>{{ $ticket->scanCode->code }}</td>
                        <td>{{ $ticket->rate->type }}</td>
                        {{-- <td>{{ $ticket->total_hour ?? '-' }}</td>
                        <td> --}}
                          {{-- @if ($ticket->total_price) --}}
                            {{-- @currentPrice($ticket->id) --}}
                          {{-- @else
                            Rp. -
                          @endif --}}
                        {{-- </td> --}}
                        <td>{{ $ticket->enter_at }}</td>
                        {{-- <td>{{ $ticket->exit_at ?? '-' }}</td>
                        <td class="text-bold-500">
                          <button class="btn btn-primary">Selesai</button>
                        </td> --}}
                      </tr> 
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="table-responsive">
                  <table class="table table-lg">
                    <thead>
                      <tr>
                        <th>Kode</th>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Lama Parkir</th>
                        <th>Total</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tickets['all'] as $ticket)
                      <tr>
                        <td>{{ $loop->count - $loop->index }}</td>
                        <td class="text-bold-500">{{ $ticket->plate_number }}</td>
                        <td>{{ $ticket->rate->type }}</td>
                        <td>{{ $ticket->total_hour ?? '-' }} Jam</td>
                        <td>@rupiah($ticket->total_price)</td>
                        <td>{{ $ticket->enter_at }}</td>
                        <td>{{ $ticket->exit_at ?? '-' }}</td>
                        <td class="text-bold-500">
                          <button class="btn btn-primary">Selesai</button>
                        </td>
                      </tr> 
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            <div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection