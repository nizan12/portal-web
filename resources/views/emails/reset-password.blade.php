<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - POLTREE</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellspacing="0" cellpadding="0" style="background: #ffffff; border-radius: 20px; box-shadow: 0 10px 40px rgba(9, 16, 87, 0.08); overflow: hidden; max-width: 480px; width: 100%;">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #091057 0%, #0d1a7a 100%); padding: 32px 36px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 800; letter-spacing: 0.02em;">
                                POLTREE
                            </h1>
                            <p style="margin: 6px 0 0; color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 500;">
                                Portal Layanan Politeknik Negeri Batam
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 36px;">
                            <h2 style="margin: 0 0 8px; color: #091057; font-size: 20px; font-weight: 800;">
                                Reset Password
                            </h2>
                            <p style="margin: 0 0 20px; color: #64748b; font-size: 14px; line-height: 1.6;">
                                Halo <strong style="color: #1e293b;">{{ $nama }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px; color: #64748b; font-size: 14px; line-height: 1.7;">
                                Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah ini untuk membuat password baru:
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding: 4px 0 28px;">
                                        <a href="{{ $resetUrl }}"
                                           style="display: inline-block; padding: 14px 36px; background: #091057; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; border-radius: 14px; box-shadow: 0 8px 24px rgba(9, 16, 87, 0.2); letter-spacing: 0.02em;">
                                            Reset Password Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Divider --}}
                            <hr style="border: none; border-top: 1px dashed #e2e8f0; margin: 0 0 20px;">

                            <p style="margin: 0 0 8px; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                                Jika Anda tidak merasa meminta reset password, abaikan email ini. Tautan ini akan kedaluwarsa dalam <strong>60 menit</strong>.
                            </p>

                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                                Jika tombol tidak berfungsi, salin URL berikut ke browser Anda:
                            </p>
                            <p style="margin: 8px 0 0; word-break: break-all; color: #3b82f6; font-size: 11px; line-height: 1.5;">
                                {{ $resetUrl }}
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f8fafc; padding: 20px 36px; text-align: center; border-top: 1px solid #f1f5f9;">
                            <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                                &copy; {{ date('Y') }} POLTREE — Politeknik Negeri Batam
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
