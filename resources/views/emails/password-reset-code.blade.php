<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كود إعادة تعيين كلمة السر</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; direction: rtl;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <!-- Header -->
        <tr>
            <td style="padding: 30px; text-align: center; background-color: #0a4f14;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">iDara</h1>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 40px 30px;">
                <h2 style="color: #333333; margin-top: 0; font-size: 20px; text-align: center;">أهلاً {{ $name }}</h2>
                <p style="color: #666666; line-height: 1.6; font-size: 16px; text-align: center;">
                    لقد طلبت إعادة تعيين كلمة السر الخاصة بك. استخدم الكود التالي لإتمام العملية:
                </p>

                <!-- OTP Code Box -->
                <div style="margin: 30px 0; padding: 20px; background-color: #f0fdf4; border: 2px dashed #1D9E75; border-radius: 12px; text-align: center;">
                    <span style="font-size: 36px; font-weight: bold; color: #0a4f14; letter-spacing: 10px; display: block;">{{ $code }}</span>
                </div>

                <p style="color: #0a4f14; font-weight: bold; text-align: center; font-size: 15px;">
                    أدخل الكود ده في خلال 15 دقيقة
                </p>

                <hr style="border: 0; border-top: 1px solid #eeeeee; margin: 30px 0;">

                <p style="color: #999999; font-size: 13px; text-align: center; margin-bottom: 0;">
                    لو مبعتش الطلب ده، تجاهل الإيميل
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="padding: 20px; text-align: center; background-color: #f8faf9; color: #aaaaaa; font-size: 12px;">
                &copy; {{ date('Y') }} iDara Workforce Management. جميع الحقوق محفوظة.
            </td>
        </tr>
    </table>
</body>
</html>
