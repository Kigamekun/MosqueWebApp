<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body style="margin: 0;">

    <div class="container" style="height: 100%; width: 100%;">
        <div class="wrapperContainer" style="margin: auto; width: 100%; max-width: 800px;">
            <div class="wrapperHead" style="background-color: #1A4D2E; padding: 3rem; border-radius: 10px 10px 0 0; ">
                <table style="width: 100%; margin-bottom: 2rem;">
                    <tbody>
                        <tr>
                            <td align="center">
                                <div class="icon" style="background-color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 1rem; width: max-content;">
                                    <img style="width: 100%; max-width: 52px;" src="https://i.ibb.co/0jcG7Sv/document-2-1.png" alt="">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <center>
                    <h1 style="color: #fff; font-family: 'Poppins', sans-serif; margin: 0; margin-bottom: .7rem;">Zakatmu !</h1>
                </center>
                <center>
                    <p class="date" style="color: #fff; font-family: 'Poppins', sans-serif;">{{now()}}</p>
                </center>
            </div>

            <div class="wrapperBody" style="background: #fff; padding: 1.4rem 3rem; border-radius: 0 0 10px 10px;">
                <div class="text" style="text-align: center; font-family: 'Poppins', sans-serif;">
                    <p style="margin: 0;">Hi <span class="name" style="font-weight: 600;">Davino</span></p>
                    <p style="margin: 0;">Zakat anda telah diterima oleh, <span class="numberRequest"
                            style="color: #1A4D2E; font-weight: 600;">Reksa Prayoga Syahputra</span></p>

                </div>



                <table style="width: 100%; padding: 1rem 0;">
                    <tbody>
                        <tr>
                            <td align="center">
                                <a href="https://plantsasri.co.id/" style="font-family: 'Poppins', sans-serif; background: #1A4D2E; padding: .8rem 2rem; border-radius: 6px; text-decoration: none; color: #fff;">View More</a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p style="text-align: center; font-family: 'Poppins', sans-serif;">Thanks for choosing Zakatin.com
                </p>
            </div>

        </div>
    </div>

</body>

</html>
