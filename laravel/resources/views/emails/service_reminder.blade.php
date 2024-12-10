@component('mail::message')
# Hello {{ $userName }},

The following services are due today:

@component('mail::table')
| Asset        | Service Date       | Next Service Date  | Service            |
|--------------|--------------------|--------------------|--------------------|
@foreach($services as $service)
| {{ $service['asset_code'] }} | {{ \Carbon\Carbon::parse($service['service_date'])->format('Y-m-d') }} | {{ \Carbon\Carbon::parse($service['next_service_date'])->format('Y-m-d') }} | {{ $service['service_name'] }} |
@endforeach
@endcomponent

Please make sure these services are completed on time.

Best Regards,  
**{{ config('app.name') }}**
@endcomponent
