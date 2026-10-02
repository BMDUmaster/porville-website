{{-- General Porville email: welcome, admin alerts, broadcast notifications, slot alerts. --}}
@extends('emails.layout')
@section('title', $heading . ' - Porville')

@section('content')
<tr>
  <td style="padding:40px 45px {{ empty($buttonUrl) ? '35px' : '20px' }};">

    <h1 style="margin:0 0 15px;text-align:center;color:#211a13;font-size:26px;">{{ $heading }}</h1>

    @if(! empty($greetingName))
      <p style="font-size:15px;color:#333;margin:0 0 14px;">Hello <strong>{{ $greetingName }}</strong>,</p>
    @endif

    @foreach((array) $lines as $line)
      <p style="font-size:14px;color:#555;line-height:23px;margin:0 0 12px;">{!! nl2br(e($line)) !!}</p>
    @endforeach

    @if(! empty($details))
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#faf5e8;border:1px solid #ead9ad;border-radius:8px;margin:20px 0 6px;">
        <tr>
          <td style="padding:16px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#555;line-height:26px;">
              @foreach($details as $label => $value)
                <tr>
                  <td style="color:#211a13;font-weight:bold;vertical-align:top;padding-right:12px;">{{ $label }}:</td>
                  <td align="right" style="vertical-align:top;">{{ $value }}</td>
                </tr>
              @endforeach
            </table>
          </td>
        </tr>
      </table>
    @endif

    @if(empty($buttonUrl))
      <p style="margin:28px 0 5px;color:#555;font-size:14px;">Warm Regards,</p>
      <p style="margin:0;color:#211a13;font-size:15px;font-weight:bold;">Team Porville</p>
    @endif
  </td>
</tr>

@if(! empty($buttonUrl))
<tr>
  <td align="center" style="padding:5px 45px 35px;">
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="background:#1c1712;border-radius:7px;">
          <a href="{{ $buttonUrl }}" style="display:inline-block;padding:14px 30px;color:#e6bd55;text-decoration:none;font-size:14px;font-weight:bold;">{{ $buttonText ?? 'Open Porville' }}</a>
        </td>
      </tr>
    </table>
    <p style="margin:28px 0 5px;color:#555;font-size:14px;">Warm Regards,</p>
    <p style="margin:0;color:#211a13;font-size:15px;font-weight:bold;">Team Porville</p>
  </td>
</tr>
@endif
@endsection
