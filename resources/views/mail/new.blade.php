<!doctype html>
<html>
 <head>
  <meta charset="utf-8">
 </head>
 <body>
  <h2>Я вас категорически приветствую, {{$user->name}}!</h2>
  <p>Пройдите по ссылке ниже, чтобы закончить регистрацию.</p>
  <a href="{{ url('/password/reset/' . $token . '?email=' . $user->email) }}"
    style="display: inline-block;
        background: #4b0082;
        color: #fff;
        padding: 1rem 1.5rem;
        text-decoration: none;
        border-radius: 3px;"
        >
        Создать пароль
        </a>
 </body>
</html>