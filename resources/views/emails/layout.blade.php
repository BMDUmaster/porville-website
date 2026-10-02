{{-- Porville email shell (dark header with logo, white card, dark footer). Inline styles only: email clients ignore <style>. --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Porville')</title>
</head>
<body style="margin:0;padding:0;background:#f5f2ec;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f5f2ec;padding:40px 15px;">
  <tr>
    <td align="center">

      <table width="600" cellpadding="0" cellspacing="0" border="0"
             style="width:100%;max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">

        <!-- HEADER -->
        <tr>
          <td align="center" style="background:#17120d;padding:28px 20px;">
            <img src="{{ \App\Support\MediaUrl::brandLogo() }}" width="120" alt="Porville"
                 style="display:block;width:120px;max-width:120px;height:auto;margin:0 auto 12px;border:0;border-radius:60px;">
            <div style="font-size:22px;font-weight:bold;color:#e6bd55;letter-spacing:1px;">PORVILLE</div>
            <div style="font-size:11px;color:#d8c395;margin-top:5px;letter-spacing:2px;">FRESH CUT &bull; PURE STANDARDS</div>
          </td>
        </tr>

        @yield('content')

        <!-- FOOTER -->
        <tr>
          <td align="center" style="background:#17120d;padding:24px 25px;">
            <div style="font-size:14px;font-weight:bold;color:#e6bd55;margin-bottom:6px;">@yield('footer_title', 'Thank you for choosing Porville.')</div>
            <p style="margin:0 0 12px;color:#d8c395;font-size:12px;">@yield('footer_note', 'Fresh Cut • Pure Standards')</p>
            <a href="{{ url('/') }}" style="color:#e6bd55;text-decoration:none;font-size:12px;">{{ parse_url(url('/'), PHP_URL_HOST) }}</a>
            <p style="margin:12px 0 0;color:#8e8068;font-size:11px;">&copy; {{ date('Y') }} Porville. All rights reserved.</p>
          </td>
        </tr>

      </table>

    </td>
  </tr>
</table>

</body>
</html>
