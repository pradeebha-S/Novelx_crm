@extends('Admin.layout')

<style>
    .mail-wrapper {
        background: linear-gradient(135deg, #f7f4fb, #ffffff);
        min-height: 100vh;
        padding: 20px 0;
    }

    .page-title {
        font-weight: 700;
        color: #4b1f6f;
        margin-bottom: 0;
    }

    .back-btn {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #fff;
        border: none;
        box-shadow: 0 10px 25px rgba(102, 50, 142, 0.12);
        transition: 0.3s ease;
    }

    .back-btn:hover {
        transform: translateY(-2px);
        background: #f3ebfa;
    }

    .reply-card {
        border: none;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.06);
        transition: 0.3s ease;
        background: #fff;
        position: relative;
    }

    .reply-card:hover {
        transform: translateY(-3px);
    }

    .reply-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(to bottom, #7b2cbf, #c77dff);
    }

    .card-header-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #7b2cbf, #9d4edd);
        color: #fff;
        box-shadow: 0 8px 18px rgba(123, 44, 191, 0.25);
    }

    .reply-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 2px;
        color: #2d1b46;
    }

    .reply-subtitle {
        font-size: 13px;
        color: #8c8c8c;
        margin: 0;
    }

    .message-text {
        text-align: justify;
        line-height: 1.9;
        color: #555;
        font-size: 15px;
    }

    .admin-theme::before {
        background: linear-gradient(to bottom, #5a189a, #9d4edd);
    }

    .employee-theme::before {
        background: linear-gradient(to bottom, #0f9d58, #34c759);
    }

    .employee-theme .icon-box {
        background: linear-gradient(135deg, #0f9d58, #34c759);
        box-shadow: 0 8px 18px rgba(15, 157, 88, 0.25);
    }

    .card-time {
        font-size: 12px;
        color: #999;
        margin-top: 15px;
        text-align: right;
    }
</style>

@section('content')
<div class="mail-wrapper">

  <div class="row d-flex justify-content-between">
        <div class="col-auto">
            <h5> <button type="button" class="btn btn-icon bg-white waves-effect me-2"
                    style="box-shadow: 0px 9px 12px -2px #66328E1F;">
                    <a href="{{ route('mail_table') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M15 6l-6 6l6 6"></path>
                        </svg>
                    </a>
                </button>
                View Reply
            </h5>
        </div>


    </div>
    {{-- Admin Query --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="card reply-card admin-theme p-4">

                <div class="card-header-custom">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5z"></path>
                            <path d="M5 22v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2"></path>
                        </svg>
                    </div>

                    <div>
                        <h5 class="reply-title">Admin Query</h5>
                        <p class="reply-subtitle">Official message from admin</p>
                    </div>
                </div>

                <p class="message-text">
                    
                <p class="message-text">
                    {{ $communication->content }}
                </p>

                <div class="card-time">

                    Sent on :
                    {{ $communication->created_at->format('d M Y • h:i A') }}

                </div>

                </p>

                
            </div>
        </div>
    </div>

    {{-- STAFF REPLIES --}}
    @forelse($communication->replies as $reply)

        <div class="row justify-content-center mb-4">

            <div class="col-lg-8">

                <div class="card reply-card employee-theme p-4">

                    <div class="card-header-custom">

                        <div class="icon-box">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2">

                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>

                                <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path>

                            </svg>

                        </div>

                        <div>

                            <h5 class="reply-title">
                                Employee Reply
                            </h5>

                            <p class="reply-subtitle">
                                Response from employee
                            </p>

                        </div>

                    </div>

                    <p class="message-text">
                        {{ $reply->message }}
                    </p>

                    <div class="card-time">

                        {{ optional($reply->user)->name }}

                        •

                        {{ $reply->created_at->format('d M Y • h:i A') }}

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="alert alert-warning text-center">

                    No reply from employee yet

                </div>

            </div>

        </div>

    @endforelse

</div>

@endsection