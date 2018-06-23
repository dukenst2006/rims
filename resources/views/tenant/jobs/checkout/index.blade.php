@extends('tenant.layouts.default')

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Job listing payment</h3>
            <div class="card-subtitle">Complete payment to post job</div>
        </div>

        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <h4>{{ $job->title }}</h4>
            </div>

            <!-- Overview Short -->
            <div class="list-group-item">
                {{ $job->overview_short }}
            </div>

            <!-- Salary -->
            <div class="list-group-item" title="Salary"><i class="ico-credit-card"></i>
                @if($job->salary_max == $job->salary_min)
                    {{ $job->salary_min }}
                @else
                    {{ $job->salary_min }} - {{ $job->salary_max }}
                @endif
            </div>

            <!-- Applicants -->
            <div class="list-group-item" title="Applicants">
                <i class="icon-people"></i> {{ $job->applicants }}
            </div>

            <!-- Location -->
            <div class="list-group-item">
                <i class="icon-location-pin"></i> {{ $job->area->name }}
            </div>

            <!-- Site -->
            <div class="list-group-item">
                Job site: {{ $job->on_location == false ? 'Remote / Off site' : 'On site'}}
            </div>

            <!-- Type -->
            <div class="list-group-item">
                Type: {{ $job->type == 'full-time' ? 'Full time' : 'Part time'}}
            </div>

        </div>

        <div class="card-body">
            <form action="{{ route('tenant.jobs.checkout.store', $job) }}" method="POST" id="job-checkout-form">
                {{ csrf_field() }}

                <div class="form-group"><!-- Output amount here -->
                    <h4>Total: {{ config('settings.cashier.symbol') }}{{ $job->cost }}</h4>
                </div>

                @if($job->cost > 0)
                    <button type="submit" class="btn btn-primary" id="job-checkout-pay">
                        Pay with Card
                    </button>
                @else
                    <button type="submit" class="btn btn-primary" id="job-checkout-free">
                        Apply changes
                    </button>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script>
        let handler = StripeCheckout.configure({
            key: '{{ config('services.stripe.key') }}',
            image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
            locale: 'auto',
            token: function (token) {   // You can access the token ID with `token.id`
                let form = $('#job-checkout-form')

                $('#job-checkout-pay').prop('disabled', true)

                $('<input>').attr({
                    type: 'hidden',
                    name: 'token',
                    value: token.id,
                }).appendTo(form)

                form.submit();
            }
        })

        $('#job-checkout-pay').click(function (e) {
            // Open Checkout with further options:
            handler.open({
                name: '{{ config('app.name') }}',
                description: "Job listing payment-{{ $job->identifier }}",
                amount: {{ $job->gatewayCost('stripe') }},
                currency: '{{ config('settings.cashier.currency') }}',
                key: '{{ config('services.stripe.key') }}',
                email: '{{ auth()->user()->email }}'
            })

            e.preventDefault();
        })

        // Close Checkout on page navigation:
        window.addEventListener('popstate', function () {
            handler.close();
        });
    </script>
@endsection
