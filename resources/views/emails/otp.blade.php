{{-- One-time code email: password reset (customer / admin) and signup verification. --}}
@extends('emails.layout')
@section('title', $title . ' - Porville')
@section('footer_title', 'Need help? Contact our support team.')

@section('content')
<tr>
  <td style="padding:40px 45px 20px;">

    <h1 style="margin:0 0 15px;text-align:center;color:#211a13;font-size:27px;">{{ $title }}</h1>

    <p style="margin:0 0 30px;text-align:center;color:#666666;font-size:15px;line-height:24px;">{{ $intro }}</p>

    <p style="margin:0 0 12px;text-align:center;color:#777777;font-size:13px;font-weight:bold;text-transform:uppercase;letter-spacing:1px;">
      Your Verification Code
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td align="center">
          <div style="display:inline-block;background:#faf5e8;border:2px solid #d5aa3d;border-radius:10px;padding:18px 35px;font-size:34px;font-weight:bold;letter-spacing:10px;color:#1c1712;">
            {{ $otp }}
          </div>
        </td>
      </tr>
    </table>

    <p style="margin:18px 0 30px;text-align:center;color:#8b6a27;font-size:14px;font-weight:bold;">
      This code is valid for {{ $minutes }} minutes.
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#fafafa;border-left:4px solid #d5aa3d;border-radius:5px;">
      <tr>
        <td style="padding:16px 18px;color:#555555;font-size:13px;line-height:21px;">
          <strong style="color:#211a13;">Security Notice</strong><br>
          Never share this OTP with anyone. Porville will never ask you for your verification code over a phone call, message, or email.
        </td>
      </tr>
    </table>

    <p style="margin:28px 0 10px;color:#666666;font-size:14px;line-height:23px;">
      {{ $ignoreText ?? 'If you did not request this, you can safely ignore this email. No changes will be made to your account.' }}
    </p>

    <p style="margin:30px 0 5px;color:#333333;font-size:14px;">Regards,</p>
    <p style="margin:0 0 10px;color:#1e1812;font-size:15px;font-weight:bold;">Team Porville</p>

  </td>
</tr>
@endsection
