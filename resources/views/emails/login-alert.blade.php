@extends('emails.layout')
@section('title', 'New Login - Porville')
@section('footer_title', 'Keeping your Porville account secure is our priority.')

@section('content')
<tr>
  <td style="padding:40px 45px;">

    <h1 style="margin:0 0 12px;text-align:center;color:#211a13;font-size:26px;">New Login Detected</h1>

    <p style="margin:0 0 30px;text-align:center;color:#777;font-size:14px;line-height:22px;">
      We noticed a new sign-in to your Porville account.
    </p>

    <p style="font-size:15px;color:#333;margin:0 0 20px;">Hello <strong>{{ $name }}</strong>,</p>

    <p style="font-size:14px;color:#555;line-height:22px;margin:0;">
      Your Porville account was successfully signed in. Here are the login details:
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#faf5e8;border:1px solid #ead9ad;border-radius:8px;margin:25px 0;">
      <tr>
        <td style="padding:18px 20px;font-size:14px;color:#555;line-height:25px;">
          <strong style="color:#211a13;">Time:</strong> {{ $time }}<br>
          <strong style="color:#211a13;">IP Address:</strong> {{ $ip }}<br>
          <strong style="color:#211a13;">Device:</strong> {{ $device }}
        </td>
      </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f7f7f7;border-left:4px solid #d5aa3d;border-radius:5px;margin-bottom:28px;">
      <tr>
        <td style="padding:16px 18px;font-size:13px;color:#555;line-height:21px;">
          <strong style="color:#211a13;">Was this you?</strong><br>
          If you recognise this login, no further action is required.
        </td>
      </tr>
    </table>

    <p style="font-size:14px;color:#555;text-align:center;line-height:22px;margin:0 0 18px;">
      If you don't recognise this activity, we recommend resetting your password immediately.
    </p>

    <table align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="background:#1c1712;border-radius:7px;">
          <a href="{{ $resetUrl }}" style="display:inline-block;padding:14px 28px;color:#e6bd55;text-decoration:none;font-size:14px;font-weight:bold;">Secure My Account</a>
        </td>
      </tr>
    </table>

    <p style="margin:35px 0 5px;color:#555;font-size:14px;">Warm Regards,</p>
    <p style="margin:0;color:#211a13;font-size:15px;font-weight:bold;">Team Porville</p>

  </td>
</tr>
@endsection
