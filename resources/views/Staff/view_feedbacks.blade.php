@extends('Staff.layout')

@section('content')
    <style>
        .parchment-bg {
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
                linear-gradient(145deg, #f5e8c7 0%, #e8d5a3 25%, #d9c590 50%, #e8d5a3 75%, #f5e8c7 100%);
            box-shadow:
                inset 0 2px 4px rgba(0, 0, 0, 0.1),
                inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                0 8px 20px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }

        .parchment-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(ellipse 40% 30% at 10% 10%, rgba(255, 255, 200, 0.4) 0%, transparent 50%),
                radial-gradient(ellipse 30% 20% at 90% 90%, rgba(240, 220, 160, 0.3) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        .flower-top {
            position: absolute;
            top: 0;
            left: 0px;
            /* width: 85px;
                height: 100px; */
            z-index: 3;
            object-fit: contain;
        }

        .quill-img {
            position: absolute;
            bottom: 0;
            right: 0px;
            width: 105px;
            height: 300px;
            z-index: 3;
            transform: rotate(10deg);
        }


        .content-wrapper {
            position: relative;
            z-index: 4;
            /* padding: 60px 30px 80px 30px; */
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 450px;
            justify-content: center;
        }

        .feedback-title {
            font-size: 1.5rem;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1.3;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }

        .quote-icon {
            color: #95a5a6 !important;
            font-size: 2rem;
            margin-bottom: 15px;
            opacity: 0.7;
        }

        .feedback-text {
            font-size: 1.15rem;
            font-family: 'Georgia', 'Times New Roman', serif;
            line-height: 1.75;
            color: #000000;
            max-width: 480px;
            /* text-shadow: 0 1px 1px rgba(255, 255, 255, 0.6); */
            word-wrap: break-word;
        }

        @media (max-width: 768px) {
            .parchment-bg {
                margin: 0 10px;
                min-height: 420px;
            }

            .flower-top {
                width: 65px;
                height: 65px;
                top: 12px;
                left: 15px;
            }

            .quill-img {
                width: 85px;
                height: 18px;
                bottom: 15px;
                right: 15px;
            }

            .ivy-right {
                width: 45px;
                height: 45px;
                top: 20px;
                right: 18px;
            }

            .content-wrapper {
                padding: 50px 20px 70px 20px;
            }

            .feedback-title {
                font-size: 1.35rem;
            }

            .feedback-text {
                font-size: 1.05rem;
                max-width: 90%;
            }
        }

        @media (max-width: 576px) {
            .flower-top {
                width: 55px;
                height: 55px;
                top: 10px;
                left: 12px;
            }

            .quill-img {
                width: 75px;
                height: 16px;
                bottom: 12px;
                right: 12px;
            }

            .content-wrapper {
                padding: 45px 15px 65px 15px;
            }

            .feedback-title {
                font-size: 1.25rem;
            }
        }
    </style>

    <div class="row align-items-center justify-content-between mb-4">
    <div class="col-auto">
        <h5 class="d-flex align-items-center mb-0">

            <button type="button" class="btn btn-icon bg-white me-2"
                style="box-shadow:0px 9px 12px -2px #66328E1F;">

                <a href="{{ route('feedback_list') }}">
                    <i class="ti tabler-chevron-left text-black"></i>
                </a>

            </button>

            <i class="ti tabler-message-circle text-primary me-2"></i>

            View Feedback |

            <span class="text-danger fw-bold d-flex align-items-center ms-2">
                <i class="ti tabler-calendar me-1"></i>

                {{ \Carbon\Carbon::parse($feedback->created_at)->format('d/m/Y') }}

            </span>
        </h5>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-12">

        <div class="parchment-bg position-relative"
            style="min-height: 500px; max-width: 620px;">

            <img src="{{ asset('assets/img/flower.png') }}"
                class="flower-top"
                alt="Flower decoration"
                onerror="this.style.display='none'"
                style="opacity:0.2;">

            <div class="content-wrapper">

                {{-- Positives --}}
                <div class="text-center mb-2">
                    <h6 class="feedback-title">Positives</h6>
                </div>

                <div class="text-center mb-4">
                    <p class="feedback-text">
                        {{ $feedback->positive_feedback ?? '-' }}
                    </p>
                </div>

                {{-- Negatives --}}
                <div class="text-center mb-2">
                    <h6 class="feedback-title">Negatives</h6>
                </div>

                <div class="text-center mb-4">
                    <p class="feedback-text">
                        {{ $feedback->negative_feedback ?? '-' }}
                    </p>
                </div>

                {{-- Suggestions --}}
                <div class="text-center mb-2">
                    <h6 class="feedback-title">Needs / Suggestions</h6>
                </div>

                <div class="text-center mb-4">
                    <p class="feedback-text">
                        {{ $feedback->suggestions ?? '-' }}
                    </p>
                </div>

                {{-- Additional Comments --}}
                <div class="text-center mb-2">
                    <h6 class="feedback-title">Additional Comments</h6>
                </div>

                <div class="text-center">
                    <p class="feedback-text">
                        {{ $feedback->additional_feedback ?? '-' }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection