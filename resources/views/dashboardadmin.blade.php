@extends('layouts.app')

@section('title', 'Dashboard Festival Admin')

@section('dashboard-css')
<style>
    body {
        background: linear-gradient(135deg, #1a0000, #330033, #660000);
        background-attachment: fixed;
        font-family: 'Montserrat', sans-serif;
        color: #fff;
    }

    .dashboard-container h2 {
        font-size: 2.5rem;
        font-weight: 900;
        text-align: center;
        color: #fff;
        text-shadow: 0 0 10px #ff0040, 0 0 20px #ff0080;
        letter-spacing: 2px;
        margin-bottom: 40px;
    }

    .stat-card {
        border-radius: 20px;
        box-shadow: 0 0 20px rgba(255, 0, 150, 0.5);
        transition: all 0.4s ease;
        cursor: pointer;
        padding: 30px 25px;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at center, rgba(255,255,255,0.1), transparent 70%);
        animation: rotate 10s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .stat-card:hover {
        transform: scale(1.08);
        box-shadow: 0 0 30px rgba(255, 50, 150, 0.8);
    }

    .stat-icon {
        font-size: 3.5rem;
        margin-bottom: 15px;
        opacity: 0.9;
        text-shadow: 0 0 10px #ff80ff;
    }

    h5 {
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    h2 {
        font-weight: 900;
        font-size: 3rem;
        line-height: 1;
        text-shadow: 0 0 15px #ff3399;
    }

    /* Festival gradient cards */
    .bg-primary    { background: linear-gradient(135deg, #ff0040, #ff8000); }
    .bg-success    { background: linear-gradient(135deg, #ff8000, #ffcc00); }
    .bg-info       { background: linear-gradient(135deg, #00cfff, #0080ff); }
    .bg-warning    { background: linear-gradient(135deg, #ff00ff, #cc00cc); }
    .bg-danger     { background: linear-gradient(135deg, #ff0000, #990000); }
    .bg-secondary  { background: linear-gradient(135deg, #6600ff, #3300cc); }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <h2 class="mb-4" data-aos="fade-down" data-aos-duration="700" data-aos-delay="200">Dashboard Festival Admin</h2>

    <div class="row g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
            <div class="stat-card bg-primary text-center">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <h5>Total Pengunjung</h5>
                <h2>{{ $totalVisitors }}</h2>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="700" data-aos-delay="400">
            <div class="stat-card bg-success text-center">
                <div class="stat-icon"><i class="fas fa-store"></i></div>
                <h5>Total Booth</h5>
                <h2>{{ $totalBooths }}</h2>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="700" data-aos-delay="500">
            <div class="stat-card bg-info text-center">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <h5>Total Event</h5>
                <h2>{{ $totalEvents }}</h2>
            </div>
        </div>
        <div class="col-md-4 mt-4" data-aos="fade-up" data-aos-duration="700" data-aos-delay="600">
            <div class="stat-card bg-warning text-center">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <h5>Total Tiket Terjual</h5>
                <h2>{{ $totalTickets }}</h2>
            </div>
        </div>
        <div class="col-md-4 mt-4" data-aos="fade-up" data-aos-duration="700" data-aos-delay="700">
            <div class="stat-card bg-danger text-center">
                <div class="stat-icon"><i class="fas fa-handshake"></i></div>
                <h5>Total Sponsor</h5>
                <h2>{{ $totalSponsors }}</h2>
            </div>
        </div>
        <div class="col-md-4 mt-4" data-aos="fade-up" data-aos-duration="700" data-aos-delay="800">
            <div class="stat-card bg-secondary text-center">
                <div class="stat-icon"><i class="fas fa-microphone"></i></div>
                <h5>Total Performer</h5>
                <h2>{{ $totalPerformers }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection

@section('dashboard-js')
<script>
    AOS.init({
        once: true,
        easing: 'ease-in-out',
        duration: 700
    });
</script>
@endsection
