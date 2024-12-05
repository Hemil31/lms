
@extends('layouts.app')
@section('styles')

.chart-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 900px;
    text-align: center;
}
#borrowedChart {
    width: 100% !important;
    height: auto !important;
    max-height: 500px;
}
h1 {
    font-size: 2rem;
    color: #333;
}
.filter-container {
    margin: 20px 0;
}
.filter-container label {
    font-size: 1rem;
    margin-right: 10px;
}
.filter-container input,
.filter-container select {
    padding: 5px;
    font-size: 1rem;
    margin-right: 10px;
}

@media (max-width: 768px) {
    .chart-container {
        width: 95%;
    }

    h1 {
        font-size: 1.5rem;
    }
}

@endsection
@section('content')
<a href="{{ url('pulse') }}" class="btn btn-primary">Pulse</a>
<a href="{{ url('horizon') }}" class="btn btn-primary">Horizon</a>
<a href="{{ url('telescope') }}" class="btn btn-primary">Telescope</a>
@include('charts.user')
<br>
@include('charts.book')
<br>
@include('charts.bookactive')
@endsection
