<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="content-heading text-center mb-4">
        <h1 class="display-4 text-dark font-weight-bold">Welcome to Your Dashboard</h1>
        <p class="lead text-muted">Manage batches, medicines, and bills all in one place.</p>
    </div>
    
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card shadow-lg rounded-lg border-0 mb-4">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-info">Manage Batches</h5>
                    <p class="card-text text-muted text-truncate" style="max-height: 60px; overflow: hidden; text-overflow: ellipsis;">
                        Easily manage and organize your batches here. You can add, edit, and delete batches with ease.
                    </p>
                    <a href="#" class="btn btn-info btn-lg btn-block">Go to Batches</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-lg rounded-lg border-0 mb-4">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-success">Manage Medicines</h5>
                    <p class="card-text text-muted text-truncate" style="max-height: 60px; overflow: hidden; text-overflow: ellipsis;">
                        Keep track of all your medicines efficiently. Add new medicines, update stock, and keep a history of all transactions.
                    </p>
                    <a href="{{ route('medicines.index') }}" class="btn btn-success btn-lg btn-block">Go to Medicines</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-lg rounded-lg border-0 mb-4">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-warning">Manage Bills</h5>
                    <p class="card-text text-muted text-truncate" style="max-height: 60px; overflow: hidden; text-overflow: ellipsis;">
                        Generate and view bills with ease. The system helps you keep track of all your sales and customer details.
                    </p>
                    <a href="#" class="btn btn-warning btn-lg btn-block">Go to Bills</a>
                </div>
            </div>
        </div>
    </div>
@endsection
