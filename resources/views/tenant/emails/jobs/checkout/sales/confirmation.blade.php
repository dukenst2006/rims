@component('mail::message')
### Job payment confirmation

Hello there,

Congratulations. Your payment for ***{{ $sale->job->title }}*** was successful.

The job is now live.

@component('mail::button', ['url' => route('jobs.show', $sale->job)])
View Job
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
