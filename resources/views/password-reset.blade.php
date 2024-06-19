@props(['url', 'name'])

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      padding: 0;
      color: #FFFFFF;
      font-size: 16px;
      font-weight: 400;
    }
    .container {
      background-color: #181623;
      background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%);
      padding: 78px 35px 89px 35px;
    }
    header {
      margin-bottom: 72px;
    }
    img {
      display: block;
      margin: 0 auto;
    }
    h2 {
      text-align: center;
      margin-top: 9px;
      font-size: 12px;
      font-weight: 500;
      color: #DDCCAA;
    }
    p {
      margin: 24px 0;
    }
    a.link {
      display: block;
      margin-bottom: 40px;
      text-decoration: none;
      background-color: #E31221;
      padding: 7px 13px;
      border-radius: 4px;
      max-width: 128px;
      text-align: center;
    }
    a:hover {
      background-color: #CC0E10;
    }
    p span,
    a span {
      color: #FFFFFF;
    }
    a + p {
      margin-bottom: 16px;
    }
    .url {
      color: #DDCCAA;
      word-break: break-all;
      margin-bottom: 41px;
      margin: 16px 0 41px 0;
    }
    .url a span {
      color: #DDCCAA;
    }
    @media only screen and (min-width: 1024px) {
      .container {
          padding: 78px 194px 82px 195px;
      }
      a {
          margin-top: 32px;
      }
      .url {
          margin-top: 24px;
      }
    }
  </style>
  
</head>
<body>
  <div class="container">
    <header>
    <img src="{{ asset('images/quote.png') }}" alt="">
    <h2>MOVIE QUOTES</h2>
    </header>
    <main>
      <p><span>Hola {{ $name }}!</span></p>
      <p><span>Please click the button below to change your password:</span></p>
      <a href="{{ $url }}" class="link"><span>Change password</span></a>
      <p><span>If clicking doesn't work, you can try copying and pasting it to your browser:</span></p>
      <p class="url"><a href=""><span>{{ $url }}</span></a></p>
      <p><span>If you have any problems, please contact us: <a href=""><span>support@moviequotes.ge</span></a></span></p>
      <p><span>MovieQuotes Crew</span></p>
    </main>
  </div>
</body>
</html>
